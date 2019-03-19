<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit"/>
    <meta name="force-rendering" content="webkit"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <title>@yield('page_title', $title)|{{adminSetting("admin_title")}}</title>
    <link rel="stylesheet" href="{{asseturl("lib/layui/css/layui.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asseturl('css/style.css') }}">
    @yield('css')
</head>
<body>
<div class="layui-layout layui-layout-admin">
    @yield('content')
</div>
<script>
    var _token = '{{ csrf_token() }}';
    var adminurl = "{{adminurl()}}";
</script>
<script src="{{asseturl("lib/layui/layui.js")}}"></script>
<script src="{{asseturl("js/public/main.js")}}"></script>
@yield('javascript')
</body>
</html>