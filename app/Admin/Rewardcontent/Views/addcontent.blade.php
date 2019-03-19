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
           <form class="layui-form" action=""  method="post" id="formAddcontent">
               
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >内容名称：</label>
                            <div class="layui-input-block">
                                    <input id="heading" type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value=""  >
                                </div>
                        </div>
                    </div>
                </div>

                <input  type="hidden" name="img"  autocomplete="off" class="layui-input" id="pictureurl" >

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >封面图片：</label>
                            <div class="layui-input-block" >
                                <div class="layui-upload pictureupload" style="margin-top: 10px;">
                                  <button type="button" class="layui-btn" id="test1">上传图片</button>
                                  <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo1" style="width: 200px;">
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
                            <label class="layui-form-label" >奖励内容：</label>
                            <div class="layui-input-block" >
                                    <textarea id="article" type="text" name="article"  lay-verify="required" autocomplete="off" class="layui-input" value="" style="min-height: 200px;padding: 10px;" ></textarea>
                                </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formAddcontent">确定</button>
                    <a  href="javascript:history.go(-1)" class="layui-btn layui-btn-normal" > 返回</a>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
   <script src="{{asseturl("js/rewardcontent/add.js")}}"></script>
@stop
    
