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
                <?php if(actionIsView('add_roles')): ?>
                <div class="layui-card-header header-action-btn">
                    <button class="layui-btn handle" data-method="addRole"><i class="layui-icon">&#xe61f;</i>添加角色</button>
                </div>
                <?php endif; ?>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col width="200">
                            <col>
                            <col width="200">
                            <col width="200">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>角色名称</th>
                            <th>角色描述</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $roleLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($data->name); ?></td>
                                <td><?php echo e($data->remark); ?></td>
                                <td><?php echo e($data->create_date); ?></td>
                                <td>
                                    <?php if(actionIsView('auth_roles')): ?>
                                    <a href="<?php echo e(adminurl("/roles/auth/".$data->id)); ?>" class="layui-btn layui-btn-sm layui-btn loadHref"><i class="layui-icon"></i>授权</a>
                                    <?php endif; ?>
                                    <?php if(actionIsView('edit_roles')): ?>
                                    <button data-method="editRole" class="layui-btn layui-btn-sm layui-btn-normal handle"
                                            data-id="<?php echo e($data->id); ?>"
                                            data-title="<?php echo e($data->name); ?>"
                                            data-remark="<?php echo e($data->remark); ?>"><i class="layui-icon"></i>编辑</button>
                                    <?php endif; ?>
                                    <?php if($data->id!=1 && actionIsView('del_roles')): ?>
                                        <button data-method="deleteRole" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="<?php echo e($data->id); ?>"><i class="layui-icon"></i>删除</button>
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
    <!----添加角色----->
    <div  class="layui-form">
        <form id="addRole" class="layui-form" style="display: none">
            <div class="layui-form-item" style="padding:0px 10px;">
                <?php echo e(csrf_field()); ?>

                <label class="layui-label">角色名称：</label>
                <input id="roleId" type="hidden" name="id" value="" />
                <input id="rolename" type="text" name="rolename" required  lay-verify="required" placeholder="请输入角色名称" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">角色描述：</label>
                <textarea id="remark" class="layui-textarea" name="remark" placeholder="请输入角色描述"></textarea>
            </div>
        </form>
    </div>
    <!----添加角色----->
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/manage/role.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>