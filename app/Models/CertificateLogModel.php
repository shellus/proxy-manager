<?php

namespace App\Models;


/**
 * App\Models\CertificateLogModel
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
 * @mixin \Eloquent
 */
class CertificateLogModel extends BaseModel
{
    protected $table = 'certificate_log';
    protected $guarded = ['id'];

    // 10 手动创建，20 开始签发，30 签发成功， 40 签发失败， 50 修改， 60 续签成功， 70 续签失败， 80 吊销
    const OP_TYPE_MANUAL_CREATE = 10;
    const OP_TYPE_CONFIG_CREATE = 20;
    const OP_TYPE_ISSUE_START = 30;
    const OP_TYPE_ISSUE_SUCCESS = 40;
    const OP_TYPE_ISSUE_FAIL = 50;
    const OP_TYPE_ISSUE_EDIT = 60;
    const OP_TYPE_RENEW_START = 70;
    const OP_TYPE_RENEW_SUCCESS = 80;
    const OP_TYPE_RENEW_FAIL = 90;
    const OP_TYPE_RENEW_REVOKE = 100;

//    const OP_TYPE_TITLES = [
//        self::OP_TYPE_MANUAL_CREATE => '手动创建',
//        self::OP_TYPE_CONFIG_CREATE => '从配置创建',
//        self::OP_TYPE_ISSUE_START => '开始签发',
//        self::OP_TYPE_ISSUE_SUCCESS => '签发成功',
//        self::OP_TYPE_ISSUE_FAIL => '签发失败',
//        self::OP_TYPE_ISSUE_EDIT => '修改',
//        self::OP_TYPE_RENEW_START => '开始续期',
//        self::OP_TYPE_RENEW_SUCCESS => '续期成功',
//        self::OP_TYPE_RENEW_FAIL => '续期失败',
//        self::OP_TYPE_RENEW_REVOKE => '吊销',
//    ];
}
