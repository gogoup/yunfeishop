<?php /*a:3:{s:64:"F:\project\shop\ThinkAdmin\app\data\view\base\payment\index.html";i:1648774486;s:67:"F:\project\shop\ThinkAdmin\app\data\view\..\..\admin\view\main.html";i:1648774486;s:71:"F:\project\shop\ThinkAdmin\app\data\view\base\payment\index_search.html";i:1648774486;}*/ ?>
<div class="layui-card"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"><!--<?php if(auth("add")): ?>--><button class='layui-btn layui-btn-sm layui-btn-primary' data-open='<?php echo url("add"); ?>'>添加支付</button><!--<?php endif; ?>--><!--<?php if(auth("remove")): ?>--><button class='layui-btn layui-btn-sm layui-btn-primary' data-action='<?php echo url("remove"); ?>' data-confirm="确定要删除这些支付吗？" data-rule="id#{key}">删除支付</button><!--<?php endif; ?>--></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-html"><div class="think-box-shadow"><fieldset><legend>条件搜索</legend><form action="<?php echo sysuri(); ?>" autocomplete="off" class="layui-form layui-form-pane form-search" method="get" onsubmit="return false"><div class="layui-form-item layui-inline"><label class="layui-form-label">支付名称</label><label class="layui-input-inline"><input class="layui-input" name="name" placeholder="请输入支付名称" value="<?php echo htmlentities((isset($get['name']) && ($get['name'] !== '')?$get['name']:''),ENT_QUOTES); ?>"></label></div><div class="layui-form-item layui-inline"><label class="layui-form-label">支付编号</label><label class="layui-input-inline"><input class="layui-input" name="code" placeholder="请输入支付编号" value="<?php echo htmlentities((isset($get['code']) && ($get['code'] !== '')?$get['code']:''),ENT_QUOTES); ?>"></label></div><div class="layui-form-item layui-inline"><label class="layui-form-label">支付方式</label><div class="layui-input-inline"><select class="layui-select" name="type"><option value="">-- 全部 --</option><?php foreach($types as $k=>$v): if(!empty($v['bind'])): if($k == input('type')): ?><option selected value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v['name'],ENT_QUOTES); ?></option><?php else: ?><option value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v['name'],ENT_QUOTES); ?></option><?php endif; ?><?php endif; ?><?php endforeach; ?></select></div></div><div class="layui-form-item layui-inline"><label class="layui-form-label">使用状态</label><div class="layui-input-inline"><select class="layui-select" name="status"><option value="">-- 全部 --</option><?php foreach(['已禁用的记录', '已激活的记录'] as $k=>$v): if($k.'' == input('status')): ?><option selected value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></option><?php else: ?><option value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></option><?php endif; ?><?php endforeach; ?></select></div></div><div class="layui-form-item layui-inline"><label class="layui-form-label">创建时间</label><label class="layui-input-inline"><input class="layui-input" data-date-range name="create_at" placeholder="请选择创建时间" value="<?php echo htmlentities((isset($get['create_at']) && ($get['create_at'] !== '')?$get['create_at']:''),ENT_QUOTES); ?>"></label></div><div class="layui-form-item layui-inline"><button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button></div></form></fieldset><table class="layui-table margin-top-10" lay-skin="line"><?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?><thead><tr><th class='list-table-check-td think-checkbox'><label><input data-auto-none data-check-target='.list-check-box' type='checkbox'></label></th><th class='list-table-sort-td'><button class="layui-btn layui-btn-xs" data-reload type="button">刷 新</button></th><th class="text-left nowrap">支付名称</th><th class="text-left nowrap">支付编号</th><th class="text-left nowrap">支付方式</th><th class="text-left nowrap">使用状态</th><th class="text-left nowrap">创建时间</th><th class="text-left nowrap"></th></tr></thead><?php endif; ?><tbody><?php foreach($list as $key=>$vo): ?><tr><td class='list-table-check-td think-checkbox'><label><input class="list-check-box" type='checkbox' value='<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>'></label></td><td class='list-table-sort-td'><label><input class="list-sort-input" data-action-blur="<?php echo sysuri(); ?>" data-loading="false" data-value="id#<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>;action#sort;sort#{value}" value="<?php echo htmlentities($vo['sort'],ENT_QUOTES); ?>"></label></td><td class="text-left nowrap"><div class="headimg headimg-xs headimg-no" data-tips-hover data-tips-image data-lazy-src="<?php echo htmlentities($vo['cover'],ENT_QUOTES); ?>"></div><?php echo htmlentities((isset($vo['name']) && ($vo['name'] !== '')?$vo['name']:''),ENT_QUOTES); ?></td><td class="text-left nowrap"><?php echo htmlentities((isset($vo['code']) && ($vo['code'] !== '')?$vo['code']:''),ENT_QUOTES); ?></td><td class="text-left nowrap"><?php echo isset($types[$vo['type']]['name']) ? htmlentities($types[$vo['type']]['name'],ENT_QUOTES) : htmlentities($vo['type'],ENT_QUOTES); ?></td><td><?php if($vo['status'] == 0): ?><span class="color-red">已禁用</span><?php elseif($vo['status'] == 1): ?><span class="color-green">已激活</span><?php endif; ?></td><td class="text-left nowrap"><?php echo htmlentities(format_datetime($vo['create_at']),ENT_QUOTES); ?></td><td class='text-left nowrap'><!--<?php if(auth("edit")): ?>--><a class="layui-btn layui-btn-sm" data-open="<?php echo url('edit'); ?>?id=<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>">编 辑</a><!--<?php endif; ?>--><!--<?php if(auth("state") and $vo['status'] == 1): ?>--><a class="layui-btn layui-btn-sm layui-btn-warm" data-action="<?php echo url('state'); ?>" data-value="id#<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>;status#0">禁 用</a><!--<?php elseif(auth("state") and $vo['status'] == 0): ?>--><a class="layui-btn layui-btn-sm layui-btn-warm" data-action="<?php echo url('state'); ?>" data-value="id#<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>;status#1">激 活</a><!--<?php endif; ?>--><!--<?php if(auth("remove")): ?>--><a class="layui-btn layui-btn-sm layui-btn-danger" data-action="<?php echo url('remove'); ?>" data-confirm="确定要删除该支付吗？" data-value="id#<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>">删 除</a><!--<?php endif; ?>--></td></tr><?php endforeach; ?></tbody></table><?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?><span class="notdata">没有记录哦</span><?php else: ?><?php echo (isset($pagehtml) && ($pagehtml !== '')?$pagehtml:''); ?><?php endif; ?></div></div></div></div>