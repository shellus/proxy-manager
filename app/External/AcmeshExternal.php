<?php

namespace App\External;

use App\External\Exception\AcmeshException;

class AcmeshExternal
{
    protected $binaryPath;
    protected $dns, $id, $key;

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
    public function config($dns, $id, $key)
    {
        $this->dns = $dns;
        $this->id = $id;
        $this->key = $key;
        return $this;
    }

    /**
     * @param array $domains
     * @return integer
     */
    public function issueAliyun($domains)
    {
        if (empty($this->dns) || empty($this->id) || empty($this->key)) {
            throw new AcmeshException('Certificate sign parameters are not configured, Please call static::config()');
        }
        $exec = ExecClass::create("Ali_Key=" . $this->id . ' Ali_Secret=' . $this->key . ' ' . $this->binaryPath . ' --issue --dns dns_ali -d ' . implode(' ', $domains));
        \Log::info('开始签发证书： 提供商=阿里云dns，命令行：[' . $exec->getShell() . ']');
        $exec->exec();
        $output = $exec->getOutput();
        \Log::info('签发结束，签发结果：[' . $output . ']');
        $code = $exec->getExecResultCode();
        if ($code !== ExecClass::SUCCESS) {
            // 已经签发的域名，可以尝试删除后重新创建
            if (strpos($output, 'Domains not changed') !== false) {
                throw new AcmeshException('Certificate already exists, Please try acme.sh --remove -d example.com');
            }
            // 禁止创建的域名，例如baidu.com
            if (strpos($output, 'Create new order error') !== false) {
                if (preg_match('/"detail":(.*)/', $output, $matches) >= 2) {
                    throw new AcmeshException('Create new order error: [' . $matches[1] . ']');
                } else {
                    throw new AcmeshException('Create new order error: [' . $output . ']');
                }
            }
            // 不是你的域名（添加dns记录失败）
            if (strpos($output, 'Error add txt for domain') !== false) {
                throw new AcmeshException('Error add txt for domain, Please check if the domain name exists');
            }

            throw new AcmeshException('Other error: [' . $output . ']');
        }
        return true;
    }

    /**
     * 获取acme的证书列表
     * @return AcmeshCertItem[]
     */
    public function list()
    {
        $exec = ExecClass::create($this->binaryPath . ' --list --listraw');
        \Log::info('列出证书，命令行：[' . $exec->getShell() . ']');
        $exec->exec();
        $output = $exec->getOutput();
        \Log::info('列出证书结果：[' . $output . ']');

        $certList = [];
        $lines = explode(PHP_EOL, $output);
        for ($i = 1; $i < count($lines); $i++) {
            $cols = explode('|', $lines[$i]);
            $item = new AcmeshCertItem;
            $item->Main_Domain = $cols[0];
            $item->KeyLength = $cols[1];
            $item->SAN_Domains = $cols[2];
            $item->Created = $cols[3];
            $item->Renew = $cols[4];
            $certList[] = $item;
        }

        return $certList;
    }

    // 删除证书
    public function remove($mainDomain)
    {
        $exec = ExecClass::create($this->binaryPath . ' --remove -d ' . $mainDomain);
        \Log::info('删除证书，命令行：[' . $exec->getShell() . ']');
        $exec->exec();
        $output = $exec->getOutput();
        \Log::info('删除证书结果：[' . $output . ']');

        // 不存在
        if (preg_match('/is not a issued domain, skip/', $output, $matches)) {
            return false;
        }
        // 删除成功
        if (preg_match('/is removed, the key and cert files are in (.+)/', $output, $matches)) {
            $dir = $matches[1];
        }
        // 已经删除过了，提示删除文件
        if (preg_match('/is already removed, You can remove the folder by yourself: (.+)/', $output, $matches)) {
            $dir = $matches[1];
        }
        // 因为要递归删除，所以要严格检查
        if (empty($dir) || strlen($dir) < 8 || substr($dir, 0, 1) !== '/' || !is_dir($dir)) {
            throw new AcmeshException('acme.sh remove cert err: [' . $output . ']');
        }

        ExecClass::create('rm -rf "' . $dir . '"')->exec();

        return true;
    }
}
