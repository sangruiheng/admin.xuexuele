layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.is_normal==""  && data.field.albumname== "" && data.field.coursename== "" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.is_normal,data.field.albumname,data.field.coursename);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    
    getDataLists(location.hash.replace('#!page=',''), "", "", ""); //初始化表格数据
    function getDataLists(page,is_normal,albumname,coursename) {
        $.ajax({
            url: adminurl + "/reporting/lists",
            data: {
                page:page,
                is_normal:is_normal,
                albumname:albumname,
                coursename:coursename,
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
                                        url: adminurl + "/reporting/lists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            is_normal:is_normal,
                                            albumname:albumname,
                                            coursename:coursename,
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
            //innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.coursename + '</td>';
            innerData += '<td>' + item.albumname + '</td>';
            innerData += '<td>' + item.albumuser + '</td>';
            innerData += '<td>' + item.create_time + '</td>';
            if(item.classify == 1){
                innerData += '<td>违反规则</td>';
            }else if(item.classify == 2){
                innerData += '<td>垃圾广告</td>';
            }else if(item.classify == 3){
                innerData += '<td>用户骚扰</td>';
            }else if(item.classify == 4){
                innerData += '<td>敏感信息</td>';
            }
            innerData += '<td>' + item.nickname + '</td>';
            if(item.state == 1){
                innerData += '<td>待审核</td>';
            }else if(item.state == 2){
                innerData += '<td>举报成功</td>';
            }else if(item.state == 3){
                innerData += '<td>已驳回</td>';
            }else if(item.state == 4){
                innerData += '<td>已恢复</td>';
            }

            innerData += '<td>';
            if(item.state == 1 || item.state == 3 || item.state == 4) {
                innerData += '<a  href= "' + adminurl + '/reporting/checkview/' + item.id + '" class="layui-btn layui-btn-sm layui-btn " data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
            }else if(item.state == 2){
                innerData += '<a  href= "' + adminurl + '/reporting/recoveryview/' + item.id + '" class="layui-btn layui-btn-sm layui-btn " data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
            }
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});