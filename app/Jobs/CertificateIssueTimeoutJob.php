<?php

namespace App\Jobs;

use App\Models\CertificateLogModel;
use App\Models\CertificateModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CertificateIssueTimeoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var $certificate CertificateModel */
    protected $certificate;
    protected $start_issue_time;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($certificate, $start_issue_time)
    {
        $this->certificate = $certificate;
        $this->start_issue_time = $start_issue_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $certificate = $this->certificate->refresh();
        if ($certificate->start_issue_time !== $this->start_issue_time) {
            \Log::info('超时检查，因为时间不匹配，认定为已经不是此次超时检查，so exit');
            return;
        }
        if ($certificate->status !== CertificateModel::STATUS_ISSUING) {
            \Log::info('超时检查，已经不在签发中，说明已经成功或者失败，so exit');
            return;
        }
        $certificate->status = CertificateModel::STATUS_UNAVAILABLE;
        $certificate->save();

        $log = new CertificateLogModel();
        $log->certificate_id = $certificate->id;
        $log->op_type = CertificateLogModel::OP_TYPE_ISSUE_FAIL;
        $log->detail = 'Timeout than 60 seconds ！';
        $log->save();
    }
}
