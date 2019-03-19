
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layui</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?php echo e(asseturl("lib/layui/css/layui.css")); ?>">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<style type="text/css">
    body{
        padding: 10px 10px 10px 10px;
    }
    .selecttype{
        height: 38px;
    }
</style>
<body> 
 
<div class="demoTable">
    
    内容分类：
    <div class="layui-inline">
      <select name="type" lay-filter="type" class="selecttype" id="type">
        <option value="">全部</option>
        <option value="2">平台内容</option>
        
        <option value="1">用户内容</option>
      </select>
    </div>
    
    <div class="layui-inline">
        <input class="layui-input" name="keywords" id="keywords" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>
 
<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> 
<script type="text/html" id="showtype">
   {{ d.type == 1 ? '用户内容' : '平台内容' }}
</script>              
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="choosecourse">选择</a>

</script>   
<script src="<?php echo e(asseturl("lib/layui/layui.js")); ?>"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
var adminurl = "<?php echo e(adminurl()); ?>";
layui.use('table', function(){
  var table = layui.table;
  
  //方法级渲染
  table.render({
    elem: '#LAY_table_user'
    ,url: adminurl+'/gate/getcourselist'
    ,cols: [[
      {field:'id', title: 'ID'}
      ,{field:'coursename', title: '课程名称'}
      ,{field:'albumname', title: '所属专辑'}
      ,{field:'type', title: '内容分类' ,templet: '#showtype'}
      ,{field:'create_time', title: '新建时间'}
      ,{field:'experience', title: '操作',templet:'#barDemo'}

    ]]
    ,id: 'testReload'
    ,page: false
    // ,height: 315
  });
  
  var $ = layui.$, active = {
    reload: function(){
      var demoReload = $('#keywords');
      var type = $('#type');
      //执行重载
      table.reload('testReload', {
        where: {
          keywords: demoReload.val(),
          type: type.val()
        }
      });
    }
  };
  
  $('.demoTable .layui-btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

  //监听工具条
  table.on('tool(user)', function(obj){
    var data = obj.data;
    if(obj.event === 'choosecourse'){
      parent.GetValue(data.coursename,data.id); 
      // $('#selectname').val(data.coursename);
      // $('#selectid').val(data.id);
      var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
       parent.layer.close(index); //关闭
    }
  });
});
</script>

</body>
</html>