@extends(config('view.app.admin').'.Common.Views.main')
@section('page_title',"单页管理|后台主页")
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <div class="layui-form table-list">
                <table class="layui-table">
                    <colgroup>
                        <col>
                        <col width="200">
                        <col width="200">
                        <col width="200">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>标题</th>
                        <th>创建时间</th>
                        <th>修改时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="listbox"></tbody>
                </table>
            </div>
            <div id="pages"></div>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        var _url = "{{url("/")}}";
    </script>
    <script src="{{asseturl("js/System/page.js")}}"></script>
@stop
