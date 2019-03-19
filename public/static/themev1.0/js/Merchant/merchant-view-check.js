//Demo
layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    form.on('submit(formSave)', function(){
        var id = $("#id").val();
        var state = $("#state").val();
        var text = $("#text_v").val();
        if(state==''){
            layer.msg('请选择审核结果！');
            return false;
        }
        $.ajax({
            url: adminurl + "/check/statusCheck",
            data:{
                id :id,
                state :state,
                text:text,
                _token :_token
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
                    location.reload();
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
    });
    //下拉框事件
    form.on('select(texts)', function(data){
        if(data.value==4){
            $("#text").css('display', 'block'); //显示
        }else{
            $("#text").css('display','none'); //隐藏
        }
    });
});