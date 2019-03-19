
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layui</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="{{asseturl("lib/layui/css/layui.css")}}">
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
    
    
    <input type="hidden" class="layui-input" name="type" id="type" autocomplete="off">
    <div class="layui-inline">
        <input class="layui-input" name="keywords" id="keywords" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>
 
<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> 
<script type="text/html" id="showtype">
   @{{ d.type == 1 ? '用户内容' : '平台内容' }}
</script>              
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="choosecourse">选择</a>

</script>   
<script src="{{asseturl("lib/layui/layui.js")}}"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>

var adminurl = "{{adminurl()}}";
layui.use('table', function(){
  var table = layui.table;
  var type = document.getElementById("type").value;
  //方法级渲染
  table.render({
    elem: '#LAY_table_user'
    ,url: adminurl+'/gate/getrewordlist'
    ,cols: [[
      {field:'id', title: 'ID'}
      ,{field:'heading', title: '内容名称'}
      ,{field:'create_time', title: '新建时间'}
      ,{field:'experience', title: '操作',templet:'#barDemo'}

    ]]
    ,id: 'testReload'
    ,page: false
    ,where: {
          type: type,
          
        }
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
          type: type.val(),
          
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
      parent.GetValue2(data.heading,data.id); 
      // $('#selectname').val(data.coursename);
      // $('#selectid').val(data.id);
      var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
       parent.layer.close(index); //关闭
    }
  });

  

});

function child(type){
    document.getElementById("type").value=type 
    // layui.$('#type').val(type);
    var demoReload = document.getElementById("keywords").value;
      var type = document.getElementById("type").value;
      //执行重载
      layui.table.reload('testReload', {
        where: {
          keywords: demoReload,
          type: type,
          
        }
      });
}
</script>

</body>
</html>