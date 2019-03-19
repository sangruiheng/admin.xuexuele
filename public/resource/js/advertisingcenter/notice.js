layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if ( data.field.heading== "") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.heading);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    
    //添加信息
    form.on('submit(addNotice)', function (data) {
        if(data.field.heading==""){
            layer.msg("消息标题不能为空！");
            return false;
        }
        if(data.field.content==""){
            layer.msg("消息内容不能为空！");
            return false;
        }
        if(data.field.identity1=="" && data.field.identity2==""){
            layer.msg("收信用户不能为空！");
            return false;
        }
        //console.log(data);
        //return false;
        $.ajax({
            url: adminurl + "/notice/addnotice",
            data:$("#addNotice").serialize(),
            method:"POST",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                res=JSON.parse(res);
                if(res.code==1){
                    layer.msg(res.msg);
                    location.href = adminurl + "/notice" ;
                    
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
   
    getDataLists(location.hash.replace('#!page=',''), ""); //初始化表格数据
    function getDataLists(page,heading) {
        $.ajax({
            url: adminurl + "/notice/noticelists",
            data: {
                page:page,
                heading:heading,
                paged:15,
                _token: _token
            },
            type: "get",
            dataType: "json",
            beforeSend: function () {
                index = layer.load(2, {
                    shade: [0.1, '#FFF'] //0.1透明度的白色背景
                });
            },
            success: function (res) {
                layer.close(index);
                if (res.code == 1) {
                    innerData = '';
                    if (res.data.total == 0) {
                        innerData += "<tr>";
                        innerData += '<td class="align-center" colspan="6">暂无数据</td>';
                        innerData += '</tr>';
                        laypage.render({elem: 'pages', count: res.data.total});
                    } else {
                        laypage.render({
                            elem: 'pages'
                            ,count: res.data.total
                            ,limit: 15
                            ,layout:['count', 'prev', 'page', 'next', 'limit', 'skip']
                            ,curr: page
                            ,hash:"page"
                            , jump: function (obj, first) {
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if (!first) {
                                    $.ajax({
                                        url: adminurl + "/notice/noticelists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            heading:heading,
                                        },
                                        type: "get",
                                        dataType: "json",
                                        beforeSend: function (request) {
                                            index = layer.load(2, {
                                                shade: [0.1, '#FFF'] //0.1透明度的白色背景
                                            });
                                        },
                                        success: function (res) {
                                            layer.close(index);
                                            if (res.code == 1) {
                                                innerData = '';
                                                if (res.count == 0) {
                                                    innerData += "<tr>";
                                                    innerData += '<td class="align-center" colspan="6">暂无数据</td>';
                                                    innerData += '</tr>';
                                                } else {
                                                    floatDataTpl(res.data.noticelists, obj.curr);
                                                }
                                                $('#listbox').html(innerData);
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
                                        },
                                        error: function () {
                                            layer.close(index);
                                            layer.msg("操作失败！！");
                                            return false;
                                        }
                                    });
                                } else {
                                    floatDataTpl(res.data.noticelists, obj.curr);
                                }
                            }
                        });
                    }
                    $('#listbox').html(innerData);
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
            },
            error: function () {
                layer.close(index);
                layer.msg("操作失败！！！");
                return false;
            }
        });
    }

    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            //innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.create_time + '</td>';
            // if(item.identity == 1){
            //     innerData += '<td>导师</td>';
            // }else if(item.identity == 2){
            //     innerData += '<td>侠客</td>';
            // }
            innerData += '<td>' + item.usertype + '</td>';
            innerData += '<td>' + item.heading + '</td>';
            innerData += '<td>';
            innerData += '<a  href= "' + adminurl + '/notice/noticeview/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});