<?php

namespace app\common\model;

use think\Model;

/**
 * 配置模型
 */
class ManystoreValue extends Model
{

    // 表名,不含前缀
    protected $name = 'manystore_value';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];


}
