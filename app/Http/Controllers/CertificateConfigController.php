<?php

namespace App\Http\Controllers;

use App\Logic\CertificateConfigLogic;
use App\Logic\CertificateLogic;
use App\Models\CertificateConfigModel;
use Illuminate\Http\Request;

class CertificateConfigController extends Controller
{
    public function indexData()
    {
        $data = [
            'TYPE_TITLES' => CertificateConfigModel::TYPE_TITLES
        ];
        return $this->success($data);
    }
    public function list(Request $request)
    {
        $data = (new CertificateConfigLogic())->list($request);
        return $this->success($data);
    }
    public function selectList(Request $request)
    {
        $data = (new CertificateConfigLogic())->selectList($request);
        return $this->success($data);
    }
    public function save(Request $request)
    {
        $data = (new CertificateConfigLogic())->save($request, empty($request['id']));
        return $this->success($data);
    }
    public function remove(Request $request)
    {
        $data = (new CertificateConfigLogic())->remove($request);
        return $this->success($data);
    }
}
