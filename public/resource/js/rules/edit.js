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
    
    form.on('submit(formSharingreward)', function (data) {
        $.ajax({
            url: adminurl + "/rules/upsharingreward",
            data: $("#formSharingreward").serialize(),
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
                    location.href = adminurl + "/rules" ;
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

    form.on('submit(formWisdombeanreward)', function (data) {
        if(data.field.platrewardbeans>100){
            layer.msg("智慧豆最多输入100%");
            return false;
        }
        $.ajax({
            url: adminurl + "/rules/upwisdombeanreward",
            data: $("#formWisdombeanreward").serialize(),
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
                    location.href = adminurl + "/rules" ;
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

    form.on('submit(formRechargeamount)', function (data) {
         var content= new Array();
        $("input[name='money']").each(function(){
            if($(this).val()!=''){
                content.push($(this).val());
            }
            
        });
        let contenttext = content.join(",")+',';

        $('#moneylist').val(contenttext);

        
        $.ajax({
            url: adminurl + "/rules/uprechargeamount",
            data: $("#formRechargeamount").serialize(),
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
                    location.href = adminurl + "/rules" ;
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

    form.on('submit(formPlatform)', function (data) {
        let picurl = $('#pictureurl').val();
        if(picurl==''){
            layer.msg("请上传图片!");
            return false;
        }
        
        $.ajax({
            url: adminurl + "/rules/updateplatform",
            data: $("#formPlatform").serialize(),
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
                    location.href = adminurl + "/rules" ;
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


    $("body").on('click','.addInputReturn',function () {
        
        var inputnum = document.getElementsByClassName('inputnum')
        //console.log(inputnum.length)
        if(inputnum.length>5){
            layer.msg("最多添加6个");
            return false;
        }
        
        var html = "";
       
        html += '<div class="layui-input-block inputnum" style="display: flex;margin-bottom: 10px;">';
        html += '<input type="text" name="money"  lay-verify="number" autocomplete="off" class="layui-input" value="" >';
        html += '<p style="display: flex;align-items: center;">元</p>';
        html += '</div>';
        
     
       $("#index-div").append(html);
    });
});