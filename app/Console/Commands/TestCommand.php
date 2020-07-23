<?php

namespace App\Console\Commands;

use App\External\AcmeshExternal;
use App\External\ExternalException;
use App\External\NginxExternal;
use App\External\NginxVhost;
use App\Models\CertificateConfigModel;
use App\Models\CertificateModel;
use App\Models\ProxyModel;
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

    public function handle(AcmeshExternal $acme)
    {
//        $output = file_get_contents(__DIR__ . '/a.txt');
//        dd(preg_match('/And the full chain certs is there:  (.+)/', $output, $matches));
        $conf = CertificateConfigModel::findOrFail(1);
        $acme->config($conf->payload['dns_provider'], $conf->payload['api_id'], $conf->payload['api_secret']);
        $result = $acme->issueAliyun(['api7.endaosi.com', 'api8.endaosi.com', 'api9.endaosi.com']);
        dump($result);
        return 0;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle2(NginxExternal $nginx)
    {
        $filename = 'test';

        $conf = NginxVhost::fromModel(ProxyModel::findOrFail(17));
        $res = $nginx->generateVhost($filename, $conf);
        dump($res);
        return 0;
    }
}
