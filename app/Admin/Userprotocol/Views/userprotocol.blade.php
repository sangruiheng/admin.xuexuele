@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                
            </blockquote>
                <form class="layui-form" action=""  method="post" id="formEdit">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->id}}" >

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >用户协议：</label>
                            <div class="layui-input-block" >
                                    <textarea id="content" type="text" name="content"  lay-verify="required" autocomplete="off" class="layui-textarea" value="" style="min-height: 400px;min-width: 600px;">{{$result->content}}</textarea> 
                                </div>
                        </div>
                    </div>
                </div>

                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formEdit">确定</button>
                    <button type="reset" class="layui-btn layui-btn-normal log-action reset" data-method="reFormEdit">重置</button>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/userprotocol/index.js")}}"></script>
    
@stop
