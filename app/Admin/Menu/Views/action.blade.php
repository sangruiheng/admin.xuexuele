@extends(config('view.app.admin').'.Common.Views.mainview')
@section("content")
    <div class="layui-row">
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row layui-form">
            <form id="dataForm" class="layui-form">
                {{csrf_field()}}
                <fieldset class="layui-elem-field">
                    <legend>操作管理</legend>
                    <div class="layui-field-box" id="listbox">
                        @foreach($menuActionLists as $data)
                            <div class="layui-inline mb10" id="checkbox_{{$data->id}}">
                                <input type="checkbox" name="type_ids[]" title="{{$data->name}}" value="{{$data->id}}">
                            </div>
                        @endforeach
                    </div>
                </fieldset>
                <div class="layui-inline" style="position: fixed;bottom:10px;right:15px;">
                    <button class="layui-btn layui-btn-sm" lay-submit lay-filter="formAdd">添加操作</button>
                    <!--<button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEdit">编辑</button>-->
                    <button class="layui-btn layui-btn-danger layui-btn-sm" lay-submit lay-filter="formDelete">删除</button>
                    <button class="layui-btn layui-btn-primary layui-btn-sm" lay-submit lay-filter="cancel">关闭</button>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        _token  = "{{csrf_token()}}";
        menu_id = "{{$menuId}}";
    </script>
    <script src="{{asseturl("js/menu/menu.action.js")}}"></script>
@stop
