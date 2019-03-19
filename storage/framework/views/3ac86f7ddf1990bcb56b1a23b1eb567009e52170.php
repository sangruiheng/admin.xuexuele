<?php $__env->startSection("content"); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                
            </blockquote>

            <div class="layui-form news_list">
                <div style="display: flex;justify-content: center;font-size: 32px;color: #515a6e;padding: 180px;">正在开发中</div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <!--  <script src="<?php echo e(asseturl("js/reporting/index.js")); ?>"></script>  -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>