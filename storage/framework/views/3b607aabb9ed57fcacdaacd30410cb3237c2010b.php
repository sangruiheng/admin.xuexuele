<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asseturl('css/plug/plug.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header"><?php echo e($title); ?></div>
                <div class="layui-card-header header-action-btn">
                    <form class="layui-form" action="">
                        <div class="layui-inline">
                            <select id="state" name="state" lay-filter="state" placeholder="">
                                <option value="">用户状态</option>
                                <option value="1">正常</option>
                                <option value="2">黑名单</option>
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input type="text" name="keyword" value="" class="layui-input" placeholder="请输入搜索关键词">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i class="layui-icon">&#xe615;</i>搜索</button>
                            <button type="reset" class="layui-btn layui-btn-warm reset"><i class="layui-icon">&#x1006;</i>重置</button>
                            <?php if(actionIsView("add_users")): ?>
                                <a href="javascript:;" data-method="add" class="layui-btn handle"><i class="layui-icon">&#xe61f;</i>添加用户</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body">
                    <div class="layui-tab layui-tab-brief" lay-filter="plugTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this">已安装插件(<?php echo e($installedCount); ?>)</li>
                            <li>插件中心(<?php echo e($plugCount); ?>)</li>
                        </ul>
                        <div class="layui-tab-content plug-box">
                            <div class="layui-tab-item layui-show">
                                <div class="layui-row layui-col-space10" id="myPlug">

                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-row layui-col-space10" id="plug">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script>
        //向js端传递授权信息
        is_edit = "<?php echo e(actionIsView("edit_users")); ?>";
        is_blacklist = "<?php echo e(actionIsView("blacklist_users")); ?>";
        //console.log(is_blacklist);
    </script>
    <script src="<?php echo e(asseturl("js/plug/plug.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>