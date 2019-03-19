<?php $__env->startSection("content"); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

                <a class="go-back" href="<?php echo e(URL::asset('/admin/certification')); ?>"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">

            <blockquote class="site-text layui-elem-quote">
                基本信息
            </blockquote>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户分类：</label>
                        <div class="layui-input-block">
                            <?php if($result->identity == 1): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="导师"   disabled>
                            <?php else: ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="侠客"   disabled>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户昵称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->nickname); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">联系电话：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->phone); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>实名信息</b></label>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">真实姓名：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->name); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">身份证号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->identitycard); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">手持身份证照片：</label>
                        <div class="layui-input-inline">

                            <p><img src="<?php echo e(URL::asset($result->identityimg)); ?>" style="width:180px;height:180px;" id="iconsrc"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学校名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->schoolname); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学历：</label>
                        <div class="layui-input-block">
                            <?php if($result->education == 1): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="小学" id="user_phone_up" disabled>
                            <?php elseif($result->education == 2): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="初中" id="user_phone_up" disabled>
                            <?php elseif($result->education == 3): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="高中" id="user_phone_up" disabled>
                             <?php elseif($result->education == 4): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="大专" id="user_phone_up" disabled>
                             <?php elseif($result->education == 5): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="本科" id="user_phone_up" disabled>
                             <?php elseif($result->education == 6): ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="研究生" id="user_phone_up" disabled>
                            <?php else: ?>
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="博士" id="user_phone_up" disabled>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">专业：</label>
                        <div class="layui-input-block">

                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="<?php echo e($result->profession); ?>" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>认证审核</b></label>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证状态：</label>
                        <div class="layui-input-block">


                            <?php if($result->certificationstate == 1): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="未认证"   disabled>
                            <?php elseif($result->certificationstate == 2): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="已认证"   disabled>
                            <?php elseif($result->certificationstate == 3): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="审核中"   disabled>
                            <?php elseif($result->certificationstate == 4): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="驳回"   disabled>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证审核：</label>
                        <div class="layui-input-block">
                            <?php if($result->certificationstate == 3): ?>
                                <a data-method="show1" class="layui-btn layui-btn-small layui-btn-blue handle" data-id="<?php echo e($result->id); ?>" data-state="2">通过</a>
                                <a data-method="show2" class="layui-btn layui-btn-small layui-btn-blue handle" data-id="<?php echo e($result->id); ?>" data-state="4">驳回</a>
                            <?php elseif($result->certificationstate == 1): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="未认证"   disabled>
                            <?php elseif($result->certificationstate == 2): ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="已认证"   disabled>
                            <?php else: ?>
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="驳回"   disabled>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/certification/detail.js")); ?>">
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>