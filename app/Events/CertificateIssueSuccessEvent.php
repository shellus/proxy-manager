<?php

namespace App\Events;

use App\Models\CertificateModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CertificateIssueSuccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /** @var $certificate CertificateModel */
    public $certificate;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }
}
