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
                @if(actionIsView('add_roles'))
                <div class="layui-card-header header-action-btn">
                    <button class="layui-btn handle" data-method="addRole"><i class="layui-icon">&#xe61f;</i>添加角色</button>
                </div>
                @endif
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col width="200">
                            <col>
                            <col width="200">
                            <col width="200">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>角色名称</th>
                            <th>角色描述</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roleLists as $data)
                            <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->remark}}</td>
                                <td>{{$data->create_date}}</td>
                                <td>
                                    @if(actionIsView('auth_roles'))
                                    <a href="{{adminurl("/roles/auth/".$data->id)}}" class="layui-btn layui-btn-sm layui-btn loadHref"><i class="layui-icon"></i>授权</a>
                                    @endif
                                    @if(actionIsView('edit_roles'))
                                    <button data-method="editRole" class="layui-btn layui-btn-sm layui-btn-normal handle"
                                            data-id="{{$data->id}}"
                                            data-title="{{$data->name}}"
                                            data-remark="{{$data->remark}}"><i class="layui-icon"></i>编辑</button>
                                    @endif
                                    @if($data->id!=1 && actionIsView('del_roles'))
                                        <button data-method="deleteRole" class="layui-btn layui-btn-sm layui-btn-danger handle" data-id="{{$data->id}}"><i class="layui-icon"></i>删除</button>
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
    <!----添加角色----->
    <div  class="layui-form">
        <form id="addRole" class="layui-form" style="display: none">
            <div class="layui-form-item" style="padding:0px 10px;">
                {{ csrf_field() }}
                <label class="layui-label">角色名称：</label>
                <input id="roleId" type="hidden" name="id" value="" />
                <input id="rolename" type="text" name="rolename" required  lay-verify="required" placeholder="请输入角色名称" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">角色描述：</label>
                <textarea id="remark" class="layui-textarea" name="remark" placeholder="请输入角色描述"></textarea>
            </div>
        </form>
    </div>
    <!----添加角色----->
@stop
@section("javascript")
    <script src="{{asseturl("js/manage/role.js")}}"></script>
@stop
