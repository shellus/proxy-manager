<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProxyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 证书
        Schema::create('certificate_config', function (Blueprint $table) {
            $table->comment = '证书签发配置';
            $table->increments('id');
            $table->string('name')->comment('名称（备注）');
            $table->unsignedInteger('type')->comment('类型：1 dns api');
            $table->text('payload')->comment('配置JSON');
            $table->timestamps();
        });
        Schema::create('certificates', function (Blueprint $table) {
            $table->comment = '证书';
            $table->increments('id');
            $table->string('main_domain')->comment('证书主域名');
            $table->unsignedInteger('certificate_config_id')->nullable()->comment('证书签发配置ID');
            $table->unsignedInteger('is_manual_upload')->comment('是否手动上传(手动上传的不续签)');
            $table->dateTime('expires_time')->comment('过期时间');
            $table->string('cert_path')->comment('证书路径');
            $table->string('cert_key_path')->comment('证书私钥路径');
            $table->timestamps();
        });
        Schema::create('certificate_domains', function (Blueprint $table) {
            $table->comment = '证书域名';
            $table->increments('id');
            $table->unsignedInteger('certificate_id')->comment('证书ID');
            $table->string('domain')->comment('域名');
            $table->timestamps();
        });
        Schema::create('certificate_log', function (Blueprint $table) {
            $table->comment = '证书日志';
            $table->increments('id');
            $table->unsignedInteger('certificate_id')->comment('证书ID');
            $table->unsignedInteger('op_type')->comment('操作类型：10 签发成功， 20 签发失败， 30 修改， 40 续签成功， 50 续签失败， 60 吊销');
            $table->timestamps();
        });

        // 代理
        Schema::create('proxy', function (Blueprint $table) {
            $table->comment = '代理';
            $table->increments('id');
            $table->string('name')->comment('名称（备注）');
            $table->string('target_address')->comment('原站地址，就算要结构化信息，也可以分割字符串实现');

            $table->unsignedInteger('http_port')->comment('监听端口');
            $table->unsignedInteger('https_port')->comment('加密监听端口');

            $table->unsignedInteger('enable_https')->comment('开关');
            $table->unsignedInteger('enable_https_only')->comment('开关');
            $table->unsignedInteger('enable_https_hsts')->comment('开关');
            $table->unsignedInteger('enable_http2')->comment('开关');
            $table->unsignedInteger('certificate_id')->nullable()->comment('证书ID');

            $table->timestamps();
        });
        Schema::create('proxy_domains', function (Blueprint $table) {
            $table->comment = 'proxy域名';
            $table->increments('id');
            $table->unsignedInteger('proxy_id')->comment('代理ID');
            $table->string('domain')->comment('域名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
