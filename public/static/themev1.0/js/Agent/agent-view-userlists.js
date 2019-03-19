layui.use(['element','jquery','layer','form','laypage','laydate'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    //按时间搜索
    laydate.render({
        elem: '#dateSelect' //指定元素
        ,range: true
    });
    //数据搜索
    form.on('submit(formSearch)', function(data){
        if(data.field.type=="" && data.field.datetime){
            layer.msg("搜索内容不能为空！");
        }else{
            getDataLists(1,_id,data.field.type,data.field.datetime);
        }
        return false;
    });
    getDataLists(1,_id,"",""); //初始化表格数据
    function getDataLists(page,_id,type,datetime){
        $.ajax({
            url: url + "/admin/agent/getUserLists/"+_id+"?type="+type+"&datetime="+datetime,
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
                        innerData +='<td class="align-center" colspan="7">暂无数据</td>';
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
                                        url: url + "/admin/agent/getUserLists?page="+obj.curr+"&type="+type+"&datetime="+datetime,
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
                                                    innerData +='<td class="align-center" colspan="10">暂无数据</td>';
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
    //数据格式化模板
    function floatDataTpl(data){
        layui.each(data, function(index, item){
            if(item.type==1){
                var type = '会员';
            }else{
                var type = '商户';
            }
            var site = item.province+" "+item.city+" "+item.area;
            innerData +="<tr>";
            innerData +='<td>'+item.id+'</td>';
            innerData +='<td>'+item.name+'</td>';
            innerData +='<td>'+item.phone+'</td>';
            innerData +='<td>'+type+'</td>';
            innerData +='<td>'+item.creation_time+'</td>';
            innerData +='</tr>';
        });
    }
});