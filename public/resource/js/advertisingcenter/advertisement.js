layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    var upload = layui.upload;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    upload = layui.upload;
    //普通图片上传
    upload.render({
        elem: '#test1'
        ,url: adminurl+'/gate/uploadspic'
        ,field: "picture"
        ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        ,before: function(obj){
          //预读本地文件示例，不支持ie8
          obj.preview(function(index, file, result){
            $('#demo1').attr('src', result); //图片链接（base64）
          });
        }
        ,done: function(res){
          //如果上传成功
          if(res.code ==1){
            
            $('#pictureurl').val(res.data);
            return layer.msg('上传成功！');
          }
          //失败
          else{
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span>');
                
          }
        }
        ,error: function(){
          //演示失败状态，并实现重传
          var demoText = $('#demoText');
          demoText.html('<span style="color: #FF5722;">上传失败</span> ');
          
        }
    });

    upload.render({
        elem: '#test2'
        ,url: adminurl+'/gate/uploadsvideo'
        ,field: "voicefile"
        ,accept: 'audio' //音频
        ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        ,done: function(res){
          //如果上传成功
          if(res.code ==1){
            $('#demo2').attr('src', res.data); 

            $('#videourl').val(res.data);
            return layer.msg('上传成功！');
          }
          //失败
          else{
                //演示失败状态，并实现重传
                var demoText = $('#demoText2');
                demoText.html('<span style="color: #FF5722;">上传失败</span> ');
          }
        }
        ,error: function(){
            //演示失败状态，并实现重传
            var demoText = $('#demoText2');
            demoText.html('<span style="color: #FF5722;">上传失败</span> ');
        }
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.name== "") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.name);
        }
        return false;
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    
    getDataLists(location.hash.replace('#!page=',''), "", "", "", ""); //初始化表格数据
    function getDataLists(page,name) {
        $.ajax({
            url: adminurl + "/advertisement/advertisementlists",
            data: {
                page:page,
                name:name,
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
                                        url: adminurl + "/advertisement/advertisementlists",
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
                                                    floatDataTpl(res.data.advertisementlists, obj.curr);
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
                                    floatDataTpl(res.data.advertisementlists, obj.curr);
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
    //修改信息
    form.on('submit(editAdvertisement)', function (data) {
        console.log('1111111');
        $.ajax({
            url: adminurl + "/advertisement/upstatus",
            data: $("#editAdvertisement").serialize(),
            method:"POST",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success:function (res) {
                 console.log('3233');
                res=JSON.parse(res);
                if(res.code==1){
                    layer.msg(res.msg);
                    location.href = adminurl + "/advertisement" ;
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    //添加
    form.on('submit(addAdvertisement)', function (data) {
        if(data.field.heading==""){
            layer.msg("广告标题不能为空！");
            return false;
        }
        if(data.field.image==""){
            layer.msg("广告封面不能为空！");
            return false;
        }
        $.ajax({
            url: adminurl + "/advertisement/addadvertisement",
            data:$("#addAdvertisement").serialize(),
            method:"POST",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                res=JSON.parse(res);
                if(res.code==1){
                    layer.msg(res.msg);
                    location.href = adminurl + "/advertisement" ;
                
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.create_time + '</td>';
            innerData += '<td>' + item.heading + '</td>';
            innerData += '<td>';
            innerData += '<img src="'+item.image +'" style="width:100%;height:80px;">';
            innerData += '</td>';
            innerData += '<td>' + item.sort + '</td>';
            innerData += '<td>';
            innerData += '<a  href= "' + adminurl + '/advertisement/advertisementview/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>修改</a>';
            innerData += '</td>';
            innerData += "</tr>";
        });
    }
});
