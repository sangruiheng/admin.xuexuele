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
                消息详情
            </blockquote>
             <form class="layui-form" action=""  method="post" id="addAdvertisement">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告标题：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                    <input id="heading" type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告显示位置：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                    <input id="sort" type="text" name="sort"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                                </div>
                        </div>
                    </div>
                </div>

                <input  type="hidden" name="image"  autocomplete="off" class="layui-input" id="pictureurl" >
                <input  type="hidden" name="content"  autocomplete="off" class="layui-input" id="videourl" >

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告封面：</label>
                            <div class="layui-input-block">
                               
                                <div class="layui-upload pictureupload" style="margin-left: 20px;">
                                  <button type="button" class="layui-btn" id="test1">上传图片</button>
                                  <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo1" style="width: 200px;">
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
                            <label class="layui-form-label" style="width: 100px;">广告音频：</label>
                            <div class="layui-input-block">
                                <div class="layui-upload videoupload" style="margin-left: 20px;">
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
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addAdvertisement">确定</button>
                    <button type="reset" class="layui-btn layui-btn-warm log-action reset">重置
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
   <script src="<?php echo e(asseturl("js/advertisingcenter/advertisement.js")); ?>"></script> 
<?php $__env->stopSection(); ?>
    

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>