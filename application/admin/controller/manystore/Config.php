<?php

namespace app\admin\controller\manystore;

use app\common\controller\Backend;
use app\common\model\ManystoreConfigGroup;
use app\common\model\Config as ConfigModel;
use think\Db;
use think\Exception;

/**
 * 商家系统配置管理
 *
 * @icon fa fa-circle-o
 */
class Config extends Backend
{

    /**
     * ManystoreConfig模型对象
     * @var \app\common\model\ManystoreConfig
     */
    protected $model = null;
    protected $noNeedRight = ['check', 'rulelist', 'get_fields_list'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\ManystoreConfig;

        $manystoreConfigGroup = new ManystoreConfigGroup();
        $group_data =  $manystoreConfigGroup->getGroupData();

        $this->view->assign('typeList', ConfigModel::getTypeList());
        $this->view->assign('ruleList', ConfigModel::getRegexList());
        $this->view->assign('groupList', $group_data);
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */



    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }

                foreach ($params as $k => &$v) {
                    $v = is_array($v) && $k !== 'setting' ? implode(',', $v) : $v;
                }
                if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                    $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                } else {
                    $params['content'] = '';
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                foreach ($params as $k => &$v) {
                    $v = is_array($v) && $k !== 'setting' ? implode(',', $v) : $v;
                }
                if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                    $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                } else {
                    $params['content'] = '';
                }

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $row['content'] = ConfigModel::encode(json_decode($row['content'],true));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 规则列表
     * @internal
     */
    public function rulelist()
    {
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $keyValue = $this->request->request("keyValue", "");

        $keyValueArr = array_filter(explode(',', $keyValue));
        $regexList = \app\common\model\Config::getRegexList();
        $list = [];
        foreach ($regexList as $k => $v) {
            if ($keyValueArr) {
                if (in_array($k, $keyValueArr)) {
                    $list[] = ['id' => $k, 'name' => $v];
                }
            } else {
                $list[] = ['id' => $k, 'name' => $v];
            }
        }
        return json(['list' => $list]);
    }


    /**
     * 获取表列表
     * @internal
     */
    public function get_table_list()
    {
        $tableList = [];
        $dbname = \think\Config::get('database.database');
        $tableList = \think\Db::query("SELECT `TABLE_NAME` AS `name`,`TABLE_COMMENT` AS `title` FROM `information_schema`.`TABLES` where `TABLE_SCHEMA` = '{$dbname}';");
        $this->success('', null, ['tableList' => $tableList]);
    }

    /**
     * 获取表字段列表
     * @internal
     */
    public function get_fields_list()
    {
        $table = $this->request->request('table');
        $dbname = \think\Config::get('database.database');
        //从数据库中获取表字段信息
        $sql = "SELECT `COLUMN_NAME` AS `name`,`COLUMN_COMMENT` AS `title`,`DATA_TYPE` AS `type` FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $fieldList = Db::query($sql, [$dbname, $table]);
        $this->success("", null, ['fieldList' => $fieldList]);
    }


}
