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
            throw new ExternalException('nginx reload err, result: [' . $exec->getExecResult() . ']');
        }
    }
    public function buildSSLConf($certPath, $keyPath)
    {
        $string = "    ssl_certificate $certPath;
    ssl_certificate_key $keyPath;
    include /etc/nginx/options-ssl-nginx.conf;
    ssl_dhparam /etc/nginx/ssl-dhparams.pem;";
    }

    public function generateVhost($domains, $targetAddress, $name, $sslConf = '')
    {
        $data = [
            'domains' => implode(' ', $domains),
            'target_address' => $targetAddress,
            'http_port' => 80,
            'https_port' => 443,
            'enable_https' => true,
            'enable_https_only' => true,
            'enable_https_hsts' => true,
            'enable_http2' => true,
            'cert_path' => '/etc/nginx/acme.sh/seafile.endaosi.com/fullchain.cer',
            'cert_key_path' => '/etc/nginx/acme.sh/seafile.endaosi.com/seafile.endaosi.com.key',
        ];
        $confString = $this->evaluatePath($data);
        $path = $this->vhostPath . '/' . $name . '.conf';
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
        try {
            include $this->tplPath;
        } catch (Throwable $e) {
            $this->handleViewException($e, $obLevel);
        }

        return ltrim(ob_get_clean());
    }
}
