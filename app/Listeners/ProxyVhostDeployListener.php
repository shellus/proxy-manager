<?php

namespace App\Listeners;

use App\Events\CertificateIssueSuccessEvent;
use App\Logic\ProxyLogic;
use App\Models\ProxyModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class ProxyVhostDeployListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CertificateIssueSuccessEvent $event)
    {
        /** @var ProxyModel[]|Collection $proxies */
        $proxies = ProxyModel::where('status', ProxyModel::STATUS_WATCH_ISSUE)->where('certificate_id', $event->certificate->id)->get();
        \Log::info('证书签发完成后部署vhost，数量：' . $proxies->count());
        $proxyLogic = new ProxyLogic();
        foreach ($proxies as $proxy) {
            \Log::info('部署vhost：' . $proxy->name);
            $proxyLogic->deploy($proxy, ProxyLogic::DEPLOY_TRIGGER_TYPE_ISSUE);
        }
    }
}
