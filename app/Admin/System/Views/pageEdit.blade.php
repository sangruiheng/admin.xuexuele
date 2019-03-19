@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction,$title)}}
                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row layui-form">
            <blockquote class="site-text layui-elem-quote">
                编辑单页
            </blockquote>
            <form id="dataForm" class="layui-form">
                {{csrf_field()}}
                <div class="layui-col-lg7 layui-col-md8">
                    <div class="layui-form-item">
                        <div class="layui-col-lg6 layui-col-md6">
                            <label class="layui-form-label">单页标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title"  lay-verify="title" placeholder="请输入知识主题" autocomplete="off" class="layui-input" @if(isset($dataContent->title)) value="{{$dataContent->title}}" @endif>
                            </div>
                        </div>
                        @if($id==6)
                        <div class="layui-col-md6" style="padding-left:15px;">
                            <div class="layui-inline">
                                <input id="video" type="text" name="video"  placeholder="请输入视频地址" autocomplete="off" class="layui-input" @if(isset($dataContent->video)) value="{{$dataContent->video}}" @endif readonly>
                            </div>
                            <div class="layui-inline">
                                <button type="button" class="layui-btn" id="test5"><i class="layui-icon"></i>上传视频</button>
                                <!--
                                <button type="button" class="layui-btn" id="test5"><i class="layui-icon"></i>预览视频</button>-->
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">单页内容</label>
                            <div class="layui-input-block">
                                <textarea id="content" name="content" style="display: none;" placeholder="请输入习俗内容">@if(isset($dataContent->content)) {{$dataContent->content}} @endif</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @if($id==6)
                    <div class="layui-col-lg5 layui-col-md4">
                        <div class="layui-form-item article-thumb-box">
                            <label for="uploadThumb" id="thumb-view" class="thumb" title="点击添加或修改图片">
                                <input type="hidden" id="topic" name="topic" value="">
                                @if(isset($dataContent->topic)) <img src="{{url("storage/".$dataContent->topic)}}" /> @endif
                            </label>
                            <input id="uploadThumb" type="file" name="imgpath" onchange="upload(this,'thumb-view')" value="" style="display: none">
                        </div>
                    </div>
                @endif
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="id" value="@if(isset($dataContent->id)){{$dataContent->id}}@endif">
                        <button class="layui-btn" lay-submit lay-filter="formEdit">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary reset">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/System/pageEdit.js")}}"></script>
@stop
