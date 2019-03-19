layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    //时间
    laydate.render({
        elem: '#searchSelect'
        //,type: 'datetime'
        ,range: '到'
    });
    
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.datetime==""   && data.field.is_normal==""  && data.field.name== "" && data.field.phone=="" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.datetime,data.field.is_normal,data.field.name,data.field.phone);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    
    

    getDataLists(location.hash.replace('#!page=',''), "", "", "", ""); //初始化表格数据
    function getDataLists(page,datetime,is_normal,name,phone) {
        $.ajax({
            url: adminurl + "/recharge/rechargelists",
            data: {
                page:page,
                datetime:datetime,
                is_normal:is_normal,
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
                                        url: adminurl + "/recharge/rechargelists",
                                        data: {
                                            page:obj.curr,
                                            paged:obj.limit,
                                            datetime:datetime,
                                            is_normal:is_normal,
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
                                                    floatDataTpl(res.data.rechargelists, obj.curr);
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
                                    floatDataTpl(res.data.rechargelists, obj.curr);
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

    //数据导出
    $("body").on("click", ".export", function () {
        // console.log('1111')
        let datetime=$('#searchSelect').val();
        let is_normal=$('#is_normal').val();
        let name=$('#name').val();
        let phone=$('#phone').val();
        window.open(adminurl + "/recharge/rechargeexport?datetime="+datetime+"&is_normal="+is_normal+"&name="+name+"&phone="+phone)
        
        return false;
    });


    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.id + '</td>';
            innerData += '<td>' + item.create_time + '</td>';
            if(item.complaint == 1){
                innerData += '<td>微信</td>';
            }else if(item.complaint == 2){
                innerData += '<td>支付宝</td>';
            }
            innerData += '<td>' + item.nickname + '</td>';
            innerData += '<td>' + item.phone + '</td>';
            innerData += '<td>' + item.wisdombean + '</td>';
            innerData +='</tr>';
        });
    }
});