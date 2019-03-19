<?php $__env->startSection("content"); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<style type="text/css">
   .chooseline{
        display: flex;

   }
   .chooseline2{
        display: flex;
   }
   .activeupload{
        background-color: #CCCCCC;
   }
   .showchoose{
        height: 40px;
        width: 40px;
   }
   .layui-row{background-color: #fff;padding-left: 10px;}
</style>

<div class="layui-body">
    <div class=" layui-tab-brief">
        <div class="layui-breadcrumb-box">
            <?php echo e(adminNav($thisAction)); ?>

            <a class="go-back" href="<?php echo e(adminurl("/gate")); ?>"><i class="layui-icon">&#xe65c;</i> 返回</a>
        </div>
    </div>
    <!-- 内容主体区域 -->


    <form class="layui-form" action=""  method="post" id="addGate">

        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                基本信息
            </blockquote>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 140px;">关卡名称：第<?php echo e($newname); ?>关</label>

                    </div>
                </div>
            </div>
        </div>






    
            
                
                    
                    
                
                
                
                    
                        
                            
                            
                                
                                
                                
                                
                                
                                
                                

                            
                            
                                
                                
                                
                                
                                
                                
                                

                            
                            
                                
                                
                                
                                
                                
                                
                                

                            

                        
                        
                        
                        
                    
                

                
                
                    
                        
                            
                            
                                
                                    
                                    

                                
                                
                                    
                                    

                                
                                

                                
                            

                        
                    
                

                
                    
                


                
                    
                    
                    
                    
                        
                            
                            
                                
                                

                                
                                    
                                    
                                        
                                        
                                    
                                

                                
                                    
                                    
                                        
                                            
                                            

                                        

                                        
                                    
                                
                            
                        
                    
                
                
                    
                        
                            
                            
                                
                            
                        
                    
                


                
                    
                    
                        
                    
                

            

    












        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                答案提示
            </blockquote>
            <input  type="hidden"  class="layui-input" name="courserid" id="selectid" autocomplete="off">

            <div class="layui-col-md12">

                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label" >推荐内容：</label>
                        <div class="layui-input-inline" >
                            <input  class="layui-input" name="selectname" id="selectname" autocomplete="off" readonly="readonly">

                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline" >

                            <button class="layui-btn layui-btn-normal selectcourse" type="button">请选择</button>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" >智慧豆：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                             <input  class="layui-input" name="answerwisdombeanuse" id="answerwisdombeanuse" autocomplete="off" lay-verify="required">
                        </div>
                        <div class="layui-form-mid ">个</div>
                    </div>

                </div>



            </div>
        </div>

        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                关卡弹窗
            </blockquote>
            

            <div class="layui-col-md12">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">成功弹窗：</label>
                                <div class="layui-input-inline">
                                    <select name="alert_id" id="alert_id" lay-filter="teshu" lay-verify="required">
                                        <option value="">请选择</option>
                                        <?php $__currentLoopData = $alertList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"><?php echo e($value->title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">失败弹窗：</label>
                                <div class="layui-input-inline">
                                    <select name="alert_errid" id="alert_errid" lay-filter="teshu" lay-verify="required">
                                        <option value="">请选择</option>
                                        <?php $__currentLoopData = $alertList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value->id); ?>"><?php echo e($value->title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                奖励信息
            </blockquote>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">智慧豆：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                            <input type="text" name="rewardbeans" id="rewardbeans"  lay-verify="required" autocomplete="off" class="layui-input"  >
                        </div>
                        <div class="layui-form-mid ">个 每关卡最多奖励（9999个智慧豆）</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">PK值：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                            <input type="text" name="pk"  id="pk" lay-verify="required" autocomplete="off" class="layui-input"  >
                        </div>
                        <div class="layui-form-mid ">分</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                          <label class="layui-form-label">特殊奖励：</label>
                          <div class="layui-input-inline">
                            <select name="teshu" id="teshu" lay-filter="teshu">
                              <option value="0">无奖励</option>
                              <option value="1">奖励文章</option>
                              <option value="2">奖励音频</option>

                            </select>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <input  type="hidden" class="layui-input" name="gaterewordid" id="gaterewordid" autocomplete="off" >

            <div class="layui-form-item showreword" style="display: none;">
                <div class="layui-inline">
                    <label class="layui-form-label" ></label>
                    <div class="layui-input-inline" >
                        <input  class="layui-input selectcontent" name="selectcontent" id="selectcontent" autocomplete="off" readonly="readonly" placeholder="">

                    </div>
                </div>
                <div class="layui-inline" >
                    <div class="layui-input-inline" >

                        <button class="layui-btn layui-btn-normal selectreword" type="button">请选择</button>
                    </div>
                </div>

            </div>
            <div class="layui-inline" style="padding-left: 20%;">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addGate">确定</button>
                <a  href="<?php echo e(adminurl("/gate")); ?>"><button type="button" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">
                取消</button></a>
            </div>

        </div>

    </form>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection("javascript"); ?>


    <script src="<?php echo e(asseturl("js/gate/add.js")); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(config('view.app.admin').'.Common.Views.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>