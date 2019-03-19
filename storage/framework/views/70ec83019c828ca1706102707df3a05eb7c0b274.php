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
                <?php echo e(adminNav($thisAction)); ?>

                <a class="go-back" href="<?php echo e(adminurl("/gate")); ?>"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->


        <form class="layui-form" action=""  method="post" id="addGate">

            <div class="layui-row">
                <input type="hidden" name="id" value="<?php echo e($result->id); ?>">
                <input type="hidden" class="gate_id" name="gate_id" value="<?php echo e($result->gate_id); ?>">
            </div>






            <div class="layui-row">
                <blockquote class="site-text layui-elem-quote searchBox">
                    编辑题目
                </blockquote>

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >题目名称：</label>
                            <div class="layui-input-block">
                                <input id="title" type="text" name="title"  lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo e($result->title); ?>"  >
                            </div>
                        </div>
                    </div>
                </div>

                <?php $optionsarry = explode(',',$result->options);$i = 1;?>
                <input type="hidden"  name="showchoosetext"  autocomplete="off" class="layui-input" id="contenttext" value="<?php echo e($result->options); ?>">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item" id="father">
                            <label class="layui-form-label">可选文字：</label>

                            <?php $__currentLoopData = $optionsarry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($i==1): ?>

                                    <div class="layui-input-block chooseline">
                                        <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="<?php echo e($value); ?>">
                                        <?php $i++;?>
                                        <?php elseif($i >=2 and $i<=6): ?>

                                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="<?php echo e($value); ?>">

                                            <?php $i++;?>


                                        <?php elseif($i == 7): ?>

                                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="<?php echo e($value); ?>">
                                            <?php if($key>23): ?>
                                                <button class="layui-btn layui-btn-primary delline" type="button">删除本行</button>
                                            <?php endif; ?>
                                    </div>
                                    <?php $i = 1;?>

                                <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                        </div>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary addline" type="button">增加一行</button>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12" style="margin-top: 10px;">
                    <input  type="hidden" name="pictureurl"  autocomplete="off" class="layui-input" id="pictureurl" value="<?php echo e($result->hintcontent); ?>">
                    <input  type="hidden" name="videourl"  autocomplete="off" class="layui-input" id="videourl"
                            value="<?php echo e($result->hintcontent); ?>">
                    <input  type="hidden" name="ispicvideo"  autocomplete="off" class="layui-input" id="ispicvideo" value="<?php echo e($result->contenttype); ?>">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">提示内容：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-primary picture <?php if($result->contenttype==1): ?> activeupload <?php endif; ?>" type="button">图片</button>
                                <button class="layui-btn layui-btn-primary video <?php if($result->contenttype==2): ?> activeupload <?php endif; ?>" type="button">语音</button>

                                <div class="layui-upload pictureupload"  <?php if($result->contenttype==2): ?>style="display: none;" <?php else: ?> style="margin-top: 10px;" <?php endif; ?>>
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img" id="demo1" style="width: 200px;" <?php if($result->contenttype==1): ?>  src="<?php echo e($result->hintcontent); ?>" <?php endif; ?>>
                                        <p id="demoText"></p>
                                    </div>
                                </div>

                                <div class="layui-upload videoupload"
                                     <?php if($result->contenttype==1): ?> style="margin-top: 10px; display: none;" <?php else: ?> style="margin-top: 10px;"<?php endif; ?> >
                                    <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>上传音频</button>
                                    <div class="layui-upload-list">
                                        <audio controls="controls" id="demo2"  <?php if($result->contenttype==2): ?>  src="<?php echo e($result->hintcontent); ?>" <?php endif; ?>>
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
                                <textarea placeholder="" class="layui-textarea" name="hintcontenttxt" lay-verify="required" id="hintcontenttxt"><?php echo e($result->hintcontent_txt); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="answer"  autocomplete="off" class="layui-input" id="answer" value="<?php echo e($result->answer); ?>">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">正确答案：</label>
                            <div class="layui-input-block" >
                                <div class="chooseline2" id="father2">
                                    <?php $answerarry = explode(',',$result->answer);?>
                                    <?php $__currentLoopData = $answerarry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answerkey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required" value="<?php echo e($answerkey); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                                <div style="margin-top: 10px;">
                                    <button class="layui-btn layui-btn-primary addanswer" type="button">+</button>
                                    <button class="layui-btn layui-btn-primary delanswer" type="button">-</button>

                                </div>
                                <div style="margin-top: 10px; width: 280px;" id="father3" >

                                    <?php $__currentLoopData = $optionsarry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionskey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"><?php echo e($optionskey); ?></button><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="layui-col-md12">
                    <div class="layui-col-md12">
                        <div class="layui-col-md6">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">所属关卡：</label>
                                    <div class="layui-input-inline">
                                        <select name="gate_id" id="gate_id" lay-verify="required">
                                            <option value="">请选择</option>
                                            <?php $__currentLoopData = $gate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php if($value->id==$result->gate_id): ?> selected="selected" <?php endif; ?> value="<?php echo e($value->id); ?>"><?php echo e($value->gatename); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="layui-inline">
                    <label class="layui-form-label">排序：</label>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input class="layui-input" name="sort" id="sort" autocomplete="off" lay-verify="required" value="<?php echo e($result->sort); ?>">
                    </div>

                </div>

            </div>







            <div class="layui-row">
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addGate">确定</button>
                    <a  href="<?php echo e(adminurl("/subject/showsubject/$result->gate_id")); ?>"><button type="button" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">
                            取消</button></a>
                </div>

            </div>

        </form>

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>


    <script src="<?php echo e(asseturl("js/subject/edit.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>