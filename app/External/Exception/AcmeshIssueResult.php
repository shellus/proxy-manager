<?php


namespace App\External;


use App\External\Exception\AcmeshException;

class AcmeshIssueResult
{
    public $full_chain_certs_path;
    public $cert_key_path;
    public $output;
    public $isSuccess;
    /** @var AcmeshException */
    public $exception;

    public static function fail($exception, $output = null)
    {
        $instance = new static();
        $instance->isSuccess = false;
        $instance->output = $output;
        $instance->exception = $exception;
        return $instance;
    }
    public static function success($full_chain_certs_path, $cert_key_path, $output)
    {
        $instance = new static();
        $instance->isSuccess = true;
        $instance->full_chain_certs_path = $full_chain_certs_path;
        $instance->cert_key_path = $cert_key_path;
        $instance->output = $output;
        return $instance;
    }
}
