layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });
    //按时间搜索
    laydate.render({
        elem: '#jzdateSelect' //指定元素
        ,type: 'datetime'
    });
    //按时间搜索
    laydate.render({
        elem: '#jzdateSelects' //指定元素
        ,type: 'datetime'
    });

    var active = {
        show1: function () {
            var id = $(this).data("id");
            var state = 2;
            var auditMsg = "";
            if(state==1){
                auditMsg = "您确认通过吗？";
            }else{
                auditMsg = "您确认通过吗？";
            }
            layer.confirm(auditMsg, {
                btn: ['确认', '取消'] //按钮
                , title: '<i class="layui-icon"></i> 确认提示'
                , icon: 0
            }, function () {
                $.ajax({
                    url: adminurl + "/certification/upstatus",
                    data: {
                        id: id,
                        status:state,
                        _token: _token,
                    },
                    type: "POST",
                    dataType: "json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1, '#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.close(index);
                        if (res.code == 1) {
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function () {
                                layer.closeAll();
                                location.reload();
                            }, 200)
                        } else if (res.code == 1002) {
                            layer.msg(res.msg);
                            setTimeout(function () {
                                location.href = adminurl + "/login";
                            }, 200)
                        } else {
                            layer.msg(res.msg);
                            return false;
                        }
                    },
                    error: function () {
                        layer.close(index);
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        },
        show2: function () {
            var id = $(this).data("id");
            var state = 4;
            var auditMsg = "";
            if(state==1){
                auditMsg = "您确认拒绝吗？";
            }else{
                auditMsg = "您确认拒绝吗？";
            }
            layer.confirm(auditMsg, {
                btn: ['确认', '取消'] //按钮
                , title: '<i class="layui-icon"></i> 确认提示'
                , icon: 0
            }, function () {
                $.ajax({
                    url: adminurl + "/certification/upstatus",
                    data: {
                        id: id,
                        status:state,
                        _token: _token,
                    },
                    type: "POST",
                    dataType: "json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1, '#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.close(index);
                        if (res.code == 1) {
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function () {
                                layer.closeAll();
                                location.reload();
                            }, 200)
                        } else if (res.code == 1002) {
                            layer.msg(res.msg);
                            setTimeout(function () {
                                location.href = adminurl + "/login";
                            }, 200)
                        } else {
                            layer.msg(res.msg);
                            return false;
                        }
                    },
                    error: function () {
                        layer.close(index);
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        },

    };

    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

});