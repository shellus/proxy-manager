<?php

namespace App\Jobs;

use App\Logic\CertificateLogic;
use App\Models\CertificateModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CertificateIssueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CertificateModel $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new CertificateLogic())->issue($this->certificate);
    }
}
