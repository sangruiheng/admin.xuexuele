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
                <a href="{{adminurl("/admins/add")}}" class="layui-btn loadHref"><i class="layui-icon">&#xe61f;</i>添加用户</a>
            </blockquote>
            <table class="layui-table">
                <colgroup>
                    <col width="200">
                    <col width="200">
                    <col width="150">
                    <col >
                    <col >
                    <col width="150">
                    <col width="150">
                </colgroup>
                <thead>
                <tr>
                    <th>用户名称</th>
                    <th>用户账号</th>
                    <th>用户角色</th>
                    <th>添加时间</th>
                    <th>修改时间</th>
                    <th>是否启用</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($adminLists as $data)
                    <tr>
                        <td>{{$data->realname}}</td>
                        <td>{{$data->username}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->admin_create_date}}</td>
                        <td>{{$data->admin_update_date}}</td>
                        <td>
                            <div class="layui-form resetcss admin-action" data-method="showAdmin">
                                <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" data-id="{{$data->admin_id}}" @if($data->state==1) checked @endif>
                            </div>
                        </td>
                        <td>
                            <a href="{{adminurl("/admins/edit/".$data->admin_id)}}" class="layui-btn layui-btn-small layui-btn-normal loadHref"><i class="layui-icon"></i>编辑</a>
                            @if($data->admin_id!=1)
                            <button data-method="deleteAdmin" class="layui-btn layui-btn-small layui-btn-danger admin-action" data-id="{{$data->admin_id}}"><i class="layui-icon"></i>删除</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/Admin/admin.js")}}"></script>
@stop
