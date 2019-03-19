layui.use(['jquery','layer','form','proving'], function(){
    var layer = layui.layer;
    var $    = layui.jquery;
    var form = layui.form;
    var proving = layui.proving;

    //提交表单
    form.on('submit(formAdd)', function (data) {
        //校验信息
        if(!proving.name($("#name").val())){
            layer.msg('请输入2-4位中文名字！');
            return false;
        }
        if(!proving.sex($('input[type="radio"]:checked').val())){
            layer.msg('此性别不可选！');
            return false;
        }

        if(!proving.phone($("#phone").val())){
            layer.msg('请输入有效的手机号码！');
            return false;
        }

        if(!proving.email($("#email").val())){
            layer.msg('请输入有效的邮箱！');
            return false;
        }


        return false;
    });


});
