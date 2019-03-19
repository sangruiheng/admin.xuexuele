<?php $__env->startSection('page_title',"后台主页"); ?>
<?php $__env->startSection("content"); ?>
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                <?php echo e(adminNav($thisAction)); ?>

            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md2">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            用户量
                            
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font"><?php echo e($usercount); ?></p>
                            <p>
                                持有智慧豆
                                <span class="layuiadmin-span-color"><?php echo e($userwisdombean); ?><i class="layui-inline layui-icon layui-icon-flag"></i></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md2">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            专辑数量
                            
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font"><?php echo e($albumcount); ?></p>
                            <p>
                                课程数量
                                <span class="layuiadmin-span-color"><?php echo e($coursecount); ?><i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md2">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            发出智慧豆
                            
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height: 75px;">

                            <p class="layuiadmin-big-font"><?php echo e($sendwisdombean); ?></p>
                            
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md2">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            平台收入
                            
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height: 75px;">

                            <p class="layuiadmin-big-font"><?php echo e($orderwisdombean); ?></p>
                            
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md2">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            平台智慧豆
                            
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height: 75px;">

                            <p class="layuiadmin-big-font"><?php echo e($platformwisdombean); ?></p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="userday" value="<?php echo e($userday); ?>" id="userday">
        <input type="hidden" name="userday" value="<?php echo e($daynum); ?>" id="daynum">
        <div style="float: right;margin-right:30%">
            <a href="<?php echo e(adminurl("?usertime=1&studytime=".$studytime)); ?>" class="layui-btn layui-btn-primary">最近一个月</a>
            <a href="<?php echo e(adminurl("?usertime=2&studytime=".$studytime)); ?>" class="layui-btn layui-btn-primary">最近一周</a>
            <a href="<?php echo e(adminurl("?usertime=3&studytime=".$studytime)); ?>" class="layui-btn layui-btn-primary">最近一年</a>
        </div>
        <div id="main" style="width: 70%;height:50%;"></div>

        <input type="hidden" name="studyday" value="<?php echo e($studyday); ?>" id="studyday">
        <input type="hidden" name="daynum2" value="<?php echo e($daynum2); ?>" id="daynum2">
        <div style="float: right;margin-right:30%;margin-top: 20px;">
            <a href="<?php echo e(adminurl("?studytime=1&usertime=".$usertime)); ?>" class="layui-btn layui-btn-primary">最近一个月</a>
            <a href="<?php echo e(adminurl("?studytime=2&usertime=".$usertime)); ?>" class="layui-btn layui-btn-primary">最近一周</a>
            <a href="<?php echo e(adminurl("?studytime=3&usertime=".$usertime)); ?>" class="layui-btn layui-btn-primary">最近一年</a>
        </div>
        <div id="main2" style="width: 70%;height:50%;"></div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>
    <!-- <script src="<?php echo e(asseturl("js/index.js")); ?>"></script> -->
    <script src="<?php echo e(asseturl("js/echarts.common.min.js")); ?>"></script>
   <!--  <script src="<?php echo e(asseturl("js/Chart.js")); ?>"></script>
    <script src="<?php echo e(asseturl("js/echarts.js")); ?>"></script> -->
    <script type="text/javascript">
        var myChart = echarts.init(document.getElementById('main'));
        var myChart2 = echarts.init(document.getElementById('main2'));

        var userdaystr=document.getElementById("userday").value;  
        var daynumstr=document.getElementById("daynum").value;  
        var userday = userdaystr.split(',');
        var daynum = daynumstr.split(',');

        var studydaystr=document.getElementById("studyday").value;  
        var daynum2str=document.getElementById("daynum2").value;  
        var studyday = studydaystr.split(',');
        var daynum2 = daynum2str.split(',');

        var option = {
            xAxis: {
                name:'时间',
                type: 'category',
                data: userday
            },
            yAxis: {
                name:'用户量',
                type: 'value'
            },
            series: [{
                data: daynum,
                type: 'line'
            }]
        };

        var option2 = {
            xAxis: {
                name:'时间',
                type: 'category',
                data: studyday
            },
            yAxis: {
                name:'播放量',
                type: 'value'
            },
            series: [{
                data: daynum2,
                type: 'line'
            }]
        };
        myChart.setOption(option);
        myChart2.setOption(option2);

    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>