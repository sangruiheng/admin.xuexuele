layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;

    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });
    //按时间搜索
    laydate.render({
        elem: '#jzdateSelect' //指定元素
        ,type: 'datetime'
    });
    //按时间搜索
    laydate.render({
        elem: '#jzdateSelects' //指定元素
        ,type: 'datetime'
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.datetime== ""  && data.field.orderstatus=="" && data.field.orderno==""  && data.field.nickname == "" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.datetime,data.field.orderstatus,data.field.orderno,data.field.nickname);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        getDataLists(1, "", "", "", "");
    });

    //全选按钮
    form.on('checkbox(allChoose)', function(data){
        var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });

    //批量删除
    form.on('submit(batchdelete)', function(data){
        var radio = document.getElementsByName('radio');
        var id = new Array();
        //将所有选中复选框的默认值写入到id数组中 push代表压入数组
        for (var i = 0; i < radio.length; i++) {
            if (radio[i].checked)
                id.push(radio[i].value);
        }

        if(id == ''){
            layer.msg("请选择要批量删除的信息！");
            return false;
        }
        layer.confirm('确认批量删除？', {
            btn: ['确认', '取消'] //按钮
            , title: '<i class="layui-icon"></i> 确认提示'
            , icon: 0
        }, function () {
            $.ajax({
                url: adminurl + "/order/delete",
                data: {
                    id: id,
                    _token: _token,
                },
                type: "DELETE",
                dataType: "json",
                beforeSend: function (request) {
                    index = layer.load(2, {
                        shade: [0.1, '#FFF'] //0.1透明度的白色背景
                    });
                },
                success: function (res) {
                    layer.close(index);
                    if (res.code == 1) {
                        layer.closeAll();
                        layer.msg(res.msg);
                        setTimeout(function () {
                            layer.closeAll();
                            getDataLists(1, "", "", "","");
                        }, 200)
                    } else if (res.code == 1002) {
                        layer.msg(res.msg);
                        setTimeout(function () {
                            location.href = adminurl + "/login";
                        }, 200)
                    } else {
                        layer.msg(res.msg);
                        return false;
                    }
                },
                error: function () {
                    layer.close(index);
                    layer.msg("操作失败！");
                    return false;
                }
            });
        });
    });

    var active = {

        orderstatus: function () {
            var id = $(this).data("id");
            var state = $(this).data("state");
            var auditMsg = "";
            if(state==3){
                auditMsg = "确定要取消订单么？";
            }else{
                auditMsg = "确定要删除已选订单么？";
            }
            layer.confirm(auditMsg, {
                btn: ['确认', '取消'] //按钮
                , title: '<i class="layui-icon"></i> 确认提示'
                , icon: 0
            }, function () {
                $.ajax({
                    url: adminurl + "/order/orderstatus",
                    data: {
                        id: id,
                        status:state,
                        _token: _token,
                    },
                    type: "POST",
                    dataType: "json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1, '#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.close(index);
                        if (res.code == 1) {
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function () {
                                layer.closeAll();
                                getDataLists(location.hash.replace('#!page=',''), "", "", "","","");
                            }, 200)
                        } else if (res.code == 1002) {
                            layer.msg(res.msg);
                            setTimeout(function () {
                                location.href = adminurl + "/login";
                            }, 200)
                        } else {
                            layer.msg(res.msg);
                            return false;
                        }
                    },
                    error: function () {
                        layer.close(index);
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        },

    };

    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    getDataLists(location.hash.replace('#!page=',''), "", "", "", ""); //初始化表格数据
    function getDataLists(page,datetime,orderstatus,orderno,nickname) {
        $.ajax({
            url: adminurl + "/order/lists",
            data: {
                page:page,
                datetime:datetime,
                orderstatus:orderstatus,
                orderno:orderno,
                nickname:nickname,
                paged:20,
                _token: _token
            },
            type: "get",
            dataType: "json",
            beforeSend: function () {
                index = layer.load(2, {
                    shade: [0.1, '#FFF'] //0.1透明度的白色背景
                });
            },
            success: function (res) {
                layer.close(index);
                if (res.code == 1) {
                    innerData = '';
                    if (res.data.total == 0) {
                        innerData += "<tr>";
                        innerData += '<td class="align-center" colspan="19">暂无数据</td>';
                        innerData += '</tr>';
                        laypage.render({elem: 'pages', count: res.data.total});
                    } else {
                        laypage.render({
                            elem: 'pages'
                            ,count: res.data.total
                            ,limit: 20
                            ,layout:['count', 'prev', 'page', 'next', 'limit', 'skip']
                            ,curr: page
                            ,hash:"page"
                            , jump: function (obj, first) {
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if (!first) {
                                    $.ajax({
                                        url: adminurl + "/order/lists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            datetime:datetime,
                                            orderstatus:orderstatus,
                                            orderno:orderno,
                                            nickname:nickname
                                        },
                                        type: "get",
                                        dataType: "json",
                                        beforeSend: function (request) {
                                            index = layer.load(2, {
                                                shade: [0.1, '#FFF'] //0.1透明度的白色背景
                                            });
                                        },
                                        success: function (res) {
                                            layer.close(index);
                                            if (res.code == 1) {
                                                innerData = '';
                                                if (res.count == 0) {
                                                    innerData += "<tr>";
                                                    innerData += '<td class="align-center" colspan="19">暂无数据</td>';
                                                    innerData += '</tr>';
                                                } else {
                                                    floatDataTpl(res.data.lists, obj.curr);
                                                }
                                                $('#listbox').html(innerData);
                                                form.render();
                                            } else if (res.code == 1002) {
                                                layer.msg(res.msg);
                                                setTimeout(function () {
                                                    location.href = adminurl + "/login";
                                                }, 200)
                                            } else {
                                                layer.msg(res.msg);
                                                return false;
                                            }
                                        },
                                        error: function () {
                                            layer.close(index);
                                            layer.msg("操作失败！！");
                                            return false;
                                        }
                                    });
                                } else {
                                    floatDataTpl(res.data.lists, obj.curr);
                                }
                            }
                        });
                    }
                    $('#listbox').html(innerData);
                    form.render();
                } else if (res.code == 1002) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 200)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            },
            error: function () {
                layer.close(index);
                layer.msg("操作失败！！！");
                return false;
            }
        });
    }

    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td><input type="checkbox" name="radio" value="'+item.id+'" lay-skin="primary"></td>';
            innerData += '<td>' + item.order_no + '</td>';
            innerData += '<td>' + item.nickname + '</td>';
            innerData += '<td>' + item.phone + '</td>';
            innerData += '<td>' + item.college_heading + '</td>';
            innerData += '<td>' + item.create_time + '</td>';

            if(item.pay_time !== null || item.pay_time !== undefined || item.pay_time !== ''){
                innerData += '<td>' + item.pay_time + '</td>';
            }else{
                innerData += '<td>--</td>';
            }
            if(item.orderstate == 1){
                innerData += '<td>已付款</td>';
            }else if(item.orderstate == 2) {
                innerData += '<td>待付款</td>';
            }else if(item.orderstate == 3){
                innerData += '<td>已取消</td>';
            }else{
                innerData += '<td></td>';
            }
            innerData += '<td>';
            if(item.orderstate == 2 ) {
                if(view_order) {
                    innerData += '<a  href= "' + adminurl + '/order/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
                }
                if(stop_order) {
                    innerData += '<a data-method="orderstatus" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="' + item.id + '" data-state="3">取消订单</a>';
                }
            }else{
                if(view_order) {
                    innerData += '<a  href= "' + adminurl + '/order/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
                }
                if(delete_order) {
                    innerData += '<a data-method="orderstatus" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="' + item.id + '" data-state="4">删除</a>';
                }
            }
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});