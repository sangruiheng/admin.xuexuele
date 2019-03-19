<?php $__env->startSection("content"); ?>
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
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->coursename); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程封面：</label>
                        <div class="layui-input-block">
                            <div class="layui-input-inline">
                                <p><img src="<?php echo e(URL::asset($result->courseimg)); ?>" style="width:180px;height:180px;" id="iconsrc"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程介绍：</label>
                        <div class="layui-input-block" style="width: 300px;padding-top: 10px;">
                            
                            <?php echo $result->coursetxt; ?>

                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程文字：</label>
                        <div class="layui-input-block" style="width: 300px;padding-top: 10px;">
                            <?php echo $result->coursecontent; ?>

                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程内容：</label>
                        <div class="layui-input-block">
                            <audio controls="controls">
                                <source type="audio/mp3" src="<?php echo e($result->coursevoice); ?>" />
                                <source type="audio/ogg" src="<?php echo e($result->coursevoice); ?>"/>
                           
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">智慧豆：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->wisdombean); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学习量：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->studysum); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">评分：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->coursescore); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block" >
                            <img src="<?php echo e(URL::asset('images/152.png')); ?>" style="height: 40px;">

                            <span style="margin-right: 10px;">
                                <?php $__currentLoopData = $score; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scoreone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($scoreone->coursescore==3): ?>
                                        <?php echo e($scoreone->total); ?>

                                    
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                
                            </span>
                            <img src="<?php echo e(URL::asset('images/153.png')); ?>" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                <?php $__currentLoopData = $score; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scoreone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($scoreone->coursescore==5): ?>
                                        <?php echo e($scoreone->total); ?>

                                    
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </span>
                            <img src="<?php echo e(URL::asset('images/154.png')); ?>" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                <?php $__currentLoopData = $score; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scoreone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($scoreone->coursescore==7): ?>
                                        <?php echo e($scoreone->total); ?>

                                    
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </span>
                            <img src="<?php echo e(URL::asset('images/155.png')); ?>" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                <?php $__currentLoopData = $score; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scoreone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($scoreone->coursescore==10): ?>
                                        <?php echo e($scoreone->total); ?>

                                    
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>留言信息</b></label>
                    </div>
                </div>
            </div>
            <!-- 内容主体区域 -->
            <div id="mainbox" class="layui-row">

                <div class="layui-form news_list">
                    <table class="layui-table" lay-filter="test" >
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>留言时间</th>
                            <th>用户名称</th>
                            <th>留言内容</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="listbox"></tbody>

                    </table>
                </div>
                <div id="pages"></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/album/albumcoursedetail.js")); ?>"></script>
    <script>
        var _id = '<?php echo e($id); ?>';
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>