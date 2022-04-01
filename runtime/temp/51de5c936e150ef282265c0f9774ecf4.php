<?php /*a:2:{s:57:"F:\project\shop\ThinkAdmin\app\admin\view\menu\index.html";i:1648774486;s:52:"F:\project\shop\ThinkAdmin\app\admin\view\table.html";i:1648774486;}*/ ?>
<div class="layui-card"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"><!--<?php if($type == 'index' and auth("add")): ?>--><button data-modal='<?php echo url("add"); ?>' class='layui-btn layui-btn-sm layui-btn-primary'>添加菜单</button><!--<?php endif; ?>--><!--<?php if($type == 'index' and auth("state")): ?>--><button data-action='<?php echo url("state"); ?>' data-rule="id#{key};status#0" class='layui-btn layui-btn-sm layui-btn-primary'>禁用菜单</button><!--<?php endif; ?>--><!--<?php if($type == 'recycle' and auth("state")): ?>--><button data-action='<?php echo url("state"); ?>' data-rule="id#{key};status#1" class='layui-btn layui-btn-sm layui-btn-primary'>激活菜单</button><!--<?php endif; ?>--></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-table"><div class="layui-tab layui-tab-card"><ul class="layui-tab-title"><?php foreach(['index'=>'系统菜单','recycle'=>'回 收 站'] as $k=>$v): if(isset($type) and $type == $k): ?><li class="layui-this color-green" data-open="<?php echo url('index'); ?>?type=<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></li><?php else: ?><li data-open="<?php echo url('index'); ?>?type=<?php echo htmlentities($k,ENT_QUOTES); ?>"><?php echo htmlentities($v,ENT_QUOTES); ?></li><?php endif; ?><?php endforeach; ?></ul><div class="layui-tab-content"><table id="MenuTable" data-url="<?php echo sysuri(); ?>" data-target-search="form.form-search"></table></div></div><script>    $(function () {
        $('#MenuTable').layTable({
            even: true, height: 'full', page: false,
            sort: {field: 'sort desc,id', type: 'asc'},
            where: {type: '<?php echo htmlentities((isset($type) && ($type !== '')?$type:"index"),ENT_QUOTES); ?>'},
            filter: function (items) {
                var type = this.where.type;
                return items.filter(function (item) {
                    return !(type === 'index' && item.status === 0);
                });
            },
            cols: [[
                {checkbox: true, field: 'sps'},
                {field: 'sort', title: '排序权重', width: 100, align: 'center', templet: '#SortInputTpl'},
                {
                    field: 'icon', title: '图 标', width: 80, align: 'center', templet: function (d) {
                        return layui.laytpl('<i class="{{d.icon}} font-s18"></i>').render(d);
                    }
                },
                {
                    field: 'title', title: '菜单名称', minWidth: 220, templet: function (d) {
                        return layui.laytpl('<span class="color-desc">{{d.spl}}</span>{{d.title}}').render(d);
                    }
                },
                {field: 'url', title: '跳转链接', minWidth: 200},
                {field: 'status', title: '菜单状态', minWidth: 120, align: 'center', templet: '#StatusSwitchTpl'},
                // {field: 'create_at', title: '创建时间', minWidth: 170, align: 'center'},
                {toolbar: '#toolbar', title: '操作面板', minWidth: 150, align: 'center', fixed: 'right'},
            ]]
        });

        // 数据状态切换操作
        layui.form.on('switch(StatusSwitch)', function (obj) {
            var data = {id: obj.value, status: obj.elem.checked > 0 ? 1 : 0};
            if (data.status > 0) data.id += obj.elem.dataset.path.replace(/-/g, ',');
            $.form.load("<?php echo url('state'); ?>", data, 'post', function (ret) {
                if (ret.code < 1) $.msg.error(ret.info, 3, function () {
                    $('#MenuTable').trigger('reload');
                }); else $('#MenuTable').trigger('reload');
                return false;
            }, false);
        });
    });
</script><!-- 数据状态切换模板 --><script type="text/html" id="StatusSwitchTpl"><!--<?php if(auth("state")): ?>-->    {{# if( "<?php echo htmlentities((isset($type) && ($type !== '')?$type:'index'),ENT_QUOTES); ?>"==='index' || (d.spc<1 || d.status<1)){ }}
    <input type="checkbox" value="{{d.sps}}" data-path="{{d.path}}" lay-skin="switch" lay-text="已激活|已禁用" lay-filter="StatusSwitch" {{d.status>0?'checked':''}}>    {{# }else{ }}
    {{d.status ? '<b class="color-green">已激活</b>' : '<b class="color-red">已禁用</b>'}}
    {{# } }}
    <!--<?php else: ?>-->    {{d.status ? '<b class="color-green">已激活</b>' : '<b class="color-red">已禁用</b>'}}
    <!--<?php endif; ?>--></script><!-- 列表排序权重模板 --><script type="text/html" id="SortInputTpl"><input type="number" min="0" data-blur-number="0" data-action-blur="<?php echo sysuri(); ?>" data-value="id#{{d.id}};action#sort;sort#{value}" data-loading="false" value="{{d.sort}}" class="layui-input text-center"></script><!-- 操控面板的模板 --><script type="text/html" id="toolbar"><!-- <?php if(isset($type) and $type == 'index'): ?> --><!-- <?php if(auth('add')): ?> -->    {{# if(d.spt<2){ }}
    <a class="layui-btn layui-btn-sm layui-btn-primary" data-title="添加系统菜单" data-modal='<?php echo url("add"); ?>?pid={{d.id}}'>添 加</a>    {{# }else{ }}
    <a class="layui-btn layui-btn-sm layui-btn-disabled">添 加</a>    {{# } }}
    <!-- <?php endif; ?> --><?php if(auth('edit')): ?><a class="layui-btn layui-btn-sm" data-title="编辑系统菜单" data-modal='<?php echo url("edit"); ?>?id={{d.id}}'>编 辑</a><?php endif; ?><!-- <?php else: ?> --><?php if(auth('remove')): ?>    {{# if( (d.spc<1 || d.status<1)){ }}
    <a class="layui-btn layui-btn-sm layui-btn-danger" data-confirm="确定要删除菜单吗？" data-action="<?php echo url('remove'); ?>" data-value="id#{{d.sps}}">删 除</a>    {{# }else{ }}
    <a class="layui-btn layui-btn-disabled layui-btn-sm">删 除</a>    {{# } }}
    <?php endif; ?><!-- <?php endif; ?> --></script></div></div></div>