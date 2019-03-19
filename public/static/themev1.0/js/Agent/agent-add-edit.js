//Demo
new PCAS("province", "city", "county", province_id, city_id, area_id); //初即化城市选择器
layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    form.on('select(province)', function(data){
        province = data.value;
        new PCAS("province", "city","county", province, "", "");
        form.render('select');
    });
    form.on('select(city)', function(data){
        city = data.value;
        new PCAS("province", "city","county", province, city, "");
        form.render('select');
    });
    //验证分成比例范围
    $("#proportion").blur(function(){
        var proportion = $("#proportion").val();
        if(proportion>20){
            layer.msg('分销信息-分成比例超出范围');
        }
    });
    $("#proportion_s").blur(function(){
        var proportion_s = $("#proportion_s").val();
        if(proportion_s>10){
            layer.msg('上级代理商-分成比例超出范围');
        }
    });
    //重置
    $("body").on("click",".reset",function(){
        document.getElementById("formdata").reset();
    });
    //新增or编辑
    form.on('submit(formSave)', function(){
        //验证分成比例范围
        var proportion = $("#proportion").val();
        if(proportion>20){
            layer.msg('分销信息-分成比例超出范围');
            return false;
        }
        var proportion_s = $("#proportion_s").val();
        if(proportion_s>10){
            layer.msg('上级代理商-分成比例超出范围');
            return false;
        }
        var city = $("#city").val();
        if(city =="--城市--"){
            layer.msg('请选择代理的市级位置');
            return false;
        }
        formSubmit("agent/create");
        return false;
    });
    //表单提交及验证
    function formSubmit(action){
        $.ajax({
            url: url + "/admin/" + action,
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
                    layer.msg(res.msg);
                    setTimeout(function(){
                        layer.closeAll();
                        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>',{
                            time:20000,
                            shade: [0.2,'#FFF']
                        });
                        window.location.href = url+'/admin/agent/agentInfo/'+_id+'?urltype=1';
                    },500)
                }else if(res.code==2){
                    layer.closeAll();
                    layer.msg(res.msg);
                }else if(res.code==1002){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        window.parent.location.href= url + "/admin/login";
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
    //点击按钮
    $('body').on('click','.handle', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    var active = {
        resetpassword:function(){
            layer.confirm('重置后的密码为[ 12345678 ]', {
                btn: ['确认','取消'] //按钮
                ,title:'<i class="layui-icon">&#xe607;</i> 确认提示',
            }, function(){
                $.ajax({
                    url: url + "/admin/agent/resetPassword",
                    data:{
                        id:_id,
                        _token:_token,
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
                            layer.msg(res.msg);
                            setTimeout(function(){
                                layer.closeAll();
                                window.location.href = url+'/admin/agent/agentInfo/'+_id+'?urltype=1';
                            },500)
                        }else if(res.code==1002){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                window.parent.location.href= url + "/admin/login";
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
    }
});