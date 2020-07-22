<?php

namespace App\Providers;

use App\External\NginxExternal;
use Illuminate\Support\ServiceProvider;

class ExternalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (config('external.binaryPaths') as $className => $binaryPath) {
            if ($className === NginxExternal::class) {
                $tplPath = config('external.nginxVhostPath');
                if (substr($tplPath, 0, 1) !== '/') {
                    $tplPath = base_path($tplPath);
                }
                $vhostPath = config('external.nginxVhostTplPath');
                if (substr($vhostPath, 0, 1) !== '/') {
                    $vhostPath = base_path($vhostPath);
                }
                $instance = new NginxExternal($binaryPath, $tplPath, $vhostPath);
            } else {
                $instance = new $className($binaryPath);
            }
            $this->app->instance($className, $instance);
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
