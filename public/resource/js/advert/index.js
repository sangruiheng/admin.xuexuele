layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if ( data.field.name== "" && data.field.id=="" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.name,data.field.id);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    getDataLists(location.hash.replace('#!page=',''), "", ""); //初始化表格数据
    function getDataLists(page,name,id) {
        $.ajax({
            url: adminurl + "/advert/getadvert",
            data: {
                page:page,
                name:name,
                id:id,
                paged:15,
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
                        innerData += '<td class="align-center" colspan="6">暂无数据</td>';
                        innerData += '</tr>';
                        laypage.render({elem: 'pages', count: res.data.total});
                    } else {
                        laypage.render({
                            elem: 'pages'
                            ,count: res.data.total
                            ,limit: 15
                            ,layout:['count', 'prev', 'page', 'next', 'limit', 'skip']
                            ,curr: page
                            ,hash:"page"
                            , jump: function (obj, first) {
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if (!first) {
                                    $.ajax({
                                        url: adminurl + "/advert/getadvert",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            
                                            id:id,
                                            name:name,
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
                                                    innerData += '<td class="align-center" colspan="6">暂无数据</td>';
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


    var active = {
        delete: function () {
            var id = $(this).data("id");
            var state = $(this).data("state");
            var auditMsg = "";
            if(state == 1){
                auditMsg = "确定删除操作么？";
            }else{
                auditMsg = "确定删除操作么？";
            }
            layer.confirm(auditMsg, {
                btn: ['确认', '取消'] //按钮
                , title: '<i class="layui-icon"></i> 确认提示'
                , icon: 0
            }, function () {
                $.ajax({
                    url: adminurl + "/gatealert/deletegatealert",
                    data: {
                        id: id,
                        status:state,
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

    //禁用 启用
    $("body").on("click", ".btn-disable", function () {
        var that=$(this);
        var advID = that.find('a').attr('data-id');
        var is_disable = that.find('a').attr('status-id');
            $.ajax({
            url: adminurl + "/advert/disableadvert",
            data: {
                adv_id:advID,
                is_disable:is_disable,
                _token: _token
            },
            type: "get",
            dataType: "json",
            // beforeSend: function () {
            //     index = layer.load(2, {
            //         shade: [0.1, '#FFF'] //0.1透明度的白色背景
            //     });
            // },
            success: function (res) {
                console.log(res);
                if(res.code == 1){
                    that.find('a').attr('status-id',res.data);
                    if(res.data == 0){
                        that.find('a').addClass("layui-bg-red");
                        that.find('a').removeClass("layui-bg-orange");
                        that.find('a').html('禁用');
                    }else if(res.data == 1){
                        that.find('a').addClass("layui-bg-orange");
                        that.find('a').removeClass("layui-bg-red");
                        that.find('a').html('启用');
                    }

                }
            },
            error: function () {
                layer.msg("操作失败！！！");
                return false;
            }
        });

    });


    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.title + '</td>';
            innerData += '<td>' + item.url + '</td>';
            // innerData += '<td>' + item.image_path + '</td>';
            innerData += '<td><img style="cursor: pointer" data-method="viewGoodsBigImg" class="handle"  src=" ' +item.image_path+'" height="30px"></td>';

            innerData += '<td>';
            innerData += '<a  href= "' + adminurl + '/advert/editadvert/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '"  data-state="2"><i class="layui-icon"></i>修改</a>';
            if(item.is_disable == 0){
                innerData += '<buttun class="btn-disable"> <a class="layui-btn layui-btn-sm layui-bg-red" data-id="' + item.id + '"  data-state="2" status-id="'+item.is_disable+'"> <i class="layui-icon"></i>禁用</a></buttun>';
            }else{
                innerData += '<buttun class="btn-disable"> <a class="layui-btn layui-btn-sm layui-bg-red" data-id="' + item.id + '"  data-state="2" status-id="'+item.is_disable+'"> <i class="layui-icon"></i>启用</a></buttun>';
            }
            // innerData += '<a data-method="delete" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2">删除</a>';
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});