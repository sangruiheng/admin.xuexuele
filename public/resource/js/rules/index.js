layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','table'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
   
    getDataLists(location.hash.replace('#!page=',''), "", "", ""); //初始化表格数据
    function getDataLists(page) {
        $.ajax({
            url: adminurl + "/rules/lists",
            data: {
                page:page,
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
                        //laypage.render({elem: 'studysumcount', studysum: res.data.studysumcount});
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
                                        url: adminurl + "/signreward/lists",
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
            innerData += '<td>' + item.name + '</td>';
            innerData += '<td>';
            if(item.name=='分享奖励'){
                innerData += item.sharerewardbeans ;
            }else if(item.name=='智慧豆打赏'){
                innerData += item.platrewardbeans ;
            }else if(item.name=='充值金额'){
                innerData += item.money ;
            }else if(item.name=='平台信息'){
                innerData += item.platename ;
            }
            innerData += '</td>';
            innerData += '<td>';
            if(item.name=='分享奖励'){
                innerData += '<a  href= "' + adminurl + '/rules/sharingreward/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
            }else if(item.name=='智慧豆打赏'){
                innerData += '<a  href= "' + adminurl + '/rules/wisdombeanreward/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
            }else if(item.name=='充值金额'){
                innerData += '<a  href= "' + adminurl + '/rules/rechargeamount/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
            }else if(item.name=='平台信息'){
                innerData += '<a  href= "' + adminurl + '/rules/platform" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
            }
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});