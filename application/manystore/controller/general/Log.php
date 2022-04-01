<?php

namespace app\manystore\controller\general;

use app\common\controller\ManystoreBase;

/**
 * 商家日志
 *
 * @icon fa fa-user
 */
class Log extends ManystoreBase
{

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $model = model('ManystoreLog');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $model
                ->where($where)
                ->where(array('shop_id'=> SHOP_ID))
                ->order($sort, $order)
                ->count();

            $list = $model
                ->where($where)
                ->where(array('shop_id'=> SHOP_ID))
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
