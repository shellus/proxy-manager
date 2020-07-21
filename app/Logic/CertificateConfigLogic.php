<?php


namespace App\Logic;


use App\Models\CertificateConfigModel;

class CertificateConfigLogic
{
    public function list($request)
    {
        return CertificateConfigModel::orderBy('id', 'desc')->paginate();
    }
    public function save($request, $isCreate)
    {
        if ($isCreate) {
            $config = new CertificateConfigModel();
        } else {
            $config = CertificateConfigModel::findOrFail($request['id']);
        }
        $config->type = $request['type'];
        $config->payload = $request['payload'];
        $config->save();
        return $config;
    }
}
