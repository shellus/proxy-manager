<?php

namespace App\Models;

/**
 * \App\Models\ProxyDomainModel
 *
 * @property int $id
 * @property int $proxy_id 代理ID
 * @property string $domain 域名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel whereProxyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyDomainModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProxyDomainModel extends BaseModel
{
    protected $table = 'proxy_domains';
    protected $guarded = ['id'];
}
