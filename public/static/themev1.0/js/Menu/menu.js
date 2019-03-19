layui.use(['element','jquery','layer','form'], function(){
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    var active = {
        addMenu: function(){
            var that = this;
            //多窗口模式，层叠置顶
            layer.open({
                type: 1 //此处以iframe举例
                ,title: '<i class="layui-icon">&#xe608;</i> 添加菜单'
                ,area: ['390px', 'auto']
                ,shade: 0.8
                ,maxmin: false
                ,content: $("#addMenu")
                ,btn: ['保存', '关闭'] //只是为了演示
                ,yes: function(){
                    if($("#menuname").val()==""){
                        $("#menuname").focus();
                        layer.msg("菜单名称不能为空！");
                        return false;
                    }
                    if($("#memuurl").val()==""){
                        $("#memuurl").focus();
                        layer.msg("菜单URL不能为空！");
                        return false;
                    }
                    if($("#menuicon").val()==""){
                        $("#menuicon").focus();
                        layer.msg("菜单图标不能为空！");
                        return false;
                    }
                    $.ajax({
                        url: adminurl + "/menus",
                        data:$("#addMenu").serialize(),
                        type:"POST",
                        dataType:"json",
                        beforeSend: function (request) {
                            index = layer.load(2, {
                                shade: [0.1,'#FFF'] //0.1透明度的白色背景
                            });
                        },
                        success:function(res){
                            layer.close(index);
                            if(res.code==1){
                                layer.msg(res.msg);
                                setTimeout(function(){
                                    location.reload();
                                },1000)
                            }else if(res.code==1002){
                                layer.msg(res.msg);
                                setTimeout(function(){
                                    location.href= adminurl + "/login";
                                },500)
                            }else{
                                layer.msg(res.msg);
                                return false;
                            }
                        },
                        error:function(){
                            layer.close(index);
                            layer.msg("操作失败！");
                            return false;
                        }
                    });
                }
                ,btn2: function(){
                    layer.closeAll();
                }
                ,zIndex: layer.zIndex //重点1
            });
        }
        ,editMenu: function(){
            var that = this;
            $("#menuname").val($(this).data("title"));
            $("#memuurl").val($(this).data("url"));
            $("#menuicon").val($(this).data("icon_class"));
            $("#menuId").val($(this).data("id"));
            var parent_id = $(this).data("parent_id");
            $("#parent").val(parent_id);
            form.render();
            layer.open({
                type: 1 //此处以iframe举例
                ,title: '<i class="layui-icon">&#xe608;</i> 编辑菜单'
                ,area: ['390px', 'auto']
                ,shade: 0.8
                ,maxmin: false
                ,content: $("#addMenu")
                ,btn: ['保存', '关闭'] //只是为了演示
                ,yes: function(){
                    if($("#menuname").val()==""){
                        $("#menuname").focus();
                        return false;
                    }
                    if($("#memuurl").val()==""){
                        $("#memuurl").focus();
                        return false;
                    }
                    if($("#menuicon").val()==""){
                        $("#menuicon").focus();
                        return false;
                    }
                    $.ajax({
                        url: adminurl + "/menus/update",
                        data:$("#addMenu").serialize(),
                        type:"PUT",
                        dataType:"json",
                        beforeSend: function (request) {
                            index = layer.load(2, {
                                shade: [0.1,'#FFF'] //0.1透明度的白色背景
                            });
                        },
                        success:function(res){
                            layer.close(index);
                            if(res.code==1){
                                layer.closeAll();
                                layer.msg(res.msg);
                                setTimeout(function(){
                                    location.reload();
                                },1000)
                            }else if(res.code==1002){
                                layer.msg(res.msg);
                                setTimeout(function(){
                                    location.href= adminurl + "/login";
                                },500)
                            }else{
                                layer.msg(res.msg);
                                return false;
                            }
                        },
                        error:function(){
                            layer.close(index);
                            layer.msg("操作失败！");
                            return false;
                        }
                    });
                }
                ,btn2: function(){
                    layer.closeAll();
                }
                ,zIndex: layer.zIndex //重点1
            });
        }
        ,deleteMenu:function(){
            var _id = $(this).data("id");
            layer.confirm('您确认删除此菜单吗？', {
                btn: ['确认','取消'] //按钮
                ,title:'<i class="layui-icon">&#xe607;</i> 确认提示'
                ,icon:0
            }, function(){
                $.ajax({
                    url: adminurl + "/menus/delete",
                    data:{
                        id:_id,
                        _token:_token,
                    },
                    type:"DELETE",
                    dataType:"json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1,'#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success:function(res){
                        layer.close(index);
                        if(res.code==1){
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location.reload();
                            },1000)
                        }else if(res.code==1002){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location.href= adminurl + "/login";
                            },500)
                        }else{
                            layer.msg(res.msg);
                            return false;
                        }
                    },
                    error:function(){
                        layer.close(index);
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        }
        ,showMenu:function(){
            var is_show = $(this).children("div").hasClass("layui-form-onswitch") ? 1: 0;
            var menuId  = $(this).children("input").data("id");
            $.ajax({
                url: adminurl + "/menus/status",
                data:{
                    _token:_token,
                    id:menuId,
                    is_show:is_show
                },
                type:"PUT",
                dataType:"json",
                beforeSend: function (request) {
                    layer.closeAll();
                    index = layer.load(2, {
                        shade: [0.3,'#FFF'] //0.1透明度的白色背景
                    });
                },
                success:function(res){
                    layer.close(index);
                    if(res.code==1){
                        layer.msg(res.msg);
                    }else if(res.code==1002){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            location.href= adminurl + "/login";
                        },500)
                    }else{
                        layer.msg(res.msg);
                        return false;
                    }
                },
                error:function(){
                    layer.close(index);
                    layer.msg("操作失败！");
                    return false;
                }
            });
        }
        ,offset: function(othis){
            var type = othis.data('type')
                ,text = othis.text();

            layer.open({
                type: 1
                ,offset: type //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
                ,id: 'layerDemo'+type //防止重复弹出
                ,content: '<div style="padding: 20px 100px;">'+ text +'</div>'
                ,btn: '关闭全部'
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,yes: function(){
                    layer.closeAll();
                }
            });
        }
        ,actionMenu: function () {
            var title = $(this).data("title");
            var _id = $(this).data("id");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.8,
                area: ['30%', "80%"],
                content: adminurl + '/menus/action/'+_id //iframe的url
            });
        }
    };
    $('#menu-action .menu-action').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
});