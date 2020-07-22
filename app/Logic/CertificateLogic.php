<?php


namespace App\Logic;


use App\Models\CertificateModel;

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
    public function manualUploadSave($request, $isCreate)
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
        return $cert;
    }
    public function remove($request)
    {
        $cert = CertificateModel::findOrFail($request['id']);
        /** @var CertificateModel $cert */
        $cert->delete();
        return $cert;
    }

}
