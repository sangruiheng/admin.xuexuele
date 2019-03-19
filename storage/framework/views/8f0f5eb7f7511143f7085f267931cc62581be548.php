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
                <?php if(actionIsView('add_admins')): ?>
                <div class="layui-card-header header-action-btn">
                    <a href="<?php echo e(adminurl("/admins/add")); ?>" class="layui-btn loadHref"><i class="layui-icon">&#xe61f;</i>添加管理员</a>
                </div>
                <?php endif; ?>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col width="200">
                            <col width="200">
                            <col width="150">
                            <col >
                            <col >
                            <col width="150">
                            <col width="150">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>管理员名称</th>
                            <th>管理员账号</th>
                            <th>管理员角色</th>
                            <th>添加时间</th>
                            <th>修改时间</th>
                            <th>是否启用</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $adminLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($data->realname); ?></td>
                                <td><?php echo e($data->username); ?></td>
                                <td><?php echo e($data->name); ?></td>
                                <td><?php echo e($data->admin_create_date); ?></td>
                                <td><?php echo e($data->admin_update_date); ?></td>
                                <td>
                                   
                                        <div class="layui-form resetcss" data-method="showAdmin">
                                            <input type="checkbox" name="is_show" lay-skin="switch" lay-filter="status" lay-text="是|否" data-id="<?php echo e($data->admin_id); ?>" checked  disabled>
                                        </div>
                                   
                                </td>
                                <td>
                                    <?php if(actionIsView('edit_admins')): ?>
                                    <a href="<?php echo e(adminurl("/admins/edit/".$data->admin_id)); ?>" class="layui-btn layui-btn-sm layui-btn-normal loadHref"><i class="layui-icon"></i>编辑</a>
                                    <?php endif; ?>
                                    <?php if($data->admin_id!=1 && actionIsView('del_admins')): ?>
                                        <button data-method="deleteAdmin" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="<?php echo e($data->admin_id); ?>"><i class="layui-icon"></i>删除</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/manage/admin.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>