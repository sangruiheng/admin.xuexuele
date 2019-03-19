<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{$url}}/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="{{$url}}/static/themev1.0/css/style.css">
</head>


<div id="action1" class="layui-row layui-form" style="padding:10px;">
    <blockquote class="site-text layui-elem-quote">
        <form class="layui-form" action="">
            <div class="layui-inline wth100">
                <select name="type" class="layui-input" lay-filter="type" lay-verify="" placeholder="">
                    <option value="">属性</option>
                    <option value="1" @if(isset($param['project_state']) && $param['project_state']==1) selected @endif>用户</option>
                    <option value="2" @if(isset($param['project_state']) && $param['project_state']==2) selected @endif>商户</option>
                </select>
            </div>
            <div class="layui-inline">
                <input type="text" name="datetime" class="layui-input" id="dateSelect" placeholder="添加时间" @if(isset($param['datetime'])) value="{{$param['datetime']}}" @endif>
            </div>
            <div class="layui-inline">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i class="layui-icon">&#xe615;</i>搜索</button>
                <button type="reset" class="layui-btn layui-btn-warm reset"><i class="layui-icon">&#x1006;</i>重置</button>
            </div>
        </form>
    </blockquote>
    <table class="layui-table">
        <colgroup>
            <col >
            <col >
            <col >
            <col >
            <col >
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>会员名称</th>
            <th>电话</th>
            <th>属性</th>
            <th>加入时间</th>
        </tr>
        </thead>
        <tbody id="agentbox">

        </tbody>
    </table>
</div>
<div id="pages">

</div>
<script>
    var _id = '{{$id}}';
    var url = '{{$url}}';
</script>
<script src="{{url("layui/layui.js")}}"></script>
<script src="{{asseturl("js/user/user-view-userlists.js")}}"></script>

