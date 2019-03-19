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
                            <div class="layui-input-inline" style="width: 200px;">
                                <input  type="text" class="layui-input" id="searchSelect" name="datetime" placeholder="开始时间 - 结束时间">
                            </div>
                            
                        </div>
                        <div class="layui-inline wth100">
                            <select id="is_normal" name="is_normal" class="layui-input" lay-filter="state" lay-verify="" placeholder="">
                                <option value="">全部</option>
                                <option value="1">微信</option>
                                <option value="2">支付宝</option>
                            </select>
                        </div>
                        
                        <div class="layui-inline">
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入充值用户名称">
                        </div>
                        <div class="layui-inline">
                            <input id="phone" type="text" name="phone" class="layui-input" placeholder="可输入手机号码">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i class="layui-icon">&#xe615;</i>搜索</button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                        
                    </form>
                    <div class="layui-inline" style="margin-left: 10px;">
                        <button class="layui-btn layui-btn-normal export" type="button"><i class="layui-icon">&#xe601;</i>数据导出</button>
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
                        <col width="">
                        <col width="">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>充值时间</th>
                        <th>充值方式</th>
                        <th>充值用户</th>
                        <th>手机号</th>
                        <th>充值金额</th>
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
    <script src="<?php echo e(asseturl("js/financial/recharge.js")); ?>"></script> 
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>