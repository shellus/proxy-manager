<?php


namespace App\External;


use App\Models\ProxyModel;

class NginxVhost
{
    /** @var string 空格分隔多个域名 */
    public $domains = '';
    public $target_address = '';
    public $http_port = 0;
    public $https_port = 0;
    public $enable_https = true;
    public $enable_https_only = true;
    public $enable_https_hsts = false;
    public $enable_http2 = true;
    public $cert_path = '';
    public $cert_key_path = '';

    /**
     * @param ProxyModel $proxy
     */
    public static function fromModel($proxy)
    {
        $instance = new static();
        $instance->domains = $proxy->domains->pluck('domain')->implode(' ');
        $instance->target_address = $proxy->target_address;
        $instance->http_port = $proxy->http_port;
        $instance->https_port = $proxy->https_port;
        $instance->enable_https = $proxy->enable_https;
        $instance->enable_https_only = $proxy->enable_https_only;
        $instance->enable_https_hsts = $proxy->enable_https_hsts;
        $instance->enable_http2 = $proxy->enable_http2;
        $instance->cert_path = $proxy->certificate->cert_path;
        $instance->cert_key_path = $proxy->certificate->cert_key_path;
        return $instance;
    }
}
