<?php

namespace App\External;

class AcmeshExternal
{
    protected $binaryPath;

    public function __construct($binaryPath)
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
}
