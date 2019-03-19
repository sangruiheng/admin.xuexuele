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
                奖励信息
            </blockquote>
             <form class="layui-form" action=""  method="post" id="formEditvoice">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->id}}"  >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">内容名称：</label>
                            <div class="layui-input-block" style="margin-left: 130px;">
                                    <input id="heading" type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->heading}}"  >
                                </div>
                        </div>
                    </div>
                </div>

                <input  type="hidden" name="img"  autocomplete="off" class="layui-input" id="pictureurl" value="{{$result->img}}">
                <input  type="hidden" name="voice"  autocomplete="off" class="layui-input" id="videourl" value="{{$result->voice}}">

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">封面图片：</label>
                            <div class="layui-input-block">
                               
                                <div class="layui-upload pictureupload" style="margin-left: 20px;">
                                  <button type="button" class="layui-btn" id="test1">上传图片</button>
                                  <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo1" style="width: 200px;" src="{{$result->img}}">
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
                            <label class="layui-form-label" style="width: 100px;">奖励内容：</label>
                            <div class="layui-input-block">
                                <div class="layui-upload videoupload" style="margin-left: 20px;">
                                  <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>上传音频</button> 
                                  <div class="layui-upload-list">
                                    <audio controls="controls" id="demo2" src="{{$result->voice}}">
                                          <source type="audio/mp3" />
                                          <source type="audio/ogg" />
                                       
                                    </audio>
                                    
                                    <p id="demoText2"></p>
                                  </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEditvoice">确定</button>
                    <a  href="javascript:history.go(-1)" class="layui-btn layui-btn-normal" > 返回</a>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
   <script src="{{asseturl("js/rewardcontent/edit.js")}}"></script> 
@stop
    
