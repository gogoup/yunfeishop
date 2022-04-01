define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'manystore/config/index' + location.search,
                    add_url: 'manystore/config/add',
                    edit_url: 'manystore/config/edit',
                    del_url: 'manystore/config/del',
                    multi_url: 'manystore/config/multi',
                    table: 'manystore/config',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'group', title: __('Group')},
                        {field: 'title', title: __('Title')},
                        {field: 'tip', title: __('Tip')},
                        {field: 'type', title: __('Type')},
                        {field: 'rule', title: __('Rule')},
                        {field: 'extend', title: __('Extend')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        add: function () {

            //不可见的元素不验证
            $("form#add-form").data("validator-options", {
                ignore: ':hidden',
                rules: {
                    content: function () {
                        return ['radio', 'checkbox', 'select', 'selects'].indexOf($("#add-form select[name='row[type]']").val()) > -1;
                    },
                    extend: function () {
                        return $("#add-form select[name='row[type]']").val() == 'custom';
                    }
                }
            });

            //渲染关联显示字段和存储字段
            var renderselect = function (id, data, defaultvalue) {
                var html = [];
                for (var i = 0; i < data.length; i++) {
                    html.push("<option value='" + data[i].name + "' " + (defaultvalue == data[i].name ? "selected" : "") + " data-subtext='" + data[i].title + "'>" + data[i].name + "</option>");
                }
                var select = $(id);
                $(select).html(html.join(""));
                select.trigger("change");
                if (select.data("selectpicker")) {
                    select.selectpicker('refresh');
                }
            };
            //关联表切换
            $(document).on('change', "#c-selectpage-table", function (e, first) {
                var that = this;
                Fast.api.ajax({
                    url: "general/config/get_fields_list",
                    data: {table: $(that).val()},
                }, function (data, ret) {
                    renderselect("#c-selectpage-primarykey", data.fieldList, first ? $("#c-selectpage-primarykey").data("value") : '');
                    renderselect("#c-selectpage-field", data.fieldList, first ? $("#c-selectpage-field").data("value") : '');
                    return false;
                });
                return false;
            });
            //如果编辑模式则渲染已知数据
            if (['selectpage', 'selectpages'].indexOf($("#c-type").val()) > -1) {
                $("#c-selectpage-table").trigger("change", true);
            }

            //切换类型时
            $(document).on("change", "#c-type", function () {
                var value = $(this).val();
                $(".tf").addClass("hidden");
                $(".tf.tf-" + value).removeClass("hidden");
                if (["selectpage", "selectpages"].indexOf(value) > -1 && $("#c-selectpage-table option").size() == 1) {
                    //异步加载表列表
                    Fast.api.ajax({
                        url: "manystore/config/get_table_list",
                    }, function (data, ret) {
                        renderselect("#c-selectpage-table", data.tableList);
                        return false;
                    });
                }
            });

            //切换显示隐藏变量字典列表
            $(document).on("change", "#add-form select[name='row[type]']", function (e) {
                $("#add-content-container").toggleClass("hide", ['select', 'selects', 'checkbox', 'radio'].indexOf($(this).val()) > -1 ? false : true);
            });

            //选择规则
            $(document).on("click", ".rulelist > li > a", function () {
                var ruleArr = $("#rule").val() == '' ? [] : $("#rule").val().split(";");
                var rule = $(this).data("value");
                var index = ruleArr.indexOf(rule);
                if (index > -1) {
                    ruleArr.splice(index, 1);
                } else {
                    ruleArr.push(rule);
                }
                $("#rule").val(ruleArr.join(";"));
                $(this).parent().toggleClass("active");
            });

            Controller.api.bindevent();
        },
        edit: function () {

            $("form#edit-form").data("validator-options", {
                ignore: ':hidden',
                rules: {
                    content: function () {
                        return ['radio', 'checkbox', 'select', 'selects'].indexOf($("#add-form select[name='row[type]']").val()) > -1;
                    },
                    extend: function () {
                        return $("#edit-form select[name='row[type]']").val() == 'custom';
                    }
                }
            });

            //切换显示隐藏变量字典列表
            $(document).on("change", "form#edit-form select[name='row[type]']", function (e) {
                $("#add-content-container").toggleClass("hide", ['select', 'selects', 'checkbox', 'radio'].indexOf($(this).val()) > -1 ? false : true);
            });

            $("#add-content-container").toggleClass("hide", ['select', 'selects', 'checkbox', 'radio'].indexOf($("form#edit-form select[name='row[type]']").val()) > -1 ? false : true);

            $(".rulelist > li > a").each(function(){
                var ruleArr = $("#rule").val() == '' ? [] : $("#rule").val().split(";");
                var rule = $(this).data("value");
                var index = ruleArr.indexOf(rule);
                if (index > -1) {
                    $(this).parent().toggleClass("active");
                }
            })


            //渲染关联显示字段和存储字段
            var renderselect = function (id, data, defaultvalue) {
                console.log(defaultvalue);
                var html = [];
                for (var i = 0; i < data.length; i++) {
                    html.push("<option value='" + data[i].name + "' " + (defaultvalue == data[i].name ? "selected" : "") + " data-subtext='" + data[i].title + "'>" + data[i].name + "</option>");
                }
                var select = $(id);
                $(select).html(html.join(""));
                select.trigger("change");
                if (select.data("selectpicker")) {
                    select.selectpicker('refresh');
                }
            };
            //关联表切换
            $(document).on('change', "#c-selectpage-table", function (e, first) {
                var that = this;
                Fast.api.ajax({
                    url: "manystore/config/get_fields_list",
                    data: {table: $(that).val()},
                }, function (data, ret) {
                    renderselect("#c-selectpage-primarykey", data.fieldList, first ? $("#c-selectpage-primarykey").data("value") : '');
                    renderselect("#c-selectpage-field", data.fieldList, first ? $("#c-selectpage-field").data("value") : '');
                    return false;
                });
                return false;
            });
            //如果编辑模式则渲染已知数据
            if (['selectpage', 'selectpages'].indexOf($("#c-type").val()) > -1) {
                $("#c-selectpage-table").trigger("change", true);
            }

            //切换类型时
            $(document).on("change", "#c-type", function () {
                var value = $(this).val();
                $(".tf").addClass("hidden");
                $(".tf.tf-" + value).removeClass("hidden");
                if (["selectpage", "selectpages"].indexOf(value) > -1 && $("#c-selectpage-table option").size() == 1) {
                    //异步加载表列表
                    Fast.api.ajax({
                        url: "manystore/config/get_table_list",
                    }, function (data, ret) {
                        renderselect("#c-selectpage-table", data.tableList);
                        return false;
                    });
                }
            });


            var value = $('#c-type').val();
            $(".tf").addClass("hidden");
            $(".tf.tf-" + value).removeClass("hidden");
            if (["selectpage", "selectpages"].indexOf(value) > -1 && $("#c-selectpage-table option").size() == 1) {
                //异步加载表列表
                Fast.api.ajax({
                    url: "manystore/config/get_table_list",
                }, function (data, ret) {
                    var value = $("#c-selectpage-table").data("value");
                    renderselect("#c-selectpage-table", data.tableList,value);
                    Fast.api.ajax({
                        url: "manystore/config/get_fields_list",
                        data: {table: value },
                    }, function (data, ret) {
                        renderselect("#c-selectpage-primarykey", data.fieldList,  $("#c-selectpage-primarykey").data("value"));
                        renderselect("#c-selectpage-field", data.fieldList, $("#c-selectpage-field").data("value"));
                        return false;
                    });
                    return false;
                });
            }


            //选择规则
            $(document).on("click", ".rulelist > li > a", function () {
                var ruleArr = $("#rule").val() == '' ? [] : $("#rule").val().split(";");
                var rule = $(this).data("value");
                var index = ruleArr.indexOf(rule);
                if (index > -1) {
                    ruleArr.splice(index, 1);
                } else {
                    ruleArr.push(rule);
                }
                $("#rule").val(ruleArr.join(";"));
                $(this).parent().toggleClass("active");
            });

            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
