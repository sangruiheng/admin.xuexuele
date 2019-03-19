layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if ( data.field.name== "" && data.field.id=="" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.name,data.field.id);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        // active[method] ? active[method].call(this, othis) : '';
    });
    getDataLists(location.hash.replace('#!page=',''), "", ""); //初始化表格数据
    function getDataLists(page,name,id) {
        $.ajax({
            url: adminurl + "/gate/lists",
            data: {
                page:page,
                name:name,
                id:id,
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
                                        url: adminurl + "/gate/lists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            
                                            id:id,
                                            name:name,
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

    //判断关卡是否有题目
    $('#addgate').click(function () {
        isCreateSubject();
    });


    function isCreateSubject() {
        $.ajax({
            url: adminurl + "/gate/iscreatesubject",
            data: {
                _token: _token
            },
            type: "get",
            dataType: "json",
            // beforeSend: function () {
            //     index = layer.load(2, {
            //         shade: [0.1, '#FFF'] //0.1透明度的白色背景
            //     });
            // },
            success: function (res) {
                console.log(res);
                if(res.code == 200){
                    $(window).attr('location',adminurl + "/gate/addgate");
                }else{
                    layer.msg('前面关卡有未添加题目,请先添加题目！');
                }
            },
            error: function (err) {

            }
        });

    }



    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.gatename + '</td>';
            innerData += '<td>' + item.rewardbeans + '</td>';
            innerData += '<td>' + item.pkvalue + '</td>';
            if(item.specialreward == 1){
                innerData += '<td>有</td>';
            }else if(item.specialreward == 2){
                innerData += '<td>无</td>';
            }
            innerData += '<td>' + item.subject_sum + '</td>';
            innerData += '<td>';
                innerData += '<a  href= "' + adminurl + '/gate/gateview/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
                innerData += '<a  href= "' + adminurl + '/subject/showsubject/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>题目管理</a>';
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});