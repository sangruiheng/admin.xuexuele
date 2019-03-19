layui.use(['layedit','element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    var upload = layui.upload;
    var layedit = layui.layedit
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;
    laydate = layui.laydate;
    upload = layui.upload;
    

    layedit.set({
      uploadImage: {
        url: adminurl+'/album/uploadspic' //接口url
        
      }
    });

    var coursetxt = layedit.build('LAY_demo1', {
      height: 180, //设置编辑器高度
      tool: ['image']
    });

    var coursecontent = layedit.build('LAY_demo2', {
      height: 180, //设置编辑器高度
      tool: ['image']
    });
  

    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.nameid== "" && data.field.albumname== "" ) {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1,data.field.albumname,data.field.nameid);
        }
        return false;
    });
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
        ,accept: 'file' //音频
        ,headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        ,done: function(res){
          //如果上传成功
          if(res.code ==1){
            $('#demo2').attr('src', res.data); 

            $('#videourl').val(res.data);
            getTime();
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
    //重置搜索
    $("body").on("click", ".reset", function () {
        getDataLists(1, "", "", "", "","","");
    });

    //添加
    form.on('submit(formAdd)', function (data) {
        var check=document.getElementsByName("free");

        let content=layedit.getContent(coursecontent);

        $('#coursecontent').val(content);
        
        
        let txt=layedit.getContent(coursetxt);
        
        $('#coursetxt').val(txt);
        

        if(check.checked){
            data.field.free=1;
            data.field.wisdombean=0;
            return false;
        }
        let pic=$('#pictureurl').val();
        if(pic==''){
            layer.msg('请上传图片');
            return false;
        }
        let video=$('#videourl').val();
        if(video==''){
            layer.msg('请上传音频');
            return false;
        }
        
        $.ajax({

            url: adminurl + "/album/addcourse",
            data: $("#formAdd").serialize(),
            type: "post",
            dataType: "json",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                //res=JSON.parse(res);
                //console.log('11111');
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.href = adminurl + "/album" ;
                } else if (res.code == 1002) { //未登录跳转到登录页
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
                layer.msg("操作失败!");
                return false;
            }
        });
        return false;
    });

    var active = {
        delete: function () {
            var id = $(this).data("id");
            var state = $(this).data("state");
            var auditMsg = "";
            if(state == 1){
                auditMsg = "确定删除操作么？";
            }else{
                auditMsg = "确定删除操作么？";
            }
            layer.confirm(auditMsg, {
                btn: ['确认', '取消'] //按钮
                , title: '<i class="layui-icon"></i> 确认提示'
                , icon: 0
            }, function () {
                $.ajax({
                    url: adminurl + "/album/coursedelete",
                    data: {
                        id: id,
                        status:state,
                        _token: _token,
                    },
                    type: "DELETE",
                    dataType: "json",
                    beforeSend: function (request) {
                        index = layer.load(2, {
                            shade: [0.1, '#FFF'] //0.1透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.close(index);
                        if (res.code == 1) {
                            layer.closeAll();
                            layer.msg(res.msg);
                            setTimeout(function () {
                                layer.closeAll();
                                getDataLists(location.hash.replace('#!page=',''), "", "", "","","");
                            }, 200)
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
                        layer.msg("操作失败！");
                        return false;
                    }
                });
            });
        },
    };
    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    

    getDataLists(location.hash.replace('#!page=',''), "", ""); //初始化表格数据
    function getDataLists(page) {
        $.ajax({
            url: adminurl + "/album/albumcourselists/"+_id,
            data: {
                page:page,

                paged:20,
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
                        innerData += '<td class="align-center" colspan="8">暂无数据</td>';
                        innerData += '</tr>';
                        laypage.render({elem: 'pages', count: res.data.total});
                    } else {
                        laypage.render({
                            elem: 'pages'
                            ,count: res.data.total
                            ,limit: 20
                            ,layout:['count', 'prev', 'page', 'next', 'limit', 'skip']
                            ,curr: page
                            ,hash:"page"
                            , jump: function (obj, first) {
                                //getRegionLists(obj.curr,obj);
                                //首次不执行
                                if (!first) {
                                    $.ajax({
                                        url: adminurl + "/album/albumcourselists/"+_id,
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
                                                    innerData += '<td class="align-center" colspan="8">暂无数据</td>';
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
                layer.msg("操作失败！");
                return false;
            }
        });
    }

    //数据格式化模板
    function floatDataTpl(data, page) {
        layui.each(data, function (index, item) {
            innerData += "<tr>";
            innerData += '<td>' + item.id + '</td>';
            innerData += '<td><img style="cursor: pointer" data-method="viewGoodsBigImg" class="handle"  src=" ' +item.courseimg+'" height="30px"></td>';
            innerData += '<td>' + item.coursename + '</td>';
            innerData += '<td>' + item.wisdombean + '</td>';
            innerData += '<td>' + item.studysum + '</td>';
            innerData += '<td>' + item.coursetime + '</td>';
            innerData += '<td>' + item.commentsum + '</td>';
            innerData += '<td>';
            //if(edit_academybanner) {
            innerData += '<a  href= "' + adminurl + '/album/albumcourseviewdetail/' + item.id + '" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2"><i class="layui-icon"></i>查看</a>';
            //}
            innerData += '<a data-method="delete" class="layui-btn layui-btn-sm layui-btn handle" data-id="' + item.id + '" data-state="2">删除</a>';
            innerData += '</td>';
            innerData +='</tr>';
        });
    }
});


function getTime() {
    setTimeout(function () {
        var duration = $("#demo2")[0].duration;
        if(isNaN(duration)){
            getTime();
        }
        else{
            let time=  formatSeconds($("#demo2")[0].duration);
            $('#coursetime').val(time);
           
        }
    }, 10);
}

function formatSeconds(value) {
    var secondTime = parseInt(value);// 秒
    var minuteTime = 0;// 分
    var hourTime = 0;// 小时
    if(secondTime > 60) {//如果秒数大于60，将秒数转换成整数
        //获取分钟，除以60取整数，得到整数分钟
        minuteTime = parseInt(secondTime / 60);
        //获取秒数，秒数取佘，得到整数秒数
        secondTime = parseInt(secondTime % 60);
        //如果分钟大于60，将分钟转换成小时
        // if(minuteTime > 60) {
        //     //获取小时，获取分钟除以60，得到整数小时
        //     hourTime = parseInt(minuteTime / 60);
        //     //获取小时后取佘的分，获取分钟除以60取佘的分
        //     minuteTime = parseInt(minuteTime % 60);
        // }
    }

    

    if(secondTime<10){
        var result = "0" + parseInt(secondTime);
    }else{
        var result = "" + parseInt(secondTime);
    }
    

    if(minuteTime > 0) {
        if(minuteTime<10){
            result = "0" + parseInt(minuteTime) + ":" + result;
        }else{
            result = "" + parseInt(minuteTime) + ":" + result;
        }
        
    }else{
        result = "00:" + result;
    }
    // if(hourTime > 0) {
    //     result = "" + parseInt(hourTime) + "小时" + result;
    // }
    return result;
}
