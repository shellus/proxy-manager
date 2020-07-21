<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * \App\Models\CertificateLogModel
 *
 * @property int $id
 * @property int $certificate_id 证书ID
 * @property int $op_type 操作类型：10 签发成功， 20 签发失败， 30 修改， 40 续签成功， 50 续签失败， 60 吊销
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel whereOpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CertificateLogModel whereUpdatedAt($value)
 */
	class CertificateLogModel extends \Eloquent {}
}

