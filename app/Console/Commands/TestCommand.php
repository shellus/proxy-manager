<?php

namespace App\Console\Commands;

use App\External\ExternalException;
use App\External\NginxExternal;
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(NginxExternal $nginx)
    {

        $res = $nginx->generateVhost(['seafile.endaosi.com'], 'http://seafile:80', 1);
        dump($res);
        return 0;
    }
}
