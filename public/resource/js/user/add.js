layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    var userArr = new Array();

    //按时间搜索
    laydate.render({
        elem: '#birthday' //指定元素
        ,type: 'date'
    });
    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });

    //初始化加载一级分类
    $(function() {
        //获取一级分类
        $.ajax({
            url: adminurl + "/subjectes/getfirst",
            data:'',
            type: "get",
            dataType: "json",
            success: function (res) {
                if (res.code == 1) {
                    var optionHtml = '<option value="">选择一级分类</option>';
                    layui.each(res.data, function (index, item) {
                        optionHtml += '<option value="'+item.id+'">'+item.subjectname+'</option>';
                    });
                    $("#category_1").html(optionHtml);
                    form.render();
                } else if (res.code == 1002) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 200)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
    })

    //一级分类
    form.on('select(category_1)', function (data) {
        $.ajax({
            url: adminurl + "/subjectes/getchild",
            data:{
                id:data.value
            },
            beforeSend: function (request) {
                index = layer.load(2, {
                    shade: [0.3, '#FFF'] //0.1透明度的白色背景
                });
            },
            type: "get",
            dataType: "json",
            success: function (res) {
                layer.close(index);
                if (res.code == 1) {
                    var optionHtml = '<option value="">选择二级分类</option>';
                    layui.each(res.data, function (index, item) {
                        optionHtml += '<option value="'+item.id+'">'+item.subjectname+'</option>';
                    });
                    //console.log(optionHtml);
                    $("#category_2").html(optionHtml);
                    form.render();
                } else if (res.code == 1002) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 200)
                } else {
                    var optionHtml = '<option value="">选择二级分类</option>';
                    $("#category_2").html(optionHtml);
                    form.render();
                    return false;
                }
            }
        });
    });
    //二级分类
    form.on('select(category_2)', function (data) {
        $.ajax({
            url: adminurl + "/subjectes/getchild",
            data:{
                id:data.value
            },
            beforeSend: function (request) {
                index = layer.load(2, {
                    shade: [0.3, '#FFF'] //0.1透明度的白色背景
                });
            },
            type: "get",
            dataType: "json",
            success: function (res) {
                layer.close(index);
                if (res.code == 1) {
                    var optionHtml = '<option value="">选择三级分类</option>';
                    layui.each(res.data, function (index, item) {
                        optionHtml += '<option value="'+item.id+'-'+item.subjectname+'">'+item.subjectname+'</option>';
                    });
                    //console.log(optionHtml);
                    $("#category_3").html(optionHtml);
                    form.render();
                } else if (res.code == 1002) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 200)
                } else {
                    var optionHtml = '<option value="">选择三级分类</option>';
                    $("#category_3").html(optionHtml);
                    form.render();
                    return false;
                }
            }
        });
    });
    //三级分类
    form.on('select(category_3)', function (data) {
        if(data.value == "" || data.value == null || data.value == undefined){
            layer.msg("请重新选择");
            return false;
        }
        //layer.msg('三级分类');
        //layer.msg(data.value);
        //获取第三级别ID  6，7  放在数组 将 6-导函数公式 拆分出来  添加时候要去重复
        subject = data.value.split("-");
        //subject[0];
        //subject[1];
        //执行插入用户标签操作
        //判断js数组是否含有现有的数据
        if(userArr.indexOf(subject[0]) > -1){
            layer.msg('重复添加');
            return false;
        }else{
            //根据获取的三级专题 subject[0]，查出其父级专题 解题研究--二级专题--三级专题
            //id: 7, twosubject: "函数导函数", onesubject: "解题研究"
            $.ajax({
                url: adminurl + "/subjectes/getparents",
                data:{
                    id:subject[0]
                },
                beforeSend: function (request) {
                    index = layer.load(2, {
                        shade: [0.3, '#FFF'] //0.1透明度的白色背景
                    });
                },
                type: "get",
                dataType: "json",
                success: function (res) {
                    layer.close(index);
                    //console.log(res);
                    var addHtml = '<div><span class="label">'+res.data.onesubject+'--'+res.data.twosubject+'--'+subject[1]+
                        '<input type="hidden" name="subject_id[]" value="'+subject[0]+'">'+
                        '<i data-method="dellabel" class="layui-icon handle">&#x1006;</i>'+
                        '</span></div>';
                    $('.addSubjectLabel').append(addHtml);
                    //将元素压入数组
                    userArr.push(subject[0]);

                }
            });
        }
    });

    var active = {
        dellabel:function () {
            var thisObj = $(this);
            layer.confirm('您确认取消选择此类别吗？', {
                btn: ['确认', '取消'] //按钮
            }, function () {
                thisObj.parent().remove();

                //获取要删除的元素的值
                var thisval = String(thisObj.prev().val());
                //获取元素在数组中的位置
                //console.log(userArr.indexOf(thisval));
                if (userArr.indexOf(thisval) > -1) {
                    userArr.splice(userArr.indexOf(thisval), 1);
                }else{

                }
                //console.log(userArr);
                layer.closeAll();
            });
        },
    };

    //添加
    form.on('submit(formEdit)', function (data) {
        $.ajax({
            url: adminurl + "/user/adduser",
            data: $("#dataForm").serialize(),
            type: "put",
            dataType: "json",
            beforeSend: function (request) {
                layer.closeAll();
                index = layer.load(2, {
                    shade: [0.3, '#FFF'] //0.1透明度的白色背景
                });
            },
            success: function (res) {
                layer.close(index);
                if (res.code == 1) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        layer.closeAll();
                        layer.msg('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i><p>加载中...</p>', {
                            time: 20000,
                            shade: [0.2, '#000']
                        });
                        location.reload();
                    }, 200);
                } else if (res.code == 1002) { //未登录跳转到登录页
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
                layer.msg("操作失败!");
                return false;
            }
        });
        return false;
    });


    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

});