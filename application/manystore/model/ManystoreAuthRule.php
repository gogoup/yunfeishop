<?php

namespace app\manystore\model;

use think\Cache;
use think\Model;

class ManystoreAuthRule extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected static function init()
    {
        self::afterWrite(function ($row) {
            Cache::rm('__manystore_menu__');
        });
    }

    public function getTitleAttr($value, $data)
    {
        return __($value);
    }

}
