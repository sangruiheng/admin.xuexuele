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
            <blockquote class="site-text layui-elem-quote searchBox">
                平台信息
            </blockquote>
           
           <form class="layui-form" action=""  method="post" id="formPlatform">
                
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">平台名称：</label>
                            <div class="layui-input-block" >
                                    <input type="text" name="nickname"  autocomplete="off" class="layui-input" id="nickname" value="{{$result->nickname or ''}}" lay-verify="required" >
                            </div>
                                
                        </div>
                    </div>
                </div>

                <input  type="hidden" name="headimg"  autocomplete="off" class="layui-input" id="pictureurl" value="{{$result->headimg or ''}}">

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">平台头像：</label>
                            <div class="layui-input-block" >
                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img" id="demo1" style="width: 200px;" src="{{$result->headimg or ''}}">
                                        <p id="demoText"></p>
                                    </div>
                                </div>  
                            </div>
                                
                        </div>
                    </div>
                </div>

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">平台介绍：</label>
                            <div class="layui-input-block" >
                                <textarea placeholder="" class="layui-textarea" name="introduction" lay-verify="required" id="introduction">{{$result->introduction or ''}}</textarea>
                            </div>
                                
                        </div>
                    </div>
                </div>

                
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formPlatform">确定</button>
                    <a class="layui-btn layui-btn-normal" href="javascript:history.go(-1)">返回</a> 
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/rules/edit.js")}}"></script>
    
@stop
