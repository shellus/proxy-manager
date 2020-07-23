<?php

namespace App\Models;
/**
 * \App\Models\CertificateModel
 *
 * @property int $id
 * @property string $main_domain 证书主域名
 * @property int|null $certificate_config_id 证书签发配置ID
 * @property int $is_manual_upload 是否手动上传(手动上传的不续签)
 * @property string $expires_time 过期时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $cert_path 证书路径
 * @property string $cert_key_path 证书私钥路径
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCertKeyPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCertPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCertificateSignConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereExpiresTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereIsManualUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CertificateModel extends BaseModel
{
    protected $table = 'certificates';
    protected $guarded = ['id'];

    const STATUS_CREATED = 10;
    const STATUS_AVAILABLE = 20;
    const STATUS_UNAVAILABLE = 30;
    const STATUS_ISSUING = 40;
    const STATUS_EXPIRED = 50;

//    const STATUS_TITLES = [
//        self::STATUS_CREATED => '待签发',
//        self::STATUS_AVAILABLE => '签发成功',
//        self::STATUS_UNAVAILABLE => '签发失败',
//        self::STATUS_ISSUING => '签发中',
//        self::STATUS_EXPIRED => '已过期',
//    ];

    // 好像没用
    const CERT_TYPE_WEBROOT = 'webroot';

    public function domains()
    {
        return $this->hasMany(CertificateDomainModel::class, 'certificate_id', 'id');
    }
}
