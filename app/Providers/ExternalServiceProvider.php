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
                $instance = new NginxExternal($binaryPath, config('external.nginxVhostPath'), config('external.nginxVhostTplPath'));
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
