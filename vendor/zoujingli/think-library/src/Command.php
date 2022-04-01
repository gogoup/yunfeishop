<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2022 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/zoujingli/ThinkLibrary
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace think\admin;

use think\admin\service\ProcessService;
use think\admin\service\QueueService;
use think\console\Input;
use think\console\Output;

/**
 * 自定义指令基类
 * Class Command
 * @package think\admin
 */
abstract class Command extends \think\console\Command
{
    /**
     * 任务控制服务
     * @var QueueService
     */
    protected $queue;

    /**
     * 进程控制服务
     * @var ProcessService
     */
    protected $process;

    /**
     * 初始化指令变量
     * @param Input $input
     * @param Output $output
     * @return static
     * @throws \think\admin\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function initialize(Input $input, Output $output): Command
    {
        $this->queue = QueueService::instance();
        $this->process = ProcessService::instance();
        if (defined('WorkQueueCode')) {
            if (!$this->queue instanceof QueueService) {
                $this->queue = QueueService::instance();
            }
            if ($this->queue->code !== WorkQueueCode) {
                $this->queue->initialize(WorkQueueCode);
            }
        }
        return $this;
    }

    /**
     * 设置失败消息并结束进程
     * @param string $message 消息内容
     * @throws Exception
     */
    protected function setQueueError(string $message)
    {
        if (defined('WorkQueueCode')) {
            $this->queue->error($message);
        } else {
            $this->output->writeln($message);
            exit("\r\n");
        }
    }

    /**
     * 设置成功消息并结束进程
     * @param string $message 消息内容
     * @throws Exception
     */
    protected function setQueueSuccess(string $message)
    {
        if (defined('WorkQueueCode')) {
            $this->queue->success($message);
        } else {
            $this->output->writeln($message);
            exit("\r\n");
        }
    }

    /**
     * 设置进度消息并继续执行
     * @param null|string $message 进度消息
     * @param null|string $progress 进度数值
     * @param integer $backline 回退行数
     * @return static
     */
    protected function setQueueProgress(?string $message = null, ?string $progress = null, int $backline = 0): Command
    {
        if (defined('WorkQueueCode')) {
            $this->queue->progress(2, $message, $progress, $backline);
        } elseif (is_string($message)) {
            $this->output->writeln($message);
        }
        return $this;
    }

    /**
     * 更新任务进度
     * @param integer $total 记录总和
     * @param integer $count 当前记录
     * @param string $message 文字描述
     * @param integer $backline 回退行数
     * @return static
     */
    public function setQueueMessage(int $total, int $count, string $message = '', int $backline = 0): Command
    {
        $total = max($total, 1);
        $prefix = str_pad("{$count}", strlen("{$total}"), '0', STR_PAD_LEFT);
        return $this->setQueueProgress("[{$prefix}/{$total}] {$message}", sprintf("%.2f", $count / $total * 100), $backline);
    }
}