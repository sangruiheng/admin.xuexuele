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
                <form class="layui-form" action="">
                    <div class="layui-inline">
                        <div class="layui-input-inline" style="width: 200px;">
                                <input  type="text" class="layui-input" id="searchSelect" name="datetime" placeholder="请选择创建时间">
                            </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                    class="layui-icon">&#xe615;</i>搜索
                        </button>
                        <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                        </button>
                    </div>
                </form>
            </blockquote>
            <div class="layui-form">
                <div style="display: flex;justify-content: flex-start;margin-bottom: 20px;">
                    <div style="background-color: #f8f8f9;margin-left: 30px;padding: 40px;" >
                        <p>课程查看量：<span><?php echo e($data->studycount); ?></span></p>
                    </div>
                    <div style="background-color: #f8f8f9;margin-left: 30px;padding: 40px;">
                        <p>智慧豆：<span><?php echo e($data->wisdombeancount); ?></span></p>
                    </div>
                    <div style="background-color: #f8f8f9;margin-left: 30px;padding: 40px;">
                        <p>智慧豆打赏：<span><?php echo e($data->rewardplatformcount); ?></span></p>
                    </div>
                </div>
            </div>
            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" id="test">
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
                        <th>课程名称</th>
                        <th>所属专辑</th>
                        <th>所属导师</th>
                        <th lay-data="{sort: true}">学习量</th>
                        <th>销售智慧豆</th>
                        <th>打赏智慧豆</th>
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
    <script src="<?php echo e(asseturl("js/subscribe/subscribe.js")); ?>"></script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>