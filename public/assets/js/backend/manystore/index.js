define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'manystore/index/index',
                    add_url: 'manystore/index/add',
                    edit_url: 'manystore/index/edit',
                    del_url: 'manystore/index/del',
                    multi_url: 'manystore/index/multi',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID'},
                        {field: 'username', title: __('Username')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'email', title: __('Email')},
                        {field: 'status', title: __("Status"), formatter: Table.api.formatter.status},
                        {field: 'logintime', title: __('Login time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                                return Table.api.formatter.operate.call(this, value, row, index);
                            }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $("#c-address_city").on("cp:updated", function() {
                var citypicker = $(this).data("citypicker");
                var province = citypicker.getCode("province");
                var city = citypicker.getCode("city");
                var district = citypicker.getCode("district");
                if(province){
                    $("#province").val(province);
                }
                if(city){
                    $("#city").val(city);
                }
                if(district){
                    $("#district").val(district);
                }
                $(this).blur();
            });
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            $("#c-address_city").on("cp:updated", function() {
                var citypicker = $(this).data("citypicker");
                var province = citypicker.getCode("province");
                var city = citypicker.getCode("city");
                var district = citypicker.getCode("district");
                if(province){
                    $("#province").val(province);
                }
                if(city){
                    $("#city").val(city);
                }
                if(district){
                    $("#district").val(district);
                }
            });
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});
