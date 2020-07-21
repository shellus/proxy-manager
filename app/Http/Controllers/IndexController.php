<?php

namespace App\Http\Controllers;

use App\External\AcmeshExternal;
use App\External\Exception\ExternalException;
use App\External\NginxExternal;
use App\Models\CertificateModel;
use App\Models\ProxyModel;
use Illuminate\Contracts\Debug\ExceptionHandler;

class IndexController extends Controller
{
    /**
     * 首页用的数据
     * @return array
     */
    public function homeData(NginxExternal $nginx, AcmeshExternal $acme)
    {
        $result = [
            'proxy_count' => ProxyModel::count(),
            'certificate_count' => CertificateModel::count(),
            'nginx_version' => '',
            'acmesh_version' => '',
        ];
        $errMessages = [];
        try {
            $result['nginx_version'] = $nginx->getVersion();
        } catch (ExternalException $exception) {
            $errMessages[] = $exception->getMessage();
            app(ExceptionHandler::class)->report($exception);
        }
        try {
            $result['acmesh_version'] = $acme->getVersion();
        } catch (ExternalException $exception) {
            $errMessages[] = $exception->getMessage();
            app(ExceptionHandler::class)->report($exception);
        }
        $result['errMessages'] = $errMessages;
        return $this->success($result);
    }
}
