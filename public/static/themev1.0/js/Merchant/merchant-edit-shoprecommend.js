layui.use(['element','jquery','layer','form'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;

    //重置
    $("body").on("click",".reset",function(){
        document.getElementById("formdata").reset();
    });
    //保存
    form.on('submit(formSave)', function(){
        $.ajax({
            url: url + "/admin/merchant/statusShopRecommend",
            data:$("#formdata").serialize(),
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
                        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>',{
                            time:20000,
                            shade: [0.2,'#FFF']
                        });
                        window.location.href = url+'/admin/merchant/shopRecommend/'+_id+'?urltype=1';
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
    });
});