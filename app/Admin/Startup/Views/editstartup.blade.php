@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote">
                修改启动页
            </blockquote>
            <form class="layui-form" action=""  method="post" id="formEdit">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->id}}" >



                {{--<div class="layui-col-md12">--}}
                    {{--<div class="layui-col-md6">--}}
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label" >页面链接：</label>--}}
                            {{--<div class="layui-input-block">--}}
                                {{--<input id="url" type="text" name="url"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->url}}"  >--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}


                <input  type="hidden" name="img"  autocomplete="off" class="layui-input" id="pictureurl" value="{{$result->image_path}}" >

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >封面图片：</label>
                            <div class="layui-input-block" >
                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img" src="{{$result->image_path}}" id="demo1" style="width: 200px;">
                                        <p id="demoText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEdit">确定</button>
                    @if ($result->is_disable == 0)
                        <buttun class="layui-btn layui-btn-normal layui-bg-red btn-disable" data-id="{{$result->id}}" status-id="{{$result->is_disable}}" >禁用</buttun>
                    @else
                        <buttun class="layui-btn layui-btn-normal layui-bg-orange btn-disable" data-id="{{$result->id}}" status-id="{{$result->is_disable}}" >启用</buttun>
                    @endif
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/startup/edit.js")}}"></script>
@stop
