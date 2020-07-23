<?php

namespace App\Http\Controllers;

use App\Logic\CertificateLogic;
use App\Models\CertificateConfigModel;
use Illuminate\Http\Request;

class CertificateController extends Controller
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
        $data = (new CertificateLogic())->list($request);
        return $this->success($data);
    }
    public function selectList(Request $request)
    {
        $data = (new CertificateLogic())->selectList($request);
        return $this->success($data);
    }
    public function save(Request $request)
    {
        $data = (new CertificateLogic())->manualUploadCreate($request, empty($request['id']));
        return $this->success($data);
    }
    public function remove(Request $request)
    {
        $data = (new CertificateLogic())->remove($request);
        return $this->success($data);
    }
}
