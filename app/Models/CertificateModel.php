<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * \App\Models\CertificateModel
 *
 * @property int $id
 * @property int|null $certificate_sign_config_id 证书签发配置ID
 * @property int $is_manual_upload 是否手动上传(手动上传的不续签)
 * @property string $expires_time 过期时间
 * @property string $path 证书路径
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCertificateSignConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereExpiresTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereIsManualUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CertificateModel extends Model
{
    protected $table = 'certificates';
    protected $guarded = ['id'];

    const CERT_TYPE_WEBROOT = 'webroot';
}
