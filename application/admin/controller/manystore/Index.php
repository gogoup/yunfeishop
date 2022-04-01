<?php

namespace app\admin\controller\manystore;

use app\manystore\model\Manystore;
use app\manystore\model\ManystoreShop;
use app\manystore\model\ManystoreAuthGroup;
use app\manystore\model\ManystoreAuthGroupAccess;
use app\common\controller\Backend;
use fast\Random;
use fast\Tree;
use think\Exception;
use think\Validate;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class Index extends Backend
{

    /**
     * @var \app\manystore\model\Manystore
     */
    protected $model = null;
    protected $shopModel = null;
    protected $selectpageFields = 'id,username,nickname,avatar';
    protected $searchFields = 'id,username,nickname';
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];

    public function _initialize()
    {
        parent::_initialize();

        $this->model = new Manystore();
        $this->shopModel = new ManystoreShop();
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where(array('is_main'=>1))
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where(array('is_main'=>1))
                ->field(['password', 'salt', 'token'], true)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            $shop = $this->request->post("shop/a");
            if ($params) {
                if (!Validate::is($params['password'], '\S{6,16}')) {
                    $this->error(__("Please input correct password"));
                }
                db()->startTrans();
                try{

                    $shop_info = $this->shopModel->save($shop);
                    if($shop_info === false){
                        $this->error($this->shopModel->getError());
                    }

                    $params['shop_id'] = $this->shopModel->id;
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                    $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                    $params['is_main'] = 1;

                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                    $result = $this->model->validate($validate)->save($params);
                    if ($result === false) {
                        $this->error($this->model->getError());
                    }


                    $manystoreAuthGroupModel = new ManystoreAuthGroup();
                    $group = [];
                    $group['shop_id'] = $this->shopModel->id;
                    $group['name'] = '超级管理员';
                    $group['rules'] = '*';
                    $group['createtime'] = time();
                    $group['updatetime'] = time();
                    $group_id = $manystoreAuthGroupModel->insertGetId($group);
                    if(!$group_id){
                        $this->error('添加失败');
                    }

                    $manystoreAuthGroupAccessModel = new ManystoreAuthGroupAccess();
                    $group_access = [];
                    $group_access['uid'] = $this->model->id;
                    $group_access['group_id'] = $group_id;

                    $manystoreAuthGroupAccessModel->insert($group_access);

                    db()->commit();
                    $this->success();
                }catch (Exception $e){
                    db()->rollback();
                    $this->error($e->getMessage());
                }

            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get(['id' => $ids,'is_main'=>1]);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $shop_info = $this->shopModel->get(array('id'=>$row['shop_id']));
        if(!$shop_info){
            $this->error(__('商家信息资料不存在'));
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            $shop = $this->request->post("shop/a");
            if ($params) {

                $result = $shop_info->save($shop);
                if($result === false){
                    $this->error(__("修改商家信息资料失败"));
                }

                if ($params['password']) {
                    if (!Validate::is($params['password'], '\S{6,16}')) {
                        $this->error(__("Please input correct password"));
                    }
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                } else {
                    unset($params['password'], $params['salt']);
                }
                //这里需要针对username和email做唯一验证
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;

                $manystoreValidate = \think\Loader::validate($validate);
                $manystoreValidate->rule([
                    'username' => 'regex:\w{3,12}|unique:manystore,username,' . $row->id,
                    'email'    => 'require|email|unique:manystore,email,' . $row->id,
                    'password' => 'regex:\S{32}',
                ]);

                $result = $row->validate($validate)->save($params);
                if ($result === false) {
                    $this->error($row->getError());
                }

                $this->success();
            }
            $this->error();
        }
        $grouplist = $this->auth->getGroups($row['id']);
        $groupids = [];
        foreach ($grouplist as $k => $v) {
            $groupids[] = $v['id'];
        }
        $this->view->assign("row", $row);
        $this->view->assign("shop", $shop_info);
        $this->view->assign("groupids", $groupids);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $row = $this->model->get(['id' => $ids,'is_main'=>1]);
            if(!$row){
                $this->error(__('No Results were found'));
            }
            db()->startTrans();
            try{
                $result = $row->delete();
                if(!$result){
                    exception('账号信息删除失败');
                }
                $result =  $this->shopModel->where(array('id'=>$row['shop_id']))->delete();
                if(!$result){
                    exception('商家信息删除失败');
                }
                db()->commit();
                $this->success('删除成功');
            }catch (Exception $e){
                db()->rollback();
                $this->error($e->getMessage());
            }
        }
        $this->error(__('You have no permission'));
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }

    /**
     * 下拉搜索
     */
    public function selectpage()
    {
        $this->dataLimit = 'auth';
        $this->dataLimitField = 'id';
        return parent::selectpage();
    }
}
