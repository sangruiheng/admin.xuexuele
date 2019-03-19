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
                消息发布
            </blockquote>
            <form class="layui-form" action=""  method="post" id="addNotice">

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">消息标题：</label>
                            <div class="layui-input-block">
                                    <input id="heading" type="text" name="heading"  lay-verify="required" autocomplete="off" class="layui-input" value=""   >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">消息内容：</label>
                            <div class="layui-input-block">
                                 <textarea type="text" name="content" id="content" lay-verify="required" placeholder="" autocomplete="off" class="layui-textarea" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">收信用户：</label>
                            <div class="layui-input-block">
                                <input id="identity1" type="checkbox" name="identity2" title="侠客"  lay-skin="primary" checked>
                                <input id="identity2" type="checkbox" name="identity1" title="导师"  lay-skin="primary" checked> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addNotice">确定</button>
                    <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reAddNotice">重置
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/advertisingcenter/notice.js")}}"></script>
@stop

