<?php


namespace App\Logic;


use App\Models\CertificateModel;
use App\Models\ProxyDomainModel;
use App\Models\ProxyModel;

class ProxyLogic
{
    public function list($request)
    {
        return ProxyModel::with(['domains'])->orderBy('id', 'desc')->paginate();
    }

    public function save($data, $isCreate)
    {
        if ($isCreate) {
            $proxy = new ProxyModel();
        } else {
            $proxy = ProxyModel::findOrFail($data['id']);
        }
        $proxy->target_address = $data['target_address'];
        $proxy->enable_https = $data['enable_https'];
        $proxy->enable_https_only = $data['enable_https_only'];
        $proxy->enable_https_hsts = $data['enable_https_hsts'];
        $proxy->enable_http2 = $data['enable_http2'];
        if ($data['certificate_id'] === CertificateModel::CERT_TYPE_WEBROOT) {
            // todo 实时申请一个(还是做成页面上生成？)
            $data['certificate_id'] = 123;
        }
        $proxy->certificate_id = $data['certificate_id'];
        $proxy->save();

        $domainIds = [];
        foreach ($data['domains'] as $domainData) {

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
