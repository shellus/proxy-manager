<?php

namespace App\Jobs;

use App\Logic\ProxyLogic;
use App\Models\ProxyModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProxyDeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var ProxyModel $proxy */
    protected $proxy;
    protected $deployTriggerType;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProxyModel $proxy, $deployTriggerType)
    {
        $this->proxy = $proxy;
        $this->deployTriggerType = $deployTriggerType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $proxy = $this->proxy;
        $proxy->status = ProxyModel::STATUS_DEPLOYING;
        $proxy->save();

        \Log::info('队列部署vhost：' . $proxy->name);

        (new ProxyLogic())->deploy($proxy, $this->deployTriggerType);

    }
}
