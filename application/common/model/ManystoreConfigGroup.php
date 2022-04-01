<?php

namespace app\common\model;

use think\Cache;
use think\Model;


class ManystoreConfigGroup extends Model
{


    // 表名
    protected $name = 'manystore_config_group';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function getGroupData(){
        $store_config_data = Cache::get('manystore_config_data');
        if(!$store_config_data){
            $data = $this->select();
            if(!empty($data)){
                foreach ($data as $value){
                    $store_config_data[$value['unique']] = $value['name'];
                }
                Cache::set('manystore_config_data',$store_config_data);
            }
        }
        return !empty($store_config_data) ? $store_config_data : [];
    }







}
