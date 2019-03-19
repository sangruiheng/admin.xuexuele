layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.is_normal==""  && data.field.uid== ""  && data.field.name=="" && data.field.phone=="") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.is_normal,data.field.uid,data.field.name,data.field.phone);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    getDataLists(location.hash.replace('#!page=',''), "", "", "", "", ""); //初始化表格数据
    function getDataLists(page,is_normal,uid,name,phone) {
        $.ajax({
            url: adminurl + "/certification/lists",
            data: {
                page:page,
                is_normal:is_normal,
                uid:uid,
                name:name,
                phone:phone,
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
                                        url: adminurl + "/certification/lists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            is_normal:is_normal,
                                            uid:uid,
                                            name:name,
                                            phone:phone,
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
                                                    floatDataTpl(res.data.lists, obj.curr);
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
                                    floatDataTpl(res.data.lists, obj.curr);
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
            innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.nickname + '</td>';
            innerData += '<td>' + item.phone + '</td>';
            innerData += '<td>' + item.create_time + '</td>';

            if(item.certificationstate == 1){
                innerData += '<td>未认证</td>';
            }else if(item.certificationstate == 2){
                innerData += '<td>已认证</td>';
            }else if(item.certificationstate == 3){
                innerData += '<td>审核中</td>';
            }else if(item.certificationstate == 4){
                innerData += '<td>驳回</td>';
            }

            innerData += '<td>';
            /*if(item.certificationstate == 1){
                innerData += '<a  href= "' + adminurl + '/certification/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="1"><i class="layui-icon"></i>查看</a>';
            }else if(item.certificationstate == 2){
                innerData += '<a  href= "' + adminurl + '/certification/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
            }else if(item.certificationstate == 3){
                innerData += '<a  href= "' + adminurl + '/certification/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="3"><i class="layui-icon"></i>查看</a>';
            }else if(item.certificationstate == 4){
                innerData += '<a  href= "' + adminurl + '/certification/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="4"><i class="layui-icon"></i>查看</a>';
            }*/
            innerData += '<a  href= "' + adminurl + '/certification/view/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" ><i class="layui-icon"></i>查看</a>';
            
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});