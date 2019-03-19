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
                    <form class="layui-form" action="">
                        <div class="layui-inline">
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入广告标题">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" style="margin-left: 20px;">
                        <a href="<?php echo e(adminurl("/advertisement/advertisementaddview")); ?>" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>发布广告</a> 
                    </div>
                </div>
            </blockquote>

            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" >
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>发布时间</th>
                        <th>广告标题</th>
                        <th>广告封面</th>
                        <th>广告出现位置</th>
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
    <script src="<?php echo e(asseturl("js/advertisingcenter/advertisement.js")); ?>"></script> 
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>