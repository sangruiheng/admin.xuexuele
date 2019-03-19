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
                <form class="layui-form" action="" id="formSearch">
                    <div class="layui-inline wth100">
                        <select id="is_normal" name="is_normal" class="layui-input" lay-filter="state" lay-verify="" placeholder="">
                            <option value="">全部</option>
                            <option value="1">待审核</option>
                            <option value="2">举报成功</option>

                        </select>
                    </div>
                    
                    <div class="layui-inline">
                        <input id="coursename" type="text" name="coursename" class="layui-input" placeholder="可输入课程名称">
                    </div>
                    <div class="layui-inline">
                        <input id="albumname" type="text" name="albumname" class="layui-input" placeholder="可输入专辑名称">
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

            <div class="layui-form news_list">
                <table class="layui-table" lay-filter="test" >
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>课程名称</th>
                        <th>所属专辑</th>
                        <th>所属用户</th>
                        <th>举报时间</th>
                        <th>举报类型</th>
                        <th>举报人</th>
                        <th>状态</th>
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
     <script src="<?php echo e(asseturl("js/reporting/index.js")); ?>"></script> 
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>