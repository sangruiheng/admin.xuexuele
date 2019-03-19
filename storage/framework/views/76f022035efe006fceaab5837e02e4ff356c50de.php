<?php $__env->startSection("content"); ?>
    <div class="layui-row">
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row layui-form">
            <form id="dataForm" class="layui-form">
                <?php echo e(csrf_field()); ?>

                <fieldset class="layui-elem-field">
                    <legend>操作管理</legend>
                    <div class="layui-field-box" id="listbox">
                        <?php $__currentLoopData = $menuActionLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="layui-inline mb10" id="checkbox_<?php echo e($data->id); ?>">
                                <input type="checkbox" name="type_ids[]" title="<?php echo e($data->name); ?>" value="<?php echo e($data->id); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </fieldset>
                <div class="layui-inline" style="position: fixed;bottom:10px;right:15px;">
                    <button class="layui-btn layui-btn-sm" lay-submit lay-filter="formAdd">添加操作</button>
                    <!--<button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEdit">编辑</button>-->
                    <button class="layui-btn layui-btn-danger layui-btn-sm" lay-submit lay-filter="formDelete">删除</button>
                    <button class="layui-btn layui-btn-primary layui-btn-sm" lay-submit lay-filter="cancel">关闭</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script>
        _token  = "<?php echo e(csrf_token()); ?>";
        menu_id = "<?php echo e($menuId); ?>";
    </script>
    <script src="<?php echo e(asseturl("js/menu/menu.action.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.mainview', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>