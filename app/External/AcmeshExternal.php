<?php


namespace App\External;


use App\External\Exception\ExternalException;

class AcmeshExternal
{
    protected $binaryPath;

    public function __construct($binaryPath = 'acme.sh')
    {
        $this->binaryPath = $binaryPath;
    }
    /**
     * @return mixed
     * @throws ExternalException
     */
    public function getVersion()
    {
        return ExecClass::create($this->binaryPath . ' -v')->exec()->checkNotFound()->matchOne('/\d+\.\d+\.\d+/');
    }
}
