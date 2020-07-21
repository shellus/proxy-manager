<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProxyModel
 *
 * @property int $id
 * @property string $target_address 原站地址
 * @property int $enable_https 开关
 * @property int $enable_https_only 开关
 * @property int $enable_https_hsts 开关
 * @property int $enable_http2 开关
 * @property int $certificate_id 证书ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttpsHsts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereEnableHttpsOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereTargetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProxyModel extends Model
{
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
}
