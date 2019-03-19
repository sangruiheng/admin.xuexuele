layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    upload = layui.upload;

    //普通图片上传
    upload.render({
        elem: '#test1'
        ,url: adminurl+'/gate/uploadspic'
        ,field: "picture"
        ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        ,before: function(obj){
          //预读本地文件示例，不支持ie8
          obj.preview(function(index, file, result){
            $('#demo1').attr('src', result); //图片链接（base64）
          });
        }
        ,done: function(res){
          //如果上传成功
          if(res.code ==1){
            
            $('#pictureurl').val(res.data);
            return layer.msg('上传成功！');
          }
          //失败
          else{
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span>');
                
          }
        }
        ,error: function(){
          //演示失败状态，并实现重传
          var demoText = $('#demoText');
          demoText.html('<span style="color: #FF5722;">上传失败</span> ');
          
        }
    });

    upload.render({
        elem: '#test2'
        ,url: adminurl+'/gate/uploadsvideo'
        ,field: "voicefile"
        ,accept: 'audio' //音频
        ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        ,done: function(res){
          //如果上传成功
          if(res.code ==1){
            $('#demo2').attr('src', res.data); 

            $('#videourl').val(res.data);
            return layer.msg('上传成功！');
          }
          //失败
          else{
                //演示失败状态，并实现重传
                var demoText = $('#demoText2');
                demoText.html('<span style="color: #FF5722;">上传失败</span> ');
          }
        }
        ,error: function(){
            //演示失败状态，并实现重传
            var demoText = $('#demoText2');
            demoText.html('<span style="color: #FF5722;">上传失败</span> ');
        }
    });
    

    //文章
    form.on('submit(formAddcontent)', function (data) {
        $.ajax({
            url: adminurl + "/rewardcontent/addcontent",
            data: $("#formAddcontent").serialize(),
            type: "post",
            dataType: "json",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                //res=JSON.parse(res);
                //console.log('11111');
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.href = adminurl + "/rewardcontent" ;
                } else if (res.code == 1002) { //未登录跳转到登录页
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 500)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            },
            error: function () {
                layer.msg("操作失败!");
                return false;
            }
        });
        return false;
    });
    //音频
    form.on('submit(formAddvoice)', function (data) {
        $.ajax({
            url: adminurl + "/rewardcontent/addvoice",
            data: $("#formAddvoice").serialize(),
            type: "post",
            dataType: "json",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                //res=JSON.parse(res);
                //console.log('11111');
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.href = adminurl + "/rewardcontent" ;
                } else if (res.code == 1002) { //未登录跳转到登录页
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 500)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            },
            error: function () {
                layer.msg("操作失败!");
                return false;
            }
        });
        return false;
    });
});