<?php

namespace app\manystore\controller\general;

use app\common\controller\ManystoreBase;
use app\common\library\Email;
use app\common\model\ManystoreConfigGroup;
use app\common\model\ManystoreConfig as ManystoreConfigModel;
use think\Cache;
use think\Exception;
use think\Validate;

/**
 * 系统配置
 *
 * @icon fa fa-cogs
 * @remark 可以在此增改系统的变量和分组,也可以自定义分组和变量,如果需要删除请从数据库中删除
 */
class Config extends ManystoreBase
{

    /**
     * @var \app\common\model\Config
     */
    protected $model = null;
    protected $config_value_model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ManystoreConfig');
        $this->config_value_model = model('ManystoreValue');
    }

    /**
     * 查看
     */
    public function index()
    {
        $siteList = [];
        $manystoreConfigGroup = new ManystoreConfigGroup();
        $groupList =  $manystoreConfigGroup->getGroupData();

        foreach ($groupList as $k => $v) {
            $siteList[$k]['name'] = $k;
            $siteList[$k]['title'] = $v;
            $siteList[$k]['list'] = [];
        }

        $config_value_data_array = [];
        $config_value_data = collection($this->config_value_model->where(array('shop_id' => SHOP_ID))->select())->toArray();
        foreach ($config_value_data as $value) {
            $config_value_data_array[$value['config_id']] = $value;
        }

        foreach ($this->model->all() as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            $value['title'] = __($value['title']);
            if (in_array($value['type'], ['select', 'selects', 'checkbox', 'radio'])) {
                $value['value'] = explode(',', isset($config_value_data_array[$value['id']]['value']) ? $config_value_data_array[$value['id']]['value'] : $value['default']);
            } else {
                $value['value'] = isset($config_value_data_array[$value['id']]['value']) ? $config_value_data_array[$value['id']]['value'] : $value['default'];
            }
            $value['content'] = json_decode($value['content'], TRUE);
            $siteList[$v['group']]['list'][] = $value;
        }

        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }

        $this->view->assign('siteList', $siteList);

        return $this->view->fetch();
    }


    /**
     * 编辑
     * @param null $ids
     */
    public function edit($ids = NULL)
    {
        if ($this->request->isPost()) {
            $row = $this->request->post("row/a");
            if ($row) {
                $configValueAll = [];

                $config_value_data_array = [];
                $config_value_data = collection($this->config_value_model->where(array('shop_id' => SHOP_ID))->select())->toArray();
                foreach ($config_value_data as $value) {
                    $config_value_data_array[$value['config_id']] = $value;
                }
                foreach ($this->model->all() as $v) {
                    if (isset($row[$v['name']])) {
                        $value = $row[$v['name']];
                        if (is_array($value) && isset($value['field'])) {
                            $value = json_encode(ManystoreConfigModel::getArrayData($value), JSON_UNESCAPED_UNICODE);
                        } else {
                            $value = is_array($value) ? implode(',', $value) : $value;
                        }
                        $v['value'] = $value;

                        $config = $v->toArray();
                        $config_value = array();
                        if (!empty($config_value_data_array[$v['id']])) {
                            $config_value['id'] = $config_value_data_array[$v['id']]['id'];
                        }
                        $config_value['shop_id'] = SHOP_ID;
                        $config_value['store_id'] = STORE_ID;
                        $config_value['config_id'] = $config['id'];
                        $config_value['value'] = $value;
                        $configValueAll[] = $config_value;
                    }
                }
                $this->config_value_model->allowField(true)->saveAll($configValueAll);
                try {
                    $this->refreshFile();
                    $this->success();
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
    }


    /**
     * 刷新配置文件
     */
    protected function refreshFile()
    {
        Cache::rm('ManystoreConfig:' . SHOP_ID);
    }


    public function selectpage()
    {
        $id = $this->request->get("id/d");
        $config = \app\common\model\ManystoreConfig::get($id);
        if (!$config) {
            $this->error(__('Invalid parameters'));
        }
        $setting = $config['setting'];
        //自定义条件
        $custom = isset($setting['conditions']) ? (array)json_decode($setting['conditions'], true) : [];
        $custom = array_filter($custom);

        $this->request->request(['showField' => $setting['field'], 'keyField' => $setting['primarykey'], 'custom' => $custom, 'searchField' => [$setting['field'], $setting['primarykey']]]);
        $this->model = \think\Db::connect()->setTable($setting['table']);
        return parent::selectpage();
    }



}
