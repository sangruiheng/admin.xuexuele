layui.use(['element','jquery','layer','form'], function(){
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    $(".logout").on('click', function(){
        $.ajax({
            url: adminurl + "/logout",
            data:{
                _token:_token,
            },
            type:"POST",
            dataType:"json",
            success:function(res){
                layer.closeAll();
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        location.href= adminurl + "/login";
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
    element.on('nav(loadding)', function(data){
        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>',{
            time:20000,
            shade: [0.2,'#000']
        });
    });
    $("body").on("click",".home",function(){
        layer.closeAll();
    });
    $("body").on("click",".loadHref",function(){
        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>',{
            time:20000,
            shade: [0.2,'#000']
        });
    });
});