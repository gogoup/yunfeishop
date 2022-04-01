<?php

namespace app\data\controller\base;

use app\data\model\BaseUserMessage;
use think\admin\Controller;

/**
 * 系统通知管理
 * Class Message
 * @package app\data\controller\base
 */
class Message extends Controller
{
    /**
     * 系统通知管理
     * @auth true
     * @menu true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $this->title = '系统通知管理';
        $query = BaseUserMessage::mQuery();
        $query->like('name')->equal('status')->dateBetween('create_at');
        $query->where(['deleted' => 0])->order('sort desc,id desc')->page();
    }

    /**
     * 添加系统通知
     * @auth true
     */
    public function add()
    {
        $this->title = '添加系统通知';
        BaseUserMessage::mForm('form');
    }

    /**
     * 编辑系统通知
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑系统通知';
        BaseUserMessage::mForm('form');
    }

    /**
     * 表单结果处理
     * @param boolean $state
     */
    protected function _form_result(bool $state)
    {
        if ($state) {
            $this->success('内容保存成功！', 'javascript:history.back()');
        }
    }

    /**
     * 修改通知状态
     * @auth true
     */
    public function state()
    {
        BaseUserMessage::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除系统通知
     * @auth true
     */
    public function remove()
    {
        BaseUserMessage::mDelete();
    }
}