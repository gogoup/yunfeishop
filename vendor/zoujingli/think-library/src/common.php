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

use think\admin\extend\CodeExtend;
use think\admin\extend\HttpExtend;
use think\admin\Helper;
use think\admin\service\AdminService;
use think\admin\service\QueueService;
use think\admin\service\SystemService;
use think\admin\service\TokenService;
use think\admin\Storage;
use think\db\Query;
use think\Model;

if (!function_exists('p')) {
    /**
     * 打印输出数据到文件
     * @param mixed $data 输出的数据
     * @param boolean $new 强制替换文件
     * @param null|string $file 保存文件名称
     * @return false|int
     */
    function p($data, bool $new = false, ?string $file = null)
    {
        return SystemService::instance()->putDebug($data, $new, $file);
    }
}
if (!function_exists('M')) {
    /**
     * 动态创建模型对象
     * @param string $name 模型名称
     * @param array $data 初始数据
     * @param string $conn 指定连接
     * @return Model
     */
    function M(string $name, array $data = [], string $conn = ''): Model
    {
        return Helper::buildModel($name, $data, $conn);
    }
}
if (!function_exists('auth')) {
    /**
     * 访问权限检查
     * @param null|string $node
     * @return boolean
     * @throws ReflectionException
     */
    function auth(?string $node): bool
    {
        return AdminService::instance()->check($node);
    }
}
if (!function_exists('sysuri')) {
    /**
     * 生成最短 URL 地址
     * @param string $url 路由地址
     * @param array $vars PATH 变量
     * @param boolean|string $suffix 后缀
     * @param boolean|string $domain 域名
     * @return string
     */
    function sysuri(string $url = '', array $vars = [], $suffix = true, $domain = false): string
    {
        return SystemService::instance()->sysuri($url, $vars, $suffix, $domain);
    }
}
if (!function_exists('admuri')) {
    /**
     * 生成后台 URL 地址
     * @param string $url 路由地址
     * @param array $vars PATH 变量
     * @param boolean|string $suffix 后缀
     * @param boolean|string $domain 域名
     * @return string
     */
    function admuri(string $url = '', array $vars = [], $suffix = true, $domain = false): string
    {
        return sysuri('admin/index/index') . '#' . url($url, $vars, $suffix, $domain)->build();
    }
}
if (!function_exists('sysconf')) {
    /**
     * 获取或配置系统参数
     * @param string $name 参数名称
     * @param mixed $value 参数内容
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function sysconf(string $name = '', $value = null)
    {
        if (is_null($value) && is_string($name)) {
            return SystemService::instance()->get($name);
        } else {
            return SystemService::instance()->set($name, $value);
        }
    }
}
if (!function_exists('sysdata')) {
    /**
     * JSON 数据读取与存储
     * @param string $name 数据名称
     * @param mixed $value 数据内容
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function sysdata(string $name, $value = null)
    {
        if (is_null($value)) {
            return SystemService::instance()->getData($name);
        } else {
            return SystemService::instance()->setData($name, $value);
        }
    }
}
if (!function_exists('sysqueue')) {
    /**
     * 注册异步处理任务
     * @param string $title 任务名称
     * @param string $command 执行内容
     * @param integer $later 延时执行时间
     * @param array $data 任务附加数据
     * @param integer $rscript 任务类型(0单例,1多例)
     * @param integer $loops 循环等待时间
     * @return string
     * @throws \think\admin\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function sysqueue(string $title, string $command, int $later = 0, array $data = [], int $rscript = 1, int $loops = 0): string
    {
        return QueueService::instance()->register($title, $command, $later, $data, $rscript, $loops)->code;
    }
}
if (!function_exists('xss_safe')) {
    /**
     * 文本内容XSS过滤
     * @param string $text
     * @return string
     */
    function xss_safe(string $text): string
    {
        $rules = [
            '#<script.*?<\/script>#i'        => '',
            '#\s+on\w+=[\'\"]+.*?(\'|\")+#i' => '',
            '#\s+on\w+=\s*.*?(\s|>)+#i'      => '$1',
        ];
        return preg_replace(array_keys($rules), array_values($rules), trim($text));
    }
}
if (!function_exists('systoken')) {
    /**
     * 生成 CSRF-TOKEN 参数
     * @param null|string $node
     * @return string
     */
    function systoken(?string $node = null): string
    {
        $result = TokenService::instance()->buildFormToken($node);
        return $result['token'] ?? '';
    }
}
if (!function_exists('sysoplog')) {
    /**
     * 写入系统日志
     * @param string $action 日志行为
     * @param string $content 日志内容
     * @return boolean
     */
    function sysoplog(string $action, string $content): bool
    {
        return SystemService::instance()->setOplog($action, $content);
    }
}
if (!function_exists('str2arr')) {
    /**
     * 字符串转数组
     * @param string $text 待转内容
     * @param string $separ 分隔字符
     * @param null|array $allow 限定规则
     * @return array
     */
    function str2arr(string $text, string $separ = ',', ?array $allow = null): array
    {
        $items = [];
        foreach (explode($separ, trim($text, $separ)) as $item) {
            if ($item !== '' && (!is_array($allow) || in_array($item, $allow))) {
                $items[] = $item;
            }
        }
        return $items;
    }
}
if (!function_exists('arr2str')) {
    /**
     * 数组转字符串
     * @param array $data 待转数组
     * @param string $separ 分隔字符
     * @param null|array $allow 限定规则
     * @return string
     */
    function arr2str(array $data, string $separ = ',', ?array $allow = null): string
    {
        foreach ($data as $key => $item) {
            if ($item === '' || (is_array($allow) && !in_array($item, $allow))) {
                unset($data[$key]);
            }
        }
        return $separ . join($separ, $data) . $separ;
    }
}
if (!function_exists('encode')) {
    /**
     * 加密 UTF8 字符串
     * @param string $content
     * @return string
     */
    function encode(string $content): string
    {
        [$chars, $length] = ['', strlen($string = iconv('UTF-8', 'GBK//TRANSLIT', $content))];
        for ($i = 0; $i < $length; $i++) $chars .= str_pad(base_convert(ord($string[$i]), 10, 36), 2, 0, 0);
        return $chars;
    }
}
if (!function_exists('decode')) {
    /**
     * 解密 UTF8 字符串
     * @param string $content
     * @return string
     */
    function decode(string $content): string
    {
        $chars = '';
        foreach (str_split($content, 2) as $char) {
            $chars .= chr(intval(base_convert($char, 36, 10)));
        }
        return iconv('GBK//TRANSLIT', 'UTF-8', $chars);
    }
}
if (!function_exists('enbase64url')) {
    /**
     * Base64安全URL编码
     * @param string $string
     * @return string
     */
    function enbase64url(string $string): string
    {
        return CodeExtend::enSafe64($string);
    }
}
if (!function_exists('debase64url')) {
    /**
     * Base64安全URL解码
     * @param string $string
     * @return string
     */
    function debase64url(string $string): string
    {
        return CodeExtend::deSafe64($string);
    }
}
if (!function_exists('http_get')) {
    /**
     * 以get模拟网络请求
     * @param string $url HTTP请求URL地址
     * @param array|string $query GET请求参数
     * @param array $options CURL参数
     * @return boolean|string
     */
    function http_get(string $url, $query = [], array $options = [])
    {
        return HttpExtend::get($url, $query, $options);
    }
}
if (!function_exists('http_post')) {
    /**
     * 以post模拟网络请求
     * @param string $url HTTP请求URL地址
     * @param array|string $data POST请求数据
     * @param array $options CURL参数
     * @return boolean|string
     */
    function http_post(string $url, $data, array $options = [])
    {
        return HttpExtend::post($url, $data, $options);
    }
}
if (!function_exists('data_save')) {
    /**
     * 数据增量保存
     * @param Model|Query|string $dbQuery
     * @param array $data 需要保存或更新的数据
     * @param string $key 条件主键限制
     * @param mixed $where 其它的where条件
     * @return boolean|integer
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function data_save($dbQuery, array $data, string $key = 'id', $where = [])
    {
        return SystemService::instance()->save($dbQuery, $data, $key, $where);
    }
}
if (!function_exists('down_file')) {
    /**
     * 下载远程文件到本地
     * @param string $source 远程文件地址
     * @param boolean $force 是否强制重新下载
     * @param integer $expire 强制本地存储时间
     * @return string
     */
    function down_file(string $source, bool $force = false, int $expire = 0): string
    {
        return Storage::down($source, $force, $expire)['url'] ?? $source;
    }
}
if (!function_exists('format_bytes')) {
    /**
     * 文件字节单位转换
     * @param string|integer $size
     * @return string
     */
    function format_bytes($size): string
    {
        if (is_numeric($size)) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
            return round($size, 2) . ' ' . $units[$i];
        } else {
            return $size;
        }
    }
}
if (!function_exists('format_datetime')) {
    /**
     * 日期格式标准输出
     * @param int|string $datetime 输入日期
     * @param string $format 输出格式
     * @return string
     */
    function format_datetime($datetime, string $format = 'Y年m月d日 H:i:s'): string
    {
        if (empty($datetime)) return '-';
        if (is_numeric($datetime)) {
            return date($format, $datetime);
        } else {
            return date($format, strtotime($datetime));
        }
    }
}
if (!function_exists('trace_file')) {
    /**
     * 输出异常数据到文件
     * @param \Exception $exception
     * @return void
     */
    function trace_file(Exception $exception)
    {
        $path = app()->getRuntimePath() . 'trace';
        if (!file_exists($path)) mkdir($path, 0755, true);
        $name = substr($exception->getFile(), strlen(app()->getRootPath()));
        $file = $path . DIRECTORY_SEPARATOR . date('Ymd_His_') . strtr($name, ['/' => '.', '\\' => '.']);
        $class = get_class($exception);
        file_put_contents($file,
            "[CODE] {$exception->getCode()}" . PHP_EOL .
            "[INFO] {$exception->getMessage()}" . PHP_EOL .
            "[FILE] {$class} in {$name} line {$exception->getLine()}" . PHP_EOL .
            "[TIME] " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL .
            '[TRACE]' . PHP_EOL . $exception->getTraceAsString()
        );
    }
}