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
            <form class="layui-form" action=""  method="post" id="formAdd">
                <input type="hidden"  name="albumid"  autocomplete="off" class="layui-input" value="<?php echo e($id); ?>">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">课程名称：</label>
                            <div class="layui-input-block">
                                <input id="coursename" type="text" name="coursename"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="" id="user_phone_up" >
                            </div>
                        </div>
                    </div>
                </div>
                <input  type="hidden" name="courseimg"  autocomplete="off" class="layui-input" id="pictureurl" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">课程封面：</label>
                            <div class="layui-input-block">
                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
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
                            <label class="layui-form-label">课程介绍：</label>
                            <div class="layui-input-block">
                                
                                <textarea  id="LAY_demo1" style="display: none;" >  
                                  
                                </textarea>
                                <input type="hidden" name="coursetxt" id="coursetxt">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">课程文字：</label>
                            <div class="layui-input-block">
                                
                                <textarea  id="LAY_demo2" style="display: none;" >  
                                  
                                </textarea>
                                <input type="hidden" name="coursecontent" id="coursecontent">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <input  type="hidden" name="coursevoice"  autocomplete="off" class="layui-input" id="videourl" >
                <input type="hidden" name="coursetime"  autocomplete="off" class="layui-input" id="coursetime" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">课程内容：</label>
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
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">智慧豆：</label>
                            <div class="layui-input-block" style="display: flex;">
                                <input id="wisdombean" type="text" name="wisdombean"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="" id="user_phone_up" style="width: 375px;margin-right: 10px;" >
                                <input type="checkbox" id="free" name="free" title="勾选免费" lay-skin="primary" value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formAdd">确定</button>
                    <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">重置</button> 
                </div>
            </form>
          
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/album/albumcourseindex.js")); ?>"></script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>