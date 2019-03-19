<?php $__env->startSection('page_title',"单页管理|后台主页"); ?>
<?php $__env->startSection("content"); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header"><?php echo e($title); ?></div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col>
                            <col width="200">
                            <col width="200">
                            <col width="200">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>创建时间</th>
                            <th>修改时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="listbox"></tbody>
                    </table>
                    <div id="pages"></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script>
        var view_pages = "<?php echo e(actionIsView('view_pages')); ?>";
        var eidt_pages = "<?php echo e(actionIsView('eidt_pages')); ?>";
        var _url = "<?php echo e(url("/")); ?>";
    </script>
    <script src="<?php echo e(asseturl("js/page/index.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>