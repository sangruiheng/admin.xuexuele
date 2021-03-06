<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit"/>
    <meta name="force-rendering" content="webkit"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <title><?php echo $__env->yieldContent('page_title', $title); ?>|<?php echo e(adminSetting("admin_title")); ?></title>
    <link rel="stylesheet" href="<?php echo e(asseturl("lib/layui/css/layui.css")); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asseturl('css/style.css')); ?>">
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">
            <img src="<?php echo e(asseturl("images/logo.png")); ?>" height="40px" alt="学学乐管理后台"/>
            <i class="iconfont">&#xe622;</i>
        </div>
        <!-- 头部区域（可配合layui已有的水平导航）
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">控制台</a></li>
            <li class="layui-nav-item"><a href="">商品管理</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
        </ul>-->
        <ul class="layui-nav layui-layout-right" lay-filter="loadding">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    
                    <?php echo e(getAdminName()); ?>

                </a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo e(adminurl("/admins/profile")); ?>"><i class="iconfont">&#xe60e;</i> 基本资料</a></dd>
                    <dd><a href="<?php echo e(adminurl("/admins/repass")); ?>"><i class="iconfont">&#xe644;</i> 密码修改</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a class="logout" href="javascript:;"><i class="iconfont">&#xe600;</i> 退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <?php if(isset($thisAction)): ?>
                <?php echo e(getAdminMenuList($thisAction)); ?>

            <?php else: ?>
                <?php echo e(getAdminMenuList()); ?>

            <?php endif; ?>
        </div>
    </div>
    <?php echo $__env->yieldContent('content'); ?>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © huimor.com - 慧摩尔
    </div>
</div>
<script src="<?php echo e(asseturl("lib/layui/layui.js")); ?>"></script>
<script>
    var _token = '<?php echo e(csrf_token()); ?>';
    var adminurl = "<?php echo e(adminurl()); ?>";
    var domainurl = "<?php echo e(env('APP_URL')); ?>";
    layui.config({
        version: false //一般用于更新模块缓存，默认不开启。设为true即让浏览器不缓存。也可以设为一个固定的值，如：201610
        ,debug: false //用于开启调试模式，默认false，如果设为true，则JS模块的节点会保留在页面
        ,base: "<?php echo e(asseturl('/lib/layui/lay/exports/')); ?>/" //设定扩展的Layui模块的所在目录，一般用于外部模块扩展
    });
</script>
<script src="<?php echo e(asseturl("js/public/main.js")); ?>"></script>
<?php echo $__env->yieldContent('javascript'); ?>
</body>
<?php echo $__env->yieldContent('dialog'); ?>
</html>