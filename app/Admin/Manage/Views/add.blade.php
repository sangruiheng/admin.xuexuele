@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">
                    @if(!$thisAction)
                        管理员资料
                    @elseif($formAction=="addForm")
                        添加管理员
                    @elseif($formAction=="editForm")
                        编辑管理员
                    @endif
                </div>
                <div class="layui-card-body">
                    <form class="layui-form" action="">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="@if(isset($adminInfo->id)) {{$adminInfo->id}} @endif" />
                        <div class="layui-col-md6 layui-col-space5">
                            <div class="layui-form-item">
                                <label class="layui-form-label">管理员名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="realname" required  lay-verify="required" placeholder="请输入用户名称" autocomplete="off" class="layui-input" value="@if(isset($adminInfo->realname)) {{$adminInfo->realname}} @endif">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">管理员账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" required  lay-verify="required" placeholder="请输入用户账号" autocomplete="off" class="layui-input" value="@if(isset($adminInfo->username)) {{$adminInfo->username}} @endif">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">密码框</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password" required @if($formAction!="editForm")lay-verify="required" @endif placeholder="请输入密码" autocomplete="off" class="layui-input" value="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属角色</label>
                                <div class="layui-input-block">
                                    <select name="role_id" lay-verify="required" @if(isset($adminInfo->id) && $adminInfo->id==1) disabled @endif>
                                        <option value="">请选择角色</option>
                                        @foreach($roleLists as $data)
                                            <option value="{{$data->id}}" @if(isset($adminInfo->role_id) && $adminInfo->role_id==$data->id) selected @endif>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md6">
                            <div class="layui-form-item up-avator-box">
                                <div class="layui-upload">
                                    <div class="layui-upload-list up-avator-show">
                                        <input type="hidden" id="putavator" name="avator" value="@if(isset($adminInfo->avator)){{$adminInfo->avator}}@else{{url("images/default.png")}}@endif">
                                        <img id="avator" class="layui-upload-img" src="@if(isset($adminInfo->avator)) {{url("storage/".$adminInfo->avator)}}@else {{url("images/default.png")}} @endif">
                                        <p id="demoText"></p>
                                    </div>
                                    <input id="avatorUpload" type="file" name="imgpath" onchange="uploadImage(this,'avator')" value="" style="display: none">
                                    <label for="avatorUpload"  type="button" class="layui-btn" id="avator">上传头像</label>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                <input type="radio" name="sex" value="1" title="男" @if(isset($adminInfo->sex) && $adminInfo->sex==1) checked @endif>
                                <input type="radio" name="sex" value="2" title="女" @if(isset($adminInfo->sex) && $adminInfo->sex==2) checked @endif>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否启用</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="status" lay-skin="switch" lay-text="是|否" @if(isset($adminInfo->status) && $adminInfo->status==1) checked @endif value="1">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea name="remark" placeholder="请输入备注内容" class="layui-textarea">@if(isset($adminInfo->remark)) {{$adminInfo->remark}} @endif</textarea>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="{{$formAction}}">立即保存</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/manage/admin.js")}}"></script>
@stop
