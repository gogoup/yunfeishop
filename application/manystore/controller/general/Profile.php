<?php

namespace app\manystore\controller\general;

use app\manystore\model\Manystore;
use app\manystore\model\ManystoreShop;
use app\common\controller\ManystoreBase;
use fast\Random;
use think\Exception;
use think\Session;
use think\Validate;

/**
 * 商家信息配置
 *
 * @icon fa fa-user
 */
class Profile extends ManystoreBase
{

    /**
     * 查看
     */
    public function index()
    {
        $shopModel = new ManystoreShop();
        $shop_info = $shopModel->where(array('id'=>SHOP_ID))->find();
        $this->view->assign('statusList',[0=>'待审核',1=>'审核通过',2=>'审核拒绝']);
        $this->view->assign('shop_info',$shop_info);
        return $this->view->fetch();
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            $params = array_filter(array_intersect_key(
                $params,
                array_flip(array('email', 'nickname', 'password', 'avatar'))
            ));
            unset($v);
            if (!Validate::is($params['email'], "email")) {
                $this->error(__("Please input correct email"));
            }
            if (isset($params['password'])) {
                if (!Validate::is($params['password'], "/^[\S]{6,16}$/")) {
                    $this->error(__("Please input correct password"));
                }
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
            }
            $exist = Manystore::where('email', $params['email'])->where('id', '<>', $this->auth->id)->find();
            if ($exist) {
                $this->error(__("Email already exists"));
            }
            if ($params) {
                $manystore = Manystore::get($this->auth->id);
                $manystore->save($params);

                Session::set("manystore", $manystore->toArray());
                $this->success();
            }
            $this->error();
        }
        return;
    }


    public function shop_update(){
        if ($this->request->isPost()) {
            $this->token();
            $shop = $this->request->post("shop/a");

            $shopModel = new ManystoreShop();
            $shopModel->save($shop,array('id'=>SHOP_ID));

            $this->success();
        }
        $this->error();
    }
}
