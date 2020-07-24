<?php

namespace App\Listeners;

use App\Events\CertificateIssueSuccessEvent;
use App\Logic\ProxyLogic;
use App\Models\ProxyModel;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProxyVhostDeployListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CertificateIssueSuccessEvent $event)
    {
        /** @var ProxyModel[] $proxies */
        $proxies = ProxyModel::where('certificate_id', $event->certificate->id)->get();
        $proxyLogic = new ProxyLogic();
        foreach ($proxies as $proxy) {
            \Log::info('证书签发完成后，部署vhost：' . $proxy->name);
            $proxyLogic->deploy($proxy);
        }
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
