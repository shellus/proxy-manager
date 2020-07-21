<?php

use App\External\AcmeshExternal;
use App\External\NginxExternal;

return [
    'binaryPaths' => [
        NginxExternal::class => env('NGINX_PATH', 'nginx'),
        AcmeshExternal::class => env('ACMESH_PATH', 'acme.sh'),
    ],
    'nginxVhostPath' => env('NGINX_VHOST_PATH', ''),
    'nginxVhostTplPath' => env('NGINX_VHOST_TPL_PATH', ''),
];
