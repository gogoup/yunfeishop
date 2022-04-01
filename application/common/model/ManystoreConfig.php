<?php

namespace app\common\model;

use think\Cache;
use think\Model;


class ManystoreConfig extends Model
{

    // 表名
    protected $name = 'manystore_config';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    protected $type = [
        'setting' => 'json',
    ];



    public static function manystore_config($shop_id = null){
        if(is_null($shop_id)){
            if(!defined('SHOP_ID')){
                return [];
            }
            $shop_id = SHOP_ID;
        }
        $seller_value_model = new ManystoreValue();
        $config =  cache('ManystoreConfig:'.$shop_id);
        if(!$config){
            $config_value_data_array = [];
            $config_value_data = collection($seller_value_model->where(array('shop_id' => $shop_id))->select())->toArray();
            foreach ($config_value_data as $value) {
                $config_value_data_array[$value['config_id']] = $value;
            }
            $config = [];
            foreach (self::select() as $k => $v) {
                $value = $v->toArray();
                $data_value = isset($config_value_data_array[$value['id']]['value']) ? $config_value_data_array[$value['id']]['value'] : $value['default'];
                if (in_array($value['type'], ['selects', 'checkbox', 'images', 'files'])) {
                    $value['value'] = explode(',', $data_value);
                } else if ($value['type'] == 'array') {
                    $value['value'] = (array)json_decode($data_value, TRUE);
                } else {
                    $value['value'] = $data_value;
                }
                $config[$value['name']] = $value['value'];
            }
            cache('ManystoreConfig:'.$shop_id,$config,null, 'ShopCacheTag:'.$shop_id);
        }
        return !empty($config) ? $config : [];
    }



    public static function getArrayData($data)
    {
        $fieldarr = $valuearr = [];
        $field = isset($data['field']) ? $data['field'] : [];
        $value = isset($data['value']) ? $data['value'] : [];
        foreach ($field as $m => $n) {
            if ($n != '') {
                $fieldarr[] = $field[$m];
                $valuearr[] = $value[$m];
            }
        }
        return $fieldarr ? array_combine($fieldarr, $valuearr) : [];
    }

    /**
     * 将字符串解析成键值数组
     * @param string $text
     * @return array
     */
    public static function decode($text, $split = "\r\n")
    {
        $content = explode($split, $text);
        $arr = [];
        foreach ($content as $k => $v) {
            if (stripos($v, "|") !== false) {
                $item = explode('|', $v);
                $arr[$item[0]] = $item[1];
            }
        }
        return $arr;
    }

    /**
     * 将键值数组转换为字符串
     * @param array $array
     * @return string
     */
    public static function encode($array, $split = "\r\n")
    {
        $content = '';
        if ($array && is_array($array)) {
            $arr = [];
            foreach ($array as $k => $v) {
                $arr[] = "{$k}|{$v}";
            }
            $content = implode($split, $arr);
        }
        return $content;
    }


    /**
     * 本地上传配置信息
     * @return array
     */
    public static function upload()
    {
        $uploadcfg = config('upload');

        $upload = [
            'cdnurl'    => $uploadcfg['cdnurl'],
            'uploadurl' => $uploadcfg['uploadurl'],
            'bucket'    => 'local',
            'maxsize'   => $uploadcfg['maxsize'],
            'mimetype'  => $uploadcfg['mimetype'],
            'multipart' => [],
            'multiple'  => $uploadcfg['multiple'],
        ];
        return $upload;
    }








}
