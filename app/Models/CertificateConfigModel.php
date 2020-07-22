<?php

namespace App\Models;

/**
 * \App\Models\CertificateSignConfigModel
 *
 * @property int $id
 * @property string $name 名称（备注）
 * @property int $type 类型：1 dns api
 * @property string $payload 配置JSON
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateConfigModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CertificateConfigModel extends BaseModel
{
    protected $table = 'certificate_config';
    protected $guarded = ['id'];
    protected $casts = [
        'payload' => 'array',
    ];

    const TYPE_ACME = 10;
    const TYPE_TITLES = [
        self::TYPE_ACME => 'acme.sh'
    ];
}
