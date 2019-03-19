layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate','upload'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    upload = layui.upload;

    //点击图片按钮
    $("body").on("click", ".picture", function () {
        var layui_row = $(this).parents('div.layui-row');
        layui_row.find('.pictureupload').show();
        layui_row.find('.videoupload').hide();
        layui_row.find(".picture").addClass("activeupload");
        layui_row.find(".video").removeClass("activeupload");
        layui_row.find("#ispicvideo").val(1);
    });

    //点击语音按钮
    $("body").on("click", ".video", function () {
        var layui_row = $(this).parents('div.layui-row');
        layui_row.find('.pictureupload').hide();
        layui_row.find('.videoupload').show();
        layui_row.find(".picture").removeClass("activeupload");
        layui_row.find(".video").addClass("activeupload");
        layui_row.find("#ispicvideo").val(2);
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
            url: adminurl + "/gate/add",
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
                    location.href = adminurl + "/gate" ;
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



    //第一次图片上传
    upload.render({
        elem: '.srh-upload'
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


    //第一次语音上传
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
        $("#father").append('<div class="layui-input-block chooseline"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required"><button class="layui-btn layui-btn-primary delline" type="button">删除本行</button></div>');
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

    //增加答案块
    $("body").on("click", ".addanswer", function () {
        var layui_row = $(this).parents('div.layui-row');
        if(layui_row.find(".changeanswer").length>=12){
            return false;
        }
        layui_row.find('.changeanswer').removeClass("activeupload");
        layui_row.find("#father2").append('<input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1"  readonly="readonly" lay-verify="required">');

    });

    //删除答案块
    $("body").on("click", ".delanswer", function () {
        var layui_row = $(this).parents('div.layui-row');
        if(layui_row.find(".changeanswer").length<=2){
            return false;
        }

        layui_row.find(".chooseline2 .activeupload").remove();
        let  contentanswer= new Array();
        layui_row.find("input[name='answertext']").each(function(){
            if($(this).val()!=''){
                contentanswer.push($(this).val());
            }


        });
        let answer = contentanswer.join(",");

        layui_row.find('#answer').val(answer);
    });


    //右侧选择答案
    $("body").on('click','.showchoose',function () {

        var layui_row = $(this).parents('div.layui-row');
        layui_row.find('.showchoose').removeClass("activeupload");
        $(this).addClass("activeupload");
        let choosevalue = $(this).html();
        let edittext = layui_row.find(".chooseline2 .activeupload").length;
        if(edittext>0){
            layui_row.find(".chooseline2 .activeupload").val(choosevalue);
            layui_row.find(".changeanswer").removeClass("activeupload");
        }else{
            layui_row.find("input[name='answertext']").each(function(){
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
    });


    //输入可选文字同步右侧提示内容
    $("body").on('input','.changechoose',function(){
        var _this = this;
        setTimeout(function(){
            if(flag){
                var layui_row = $(_this).parents('div.layui-row');
                layui_row.find("#father3").html("");
                var content= new Array();
                layui_row.find("input[name='choosetext']").each(function(){
                    if($(this).val()!=''){
                        content.push($(this).val());
                    }
                    layui_row.find("#father3").append('<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;">'+$(this).val()+'</button>');

                });
                let contenttext = content.join(",");
                layui_row.find('#contenttext').val(contenttext);

                layui_row.find("input[name='choosetext']").each(function(){
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


    //增加题目
    $('body').on('click', '.add-sbuject', function () {
        var rand = RndNum(4);////看这里，加了一个随机数。
        var str = `
             <div class="layui-row srh-box">
            <blockquote class="site-text layui-elem-quote searchBox">
                题目信息
                <button class="layui-btn layui-btn-sm layui-btn-danger del-sbuject" type="button" style="float: right">删除题目</button>
            </blockquote>
            <input type="hidden"  name="showchoosetext"  autocomplete="off" class="layui-input" id="contenttext" >
            <div class="layui-col-md4">
                <div class="layui-col-md4">
                    <div class="layui-form-item" id="father">
                        <label class="layui-form-label">可选文字：</label>
                        <div class="layui-input-block chooseline">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">

                        </div>
                        <div class="layui-input-block chooseline">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">

                        </div>
                        <div class="layui-input-block chooseline">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">
                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">

                        </div>

                    </div>
                </div>
            </div>

            <input type="hidden" name="answer"  autocomplete="off" class="layui-input" id="answer">
            <div class="layui-col-md4">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">正确答案：</label>
                        <div class="layui-input-block" >
                            <div class="chooseline2" id="father2">
                                <input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required">
                                <input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required">

                            </div>
                            <div style="margin-top: 10px;">
                                <button class="layui-btn layui-btn-primary addanswer" type="button">+</button>
                                <button class="layui-btn layui-btn-primary delanswer" type="button">-</button>

                            </div>
                  
                        </div>

                    </div>
                </div>
            </div>

            <div class="layui-col-md4" style="margin-top: 10px;margin-left: 10%; width: 280px;" id="father3"  >
                <button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button>
            </div>


            <div class="layui-col-md4" style="margin-top: 10px;">
                <input  type="hidden" name="pictureurl"  autocomplete="off" class="layui-input pictureurl-${rand}" id="pictureurl" >
                <input  type="hidden" name="videourl"  autocomplete="off" class="layui-input videourl-${rand}" id="videourl" >
                <input  type="hidden" name="ispicvideo"  autocomplete="off" class="layui-input" id="ispicvideo" value="1">
                <div class="layui-col-md12">
                    <div class="layui-form-item">
                        <label class="layui-form-label">提示内容：</label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary picture activeupload" type="button">图片</button>
                            <button class="layui-btn layui-btn-primary video" type="button">语音</button>

                            <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                <button type="button" class="layui-btn img-upload-${rand}" style="display: inline-block">上传图片</button>
                                <div class="layui-upload-list" style="display: inline-block">
                                    <img class="layui-upload-img img-container-${rand}" id="demo1" style="width: 50px;">
                                    <p id="demoText"></p>
                                </div>
                            </div>

                            <div class="layui-upload videoupload" style="margin-top: 10px; display: none;">
                                <button type="button" class="layui-btn video-upload-${rand}" id="test2" style="display: inline-block;margin-top: -20px"><i class="layui-icon"></i>上传音频</button>
                                <div class="layui-upload-list" style="display: inline-block">
                                    <audio controls="controls" class="video-container-${rand}" id="demo2" style="width: 200px" >
                                        <source type="audio/mp3" />
                                        <source type="audio/ogg" />

                                    </audio>

                                    <p id="demoText2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="layui-col-md12">
                    <div class="layui-form-item">
                        <label class="layui-form-label">提示文案：</label>
                        <div class="layui-input-block">
                            <textarea placeholder="" class="layui-textarea" name="hintcontenttxt" lay-verify="required" id="hintcontenttxt"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-col-md4" style="margin-top: 1%;">
                <label class="layui-form-label" style="padding-left: 20%" >排序：</label>
                <div class="layui-input-inline" style="width: 80px;">
                    <input  class="layui-input" name="answerwisdombeanuse" id="answerwisdombeanuse" autocomplete="off" lay-verify="required">
                </div>
            </div>

        </div>
        `;
        $('.box-container').append(str);
        imgUload('.img-upload-' + rand,'.img-container-'+rand,'.pictureurl-'+rand);//看这里
        voiceUpload('.video-upload-' + rand,'.video-container-'+rand,'.videourl-'+rand);//看这里
    });

    //删除题目
    $('body').on('click', '.del-sbuject', function () {
        var del_box = $(this).parents('div.srh-box');
        console.log(del_box);
        del_box.remove();
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

/*
* 动态添加题目后的上传图片
* btnUpload 点击按钮
* containerUpload 显示的图片
* hidPictureurl  隐藏域的图片
* */
function imgUload(btnUpload,containerUpload,hidPictureurl) {
    layui.use('upload',function () {
        upload.render({
            elem: btnUpload
            ,url: adminurl+'/gate/uploadspic'
            ,field: "picture"
            ,headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    // $('#demo1').attr('src', result); //图片链接（base64）
                    $(containerUpload).attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传成功
                if(res.code ==1){

                    $(hidPictureurl).val(res.data);
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
    })
}

//动态添加题目后的语音上传
function voiceUpload(btnUpload,containerUpload,hidVideourl) {
    upload.render({
        elem: btnUpload
        ,url: adminurl+'/gate/uploadsvideo'
        ,field: "voicefile"
        ,accept: 'file' //音频
        ,headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        ,done: function(res){
            //如果上传成功
            if(res.code ==1){
                $(containerUpload).attr('src', res.data);

                $(hidVideourl).val(res.data);
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

}

//生成随机数
function RndNum(n){
    var rnd="";
    for(var i=0;i<n;i++)
        rnd+=Math.floor(Math.random()*10);
    return rnd;
}