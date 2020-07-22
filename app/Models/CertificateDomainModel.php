<?php

namespace App\Models;

/**
 * App\Models\CertificateDomainModel
 *
 * @property int $id
 * @property int $certificate_id 证书ID
 * @property string $domain 域名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateDomainModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CertificateDomainModel extends BaseModel
{
    protected $table = 'certificate_domains';
    protected $guarded = ['id'];
}
