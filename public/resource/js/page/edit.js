layui.use(['jquery','layer','form','layedit','upload','ychl'], function() {

    var form = layui.form;
    var $ = layui.jquery;
    var layer = layui.layer;
    var layedit = layui.layedit;
    var upload  = layui.upload;
    var editIndex = layedit.build('content'); //建立编辑器
    var ychl    = layui.ychl;
    upload.render({
        elem: '#test5'
        ,url: adminurl + '/uploads/video',
        before: function(obj){
            layer.load(2, {
                shade: [0.1,'#FFF'] //0.1透明度的白色背景
            });
            this.data={'_token':_token};
        }
        ,accept: 'video' //视频
        ,done: function(res){
            if(res.code){
                layer.closeAll();
                layer.msg(res.msg);
                $("#video").val(res.data.url);
            }else{
                layer.closeAll();
                layer.msg(res.msg);
            }
        }
    });

    //编辑单页内容
    form.on('submit(formEdit)', function(){
        layedit.sync(editIndex);
        formSubmit("pages/update");
        return false;
    });
    //重置
    $("body").on("click",".reset",function(){
        $("#thumb-view").children("img").remove();
    });
    //表单提交及验证
    function formSubmit(action){
        ychl.ajax({
            url: adminurl + "/" + action,
            data:$("#dataForm").serialize(),
            method:"PUT",
            done:function (res) {
                layer.closeAll();
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>',{
                            time:20000,
                            shade: [0.2,'#000']
                        });
                        location.href= adminurl + "/pages";
                    },500);
                }else if(res.code==2){
                    layer.msg(res.msg);
                }else{
                    layer.msg(res.msg,{
                        zIndex:layer.zIndex
                    });
                    return false;
                }
            }
        });
    }
});