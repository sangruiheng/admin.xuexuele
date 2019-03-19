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
                <div style="display: flex;">
                    <form class="layui-form" action="" id="formSearch">

                        <div class="layui-inline">
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入题目名称">
                        </div>
                        
                            
                        
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                        class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" style="margin-left: 10px;">
                        <a href="<?php echo e(adminurl("/subject/addsubject/$gate_id")); ?>" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新增题目</a>
                    </div>
                    <a href="<?php echo e(adminurl("/gate")); ?>" style="margin-left: 1%" class="layui-btn layui-bg-orange"><i class="layui-icon">&#xe608;</i>返回关卡</a>
                </div>
            </blockquote>

            <input type="hidden" name="gate_id" value="<?php echo e($gate_id); ?>">
            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" >
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>题目名称</th>
                        <th>提示文案</th>
                        <th>正确答案</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="listbox"></tbody>
                </table>
            </div>
            <div id="pages"></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/subject/index.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>