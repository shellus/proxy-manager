<?php


namespace App\Logic;


use App\External\NginxExternal;
use App\External\NginxVhost;
use App\Logic\Exception\LogicException;
use App\Models\CertificateModel;
use App\Models\ProxyDomainModel;
use App\Models\ProxyModel;

class ProxyLogic
{
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
        $this->deploy($proxy);
    }

    public function deploy(ProxyModel $proxy)
    {
        /** @var NginxExternal $nginx */
        $nginx = app(NginxExternal::class);
        $conf = NginxVhost::fromModel($proxy);
        $nginx->generateVhost($proxy->id, $conf);
        $nginx->reload();
    }

    public function save($request, $isCreate)
    {
        if ($isCreate) {
            $proxy = new ProxyModel();
        } else {
            $proxy = ProxyModel::findOrFail($request['id']);
        }
        $proxy->name = $request['name'];
        $proxy->http_port = $request['http_port'];
        $proxy->https_port = $request['https_port'];
        $proxy->target_address = $request['target_address'];
        $proxy->enable_https = $request['enable_https'];
        $proxy->enable_https_only = $request['enable_https_only'];
        $proxy->enable_https_hsts = $request['enable_https_hsts'];
        $proxy->enable_http2 = $request['enable_http2'];
        $proxy->certificate_id = $request['certificate_id'];
        $proxy->save();

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

        return $proxy;
    }
}
