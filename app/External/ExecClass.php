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
    const SUCCESS = 0;

    /** @var string create后生成的命令行，debug用，可以拿出去手动执行，但是要注意用户和env的区别 */
    protected $shell;
    /** @var string 最后一行输出 */
    protected $result;
    /** @var array 全部输出 */
    protected $output;
    /** @var int 退出代码，0代表成功，一般1代表失败，尽量只用非零来判断失败 */
    protected $exitCode;

    protected function __construct()
    {
    }

    public static function create($shell)
    {
        $instance = new static();
        $instance->shell = $shell;
        $instance->exec();
        return $instance;
    }

    public function exec()
    {
        $this->output = [];
        $this->exitCode = null;
        exec($this->shell . ' 2>&1', $this->output, $this->exitCode);
        return $this;
    }

    public function getShell()
    {
        return $this->shell;
    }

    /**
     * 全部的输出
     * @return string
     */
    public function getOutput()
    {
        return implode(PHP_EOL, $this->output);
    }

    /**
     * exit code
     * @return mixed
     */
    public function getExecResultCode()
    {
        return $this->exitCode;
    }

    public function checkNotFound()
    {
        // sh: 1: acme.sh: not found
        if (preg_match('/sh: 1: .+: not found/', $this->getOutput(), $matches)) {
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
        $count = preg_match($p, $this->getOutput(), $matches);
        if ($count !== 1) {
            throw new ExternalException('regex match err, command: [' . $this->shell . ']' . ' result: [' . $this->getOutput() . ']');
        }
        return $matches[0];
    }
}
