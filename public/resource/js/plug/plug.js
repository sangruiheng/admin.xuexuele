layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'flow','ychl'], function () {
    var element = layui.element;
    var flow = layui.flow;
    var form = layui.form;
    var $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    var ychl  = layui.ychl;
    flow.load({
        elem: '#myPlug' //流加载容器
        ,scrollElem: '#myPlug' //滚动条所在元素，一般不用填，此处只是演示需要。
        ,done: function(page, next){ //执行下一页的回调
            var data = '';
            ychl.ajax({
                url:adminurl + "/plugs/installedLists",
                data: {page:page},
                type: "get",
                done:function (res) {
                    if (res.code == 1) {
                        data = res.data.lists;
                        $("#myPlugCount").text(res.data.count);
                        var lists = loadMyPlugLists(data);
                        var lis = [];
                        lis.push(lists)
                        //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                        //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                        next(lis.join(''), page < (res.data.count / 12)); //假设总页数为 10
                    } else {
                        layer.msg(res.msg);
                        return false;
                    }
                }
            });
        }
    });
    element.on('tab(plugTab)', function(data){
        if(data.index){
            flow.load({
                elem: '#plug' //流加载容器
                ,scrollElem: '#plug' //滚动条所在元素，一般不用填，此处只是演示需要。
                ,done: function(page, next){ //执行下一页的回调
                    var data = '';
                    ychl.ajax({
                        url: "http://plug.huimor.com/api/plugs/lists",
                        data: {
                            page:page
                        },
                        type: "get",
                        done:function (res) {
                            layer.close(index);
                            if (res.code == 1) {
                                data = res.data.lists;
                                console.log(data);
                                var lists = loadPlugLists(data);
                                var lis = [];
                                lis.push(lists)
                                //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                                //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                                next(lis.join(''), page < (res.data.count / 12)); //假设总页数为 10
                            }else{
                                layer.msg(res.msg);
                                return false;
                            }
                        }
                    });
                }
            });
        }
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.keyword == "" && data.field.ispay == "" && data.field.state == "") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1, data.field.ispay, data.field.state, data.field.keyword, "");
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        getDataLists(1, "", "", "", "");
    });
    var active = {
        add: function () {
            layer.open({
                type: 1 //此处以iframe举例
                , title: '<i class="layui-icon">&#xe608;</i> 添加用户'
                , area: ['390px', 'auto']
                , shade: 0.8
                , maxmin: false
                , content: $("#addUser")
                , btn: ['保存', '关闭'] //只是为了演示
                , yes: function () {
                    if ($("#name").val() == "") {
                        $("#name").focus();
                        layer.msg("用户名称不能为空！");
                        return false;
                    }
                    if ($("#mobile").val() == "") {
                        $("#mobile").focus();
                        layer.msg("手机号码不能为空！");
                        return false;
                    }
                    if ($("#password").val() == "") {
                        $("#password").focus();
                        layer.msg("密码不能为空！");
                        return false;
                    }
                    if ($("#password").val().length < 6) {
                        $("#password").focus();
                        layer.msg("密码不能少于6位！");
                        return false;
                    }
                    ychl.ajax({
                        url: adminurl + "/users/add",
                        data: $("#addUser").serialize(),
                        type: "POST",
                        done:function (res) {
                            layer.close(index);
                            if (res.code == 1) {
                                layer.msg(res.msg);
                                setTimeout(function () {
                                    layer.closeAll();
                                    $("#addUser")[0].reset();
                                    getDataLists(1, "", "", "", "");
                                }, 500)
                            }else{
                                layer.msg(res.msg);
                                return false;
                            }
                        }
                    });
                }
                , btn2: function () {
                    layer.closeAll();
                }
                , zIndex: layer.zIndex //重点1
            });
        },
        install:function () {
            var plug_id = $(this).data('id');
            var down_url = $(this).data('down');
            var type = $(this).data('type');
            var key  = $(this).data('key');
            var label = $(this).data('label');
            ychl.ajax({
                url: adminurl + "/plugs/install",
                data:{
                    plug_id:plug_id,
                    plug_type:type,
                    plug_key:key,
                    down_url:down_url,
                    menu_label:label
                },
                type: "get",
                done:function (res) {
                    layer.close(index);
                    if (res.code == 1) {
                        layer.msg(res.msg);
                        //安装成功后弹出插件配置说明
                        setTimeout(function () {
                            layer.open({
                                type: 2,
                                title: '插件详情',
                                shade: [0.6, '#000'],
                                maxmin: true, //开启最大化最小化按钮
                                area: ['893px', '600px'],
                                content: adminurl + '/plugs/view/' + plug_id
                            });
                        },3000);

                    }else{
                        layer.msg(res.msg);
                        return false;
                    }
                }
            });
        }
    };
    $('body').on('click', '.handle', function () {

        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    //数据格式化模板
    function loadMyPlugLists(data,page){
        console.log(data);
        var listData = '';
        layui.each(data, function (index, item) {
            listData += '<div class="layui-col-md4">';
            listData += '<div class="cont-box">';
            listData += '<div class="plug-info layui-col-md12">';
            listData += '<h3>插件名称：'+item.name+'【'+item.type_name+'】</h3>';
            listData += '<div class="plug-desc">描述：'+item.description+'</div>';
            listData += '<div class="plug-btn">';
            listData += '<a href="'+adminurl+'/plugs/details/'+item.id+'" class="layui-btn layui-btn-sm">查看详情</a>';
            listData += '<!--<a href="javascript:;" class="layui-btn layui-btn-sm layui-btn-danger">卸载</a>--></div></div>';
            listData += '</div></div>';
        });
        return listData;
    }

    function loadPlugLists(data,page){
        var listData = '';
        layui.each(data, function (index, item) {
            listData += '<div class="layui-col-md4">';
            listData += '<div class="cont-box">';
            listData += '<div class="plug-info layui-col-md12">';
            listData += '<h3>插件名称：'+item.name+'【'+item.type_name+'】</h3>';
            listData += '<div class="plug-desc">描述：'+item.description+'</div>';
            listData += '<div class="plug-btn">';
            listData += '<a href="'+adminurl+'/plugs/details/'+item.id+'" class="layui-btn layui-btn-sm">查看详情</a>';
            listData += '<button data-method="install" data-id="'+item.id+'" data-down="'+item.plug_url+'" data-type="'+item.type+'" data-key="'+item.plug_key+'" data-label="'+item.menu_label+'" class="layui-btn layui-btn-sm layui-btn-danger handle">安装</button></div></div>';
            listData += '</div></div>';
        });
        return listData;
    }
});