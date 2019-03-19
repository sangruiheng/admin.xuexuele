@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
                
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{$title}}</div>
                @if(actionIsView('add_admins'))
                <div class="layui-card-header header-action-btn">
                    <a href="{{adminurl("/admins/add")}}" class="layui-btn loadHref"><i class="layui-icon">&#xe61f;</i>添加管理员</a>
                </div>
                @endif
                <div class="layui-card-body">
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
                            <th>管理员名称</th>
                            <th>管理员账号</th>
                            <th>管理员角色</th>
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
                                   
                                        <div class="layui-form resetcss" data-method="showAdmin">
                                            <input type="checkbox" name="is_show" lay-skin="switch" lay-filter="status" lay-text="是|否" data-id="{{$data->admin_id}}" checked  disabled>
                                        </div>
                                   
                                </td>
                                <td>
                                    @if(actionIsView('edit_admins'))
                                    <a href="{{adminurl("/admins/edit/".$data->admin_id)}}" class="layui-btn layui-btn-sm layui-btn-normal loadHref"><i class="layui-icon"></i>编辑</a>
                                    @endif
                                    @if($data->admin_id!=1 && actionIsView('del_admins'))
                                        <button data-method="deleteAdmin" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="{{$data->admin_id}}"><i class="layui-icon"></i>删除</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/manage/admin.js")}}"></script>
@stop
