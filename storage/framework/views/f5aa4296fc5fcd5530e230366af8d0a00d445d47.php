<?php $__env->startSection("content"); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style type="text/css">
        .chooseline{
            display: flex;

        }
        .chooseline2{
            display: flex;
        }
        .activeupload{
            background-color: #CCCCCC;
        }
        .showchoose{
            height: 40px;
            width: 40px;
        }
        .layui-row{background-color: #fff;padding-left: 10px;}
    </style>

    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">

                <a class="go-back" href="<?php echo e(adminurl("/gate")); ?>"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->


        <form class="layui-form" action=""  method="post" id="addGate">


            <input type="hidden" class="gate_id" name="gate_id" value="<?php echo e($gate_id); ?>">
            <div class="layui-row">
                <blockquote class="site-text layui-elem-quote searchBox">
                    添加题目
                </blockquote>


                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >题目名称：</label>
                            <div class="layui-input-block">
                                <input id="title" type="text" name="title"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden"  name="showchoosetext"  autocomplete="off" class="layui-input" id="contenttext" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item" id="father">
                            <label class="layui-form-label">可选文字：</label>
                            <div class="layui-input-block chooseline">
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >

                            </div>
                            <div class="layui-input-block chooseline">
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >

                            </div>
                            <div class="layui-input-block chooseline">
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >
                                <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" >

                            </div>

                        </div>
                        <div class="layui-input-block">
                        <button class="layui-btn layui-btn-primary addline" type="button">增加一行</button>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12" style="margin-top: 10px;">
                    <input  type="hidden" name="pictureurl"  autocomplete="off" class="layui-input" id="pictureurl" >
                    <input  type="hidden" name="videourl"  autocomplete="off" class="layui-input" id="videourl" >
                    <input  type="hidden" name="ispicvideo"  autocomplete="off" class="layui-input" id="ispicvideo" value="1">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">提示内容：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-primary picture activeupload" type="button">图片</button>
                                <button class="layui-btn layui-btn-primary video" type="button">语音</button>

                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img" id="demo1" style="width: 200px;">
                                        <p id="demoText"></p>
                                    </div>
                                </div>

                                <div class="layui-upload videoupload" style="margin-top: 10px; display: none;">
                                    <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>上传音频</button>
                                    <div class="layui-upload-list">
                                        <audio controls="controls" id="demo2" >
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
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">提示文案：</label>
                            <div class="layui-input-block">
                                <textarea placeholder="" class="layui-textarea" name="hintcontenttxt" lay-verify="required" id="hintcontenttxt"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="answer"  autocomplete="off" class="layui-input" id="answer">
                <div class="layui-col-md12">
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
                                <div style="margin-top: 10px; width: 280px;" id="father3" >

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="layui-inline">
                    <label class="layui-form-label">排序：</label>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input class="layui-input" name="sort" id="sort" autocomplete="off" lay-verify="required">
                    </div>

                </div>
            </div>






            <div class="layui-row">

                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addGate">确定</button>
                    <a  href="<?php echo e(adminurl("/subject/showsubject/$gate_id")); ?>"><button type="button" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">
                            取消</button></a>
                </div>

            </div>

        </form>

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>


    <script src="<?php echo e(asseturl("js/subject/add.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>