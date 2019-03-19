layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;

    //点击按钮
    $('body').on('click','.handle', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    var active = {
        delete:function(){
            var id = $(this).data("id");
            layer.confirm("你确认删除该商品吗？", {
                btn: ['确认','取消'] //按钮
                ,title:'<i class="layui-icon">&#xe607;</i> 确认提示'
                ,icon:0
            }, function(){
                $.ajax({
                    url: url + "/admin/merchant/commentDelete",
                    data:{
                        id:id,
                        _token:_token,
                    },
                    type:"delete",
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
                                getDataLists(1,_id);
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
        },
    }
    getDataLists(1,_id); //初始化表格数据
    function getDataLists(page,_id){
        $.ajax({
            url: url + "/admin/merchant/getCommentLists/"+_id,
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
                                        url: url + "/admin/merchant/getCommentLists/"+_id+"?page="+obj.curr,
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
            var site = item.province+" "+item.city+" "+item.area;
            innerData +="<tr>";
            innerData +='<td>'+item.id+'</td>';
            innerData +='<td>'+item.user_name+'</td>';
            innerData +='<td>'+item.comment_rank+' 星</td>';
            innerData +='<td>'+item.comment_content+'</td>';
            innerData +='<td>'+item.reply_content+'</td>';
            innerData +='<td>'+item.creation_time+'</td>';
            innerData +='<td>';
            innerData += '<button class="layui-btn layui-btn-small layui-btn-danger handle" data-method="delete" data-id="'+item.id+'" data-type="'+item.state+'"><i class="layui-icon"></i>删除</button> ';
            innerData +='</td></tr>';
        });
    }
});