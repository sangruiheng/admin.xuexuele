layui.define(['jquery', 'form','laypage','table'], function(exports){
    /*
     * 封装常用js方法
     * @CopyRight 易创互联 2014-2018 www.huimor.com
     * @auth tzchao
     * @2018-06-01
     */
    var form    = layui.form;
    var laypage = layui.laypage;
    var table   = layui.table;
    var $   = layui.jquery;
    var ychl = {
        ajax:function (param) { //ajax数据请求
            param.loadding = param.loadding != undefined ? param.loadding : true;
            $.ajax({
                url: param.url,
                data:param.data,
                type:param.method,
                dataType:"json",
                beforeSend: function (request) {
                    if(param.loadding == true){
                        index = layer.load(2, {
                            shade: [0.1,'#FFF'] //0.1透明度的白色背景
                        });
                    }else{
                        index = '';
                    }
                },
                success:function(res){
                    layer.close(index);
                    if(res.code == 1002){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            location.href= adminurl + "/login";
                        },500);
                    }else{
                        param.done(res);
                    }
                },
                error:function(){
                    layer.close(index);
                    layer.msg("网络异常，稍后重试~");
                    return false;
                }
            });
        },
        logout:function () {  //退出登录
            ychl.ajax({
                url:adminurl + '/logout',
                data:{_token:_token},
                method:'POST',
                done:function (res) {
                    if(res.code==1){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            location.href= adminurl + '/login';
                        },1000);
                    }else{
                        layer.msg(res.msg);
                        return false;
                    }
                }
            });
        },
        dialog:{  //模态框/弹出框
            add:function (param) {

            },
            confirm:function (param) { //确认提示方法
                layer.confirm(param.msg,{
                    btn: ['确认', '取消'] //按钮
                    , title: '<i class="layui-icon">&#xe607;</i> 确认提示'
                    , icon: 0
                },function () {
                    ychl.ajax({
                        url:param.url,
                        data:param.data,
                        method:param.method,
                        done:function (res) {
                            param.done(res);
                        }
                    });
                });
            }
        },
        upload:{ //文件上传方法
            image:function (type,param) {
                if(type == 'base64'){
                    var file = param.imgObj.files[0];
                    if(!file){
                        layer.closeAll();
                        return false;
                    }
                    //判断类型是不是图片
                    if (!/image\/\w+/.test(file.type)) {
                        layer.closeAll();
                        layer.msg("请确保文件为图像类型");
                        return false;
                    }
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        imgRes = this.result;
                        ychl.ajax({
                            url:param.url,
                            data:{
                                image: this.result,
                                _token: _token
                            },
                            method:'PUT',
                            done:function (res) {
                                if(res.code==1){
                                    $(param.view).attr("src", imgRes);
                                    param.done(res);
                                }else{
                                    layer.msg(res.msg);
                                    return false;
                                }
                            }
                        });
                    }
                }else if(type == 'file'){

                }else{
                    layer.msg('上传方式不存在');
                }
            }
        },
        table:{ //表格列表
            static:function (param) {
                param.data.page = param.data.page ? param.data.page : 1; //默认为第一页
                param.data.pageSize = param.data.pageSize ? param.data.pageSize : 10;  //默认为一页显示10条数据
                param.data.colCount = param.data.colCount ? param.data.colCount : 1;  //总列数
                ychl.ajax({
                    url: param.url,
                    data: param.data,
                    method: param.method,
                    done:function (res) {
                        layer.close(index);
                        if (res.code == 1) {
                            innerData = '';
                            if (res.data.count == 0) {
                                innerData += "<tr>";
                                innerData += '<td class="align-center" colspan="'+param.data.colCount+'">暂无数据</td>';
                                innerData += '</tr>';
                                laypage.render({elem: param.elemPage, count: res.data.count});
                                $('#' + param.listbox).html(innerData);
                                form.render();
                            } else {
                                laypage.render({
                                    elem: param.elemPage
                                    , count: res.data.count
                                    , limit: param.data.pageSize
                                    ,layout: ['count', 'prev', 'page', 'next', 'limit', 'refresh', 'skip']
                                    , jump: function (obj, first) {
                                        //首次不执行
                                        if (!first) {
                                            param.data.page = obj.curr;
                                            param.data.pageSize = obj.limit;
                                            ychl.ajax({
                                                url: param.url,
                                                data: param.data,
                                                method: param.method,
                                                done:function (res) {
                                                    layer.close(index);
                                                    if (res.code == 1) {
                                                        innerData = '';
                                                        if (res.count == 0) {
                                                            innerData += "<tr>";
                                                            innerData += '<td class="align-center" colspan="'+param.data.colCount+'">暂无数据</td>';
                                                            innerData += '</tr>';
                                                            laypage.render({elem: param.elemPage, count: res.data.count});
                                                        } else {
                                                            innerData = param.dataTpl(res,obj.curr,param.data.pageSize);
                                                        }
                                                        $('#' + param.listbox).html(innerData);
                                                        form.render();
                                                    }else{
                                                        layer.msg(res.msg);
                                                        return false;
                                                    }
                                                }
                                            });
                                        } else {
                                            innerData = param.dataTpl(res,param.data.page,param.data.pageSize);
                                            $('#' + param.listbox).html(innerData);
                                            form.render();
                                        }
                                    }
                                });
                            }
                        }else{
                            layer.msg(res.msg);
                            return false;
                        }
                    }
                });
            },
            dynamic:function (param) { //动态表格
                table.render({
                    elem:'#'+ param.elem , //绑定表格ID
                    // height: 500,
                    url: param.url,     //数据请求地址
                    where: param.where ? param.where : '', //搜索条件 对象
                    loading: param.loading ? param.loading : true,
                    page: param.page ? param.page : true, //是否开启分页 true/false
                    limit: param.limit ? param.limit : 10, //每页显示数量
                    layout: param.layout ? param.layout : ['prev', 'page', 'next', 'skip', 'limit', 'count'], //分页样式
                    sortType:param.sortType ? param.sortType : 'remote', //排序方式: "local"前端排序，remote 后台排序
                    cols: param.cols, //表格字段数据
                });
            }
        }
    };
    exports('ychl',ychl);
});