<?php

namespace App\Http\Controllers;

use App\Jobs\CertificateIssueJob;
use App\Logic\CertificateLogic;
use App\Models\CertificateConfigModel;
use App\Models\CertificateLogModel;
use App\Models\CertificateModel;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function indexData()
    {
        $data = [
            'TYPE_TITLES' => CertificateConfigModel::TYPE_TITLES,
            'STATUS_TITLES' => CertificateModel::STATUS_TITLES,
            'OP_TYPE_TITLES' => CertificateLogModel::OP_TYPE_TITLES,
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
    public function createFromConfig(Request $request)
    {
        $data = (new CertificateLogic())->fromConfigCreate($request['certificate_config_id'], $request['domains']);
        return $this->success($data);
    }
    public function remove(Request $request)
    {
        $data = (new CertificateLogic())->remove($request);
        return $this->success($data);
    }
    public function log(Request $request)
    {
        $data = (new CertificateLogic())->log($request);
        return $this->success($data);
    }
    public function issue(Request $request)
    {
        (new CertificateLogic())->issueReady($request);
        return $this->success([]);
    }
}
