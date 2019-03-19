layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    //数据搜索
    form.on('submit(formSearch)', function(data){
        if(data.field.keyword==""){
            layer.msg("搜索内容不能为空！");
        }else{
            getDataLists(1,data.field.keyword);
        }
        return false;
    });
    getDataLists(1,""); //初始化表格数据
    //数据格式化模板
    function floatDataTpl(data){
        layui.each(data, function(index, item){
            innerData +="<tr>";
            innerData +='<td>'+item.id+'</td>';
            innerData +='<td>'+item.name+'</td>';
            innerData +='<td>'+item.pname+'</td>';
            innerData +='<td>'+item.phone+'</td>';
            innerData +='<td>'+item.recommend_count+'</td>';
            innerData +='<td>'+item.buy_count+'</td>';
            innerData +='<td>'+item.user_count+'</td>';
            innerData +='<td class="project-action">';
            innerData += '<a href="'+adminurl+'/user/view/'+item.id+'" class="layui-btn layui-btn-small handle" data-title="fds"><i class="layui-icon"></i>查看</a> ';
            innerData +='</td></tr>';
        });
    }
    function getDataLists(page,keyword){
        $.ajax({
            url: adminurl + "/user/lists?page="+page + "&keyword=" + keyword,
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
                        innerData +='<td class="align-center" colspan="8">暂无数据</td>';
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
                                        url: adminurl + "/user/lists?page="+obj.curr + "&keyword=" + keyword,
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
                                                    innerData +='<td class="align-center" colspan="8">暂无数据</td>';
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