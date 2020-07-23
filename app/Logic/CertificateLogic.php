<?php


namespace App\Logic;


use App\External\AcmeshExternal;
use App\Models\CertificateConfigModel;
use App\Models\CertificateDomainModel;
use App\Models\CertificateLogModel;
use App\Models\CertificateModel;
use Carbon\Carbon;
use Illuminate\Contracts\Debug\ExceptionHandler;

class CertificateLogic
{
    public function list($request)
    {
        return CertificateModel::orderBy('id', 'desc')->paginate();
    }
    public function selectList($request)
    {
        return CertificateModel::orderBy('id', 'desc')->select(['id', 'main_domain as name'])->get();
    }
    public function fromConfigCreate($certificateConfigId, $domains)
    {
        $certificate = new CertificateModel();
        $certificate->main_domain = $domains[0];
        $certificate->certificate_config_id = $certificateConfigId;
        $certificate->is_manual_upload = false;
        $certificate->expires_time = Carbon::now()->addMonths(3);
        $certificate->status = CertificateModel::STATUS_CREATED;
        $certificate->save();

        foreach ($domains as $domainStr) {
            $domain = new CertificateDomainModel;
            $domain->certificate_id = $certificate->id;
            $domain->domain = $domainStr;
            $domain->save();
        }

        $log = new CertificateLogModel();
        $log->certificate_id = $certificate->id;
        $log->op_type = CertificateLogModel::OP_TYPE_CONFIG_CREATE;
        $log->save();
    }

    /**
     * @param CertificateModel $certificate
     */
    public function issue($certificate)
    {
        if ($certificate->is_manual_upload) {
            throw new \Exception('手动创建的证书不可签发，请使用签发配置创建');
        }

        // 写开始日志
        $certificate->status = CertificateModel::STATUS_ISSUING;
        $certificate->start_issue_time = Carbon::now()->toDateTimeString();
        $certificate->save();

        $log = new CertificateLogModel();
        $log->certificate_id = $certificate->id;
        $log->op_type = CertificateLogModel::OP_TYPE_ISSUE_START;
        $log->save();

        // 开始签发
        $config = CertificateConfigModel::findOrFail($certificate->certificate_config_id);
        /** @var AcmeshExternal $acme */
        $acme = app(AcmeshExternal::class);
        $acme->config($config->payload['dns_provider'], $config->payload['api_id'], $config->payload['api_secret']);
        $issueResult = $acme->issueAliyun($certificate->domains->pluck('domain')->toArray());

        // 写入结果和日志
        if ($issueResult->isSuccess) {
            $certificate->cert_path = $issueResult->full_chain_certs_path;
            $certificate->cert_key_path = $issueResult->cert_key_path;
            $certificate->status = CertificateModel::STATUS_AVAILABLE;
            $certificate->save();

            $log = new CertificateLogModel();
            $log->certificate_id = $certificate->id;
            $log->op_type = CertificateLogModel::OP_TYPE_ISSUE_SUCCESS;
            $log->detail = $issueResult->output;
            $log->save();
        } else {
            // 记录异常
            app(ExceptionHandler::class)->report($issueResult->exception);

            $certificate->status = CertificateModel::STATUS_UNAVAILABLE;
            $certificate->save();

            $log = new CertificateLogModel();
            $log->certificate_id = $certificate->id;
            $log->op_type = CertificateLogModel::OP_TYPE_ISSUE_FAIL;
            $log->detail = $issueResult->exception->getMessage();
            $log->save();
        }

    }
    public function manualUploadCreate($request, $isCreate)
    {
        if ($isCreate) {
            $cert = new CertificateModel();
        } else {
            $cert = CertificateModel::findOrFail($request['id']);
        }
        /** @var CertificateModel $cert */
        $cert->certificate_config_id = null;
        $cert->is_manual_upload = true;
        $cert->expires_time = $request['expires_time'];
        $cert->cert_path = $request['cert_path'];
        $cert->cert_key_path = $request['cert_key_path'];
        $cert->main_domain = $request['main_domain'];
        $cert->save();

        $log = new CertificateLogModel();
        $log->certificate_id = $cert->id;
        $log->op_type = CertificateLogModel::OP_TYPE_MANUAL_CREATE;
        $log->save();

        return $cert;
    }

    /**
     * 删除不是吊销，只是删除我们的数据库，而没有去操作外部命令
     * @param $request
     * @return CertificateModel|CertificateModel[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function remove($request)
    {
        $cert = CertificateModel::findOrFail($request['id']);
        /** @var CertificateModel $cert */
        $cert->delete();
        return $cert;
    }

}
