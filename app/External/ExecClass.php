<?php


namespace App\External;

use App\External\Exception\CommandNotFoundExternalException;
use App\External\Exception\ExternalException;

/**
 * 注意：
 * 1：例如普通用户可能没有权限执行nginx -s发送进程信号
 * 2：环境变量，php执行用户没有对应的env，所以需要完整路径
 * Class ExecClass
 * @package App\External
 */
class ExecClass
{
    protected $shell;
    protected $result;
    protected $resultCode;

    protected function __construct(){}

    public static function create($shell)
    {
        $instance = new static();
        $instance->shell = $shell;
        $instance->exec();
        return $instance;
    }
    public function exec()
    {
        $this->result = exec($this->shell . ' 2>&1', $output, $this->resultCode);
        return $this;
    }

    public function getExecResult()
    {
        return $this->result;
    }
    public function getExecResultCode()
    {
        return $this->resultCode;
    }
    public function checkNotFound()
    {
        // sh: 1: acme.sh: not found
        if (preg_match('/sh: 1: .+: not found/', $this->result, $matches)) {
            throw new CommandNotFoundExternalException('command: [' . $this->shell . '] not found, please check env');
        }
        return $this;
    }
    /**
     * @param $p
     * @return mixed
     * @throws ExternalException
     */
    public function matchOne($p)
    {
        $count = preg_match($p, $this->result, $matches);
        if ($count !== 1) {
            throw new ExternalException('regex match err, command: [' . $this->shell . ']'. ' result: [' . $this->result . ']');
        }
        return $matches[0];
    }
}
