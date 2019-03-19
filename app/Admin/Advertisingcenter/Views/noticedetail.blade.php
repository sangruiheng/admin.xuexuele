@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
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
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">消息标题：</label>
                        <div class="layui-input-block">
                                <input type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->heading}}"   disabled>
                            </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">消息内容：</label>
                        <div class="layui-input-block">
                            <textarea type="text" name="content"  lay-verify="required" placeholder="" autocomplete="off" class="layui-textarea"  disabled >{{$result->content}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
@section("javascript")
   
@stop
    
