@extends('Public.mainview')
@section("content")
    <div class="row">
        <!-- 内容主体区域 -->
        <div id="role-action" class="layui-row layui-form" style="padding:10px;">
            <form id="dataForm" class="layui-form">
                {{csrf_field()}}
                <!--
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <div class="layui-col-md6">
                            <label class="layui-form-label">单页标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" disabled lay-verify="title" placeholder="请输入知识主题" autocomplete="off" class="layui-input" @if(isset($dataContent->title)) value="{{$dataContent->title}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="layui-col-md6">
                    <div class="layui-form-item article-thumb-box">
                        <label for="uploadThumb" id="thumb-view" class="thumb" title="点击添加或修改图片">
                            <input type="hidden" id="topic" name="topic" value="">
                            @if(isset($dataContent->topic)) <img src="{{url("storage/".$dataContent->topic)}}" /> @endif
                        </label>
                        <input id="uploadThumb" type="file" name="imgpath" onchange="upload(this,'thumb-view')" value="" style="display: none">
                    </div>
                </div>-->
                <div class="layui-col-md12">
                    <fieldset class="layui-elem-field">
                        <legend>内容详情</legend>
                        <div class="layui-field-box">
                            @if(isset($dataContent->content)) {!!$dataContent->content!!} @endif
                        </div>
                    </fieldset>
                </div>
                <div class="layui-form-item">
                </div>
            </form>
        </div>
    </div>
@stop
