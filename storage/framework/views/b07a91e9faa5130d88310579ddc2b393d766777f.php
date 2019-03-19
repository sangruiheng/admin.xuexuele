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
                <div class="layui-inline" style="margin-left: 20px;">
                        <a href="<?php echo e(adminurl("/signreward/addsignreward")); ?>" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新建奖励</a> 
                    </div>
            </blockquote>
           
            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" id="test">
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>奖励名称</th>
                        <th>每月签到次数</th>
                        <th>奖励智慧豆</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="listbox"></tbody>
                </table>
            </div>
            <!-- <div id="pages"></div> -->
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <script src="<?php echo e(asseturl("js/signreward/signreward.js")); ?>"></script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>