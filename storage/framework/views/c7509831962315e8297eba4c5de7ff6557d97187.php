<?php $__env->startSection("content"); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header"><?php echo e($title); ?></div>
                <div class="layui-card-body">
                    <form class="layui-form">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="roles_id" value="<?php echo e($roles_id); ?>" />
                        <blockquote class="site-text layui-elem-quote" id="menubox">
                            <input type="checkbox" name="" title="全选" lay-filter="menu" lay-skin="primary" <?php if($roles_id==1): ?> disabled <?php endif; ?>>
                        </blockquote>
                        <div class="menu-auth-box" id="menuChild">
                            <?php $__currentLoopData = $menuLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="colla-item">
                                    <h2 class="colla-title">
                                        <input id="menuChildChildBox_<?php echo e($key); ?>" type="checkbox" data-key="<?php echo e($key); ?>" title="<?php echo e($data->title); ?>" lay-skin="primary" lay-filter="menuChild"
                                               <?php if($roles_id==1): ?> disabled <?php endif; ?>
                                               <?php if($data->thisIsChecked==1): ?> checked <?php endif; ?>>
                                    </h2>
                                    <div class="layui-colla-content layui-show" id="menuChildChild_<?php echo e($key); ?>">
                                        <table>
                                            <colgroup>
                                                <col width="150">
                                                <col>
                                            </colgroup>
                                            <tbody>
                                            <?php if(count($data->twoMenu)): ?>
                                                <?php $__currentLoopData = $data->twoMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <input class="menu_id" type="checkbox" name="menu_id[]" data-pkey="<?php echo e($key); ?>" title="<?php echo e($val->title); ?>" lay-skin="primary" lay-filter="menuItem" value="<?php echo e($val->id); ?>" <?php if(in_array($val->id,$roleMenuIdArr)): ?> checked <?php endif; ?> <?php if($roles_id==1): ?> disabled <?php endif; ?>>
                                                        </td>
                                                        <td>
                                                            <?php if(count($val->menu_action)): ?>
                                                                <?php $__currentLoopData = $val->menu_action; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <input type="checkbox" name="menu_action_id[<?php echo e($val->id); ?>][]" data-pkey="<?php echo e($key); ?>" title="<?php echo e($v->name); ?>" lay-skin="primary" lay-filter="menuAction" value="<?php echo e($v->id); ?>" <?php if($v->menu_action_checked==1): ?> checked <?php endif; ?> <?php if($roles_id==1): ?> disabled <?php endif; ?>>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td><input class="menu_id" type="checkbox" name="menu_id[]" data-pkey="<?php echo e($key); ?>" title="<?php echo e($data->title); ?>" lay-skin="primary" lay-filter="menuItem" value="<?php echo e($data->id); ?>" <?php if(in_array($data->id,$roleMenuIdArr)): ?> checked <?php endif; ?> <?php if($roles_id==1): ?> disabled <?php endif; ?>></td>
                                                    <td>
                                                        <?php if(count($data->menu_action)): ?>
                                                            <?php $__currentLoopData = $data->menu_action; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <input type="checkbox" name="menu_action_id[<?php echo e($data->id); ?>][]" data-pkey="<?php echo e($key); ?>" title="<?php echo e($n->name); ?>" lay-skin="primary" lay-filter="menuAction" value="<?php echo e($n->id); ?>" <?php if($n->menu_action_checked==1): ?> checked <?php endif; ?> <?php if($roles_id==1): ?> disabled <?php endif; ?>>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="layui-form-btn">
                            <?php if($roles_id!=1): ?>
                                <button type="submit" class="layui-btn layui-btn-big layui-btn" lay-submit lay-filter="saveAuth"><i class="layui-icon"></i>保存</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/manage/role.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>