layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','table'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    //var table = layui.table;
    //时间
    laydate.render({
        elem: '#searchSelect'
        //,type: 'datetime'
        ,range: '到'
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.datetime=="") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.datetime);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    
    getDataLists(location.hash.replace('#!page=',''), "", "", "", "", "", ""); //初始化表格数据
    function getDataLists(page,datetime) {
        $.ajax({
            url: adminurl + "/subscribe/lists",
            data: {
                page:page,
                datetime:datetime,
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
                        //layui.render({elem: 'studysumcount', count: res.data.studysumcount});
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
                                        url: adminurl + "/subscribe/lists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
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
            innerData += '<td>';
            innerData += '<a  href= "' + adminurl + '/users/albumcourseviewdetail/' + item.id + '" class="layui-btn layui-btn-sm layui-btn-normal handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>'+item.coursename+'</a>';
            innerData += '</td>';
            innerData += '<td>' + item.albumname + '</td>';
            innerData += '<td>' + item.nickname + '</td>';
            innerData += '<td>' + item.studysum + '</td>';
            innerData += '<td>' + item.wisdombean + '</td>';
            innerData += '<td>' + item.rewardplatform + '</td>';
            innerData +='</tr>';
        });
    }
});