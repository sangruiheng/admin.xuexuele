layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    upload = layui.upload;
    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });

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


    //修改
    form.on('submit(formEdit)', function (data) {
        console.log(1);
        $.ajax({
            url: adminurl + "/startup/updatestartup",
            data: $("#formEdit").serialize(),
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
                    location.href = adminurl + "/startup" ;
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


    //禁用 启用
    $("body").on("click", ".btn-disable", function () {
        var that=$(this);
        var startupID = that.attr('data-id');
        var is_disable = that.attr('status-id');
        $.ajax({
            url: adminurl + "/startup/disablestartup",
            data: {
                startup_id:startupID,
                is_disable:is_disable,
                _token: _token
            },
            type: "get",
            dataType: "json",
            // beforeSend: function () {
            //     index = layer.load(2, {
            //         shade: [0.1, '#FFF'] //0.1透明度的白色背景
            //     });
            // },
            success: function (res) {
                console.log(res);
                if(res.code == 1){
                    that.attr('status-id',res.data);
                    if(res.data == 0){
                        that.addClass("layui-bg-red");
                        that.removeClass("layui-bg-orange");
                        that.html('禁用');
                    }else if(res.data == 1){
                        that.addClass("layui-bg-orange");
                        that.removeClass("layui-bg-red");
                        that.html('启用');
                    }

                }
            },
            error: function () {
                layer.msg("操作失败！！！");
                return false;
            }
        });

    });





});