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
            <blockquote class="site-text layui-elem-quote searchBox">
                奖励信息
            </blockquote>
           
           <form class="layui-form" action=""  method="post" id="formEdit">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo e($result->id); ?>" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 90px;">奖励名称：</label>
                            <div class="layui-input-block" style="margin-left: 120px;">
                                    <input id="name" type="text" name="name"  lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo e($result->name); ?>"   >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 90px;">每月签到：</label>
                            <div class="layui-input-block" style="display: flex;align-items: center;">
                                 <input id="day" type="text" name="day"  lay-verify="required|number" autocomplete="off" class="layui-input" value="<?php echo e($result->day); ?>" number>
                                 <label>次</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 90px;">奖励智慧豆：</label>
                            <div class="layui-input-block" style="display: flex;align-items: center;">
                                <input id="rewordbeans" type="text" name="rewordbeans"  lay-verify="required|number" autocomplete="off" class="layui-input" value="<?php echo e($result->rewordbeans); ?>" number><label>个</label> 
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
    <script src="<?php echo e(asseturl("js/signreward/edit.js")); ?>"></script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>