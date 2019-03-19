@extends('Public.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li @if(!in_array(137, $action_id))style="display: none"@endif data-method="user_info" data-id="{{$id}}" class=" handle">详细信息</li>
                <li @if(!in_array(138, $action_id))style="display: none"@endif data-method="subordinate_user" data-id="{{$id}}" class=" handle">下级用户</li>
                <li @if(!in_array(139, $action_id))style="display: none"@endif data-method="user_profit" data-id="{{$id}}" class=" handle">用户盈利</li>
            </ul>
            <div class="layui-tab-content" id="admin-action">

            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        var _token = '{{ csrf_token() }}';
        var _id = '{{$id}}';
    </script>
    <script src="{{asseturl("js/User/user-view.js")}}"></script>
@stop
