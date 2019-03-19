layui.use(['element','jquery','layer','form','layedit','upload'], function() {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    var layedit = layui.layedit;
    var upload  = layui.upload;
    var editIndex = layedit.build('content'); //建立编辑器
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
        $.ajax({
            url: adminurl + "/" + action,
            data:$("#dataForm").serialize(),
            type:"put",
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
                            shade: [0.2,'#000']
                        });
                        location.href= adminurl + "/pages";
                    },500)
                }else if(res.code==2){
                    layer.closeAll();
                    layer.msg(res.msg);
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
//缩略图上传
function upload(obj,id) {
    index = layer.load(2, {
        shade: [0.1,'#FFF'] //0.1透明度的白色背景
    });
    var file = obj.files[0];
    if(!file){
        layer.closeAll();
        return false;
    }
    //判断类型是不是图片
    if(!/image\/\w+/.test(file.type)){
        layer.closeAll();
        layer.msg("请确保文件为图像类型");
        return false;
    }
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(e){
        imgRes = this.result;
        $.ajax({
            type: "PUT",
            url: adminurl+"/uploads/uploadthumb",
            data: {
                imgpath: this.result ,
                _token:_token
            },
            dataType:'json',
            success: function(res) {
                if(res.code==1){
                    layer.closeAll();
                    $("#thumb-view").children("img").remove();
                    layer.msg(res.msg);
                    $("#thumb-view").append('<img  class="layui-upload-img" src="'+imgRes+'">');
                    $("#topic").val(res.data.thumb);
                }else if(res.code==1002){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        location.href= adminurl + "/login";
                    },500)
                }else{
                    layer.msg(res.msg);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                layer.close(index);
                alert("上传失败，请检查网络后重试");
            }
        });
    }
}