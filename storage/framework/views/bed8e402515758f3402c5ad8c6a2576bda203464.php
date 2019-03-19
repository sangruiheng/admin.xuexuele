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
<body>
<div class="layui-layout layui-layout-admin">
    <?php echo $__env->yieldContent('content'); ?>
</div>
<script>
    var _token = '<?php echo e(csrf_token()); ?>';
    var adminurl = "<?php echo e(adminurl()); ?>";
</script>
<script src="<?php echo e(asseturl("lib/layui/layui.js")); ?>"></script>
<script src="<?php echo e(asseturl("js/public/main.js")); ?>"></script>
<?php echo $__env->yieldContent('javascript'); ?>
</body>
</html>