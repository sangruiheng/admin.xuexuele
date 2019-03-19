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
                    <div class="layui-inline wth100">
                        <select id="is_normal" name="is_normal" class="layui-input" lay-filter="state" lay-verify="" placeholder="">
                            <option value="">审核状态</option>
                            <!-- <option value="1">未认证</option> -->
                            <option value="2">已认证</option>
                            <option value="3">审核中</option>
                            <option value="4">驳回</option>
                        </select>
                    </div>

                    <div class="layui-inline">
                        <input id="uid" type="text" name="uid" class="layui-input" placeholder="可输入用户ID">
                    </div>
                    <div class="layui-inline">
                        <input id="name" type="text" name="name" class="layui-input" placeholder="可输入名称">
                    </div>
                    <div class="layui-inline">
                        <input id="phone" type="text" name="phone" class="layui-input" placeholder="联系电话">
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
                        <col width="90">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户昵称</th>
                        <th>联系电话</th>
                        <th>申请日期</th>
                        <th>审核状态</th>
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
    <script src="<?php echo e(asseturl("js/certification/index.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>