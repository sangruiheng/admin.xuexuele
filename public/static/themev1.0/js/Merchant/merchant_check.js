//Demo
new PCAS("province", "city", "county", "", "", ""); //初即化城市选择器
layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    form.on('select(province)', function(data){
        province = data.value;
        new PCAS("province", "city","county", province, "", "");
        form.render('select');
    });
    form.on('select(city)', function(data){
        city = data.value;
        new PCAS("province", "city","county", province, city, "");
        form.render('select');
    });
    //数据搜索
    form.on('submit(formSearch)', function(data){
        if(data.field.province=="" && data.field.city=="" && data.field.county=="" && data.field.state=="" && data.field.classify=="" && data.field.keyword==""){
            layer.msg("搜索内容不能为空！");
        }else{
            getDataLists(1,data.field.province,data.field.city,data.field.county,data.field.state,data.field.classify,data.field.keyword);
        }
        return false;
    });
    getDataLists(1,"","","","","",""); //初始化表格数据
    //数据格式化模板
    function floatDataTpl(data){
        layui.each(data, function(index, item){
            if(item.state==3){
                var state = '待审核';
                var text = '审核';
            }else if(item.state==4){
                var state = '已驳回';
                var text = '查看';
            }else{
                var state = '已通过';
                var text = '查看';
            }
            innerData +="<tr>";
            innerData +='<td>'+item.id+'</td>';
            innerData +='<td>'+item.user_name+'</td>';
            innerData +='<td>'+item.phone+'</td>';
            innerData +='<td>'+item.store_name+'</td>';
            innerData +='<td>'+item.industry+'</td>';
            innerData +='<td>'+item.site+'</td>';
            innerData +='<td>'+item.creation_time+'</td>';
            innerData +='<td>'+state+'</td>';
            innerData +='<td>';
            innerData += '<a href="'+adminurl+'/check/checkView/'+item.id+'" class="layui-btn layui-btn-small handle" data-title="fds"><i class="layui-icon"></i>'+text+'</a> ';
            innerData +='</td></tr>';
        });
    }
    //点击按钮
    $('body').on('click','.handle', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    var active = {
        status:function(){
            var _id = $(this).data("id");
            var _state = $(this).data("type");
            if(_state==1){
                var text = '你确认冻结该商户吗？';
            }else{
                var text = '你确认启用该商户吗？';
            }
            layer.confirm(text, {
                btn: ['确认','取消'] //按钮
                ,title:'<i class="layui-icon">&#xe607;</i> 确认提示'
                ,icon:0
            }, function(){
                $.ajax({
                    url: adminurl + "/merchant/status",
                    data:{
                        id:_id,
                        state:_state,
                        _token:_token,
                    },
                    type:"PUT",
                    dataType:"json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1,'#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success:function(res){
                        layer.close(index);
                        if(res.code==1){
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function(){
                                layer.closeAll();
                                getDataLists(1,"","","","","","");
                            },500)
                        }else if(res.code==1002){
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location.href= adminurl + "/login";
                            },500)
                        }else{
                            layer.msg(res.msg);
                            return false;
                        }
                    },
                    error:function(){
                        layer.close(index);
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        }
    }
    function getDataLists(page,province,city,county,state,classify,keyword){
        $.ajax({
            url: adminurl + "/check/checkLists?page="+page + "&province="+ province +"&city="+ city +"&county="+ county +"&state="+ state +"&classify=" + classify +"&keyword=" + keyword,
            data:'',
            type:"get",
            dataType:"json",
            beforeSend: function () {
                index = layer.load(2, {
                    shade: [0.1,'#FFF'] //0.1透明度的白色背景
                });
            },
            success:function(res){
                layer.close(index);
                if(res.code==1){
                    innerData = '';
                    if(res.count==0){
                        innerData +="<tr>";
                        innerData +='<td class="align-center" colspan="9">暂无数据</td>';
                        innerData +='</tr>';
                        laypage.render({elem: 'pages', count: res.count});
                    }else{
                        laypage.render({
                            elem: 'pages'
                            ,count:res.count
                            ,limit:15
                            ,layout:['count','prev', 'page', 'next']
                            ,jump: function(obj, first){
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if(!first){
                                    $.ajax({
                                        url: adminurl + "/check/checkLists?page="+obj.curr + "&province="+ province +"&city="+ city +"&county="+ county +"&state="+ state +"&classify=" + classify +"&keyword=" + keyword,
                                        data:'',
                                        type:"get",
                                        dataType:"json",
                                        beforeSend: function (request) {
                                            index = layer.load(2, {
                                                shade: [0.1,'#FFF'] //0.1透明度的白色背景
                                            });
                                        },
                                        success:function(res){
                                            layer.close(index);
                                            if(res.code==1){
                                                innerData = '';
                                                if(res.count==0){
                                                    innerData +="<tr>";
                                                    innerData +='<td class="align-center" colspan="9">暂无数据</td>';
                                                    innerData +='</tr>';
                                                }else{
                                                    floatDataTpl(res.data,page);
                                                }
                                                $('#agentbox').html(innerData);
                                                form.render();
                                            }else if(res.code==1002){
                                                layer.msg(res.msg);
                                                setTimeout(function(){
                                                    location.href= adminurl + "/login";
                                                },500)
                                            }else{
                                                layer.msg(res.msg);
                                                return false;
                                            }
                                        },
                                        error:function(){
                                            layer.close(index);
                                            layer.msg("操作失败！");
                                            return false;
                                        }
                                    });
                                }else{
                                    floatDataTpl(res.data,page);
                                }
                            }
                        });
                    }
                    $('#agentbox').html(innerData);
                    form.render();
                }else if(res.code==1002){
                    layer.msg(res.msg);
                    setTimeout(function(){
                        location.href= adminurl + "/login";
                    },500)
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            },
            error:function(){
                layer.close(index);
                layer.msg("操作失败！");
                return false;
            }
        });
    }
});