<?php


namespace App\External;


use App\External\Exception\ExternalException;

class NginxExternal
{
    protected $binaryPath;

    public function __construct($binaryPath = 'nginx')
    {
        $this->binaryPath = $binaryPath;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return ExecClass::create($this->binaryPath . ' -v')->exec()->checkNotFound()->matchOne('/\d+\.\d+\.\d+/');
    }

    /**
     * 无返回值，无异常即为成功
     * @throws ExternalException
     */
    public function reload()
    {
        $exec = ExecClass::create($this->binaryPath . ' -s reload')->exec()->checkNotFound();
        if ($exec->getExecResultCode() !== 0) {
            throw new ExternalException('nginx reload err, result: [' . $exec->getExecResult() . ']');
        }
    }
}
