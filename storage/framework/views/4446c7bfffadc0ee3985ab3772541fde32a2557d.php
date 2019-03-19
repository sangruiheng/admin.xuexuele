<?php $__env->startSection("content"); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote">
                基本信息
            </blockquote>
            <form class="layui-form" action=""  method="post" id="formEdit">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo e($result->id); ?>" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">专辑名称：</label>
                            <div class="layui-input-block">
                                <input type="text" name="albumname"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->albumname); ?>" id="user_phone_up">
                            </div>
                        </div>
                    </div>
                </div>
                <input  type="hidden" name="albumimg"  autocomplete="off" class="layui-input" id="pictureurl" value="<?php echo e($result->albumimg); ?>">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">专辑封面：</label>
                            <div class="layui-input-block">
                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img src="<?php echo e($result->albumimg); ?>" class="layui-upload-img" id="demo1" style="width: 200px;">
                                        <p id="demoText"></p>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">专辑介绍：</label>
                            <div class="layui-input-block">
                                <input type="text" name="albumcontent"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->albumcontent); ?>" id="user_phone_up">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEdit">确定</button>
                    <a href="javascript:history.go(-1)" class="layui-btn layui-btn-normal" target="_blank">返回</a> 
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/album/albumindex.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>