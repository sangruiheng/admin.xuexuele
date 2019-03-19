<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登录界面</title>
    <meta name="renderer" content="webkit"/>
    <meta name="force-rendering" content="webkit"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{asseturl("css/style.css")}}" type="text/css" rel="stylesheet">
    <style>
        body{color:#fff; font-family:"微软雅黑"; font-size:14px;background: url({{asseturl("images/bg3.jpg")}}) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;}
        .wrap1{position:absolute; top:0; right:0; bottom:0; left:0; margin:auto }/*把整个屏幕真正撑开--而且能自己实现居中*/
        .main_content{background-color:rgba(255,255,255,.8); margin-left:auto; margin-right:auto; text-align:left; float:none; border-radius:8px;}
        .form-group{position:relative;}
        .login_btn{display:block; background:#687aa2; color:#fff; font-size:15px; width:100%; line-height:50px; border-radius:3px; border:none; }
        .login_input{width:100%; border:1px solid #687aa2; border-radius:5px; line-height:40px; padding:2px 5px 2px 30px; background:none;color:#999;}
        .icon_font{position:absolute; bottom:15px; left:10px; font-size:18px; color:#3872f6;}
        .font16{font-size:16px;}
        .mg-t20{margin-top:20px;}
        .mg-t20 i{position: absolute;  top: 10px;  left: 6px;color:#687aa2;font-size:20px;}
        @media (min-width:200px){.pd-xs-20{padding:20px;}}
        @media (min-width:768px){.pd-sm-50{padding:50px;}}
        #grad {
            background: -webkit-linear-gradient(#4990c1, #52a3d2, #6186a3); /* Safari 5.1 - 6.0 */
            background: -o-linear-gradient(#4990c1, #52a3d2, #6186a3); /* Opera 11.1 - 12.0 */
            background: -moz-linear-gradient(#4990c1, #52a3d2, #6186a3); /* Firefox 3.6 - 15 */
            background: linear-gradient(#4990c1, #52a3d2, #6186a3); /* 标准的语法 */
        }
    </style>
</head>

<body>

<div class="container wrap1" style="height:450px;">
    <h2 class="mg-b20 text-center">后台管理系统</h2>
    <div class="col-sm-8 col-md-5 center-auto pd-sm-50 pd-xs-20 main_content">
        <form class="layui-form" lay-filter="login">
            <div class="form-group mg-t20">
                <i class="iconfont">&#xe620;</i>
                <input type="text" class="login_input" id="username" placeholder="请输入用户名" />
            </div>
            <div class="form-group mg-t20">
                <i class="iconfont">&#xe644;</i>
                <input type="password" class="login_input" id="password" placeholder="请输入密码" />
            </div>
            <!--
            <div class="checkbox mg-b25">
                <label>
                    <input type="checkbox" />记住我的登录信息
                </label>
            </div>
            -->
            <button type="submit" class="login_btn">登 录</button>
        </form>
    </div><!--row end-->
</div><!--container end-->
<script>
    var _token = '{{ csrf_token() }}';
    var adminurl = "{{adminurl()}}";
</script>
<script src="{{asseturl("lib/layui/layui.js")}}"></script>
<script src="{{asseturl("js/manage/login.js")}}"></script>
</body>
</html>
