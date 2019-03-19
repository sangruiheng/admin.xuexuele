layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    upload = layui.upload;


    $("body").on("click", ".picture", function () {
        $('.pictureupload').show();
        $('.videoupload').hide();
        $(".picture").addClass("activeupload");
        $(".video").removeClass("activeupload");
        $("#ispicvideo").val(1);
    });

    $("body").on("click", ".video", function () {
        $('.pictureupload').hide();
        $('.videoupload').show();
        $(".picture").removeClass("activeupload");
        $(".video").addClass("activeupload");
        $("#ispicvideo").val(2);
    });

    $("body").on("click", ".reset", function () {
        location.reload();
    });

    form.on('submit(addGate)', function (data) {

        // let rewardbeans=$('#rewardbeans').val();
        // let pkvalue=$('#pk').val();
        // let teshu=$('#teshu').val();
        // let gaterewordid=$('#gaterewordid').val();
        let pictureurl=$('#pictureurl').val();
        let videourl=$('#videourl').val();
        let ispicvideo=$('#ispicvideo').val();
        let gate_id = $('.gate_id').val();
        // let hintcontenttxt=$('#hintcontenttxt').val();
        // let showchoosetext='';//$('#contenttext').val();
        // let answer='';//$('#answer').val();
        // let courserid=$('#selectid').val();
        // let answerwisdombeanuse=$('#answerwisdombeanuse').val();

        if(ispicvideo==1 && pictureurl==''){
            layer.msg("请上传图片!");
            return false;
        };
        if(ispicvideo==2 && videourl==''){
            layer.msg("请上传音频!");
            return false;
        }

        $.ajax({
            url: adminurl + "/subject/editgatesubject",
            type: 'POST',
            dataType: 'json',
            data: $("#addGate").serialize(),

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
            .done(function(res) {
                console.log("success");
                // res=JSON.parse(res);
                // return false;
                //console.log('11111');
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.href = adminurl + "/subject/showsubject/"+gate_id;
                } else if (res.code == 1002) { //未登录跳转到登录页
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 500)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            })
            .fail(function() {
                console.log("error");
                layer.msg("操作失败!");
                return false;
            })
            .always(function() {
                console.log("complete");
            });

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

    // $("body").on('change','.changechoose',function () {
    //     $('#father3').html("");
    //     var content= new Array();
    //     $("input[name='choosetext']").each(function(){
    //         if($(this).val()!=''){
    //             content.push($(this).val());
    //         }

    //         $("#father3").append('<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;">'+$(this).val()+'</button>');


    //     });
    //     let contenttext = content.join(",");

    //     $('#contenttext').val(contenttext);

    // });

    $("body").on("click", ".addline", function () {
        if($(".chooseline").length>=8){
            return false;
        }
        $("#father").append('<div class="layui-input-block chooseline"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" ><button class="layui-btn layui-btn-primary delline" type="button">删除本行</button></div>');
        $("input[name='choosetext']").each(function(){
            if($(this).val()==''){
                $(this).focus();
                return false;
            }



        });

    });


    $("body").on('click','.delline',function () {
        $('#father3').html("");
        $(this).parent().remove();
        var content= new Array();
        $("input[name='choosetext']").each(function(){
            if($(this).val()!=''){
                content.push($(this).val());
            }
            $("#father3").append('<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;">'+$(this).val()+'</button>');

        });
        let contenttext = content.join(",");

        $('#contenttext').val(contenttext);


    });


    $("body").on("click", ".addanswer", function () {

        if($(".changeanswer").length>=12){
            return false;
        }
        $('.changeanswer').removeClass("activeupload");
        $("#father2").append('<input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1"  readonly="readonly" lay-verify="required">');

    });

    $("body").on("click", ".delanswer", function () {
        if($(".changeanswer").length<=2){
            return false;
        }

        $(".chooseline2 .activeupload").remove();
        let  contentanswer= new Array();
        $("input[name='answertext']").each(function(){
            if($(this).val()!=''){
                contentanswer.push($(this).val());
            }


        });
        let answer = contentanswer.join(",");

        $('#answer').val(answer);
    });



    $("body").on('click','.showchoose',function () {

        $('.showchoose').removeClass("activeupload");
        $(this).addClass("activeupload");
        let choosevalue = $(this).html();
        let edittext = $(".chooseline2 .activeupload").length;
        if(edittext>0){
            $(".chooseline2 .activeupload").val(choosevalue);
            $(".changeanswer").removeClass("activeupload");
        }else{
            $("input[name='answertext']").each(function(){
                if($(this).val()==''){
                    $(this).val(choosevalue);
                    return false;
                }



            });
        }

        let  contentanswer= new Array();
        $("input[name='answertext']").each(function(){
            if($(this).val()!=''){
                contentanswer.push($(this).val());
            }


        });
        let answer = contentanswer.join(",");

        $('#answer').val(answer);

    });

    var flag = true;
    $("body").on('compositionstart','.changechoose',function(){
        flag = false;
    })
    $("body").on('compositionend','.changechoose',function(){
        flag = true;
    })
    $("body").on('input','.changechoose',function(){
        var _this = this;
        setTimeout(function(){
            if(flag){
                // console.log($(_this).val());
                $('#father3').html("");
                var content= new Array();
                $("input[name='choosetext']").each(function(){
                    if($(this).val()!=''){
                        content.push($(this).val());
                    }

                    $("#father3").append('<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;">'+$(this).val()+'</button>');


                });
                let contenttext = content.join(",");

                $('#contenttext').val(contenttext);

                $("input[name='choosetext']").each(function(){
                    if($(this).val()==''){
                        $(this).focus();
                        return false;
                    }



                });
            }
        },0)
    });


    $("body").on("click", ".changeanswer", function () {

        $(".changeanswer").removeClass("activeupload");
        $(this).addClass("activeupload");
    });

    $("body").on("click", ".selectcourse", function () {

        layer.open({
            type: 2,
            title: '选择课程',
            area: ['900px', '600px'],
            content: adminurl + "/gate/courselist", //这里content是一个普通的String
            success: function(layero, index){
                console.log(layero, index);
            }
        });
    });

    $("body").on("click", ".selectreword", function () {

        layer.open({
            type: 2,
            title: '选择课程',
            area: ['900px', '600px'],
            content: adminurl + "/gate/rewordlist", //这里content是一个普通的String
            success: function(layero, index){
                console.log(layero, index);
                let teshuval = $('#teshu').val()
                var iframe = window['layui-layer-iframe' + index];
                iframe.child(teshuval)
            }
        });
    });


    form.on('select(teshu)', function(data){
        let teshuval = $('#teshu').val()
        console.log(teshuval)
        if(teshuval!=0){
            $('.showreword').show();
        }else{
            $('.showreword').hide();
        }
        $('#selectcontent').val('');
        $('#gaterewordid').val('');
    });
});

function GetValue(name,id){
    $('#selectname').val(name);
    $('#selectid').val(id);
}

function GetValue2(name,id){
    $('#selectcontent').val(name);
    $('#gaterewordid').val(id);
}
