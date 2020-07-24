<?php

namespace App\Models;

/**
 * \App\Models\ProxyLogModel
 *
 * @property int $id
 * @property int $proxy_id proxyID
 * @property int $op_type ProxyLogModel::OP_TYPE_TITLES
 * @property string|null $detail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereOpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereProxyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyLogModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProxyLogModel extends BaseModel
{
    protected $table = 'proxy_log';
    protected $guarded = ['id'];

    const OP_TYPE_CREATE = 10;
    const OP_TYPE_DEPLOY_START = 20;
    const OP_TYPE_DEPLOY_FAIL = 30;
    const OP_TYPE_DEPLOY_SUCCESS = 40;
    const OP_TYPE_EDIT = 50;

    const OP_TYPE_NAMES = [
        'OP_TYPE_CREATE' => self::OP_TYPE_CREATE,
        'OP_TYPE_DEPLOY_START' => self::OP_TYPE_DEPLOY_START,
        'OP_TYPE_DEPLOY_FAIL' => self::OP_TYPE_DEPLOY_FAIL,
        'OP_TYPE_DEPLOY_SUCCESS' => self::OP_TYPE_DEPLOY_SUCCESS,
        'OP_TYPE_EDIT' => self::OP_TYPE_EDIT,
    ];
    const OP_TYPE_TITLES = [
        self::OP_TYPE_CREATE => '创建',
        self::OP_TYPE_DEPLOY_START => '开始部署',
        self::OP_TYPE_DEPLOY_FAIL => '部署失败',
        self::OP_TYPE_DEPLOY_SUCCESS => '部署成功',
        self::OP_TYPE_EDIT => '编辑',
    ];
    protected $casts = [
        'detail' => 'array'
    ];
}
