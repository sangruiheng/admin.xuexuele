@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
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

            <div class="layui-form news_list">
                <div style="display: flex;justify-content: center;font-size: 32px;color: #515a6e;padding: 180px;">正在开发中</div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <!--  <script src="{{asseturl("js/reporting/index.js")}}"></script>  -->
@stop
