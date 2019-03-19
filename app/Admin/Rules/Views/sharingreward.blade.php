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
                分享奖励
            </blockquote>
           
           <form class="layui-form" action=""  method="post" id="formSharingreward">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->id}}" >
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >奖励名称：</label>
                            <div class="layui-input-block">
                                    <input id="sharerewardbeans" type="text" name="sharerewardbeans"  lay-verify="required|number" autocomplete="off" class="layui-input" value="{{$result->sharerewardbeans}}"   >
                                    <p><em style="color: red;">*</em>不填入不产生平台分享奖励</p>
                                </div>
                                
                        </div>
                    </div>
                </div>
                
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSharingreward">确定</button>
                    <a class="layui-btn layui-btn-normal" href="javascript:history.go(-1)">返回</a> 
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/rules/edit.js")}}"></script>
    
@stop
