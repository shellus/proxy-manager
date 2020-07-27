<?php

namespace App\Console\Commands\Certificate;

use Illuminate\Console\Command;

class CheckExistsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cert:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查证书是否在文件系统存在，一般docker启动时(还是别搞了，首页提示一下得了)';

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
        $this->warn('命令未实现');
        return 0;
    }
}
