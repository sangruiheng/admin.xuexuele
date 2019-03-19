<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('page_title', $title)|{{adminSetting("admin_title")}}</title>
    <link rel="stylesheet" href="{{url("layui/css/layui.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asseturl('css/style.css') }}">
    @yield('css')
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">
            <img src="{{asseturl("images/logo.png")}}" height="40px" alt="易创管理后台"/></div>
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
                    <img src="{{getAdminAvator()}}" class="layui-nav-img">
                    {{getAdminName()}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="{{adminurl("/profile")}}"><i class="iconfont">&#xe60e;</i> 基本资料</a></dd>
                    <dd><a href="{{adminurl("/repass")}}"><i class="iconfont">&#xe644;</i> 密码修改</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a class="home" href="{{adminSetting("web_url")}}" target="_blank"><i class="layui-icon">&#xe68e;</i> 前台首页</a></li>
            <li class="layui-nav-item"><a class="logout" href="javascript:;"><i class="iconfont">&#xe600;</i> 退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            @if(isset($thisAction))
                {{getAdminMenuList($thisAction)}}
                @else
                {{getAdminMenuList()}}
            @endif
        </div>
    </div>
    @yield('content')

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © huimor.com - 慧摩尔
    </div>
</div>
<script>
    var _token = '{{ csrf_token() }}';
    var adminurl = "{{adminurl()}}";
</script>
<script src="{{url("layui/layui.js")}}"></script>
<script src="{{asseturl("js/Public/main.js")}}"></script>
@yield('javascript')
</body>
</html>