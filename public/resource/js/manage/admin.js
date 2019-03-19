layui.use(['form', 'jquery','ychl'], function () {
    var form = layui.form;
    var $ = layui.jquery;
    var ychl = layui.ychl;
    //监听提交
    form.on('submit(addForm)', function () {
        ychl.ajax({
            url:adminurl + '/admins',
            data:$("form").serialize(),
            method:'POST',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    //编辑用户信息
    form.on('submit(editForm)', function () {
        ychl.ajax({
            url:adminurl + '/admins/edit',
            data:$("form").serialize(),
            method:'PUT',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    //密码修改
    form.on('submit(repassForm)', function () {
        ychl.ajax({
            url: adminurl + "/admins/repass",
            data:$("form").serialize(),
            method:'PUT',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });

    //系统基本信息设置
    form.on('submit(formBase)', function (data) {
        ychl.ajax({
            url: adminurl + "/bases",
            data:$("form").serialize(),
            method:'PUT',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
    });

    //监听开关状态
    form.on('switch(status)', function(data){
        var is_show = data.elem.checked ? 1 : 2;
        var adminId = $(this).data("id");
        ychl.ajax({
            url:adminurl + "/admins/edit/status",
            data: {
                _token: _token,
                id: adminId,
                is_show: is_show
            },
            method:'PUT',
            done:function (res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
    });

    var active = {
        deleteAdmin: function () {
            var _id = $(this).data("id");
            var obj = $(this);
            ychl.dialog.confirm({
                url:adminurl + "/admins/del",
                data:{
                    id:_id,
                    _token:_token
                },
                method:'DELETE',
                msg:'您确认删除此管理员吗？',
                done:function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg);
                        obj.parent().parent().remove();
                    }else{
                        layer.msg(res.msg);
                        return false;
                    }
                }
            });
        }
    };
    $('body .handle').on('click', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
});
//头像上传操作~
function uploadImage(obj, id) {
    ychl.upload.image('base64',{
        imgObj:obj,    //图片对象
        url:adminurl + '/admins/upload',  //上传路径
        view:'#avator', //绑定图片显示区域
        done:function (res) {
            if(res.code == 1){
                layer.msg(res.msg);
                $("#putavator").val(res.data.source); //为表单附值
            }else{
                layer.msg(res.msg);
            }
        }
    });
}