<?php


namespace App\External;


class AcmeshCertItem
{
    public $Main_Domain = "";
    public $KeyLength = "";
    public $SAN_Domains = "no";
    /** @var string 这个参数，如果为空字符串，说明证书无效 */
    public $Created = "Wed Jul 22 03:01:16 UTC 2020";
    public $Renew = "Sun Sep 20 03:01:16 UTC 2020";
}
