<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2022 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免费声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\admin\Controller;
use think\admin\service\AdminService;
use think\admin\service\ModuleService;
use think\admin\service\SystemService;
use think\admin\storage\AliossStorage;
use think\admin\storage\QiniuStorage;
use think\admin\storage\TxcosStorage;

/**
 * 系统参数配置
 * Class Config
 * @package app\admin\controller
 */
class Config extends Controller
{
    const themes = [
        'default' => '默认色0',
        'white'   => '简约白0',
        'red-1'   => '玫瑰红1',
        'blue-1'  => '深空蓝1',
        'black-1' => '经典黑1',
        'green-1' => '小草绿1',
        'red-2'   => '玫瑰红2',
        'blue-2'  => '深空蓝2',
        'black-2' => '经典黑2',
        'green-2' => '小草绿2',
    ];

    /**
     * 系统参数配置
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '系统参数配置';
        $this->super = AdminService::instance()->isSuper();
        $this->version = ModuleService::instance()->getVersion();
        $this->fetch();
    }

    /**
     * 修改系统参数
     * @auth true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function system()
    {
        $this->_applyFormToken();
        if ($this->request->isGet()) {
            $this->title = '修改系统参数';
            $this->themes = static::themes;
            $this->fetch();
        } else {
            $post = $this->request->post();
            // 修改网站后台入口路径，配置运行环境变量
            if (!empty($post['xpath'])) {
                if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $post['xpath'])) {
                    $this->error('后台入口名称需要是由英文字母开头！');
                }
                if ($post['xpath'] !== 'admin' && file_exists($this->app->getBasePath() . $post['xpath'])) {
                    $this->error("后台入口名称{$post['xpath']}已经存在应用！");
                }
                SystemService::instance()->setRuntime(null, [$post['xpath'] => 'admin']);
            }
            // 修改网站 ICON 图标文件，替换 public/favicon.ico 文件
            if (preg_match('#^https?://#', $icon = $post['site_icon'] ?? '')) try {
                SystemService::instance()->setFavicon($icon);
            } catch (\Exception $exception) {
                trace_file($exception);
                $this->error($exception->getMessage());
            }
            // 数据数据到系统配置表
            foreach ($post as $k => $v) sysconf($k, $v);
            sysoplog('系统配置管理', "修改系统参数成功");
            $this->success('修改系统参数成功！', 'javascript:location.reload()');
        }
    }

    /**
     * 修改文件存储
     * @auth true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storage()
    {
        $this->_applyFormToken();
        if ($this->request->isGet()) {
            $this->type = input('type', 'local');
            if ($this->type === 'alioss') {
                $this->points = AliossStorage::region();
            } elseif ($this->type === 'qiniu') {
                $this->points = QiniuStorage::region();
            } elseif ($this->type === 'txcos') {
                $this->points = TxcosStorage::region();
            }
            $this->fetch("storage-{$this->type}");
        } else {
            $post = $this->request->post();
            if (!empty($post['storage']['allow_exts'])) {
                $deny = ['sh', 'asp', 'bat', 'cmd', 'exe', 'php'];
                $exts = array_unique(str2arr(strtolower($post['storage']['allow_exts'])));
                if (count(array_intersect($deny, $exts)) > 0) $this->error('禁止上传可执行的文件！');
                $post['storage']['allow_exts'] = join(',', $exts);
            }
            foreach ($post as $name => $value) sysconf($name, $value);
            sysoplog('系统配置管理', "修改系统存储参数");
            $this->success('修改文件存储成功！');
        }
    }
}