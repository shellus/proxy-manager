<?php

namespace App\External;

use App\External\Exception\ExternalException;

class NginxExternal
{
    protected $binaryPath;
    protected $vhostPath;
    protected $tplPath;

    public function __construct($binaryPath, $vhostPath, $tplPath)
    {
        $this->binaryPath = $binaryPath;
        $this->vhostPath = $vhostPath;
        $this->tplPath = $tplPath;
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
            throw new ExternalException('nginx reload err, result: [' . $exec->getOutput() . ']');
        }
    }

    /**
     * @param NginxVhost $conf
     * @return string
     * @throws \Throwable
     */
    public function generateVhost($filename, $conf)
    {
        $data = [
            'conf' => $conf,
        ];
        $confString = $this->evaluatePath($data);
        $path = $this->vhostPath . '/' . $filename . '.conf';
        file_put_contents($path, $confString);
        return $path;
    }

    protected function evaluatePath($__data)
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($__data, EXTR_SKIP);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        include $this->tplPath;

        return ltrim(ob_get_clean());
    }
}
