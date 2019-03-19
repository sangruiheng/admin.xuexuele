@extends('Public.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="admin-action" class="layui-row" style="padding:10px;">
            <blockquote class="site-text layui-elem-quote">
                <form class="layui-form" action="">
                    <div class="layui-inline">
                        <input type="text" name="keyword" class="layui-input" placeholder="用户名称 / ID / 电话"  style="width:200px" @if(isset($param['keyword'])) value="{{$param['keyword']}}" @endif>
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
                    <col >
                    <col >
                    <col >
                </colgroup>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>推荐人</th>
                    <th>电话</th>
                    <th>推荐总数</th>
                    <th>购买总数</th>
                    <th>下级会员</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="agentbox">

                </tbody>
            </table>
        </div>
        <div id="pages">

        </div>
    </div>
@stop
@section("javascript")
    <script>
        var _token = '{{ csrf_token() }}';
    </script>
    <script src="{{asseturl("js/User/user.js")}}"></script>
@stop
