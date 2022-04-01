<?php /*a:2:{s:57:"F:\project\shop\ThinkAdmin\app\admin\view\auth\apply.html";i:1648774486;s:51:"F:\project\shop\ThinkAdmin\app\admin\view\main.html";i:1648774486;}*/ ?>
<div class="layui-card"><style>    ul.ztree li {
        white-space: normal !important;
    }

    ul.ztree li span.button.switch {
        margin-right: 5px;
    }

    ul.ztree ul ul li {
        display: inline-block;
        white-space: normal;
    }

    ul.ztree > li {
        padding: 15px 25px 15px 15px;
    }

    ul.ztree > li > ul {
        margin-top: 12px;
        border-top: 1px solid rgba(0, 0, 0, .1);
    }

    ul.ztree > li > ul > li {
        padding: 5px;
    }

    ul.ztree > li > a > span {
        font-weight: 700;
        font-size: 15px;
    }

    ul.ztree .level2 .button.level2 {
        background: 0 0;
    }
</style><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-html"><div class="think-box-shadow"><ul id="zTree" class="ztree notselect"></ul><div class="hr-line-dashed"></div><div class="layui-form-item text-center"><button class="layui-btn" data-submit-role type='button'>保存数据</button><button class="layui-btn layui-btn-danger" type='button' onclick="window.history.back()">取消编辑</button></div></div></div></div><script>    require(['jquery.ztree'], function () {
        new function () {
            var that = this;
            this.data = {}, this.ztree = null, this.setting = {
                view: {showLine: false, showIcon: false, dblClickExpand: false},
                check: {enable: true, nocheck: false, chkboxType: {"Y": "ps", "N": "ps"}}, callback: {
                    beforeClick: function (id, node) {
                        node.children.length < 1 ? that.ztree.checkNode(node, !node.checked, null, true) : that.ztree.expandNode(node);
                        return false;
                    }
                }
            };
            this.renderChildren = function (list, level) {
                var childrens = [];
                for (var i in list) childrens.push({
                    open: true, node: list[i].node, name: list[i].title || list[i].node,
                    checked: list[i].checked || false, children: this.renderChildren(list[i]._sub_, level + 1)
                });
                return childrens;
            };
            this.getData = function () {
                $.form.load('<?php echo url("apply"); ?>', {id: '<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>', action: 'get'}, 'post', function (ret) {
                    return (that.data = that.renderChildren(ret.data, 1)), that.showTree(), false;
                });
            };
            this.showTree = function () {
                this.ztree = $.fn.zTree.init($("#zTree"), this.setting, this.data);
                while (true) {
                    var nodes = this.ztree.getNodesByFilter(function (node) {
                        return (!node.node && node.children.length < 1);
                    });
                    if (nodes.length < 1) break;
                    for (var i in nodes) this.ztree.removeNode(nodes[i]);
                }
            };
            this.submit = function () {
                var nodes = [], data = this.ztree.getCheckedNodes(true);
                for (var i in data) if (data[i].node) nodes.push(data[i].node);
                $.form.load('<?php echo url("apply"); ?>', {id: '<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>', action: 'save', nodes: nodes}, 'post');
            };
            // 刷新数据
            this.getData();
            // 提交表单
            $('[data-submit-role]').on('click', function () {
                that.submit();
            });
        };
    });
</script></div>