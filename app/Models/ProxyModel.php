<?php

namespace App\Models;

/**
 * \App\Models\ProxyModel
 *
 * @property int $id
 * @property string $name 名称（备注）
 * @property string $target_address 原站地址，就算要结构化信息，也可以分割字符串实现
 * @property int $http_port 监听端口
 * @property int $https_port 加密监听端口
 * @property bool $enable_https 开关
 * @property bool $enable_https_only 开关
 * @property bool $enable_https_hsts 开关
 * @property bool $enable_http2 开关
 * @property int|null $certificate_id 证书ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CertificateModel|null $certificate
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProxyDomainModel[] $domains
 * @property-read int|null $domains_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttpsHsts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttpsOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereHttpPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereHttpsPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereTargetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $status ProxyModel::STATUS_TITLES
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereStatus($value)
 */
class ProxyModel extends BaseModel
{
    const STATUS_NO_DEPLOY = 10;
    const STATUS_DEPLOYING = 20;
    const STATUS_DEPLOY_READY = 30;
    const STATUS_DEPLOYED = 40;
    const STATUS_DEPLOY_FAIL = 50;
    const STATUS_WATCH_ISSUE = 60;
    const STATUS_TITLES = [
        self::STATUS_NO_DEPLOY => '未部署', // 创建后就是这个状态
        self::STATUS_WATCH_ISSUE => '等待证书签发', // 如果选择了开启SSL，然后选了一个签发方式的话，创建后就进入这个状态
        self::STATUS_DEPLOY_READY => '部署: 等待队列',
        self::STATUS_DEPLOYING => '部署中',
        self::STATUS_DEPLOYED => '已部署',
        self::STATUS_DEPLOY_FAIL => '部署失败',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $table = 'proxy';
    protected $guarded = ['id'];
    protected $casts = [
        'enable_https' => 'boolean',
        'enable_https_only' => 'boolean',
        'enable_https_hsts' => 'boolean',
        'enable_http2' => 'boolean',
    ];

    public function domains()
    {
        return $this->hasMany(ProxyDomainModel::class, 'proxy_id', 'id');
    }
    public function certificate()
    {
        return $this->belongsTo(CertificateModel::class, 'certificate_id', 'id');
    }
    public function logs()
    {
        return $this->hasMany(ProxyLogModel::class, 'proxy_id', 'id');
    }
}
