<?php

namespace App\Console\Commands;

use App\Logic\ProxyLogic;
use App\Models\ProxyModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Collection;

class DeployAllProxyVhostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxy:deploy-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从数据库部署所有vhost出去，用于nginx vhost文件夹被清空的情况，例如docker重新启动，有数据库但是没有vhost';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var ProxyModel[]|Collection $proxies */
        $proxies = ProxyModel::where('status', ProxyModel::STATUS_DEPLOYED)->get();
        \Log::info('从数据库部署所有vhost，数量：' . $proxies->count());

        $proxyLogic = new ProxyLogic();
        foreach ($proxies as $proxy) {
            \Log::info('部署vhost：' . $proxy->name);
            try {
                $proxyLogic->deploy($proxy, ProxyLogic::DEPLOY_TRIGGER_TYPE_BOOT);
            } catch (\Throwable $exception) {
                app(ExceptionHandler::class)->report($exception);
            }
        }
        return 0;
    }
}
