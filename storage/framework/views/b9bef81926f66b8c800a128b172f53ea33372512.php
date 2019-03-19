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
                <div class="layui-card-header">
                    <?php if(!$thisAction): ?>
                        管理员资料
                    <?php elseif($formAction=="addForm"): ?>
                        添加管理员
                    <?php elseif($formAction=="editForm"): ?>
                        编辑管理员
                    <?php endif; ?>
                </div>
                <div class="layui-card-body">
                    <form class="layui-form" action="">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="id" value="<?php if(isset($adminInfo->id)): ?> <?php echo e($adminInfo->id); ?> <?php endif; ?>" />
                        <div class="layui-col-md6 layui-col-space5">
                            <div class="layui-form-item">
                                <label class="layui-form-label">管理员名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="realname" required  lay-verify="required" placeholder="请输入用户名称" autocomplete="off" class="layui-input" value="<?php if(isset($adminInfo->realname)): ?> <?php echo e($adminInfo->realname); ?> <?php endif; ?>">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">管理员账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" required  lay-verify="required" placeholder="请输入用户账号" autocomplete="off" class="layui-input" value="<?php if(isset($adminInfo->username)): ?> <?php echo e($adminInfo->username); ?> <?php endif; ?>">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">密码框</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password" required <?php if($formAction!="editForm"): ?>lay-verify="required" <?php endif; ?> placeholder="请输入密码" autocomplete="off" class="layui-input" value="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属角色</label>
                                <div class="layui-input-block">
                                    <select name="role_id" lay-verify="required" <?php if(isset($adminInfo->id) && $adminInfo->id==1): ?> disabled <?php endif; ?>>
                                        <option value="">请选择角色</option>
                                        <?php $__currentLoopData = $roleLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($data->id); ?>" <?php if(isset($adminInfo->role_id) && $adminInfo->role_id==$data->id): ?> selected <?php endif; ?>><?php echo e($data->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md6">
                            <div class="layui-form-item up-avator-box">
                                <div class="layui-upload">
                                    <div class="layui-upload-list up-avator-show">
                                        <input type="hidden" id="putavator" name="avator" value="<?php if(isset($adminInfo->avator)): ?><?php echo e($adminInfo->avator); ?><?php else: ?><?php echo e(url("images/default.png")); ?><?php endif; ?>">
                                        <img id="avator" class="layui-upload-img" src="<?php if(isset($adminInfo->avator)): ?> <?php echo e(url("storage/".$adminInfo->avator)); ?><?php else: ?> <?php echo e(url("images/default.png")); ?> <?php endif; ?>">
                                        <p id="demoText"></p>
                                    </div>
                                    <input id="avatorUpload" type="file" name="imgpath" onchange="uploadImage(this,'avator')" value="" style="display: none">
                                    <label for="avatorUpload"  type="button" class="layui-btn" id="avator">上传头像</label>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                <input type="radio" name="sex" value="1" title="男" <?php if(isset($adminInfo->sex) && $adminInfo->sex==1): ?> checked <?php endif; ?>>
                                <input type="radio" name="sex" value="2" title="女" <?php if(isset($adminInfo->sex) && $adminInfo->sex==2): ?> checked <?php endif; ?>>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否启用</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="status" lay-skin="switch" lay-text="是|否" <?php if(isset($adminInfo->status) && $adminInfo->status==1): ?> checked <?php endif; ?> value="1">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea name="remark" placeholder="请输入备注内容" class="layui-textarea"><?php if(isset($adminInfo->remark)): ?> <?php echo e($adminInfo->remark); ?> <?php endif; ?></textarea>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="<?php echo e($formAction); ?>">立即保存</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/manage/admin.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>