<?php


namespace App\Logic;


use App\External\NginxExternal;
use App\External\NginxVhost;
use App\Jobs\CertificateIssueJob;
use App\Jobs\ProxyDeployJob;
use App\Logic\Exception\LogicException;
use App\Models\CertificateModel;
use App\Models\ProxyDomainModel;
use App\Models\ProxyLogModel;
use App\Models\ProxyModel;
use Illuminate\Support\Arr;

class ProxyLogic
{
    // 部署类型， 用来写日志用
    const DEPLOY_TRIGGER_TYPE_BOOT = 10;
    const DEPLOY_TRIGGER_TYPE_CREATE = 20;
    const DEPLOY_TRIGGER_TYPE_EDIT = 30;
    const DEPLOY_TRIGGER_TYPE_ISSUE = 40;
    const DEPLOY_TRIGGER_TYPE_MANUAL = 50;

    const DEPLOY_TRIGGER_TYPE_NAMES = [
        'DEPLOY_TRIGGER_TYPE_BOOT' => self::DEPLOY_TRIGGER_TYPE_BOOT,
        'DEPLOY_TRIGGER_TYPE_CREATE' => self::DEPLOY_TRIGGER_TYPE_CREATE,
        'DEPLOY_TRIGGER_TYPE_EDIT' => self::DEPLOY_TRIGGER_TYPE_EDIT,
        'DEPLOY_TRIGGER_TYPE_ISSUE' => self::DEPLOY_TRIGGER_TYPE_ISSUE,
        'DEPLOY_TRIGGER_TYPE_MANUAL' => self::DEPLOY_TRIGGER_TYPE_MANUAL,
    ];

    const DEPLOY_TRIGGER_TYPES = [
        self::DEPLOY_TRIGGER_TYPE_BOOT,
        self::DEPLOY_TRIGGER_TYPE_CREATE,
        self::DEPLOY_TRIGGER_TYPE_EDIT,
        self::DEPLOY_TRIGGER_TYPE_ISSUE,
        self::DEPLOY_TRIGGER_TYPE_MANUAL,
    ];
    const DEPLOY_TRIGGER_TYPE_TITLES = [
        self::DEPLOY_TRIGGER_TYPE_BOOT => '启动时部署全部VHOST',
        self::DEPLOY_TRIGGER_TYPE_CREATE => '创建时触发部署',
        self::DEPLOY_TRIGGER_TYPE_EDIT => '编辑时触发部署',
        self::DEPLOY_TRIGGER_TYPE_ISSUE => '证书签发完成触发部署',
        self::DEPLOY_TRIGGER_TYPE_MANUAL => '手动触发重新部署',
    ];

    public function list($request)
    {
        return ProxyModel::with(['domains'])->orderBy('id', 'desc')->paginate();
    }
    public function remove($request)
    {
        $proxy = ProxyModel::findOrFail($request['id']);
        $proxy->delete();
    }
    public function generateConf($request)
    {
        $proxy = ProxyModel::findOrFail($request['id']);
        if ($proxy->certificate->status !== CertificateModel::STATUS_AVAILABLE) {
            throw new LogicException('证书不可用，无法部署');
        }
        $this->deploy($proxy, ProxyLogic::DEPLOY_TRIGGER_TYPE_MANUAL);
    }

    /**
     * 调用处：
     * 1：开机部署全部
     * 2：创建后即时部署
     * 3：证书签发后事件触发部署
     * 4：页面点击部署
     *
     * @param ProxyModel $proxy
     * @throws \Throwable
     */
    public function deploy(ProxyModel $proxy, $deployTriggerType)
    {
        if (!in_array($deployTriggerType, static::DEPLOY_TRIGGER_TYPES)) {
            throw new \Exception('未知的部署类型: ' . var_export($deployTriggerType, true));
        }

        // 日志
        $log = new ProxyLogModel();
        $log->proxy_id = $proxy->id;
        $log->op_type = ProxyLogModel::OP_TYPE_DEPLOY_START;
        $log->detail = [$deployTriggerType, static::DEPLOY_TRIGGER_TYPE_TITLES[$deployTriggerType]];
        $log->save();

        /** @var NginxExternal $nginx */
        $nginx = app(NginxExternal::class);
        $conf = NginxVhost::fromModel($proxy);
        try {
            $nginx->generateVhost($proxy->id, $conf);
            $nginx->reload();
        } catch (\Throwable $exception) {
            // 日志
            $log = new ProxyLogModel();
            $log->proxy_id = $proxy->id;
            $log->op_type = ProxyLogModel::OP_TYPE_DEPLOY_FAIL;
            $log->detail = [$deployTriggerType, $exception];
            $log->save();

            $proxy->status = ProxyModel::STATUS_DEPLOY_FAIL;
            $proxy->save();
            throw $exception;
        }
        // 日志
        $log = new ProxyLogModel();
        $log->proxy_id = $proxy->id;
        $log->op_type = ProxyLogModel::OP_TYPE_DEPLOY_SUCCESS;
        $log->detail = [$deployTriggerType, $proxy->toArray()];
        $log->save();

        $proxy->status = ProxyModel::STATUS_DEPLOYED;
        $proxy->save();
    }

    public function save($request, $isCreate)
    {
        if ($isCreate) {
            $proxy = new ProxyModel();
        } else {
            $proxy = ProxyModel::findOrFail($request['id']);
        }
        $proxy->status = ProxyModel::STATUS_NO_DEPLOY;
        $proxy->name = $request['name'];
        $proxy->http_port = $request['http_port'];
        $proxy->https_port = $request['https_port'];
        $proxy->target_address = $request['target_address'];
        $proxy->enable_https = $request['enable_https'];
        $proxy->enable_https_only = $request['enable_https_only'];
        $proxy->enable_https_hsts = $request['enable_https_hsts'];
        $proxy->enable_http2 = $request['enable_http2'];

        if ($proxy->enable_https) {
            if ($request['certificate_id']) {
                // 直接选择已有证书
                $proxy->certificate_id = $request['certificate_id'];
            } elseif ($request['certificate_config_id']) {
                // 使用签发配置来签发
                // 虚拟字段，仅用来传输，不存入数据库
                // 如果是编辑保存的时候，这个字段是没有的，因为变成了certificate_id，就不会进入这里
                $domainStrArr =  Arr::pluck($request['domains'], 'domain');
                $certificate = (new CertificateLogic())->fromConfigCreate($request['certificate_config_id'], $domainStrArr);
                $proxy->certificate_id = $certificate->id;
                dispatch(new CertificateIssueJob($certificate));
            } else {
                throw new \Exception('启用HTTPS，但是没有配置证书，创建失败');
            }
        } else {
            $proxy->certificate_id = null;
        }

        $proxy->save();

        // 保存代理域名
        $domainIds = [];
        foreach ($request['domains'] as $domainData) {

            if (!empty($domainData['id'])){
                $domain = ProxyDomainModel::findOrFail($domainData['id']);
            } else {
                $domain = new ProxyDomainModel;
                $domain->proxy_id = $proxy->id;
            }
            $domain->domain = $domainData['domain'];
            $domain->save();
            $domainIds[] = $domain->id;
        }
        ProxyDomainModel::where('proxy_id', $proxy->id)->whereNotIn('id', $domainIds)->delete();

        $log = new ProxyLogModel();
        $log->proxy_id = $proxy->id;
        if ($isCreate) {
            $log->op_type = ProxyLogModel::OP_TYPE_CREATE;
            $log->detail = $proxy->toArray();
        } else {
            $log->op_type = ProxyLogModel::OP_TYPE_EDIT;
            $log->detail = $proxy->toArray();
        }
        $log->save();

        // 立即部署
        if ($request['deploy_now']) {
            // 非SSL，可以直接部署
            // 证书可用，可以直接部署
            if ((!$proxy->enable_https) || ($proxy->certificate && $proxy->certificate->status === CertificateModel::STATUS_AVAILABLE)) {
                $proxy->status = ProxyModel::STATUS_DEPLOY_READY;
                $proxy->save();
                dispatch(new ProxyDeployJob($proxy, $isCreate ? ProxyLogic::DEPLOY_TRIGGER_TYPE_CREATE : ProxyLogic::DEPLOY_TRIGGER_TYPE_EDIT));
            } else {
                // 是SSL，但是证书不可用：等待证书签发成功事件时，事件触发来部署
                // 有这个状态才行哦，不然就算证书签发成功事件也不会部署它的
                $proxy->status = ProxyModel::STATUS_WATCH_ISSUE;
                $proxy->save();
            }
        }

        return $proxy;
    }

    public function log($request)
    {
        $proxy = ProxyModel::with(['logs'])->findOrFail($request['id']);
        return $proxy;
    }
}
