<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WaitMysqlConnectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wait-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '持续阻塞，直到mysql可用';

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
        $i = 0;
        while (!$this->test(++$i)) {
            sleep(1);
        }
        return 0;
    }
    protected function test($i)
    {
        try {
            \DB::connection()->select('select version();');
        } catch (\Illuminate\Database\QueryException $exception) {
            if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') === 0) {
                $err = mb_convert_encoding($exception->getMessage(), 'utf-8', 'gbk');
            } else {
                $err = $exception->getMessage();
            }
            $this->warn("Wait mysql running stat [$i]: " . str_replace(PHP_EOL, ' ', $err));
            return false;
        }
        return true;
    }
}
