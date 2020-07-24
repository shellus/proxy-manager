<?php

namespace App\Console\Commands;

use App\Events\CertificateIssueSuccessEvent;
use App\Models\CertificateModel;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $certificate = CertificateModel::findOrFail(5);
        event(new CertificateIssueSuccessEvent($certificate));
        return 0;
    }
}
