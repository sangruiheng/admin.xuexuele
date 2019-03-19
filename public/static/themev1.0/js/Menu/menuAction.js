layui.use(['element','jquery','layer','form'], function() {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    //添加商品类别
    form.on('submit(formAdd)', function(){
        var formHtml = addMenuAction();
        layer.open({
            title: '添加操作',
            type: 1,
            area:["60%",""],
            btn: ['保存', '关闭'],
            content: formHtml,
            yes: function(){
                var name = $("#rangename").val();
                var actionkey  = $("#actionkey").val();
                if(name==""){
                    layer.msg("操作名称不能为空");
                    $("#name").focus();
                    return false;
                }else if(actionkey==""){
                    layer.msg("操作索引不能为空");
                    $("#actionkey").focus();
                    return false;
                }
                $.ajax({
                    url: adminurl + "/menus/actionAdd",
                    data:$("#formdata").serialize(),
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
                            layer.closeAll();
                            var innerData = '<div class="layui-inline mb10" id="checkbox_'+res.data.id+'">'+
                                '<input type="checkbox" name="" title="'+res.data.name+'" value="'+res.data.id+'">'+
                                '</div>';
                            $('#listbox').append(innerData);
                            form.render();
                            layer.msg("添加成功！");
                        }else if(res.code==1002){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location.href= adminurl + "/login";
                            },500)
                        }else{
                            layer.msg(res.msg,{
                                zIndex:layer.zIndex
                            });
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
        });
        return false;
    });
    //保存服务人员
    form.on('submit(formDelete)', function(){
        var _checkboxIds = [];
        $("body").find("input[type='checkbox']:checked").each(function(){
            _checkboxIds.push($(this).val());
        });
        if(_checkboxIds.length<1){
            layer.msg("请选择要删除的操作！");
            return false;
        }
        layer.confirm('您确定要删除所选操作吗？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            $.ajax({
                url: adminurl + "/menus/actionDel",
                data:{
                    _token:_token,
                    menu_id:menu_id,
                    action_ids:_checkboxIds
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
                        layui.each(res.data, function (index, item) {
                            $("#checkbox_"+item).remove();
                        });
                        form.render();
                        layer.msg("删除成功！");
                    }else if(res.code==1002){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            location.href= adminurl + "/login";
                        },500)
                    }else{
                        layer.msg(res.msg,{
                            zIndex:layer.zIndex
                        });
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
        return false;
    });
    //编辑服务人员
    form.on('submit(formEdit)', function(){
        var _checkboxIds = [];
        $("body").find("input[type='checkbox']:checked").each(function(){
            _checkboxIds.push($(this).val());
        });
        if(_checkboxIds.length>1){
            layer.msg("一次只能编辑一个");
            console.log(_checkboxIds);
        }else{
            layer.prompt({title: '输入级别名称，并确认', formType: 0}, function(text, index){
                if(text.length<2){
                    layer.msg("类别名称不能少于2个字！");
                    return false;
                }else if(text.length > 8){
                    layer.msg("类别名称不能大于8个字！");
                    return false;
                }
                $.ajax({
                    url: adminurl + "/goods/editquality",
                    data:{
                        _token:_token,
                        type_id:_checkboxIds[0],
                        name:text
                    },
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
                            $("#checkbox_"+_checkboxIds[0]).find("input").attr("title",text);
                            form.render();
                            layer.msg("修改成功！");
                        }else if(res.code==1002){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location.href= adminurl + "/login";
                            },500)
                        }else{
                            layer.msg(res.msg,{
                                zIndex:layer.zIndex
                            });
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
            var editname = $("#checkbox_"+_checkboxIds[0]).find("span").html();
            $(".layui-layer-input").val(editname);
        }
        return false;
    });
    //取消
    form.on('submit(cancel)', function(){
        parent.layer.closeAll();
        return false;
    });
    //重置
    $("body").on("click",".reset",function(){
        $("#thumb-view").children("img").remove();
    });
    function formSubmit(action,menu_id,method,content){
        $.ajax({
            url: adminurl + "/menus/" + action,
            data:{
                _token:_token,
                menu_id:menu_id,
                name:content
            },
            type:method,
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
                    var innerData = '<div class="layui-inline mb10" id="checkbox_'+res.data.id+'">'+
                        '<input type="checkbox" name="" title="'+res.data.name+'" value="'+res.data.id+'">'+
                        '</div>';
                    $('#listbox').append(innerData);
                    form.render();
                    layer.msg("添加成功！");
                }else if(res.code==1002){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        location.href= adminurl + "/login";
                    },500)
                }else{
                    layer.msg(res.msg,{
                        zIndex:layer.zIndex
                    });
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

    function addMenuAction() {
        var innerHtml = "";
        innerHtml = '<form id="formdata" class="layui-form layui-layer-wrap" style="">'+
            '<div class="layui-form-item" style="padding:0px 10px;">'+
            '<input type="hidden" name="_token" value="'+_token+'">'+
            '<input type="hidden" name="menu_id" value="'+menu_id+'">'+
            '<label class="layui-label">操作名称：</label>'+
            '<input id="name" type="text" name="name" required="" lay-verify="required" placeholder="请输入操作名称" autocomplete="off" class="layui-input layui-form-danger">'+
            '<label class="layui-label">操作索引：</label>'+
            '<input id="actionkey" type="text" name="key" required="" lay-verify="required" placeholder="add_users" autocomplete="off" class="layui-input layui-form-danger" value="">'+
            '<label class="layui-label">操作路径：</label>'+
            '<input id="actionkey" type="text" name="path" required="" lay-verify="required" placeholder="/users/add" autocomplete="off" class="layui-input layui-form-danger" value="">'+
            '</div>'+
            '</form>';
        return innerHtml;
    }
});