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
                消息详情
            </blockquote>
             <form class="layui-form" action=""  method="post" id="addAdvertisement">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告标题：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                    <input id="heading" type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告显示位置：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                    <input id="sort" type="text" name="sort"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告封面：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                <div class="layui-upload-list up-avator-show">
                                    <img id="image" class="layui-upload-img" src="" style="width: 200px;height: 200px;margin-bottom: 10px;">
                                </div>
                                <div class="layui-upload">
                                    <input id="avatorUpload" type="file" name="imgpath" onchange="uploadImage(this,'image')" value="" style="display: none">
                                    <label for="avatorUpload"  type="button" class="layui-btn" id="image">上传图片</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告音频：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                <audio controls="true" src="" type="audio/mpeg" style="width:425px;height:36px;margin-bottom: 10px;" id="content">
                                </audio>
                                <div class="layui-upload">
                                    <button type="button" class="layui-btn" id="music"><i class="layui-icon"></i>上传音频</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addAdvertisement">确定</button>
                    <button type="reset" class="layui-btn layui-btn-warm log-action reset">重置
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
   <script src="{{asseturl("js/advertisingcenter/advertisement.js")}}"></script> 
@stop
    
