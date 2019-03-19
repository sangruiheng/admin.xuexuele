@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class="layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{$title}}</div>
                <div class="layui-card-body">
                    <form id="dataForm" class="layui-form" action="" lay-filter="component-form-group">
                        {{csrf_field()}}
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>基础信息</legend>
                        </fieldset>
                        <div class="layui-form-item">
                            <label class="layui-form-label">模块目录</label>
                            <div class="layui-input-block">
                                <input type="text" name="dir" lay-verify="required" autocomplete="off" placeholder="User,请注意大小写" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">模块名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" placeholder="User(生成后文件为UserController.php，User.php)" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生成css文件</label>
                            <div class="layui-input-block">
                                <input type="radio" name="css" value="1" title="是"><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><div>是</div></div>
                                <input type="radio" name="css" value="0" title="否" checked=""><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>否</div></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生成JS文件</label>
                            <div class="layui-input-block">
                                <input type="radio" name="js" value="1" title="是" checked=""><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><div>是</div></div>
                                <input type="radio" name="js" value="0" title="否"><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>否</div></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生成操作</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="action[lists]" title="列表"><div class="layui-unselect layui-form-checkbox"><span>列表</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="action[add]" title="添加" checked=""><div class="layui-unselect layui-form-checkbox layui-form-checked"><span>添加</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="action[edit]" title="修改"><div class="layui-unselect layui-form-checkbox"><span>修改</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="action[del]" title="删除"><div class="layui-unselect layui-form-checkbox"><span>删除</span><i class="layui-icon layui-icon-ok"></i></div>
                            </div>
                        </div>
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>菜单信息</legend>
                        </fieldset>
                        <div class="layui-col-md6">
                            <div class="layui-form-item">
                                <label class="layui-form-label">菜单名称：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="menuname" required  lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input layui-form-danger">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上级菜单：</label>
                            <div class="layui-input-inline">
                                <select name="parent_id" lay-filter="parent">
                                    <option value="0">顶级菜单</option>
                                    @foreach($firstMenuList as $data)
                                        <option value="{{$data->id}}">{{$data->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-col-md6">
                            <div class="layui-form-item">
                                <label class="layui-form-label">菜单URL：</label>
                                <div class="layui-input-block">
                                    <input id="memuurl" type="text" name="url" required  lay-verify="required" placeholder="login" autocomplete="off" class="layui-input layui-form-danger">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">菜单图标：</label>
                            <div class="layui-input-inline">
                                <input id="menuicon" type="text" name="icon_class" required  lay-verify="required" placeholder="请输入菜单图标" autocomplete="off" class="layui-input layui-form-danger">
                            </div>
                            <div class="layui-inline">
                                <div class="layui-form-mid layui-word-aux"><a href="{{url("/resource/font/demo_unicode.html")}}" target="_blank">查看图标</a></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="add">立即创建</button>
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
    <script src="{{asseturl("js/create/create.js")}}"></script>
@stop
