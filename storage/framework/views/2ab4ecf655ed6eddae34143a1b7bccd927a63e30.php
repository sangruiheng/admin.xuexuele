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
                <?php if(actionIsView('add_menus') || actionIsView('sort_menus')): ?>
                <div class="layui-card-header header-action-btn">
                    <div class="layui-inline">
                        <?php if(actionIsView('add_menus')): ?>
                        <button class="layui-btn handle" data-method="addMenu"><i class="layui-icon">&#xe61f;</i>添加菜单</button>
                        <?php endif; ?>
                        <?php if(actionIsView('sort_menus')): ?>
                        <button class="layui-btn handle" data-method="updateSort"><i class="layui-icon iconfont">&#xe6a8;</i>更新排序</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="layui-card-body">
                    <form id="dataForm">
                        <?php echo e(csrf_field()); ?>

                        <table class="layui-table">
                            <colgroup>
                                <col>
                                <col width="200">
                                <col width="150">
                                <col width="100">
                                <col width="230">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>菜单名称</th>
                                <th>创建时间</th>
                                <th>是否显示</th>
                                <th>序号</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $menuLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data->title); ?></td>
                                    <td><?php echo e($data->create_date); ?></td>
                                    <td>
                                        <div class="layui-form resetcss">
                                            <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" lay-filter="status" data-id="<?php echo e($data->id); ?>" <?php if($data->is_show): ?> checked <?php endif; ?> <?php if(!actionIsView('sort_menus')): ?> disabled <?php endif; ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="sort[<?php echo e($data->id); ?>]" placeholder="序号" autocomplete="off" class="layui-input" value="<?php echo e($data->sort); ?>" <?php if(!actionIsView('sort_menus')): ?> disabled <?php endif; ?>>
                                    </td>
                                    <td>
                                        <?php if(!count($data->child) && actionIsView('action_menus')): ?>
                                            <a data-method="actionMenu" class="layui-btn layui-btn-sm handle" data-id="<?php echo e($data->id); ?>" data-title="<?php echo e($data->title); ?>"><i class="layui-icon"></i>操作管理</a>
                                        <?php endif; ?>
                                        <?php if(actionIsView('edit_menus')): ?>
                                        <a data-method="editMenu" class="layui-btn layui-btn-sm layui-btn-normal handle"
                                           data-id="<?php echo e($data->id); ?>"
                                           data-title="<?php echo e($data->title); ?>"
                                           data-parent_id="<?php echo e($data->parent_id); ?>"
                                           data-url="<?php echo e($data->url); ?>"
                                           data-icon_class="<?php echo e($data->icon_class); ?>"><i class="layui-icon"></i>编辑</a>
                                        <?php endif; ?>
                                        <?php if(actionIsView('del_menus')): ?>
                                        <a data-method="deleteMenu" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="<?php echo e($data->id); ?>"><i class="layui-icon"></i>删除</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if(count($data->child)): ?>
                                    <?php $__currentLoopData = $data->child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>　　｜－<?php echo e($childData->title); ?></td>
                                            <td><?php echo e($childData->create_date); ?></td>
                                            <td>
                                                <div class="layui-form resetcss">
                                                    <input type="checkbox" name="is_show" lay-skin="switch" lay-filter="status" lay-text="是|否" data-id="<?php echo e($childData->id); ?>" <?php if($childData->is_show): ?> checked <?php endif; ?> <?php if(!actionIsView('sort_menus')): ?> disabled <?php endif; ?>>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="sort[<?php echo e($childData->id); ?>]" placeholder="序号" autocomplete="off" class="layui-input" value="<?php echo e($childData->sort); ?>" <?php if(!actionIsView('sort_menus')): ?> disabled <?php endif; ?>>
                                            </td>
                                            <td>
                                                <?php if(actionIsView('action_menus')): ?>
                                                <a data-method="actionMenu" class="layui-btn layui-btn-sm handle" data-id="<?php echo e($childData->id); ?>" data-title="<?php echo e($childData->title); ?>"><i class="layui-icon"></i>操作管理</a>
                                                <?php endif; ?>
                                                <?php if(actionIsView('edit_menus')): ?>
                                                <a data-method="editMenu" class="layui-btn layui-btn-sm layui-btn-normal handle"
                                                   data-id="<?php echo e($childData->id); ?>"
                                                   data-title="<?php echo e($childData->title); ?>"
                                                   data-parent_id="<?php echo e($childData->parent_id); ?>"
                                                   data-url="<?php echo e($childData->url); ?>"
                                                   data-icon_class="<?php echo e($childData->icon_class); ?>"><i class="layui-icon"></i>编辑</a>
                                                <?php endif; ?>
                                                <?php if(actionIsView('del_menus')): ?>
                                                <a data-method="deleteMenu" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="<?php echo e($childData->id); ?>"><i class="layui-icon"></i>删除</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </form>
                    <div id="pages"></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('dialog'); ?>
    <!----添加菜单----->
    <div  class="layui-form">
        <form id="addMenu" class="layui-form" style="display: none">
            <div class="layui-form-item" style="padding:0px 10px;">
                <?php echo e(csrf_field()); ?>

                <label class="layui-label">菜单名称：</label>
                <input id="menuId" type="hidden" name="id" value="" />
                <input id="menuname" type="text" name="title" required  lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">父亲级菜单(默认为顶级菜单)：</label>
                <div class="layui-form-item">
                    <select id="parent" name="parent_id" lay-filter="parent">
                        <option value="0">顶级菜单</option>
                        <?php $__currentLoopData = $firstMenuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($data->id); ?>"><?php echo e($data->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <label class="layui-label">菜单URL：</label>
                <input id="memuurl" type="text" name="url" required  lay-verify="required" placeholder="login" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">菜单图标：<a href="<?php echo e(url("/resource/font/demo_unicode.html")); ?>" target="_blank">查看图标</a> </label>
                <input id="menuicon" type="text" name="icon_class" required  lay-verify="required" placeholder="请输入菜单图标" autocomplete="off" class="layui-input layui-form-danger">
            </div>
        </form>
    </div>
    <!----添加菜单----->
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
<script src="<?php echo e(asseturl("js/menu/menu.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>