layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    //按时间搜索
    laydate.render({
        elem: '#dateSelect' //指定元素
        , range: true
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.keyword == "" && data.field.region_id == "" && data.field.status == "" && data.field.datetime=="") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1, data.field.region_id, data.field.status,data.field.datetime,data.field.keyword);
        }
        return false;
    });
    var active = {
        view:function(){
            var _id    = $(this).data("id");
            var _title = $(this).data("title");
            layer.open({
                type: 2,
                title: _title,
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '90%'],
                content: adminurl + '/pages/view/'+_id //iframe的url
            });
        },
    };
    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    getDataLists(1, "", "","",""); //初始化表格数据
    function getDataLists(page, region_id,status,datetime,keyword) {
        $.ajax({
            url: adminurl + "/pages/lists?page=" + page,
            data: '',
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
                    if (res.count == 0) {
                        innerData += "<tr>";
                        innerData += '<td class="align-center" colspan="9">暂无数据</td>';
                        innerData += '</tr>';
                    } else {
                        laypage.render({
                            elem: 'pages'
                            , count: res.count
                            , limit: 15
                            , jump: function (obj, first) {
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if (!first) {
                                    $.ajax({
                                        url: adminurl + "/pages/lists?page=" + obj.curr,
                                        data: '',
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
                                                    innerData += '<td class="align-center" colspan="9">暂无数据</td>';
                                                    innerData += '</tr>';
                                                } else {
                                                    floatDataTpl(res.data, page);
                                                }
                                                $('#listbox').html(innerData);
                                                form.render();
                                            } else if (res.code == 1002) {
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
                                            layer.close(index);
                                            layer.msg("操作失败！");
                                            return false;
                                        }
                                    });
                                } else {
                                    floatDataTpl(res.data, page);
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
                    }, 500)
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
    }

    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.title + '</td>';
            innerData += '<td>' + item.create_date + '</td>';
            innerData += '<td>' + item.update_date + '</td>';
            innerData += '<td>'+
                '<button data-method="view" data-id="'+item.id+'" data-title="'+item.title+'" class="layui-btn layui-btn-small handle">查看</button>'+
                '<a href="'+adminurl+'/pages/edit/'+item.id+'" class="layui-btn layui-btn-small layui-btn-normal loadHref">编辑</a>'+
                '</td>';
            innerData += '</tr>';
        });
    }
})