layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    
    form.on('submit(formAdd)', function (data) {
        if(data.field.name.length>20){
            layer.msg("最多输入20个字符");
            return false;
        }
       if(data.field.day>31){
           layer.msg("每月最多31天！");
           return false;
        }
        $.ajax({
            url: adminurl + "/signreward/add",
            data: $("#formAdd").serialize(),
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
                    location.href = adminurl + "/signreward" ;
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