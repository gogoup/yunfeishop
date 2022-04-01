<?php /*a:3:{s:57:"F:\project\shop\ThinkAdmin\app\admin\view\auth\index.html";i:1648774486;s:52:"F:\project\shop\ThinkAdmin\app\admin\view\table.html";i:1648774486;s:64:"F:\project\shop\ThinkAdmin\app\admin\view\auth\index_search.html";i:1648774486;}*/ ?>
<div class="layui-card"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"><!--<?php if(auth("add")): ?>--><button data-modal='<?php echo url("add"); ?>' data-table-id="RoleTable" class='layui-btn layui-btn-sm layui-btn-primary'>添加权限</button><!--<?php endif; ?>--><!--<?php if(auth("remove")): ?>--><button data-action='<?php echo url("remove"); ?>' data-table-id="RoleTable" data-rule="id#{id}" data-confirm="确定要批量删除权限吗？" class='layui-btn layui-btn-sm layui-btn-primary'>批量删除</button><!--<?php endif; ?>--></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-table"><div class="think-box-shadow"><fieldset><legend>条件搜索</legend><form class="layui-form layui-form-pane form-search" action="<?php echo sysuri(); ?>" onsubmit="return false" method="get" autocomplete="off"><div class="layui-form-item layui-inline"><label class="layui-form-label">权限名称</label><div class="layui-input-inline"><input name="title" value="<?php echo htmlentities((isset($get['title']) && ($get['title'] !== '')?$get['title']:''),ENT_QUOTES); ?>" placeholder="请输入权限名称" class="layui-input"></div></div><div class="layui-form-item layui-inline"><label class="layui-form-label">权限描述</label><div class="layui-input-inline"><input name="desc" value="<?php echo htmlentities((isset($get['desc']) && ($get['desc'] !== '')?$get['desc']:''),ENT_QUOTES); ?>" placeholder="请输入权限描述" class="layui-input"></div></div><div class="layui-form-item layui-inline"><label class="layui-form-label">使用状态</label><div class="layui-input-inline"><select class="layui-select" name="status"><option value=''>-- 全部状态 --</option><?php foreach(['已禁用的权限','已激活的权限'] as $k=>$v): if(isset($get['status']) and $get['status'] == $k.""): ?><option selected value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></option><?php else: ?><option value="<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></option><?php endif; ?><?php endforeach; ?></select></div></div><div class="layui-form-item layui-inline"><label class="layui-form-label">创建时间</label><div class="layui-input-inline"><input data-date-range name="create_at" value="<?php echo htmlentities((isset($get['create_at']) && ($get['create_at'] !== '')?$get['create_at']:''),ENT_QUOTES); ?>" placeholder="请选择创建时间" class="layui-input"></div></div><div class="layui-form-item layui-inline"><button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button></div></form></fieldset><table id="RoleTable" data-url="<?php echo sysuri(); ?>" data-target-search="form.form-search"></table></div></div></div><script>    $(function () {
        // 初始化表格组件
        $('#RoleTable').layTable({
            even: true, height: 'full',
            sort: {field: 'sort desc,id', type: 'desc'},
            cols: [[
                {checkbox: true, fixed: true},
                {field: 'sort', title: '排序权重', align: 'center', width: 100, sort: true, templet: '#SortInputRoleTableTpl'},
                {field: 'title', title: '权限名称', align: 'center', minWidth: 140},
                {field: 'desc', title: '权限描述', align: 'center', minWidth: 110, templet: '<div>{{d.desc||"-"}}</div>'},
                {field: 'status', title: '权限状态', align: 'center', minWidth: 110, templet: '#StatusSwitchRoleTableTpl'},
                {field: 'create_at', title: '创建时间', align: 'center', minWidth: 170, sort: true},
                {toolbar: '#ToolbarRoleTableTpl', title: '操作面板', align: 'center', minWidth: 210, fixed: 'right'},
            ]]
        });

        // 数据状态切换操作
        layui.form.on('switch(StatusSwitchRoleTable)', function (obj) {
            var data = {id: obj.value, status: obj.elem.checked > 0 ? 1 : 0};
            $.form.load("<?php echo url('state'); ?>", data, 'post', function (ret) {
                if (ret.code < 1) $.msg.error(ret.info, 3, function () {
                    $('#RoleTable').trigger('reload');
                });
                return false;
            }, false);
        });
    });

</script><!-- 列表排序权重模板 --><script type="text/html" id="SortInputRoleTableTpl"><input type="number" min="0" data-blur-number="0" data-action-blur="<?php echo sysuri(); ?>" data-value="id#{{d.id}};action#sort;sort#{value}" data-loading="false" value="{{d.sort}}" class="layui-input text-center"></script><!-- 数据状态切换模板 --><script type="text/html" id="StatusSwitchRoleTableTpl"><!--<?php if(auth("state")): ?>--><input type="checkbox" value="{{d.id}}" lay-skin="switch" lay-text="已激活|已禁用" lay-filter="StatusSwitchRoleTable" {{d.status>0?'checked':''}}><!--<?php else: ?>-->    {{d.status ? '<b class="color-green">已启用</b>' : '<b class="color-red">已禁用</b>'}}
    <!--<?php endif; ?>--></script><!-- 数据操作工具条模板 --><script type="text/html" id="ToolbarRoleTableTpl"><!--<?php if(auth('edit')): ?>--><a class="layui-btn layui-btn-primary layui-btn-sm" data-title="编辑权限" data-modal='<?php echo url("edit"); ?>?id={{d.id}}'>编 辑</a><!--<?php endif; ?>--><!--<?php if(auth("apply")): ?>--><a class="layui-btn layui-btn-normal layui-btn-sm" data-open='<?php echo url("apply"); ?>?id={{d.id}}'>授 权</a><!--<?php endif; ?>--><!--<?php if(auth("remove")): ?>--><a class="layui-btn layui-btn-danger layui-btn-sm" data-confirm="确定要删除权限吗?" data-action="<?php echo url('remove'); ?>" data-value="id#{{d.id}}">删 除</a><!--<?php endif; ?>--></script></div>