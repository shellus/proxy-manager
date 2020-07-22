<?php

namespace App\Http\Controllers;

use App\Logic\ProxyLogic;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function indexData()
    {
        return [];
    }
    public function list(Request $request)
    {
        $data = (new ProxyLogic())->list($request);
        return $this->success($data);
    }
    public function save(Request $request)
    {
        $data = (new ProxyLogic())->save($request, empty($request['id']));
        return $this->success($data);
    }
    public function remove(Request $request)
    {
        $data = (new ProxyLogic())->remove($request);
        return $this->success($data);
    }
    public function generateConf(Request $request)
    {
        $data = app()->call([(new ProxyLogic()), 'generateConf'], ['request' => $request]);
        return $this->success($data);
    }
}
