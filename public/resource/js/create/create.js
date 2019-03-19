layui.use(['jquery', 'layer', 'form','ychl'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var form = layui.form;
    var ychl = layui.ychl;
    //添加
    form.on('submit(add)', function (data) {
        ychl.ajax({
            url: adminurl + "/create",
            data: $("#dataForm").serialize(),
            method: "POST",
            done:function (res) {
                layer.closeAll();
                if(res.code == 1){
                    layer.msg();
                }else{
                    layer.msg(res.msg);
                }
            }
        });
        return false;
    });
});