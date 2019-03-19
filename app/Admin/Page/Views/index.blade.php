@extends(config('view.app.admin').'.Common.Views.main')
@section('page_title',"单页管理|后台主页")
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{$title}}</div>
                <div class="layui-card-body">
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
                    <div id="pages"></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        var view_pages = "{{actionIsView('view_pages')}}";
        var eidt_pages = "{{actionIsView('eidt_pages')}}";
        var _url = "{{url("/")}}";
    </script>
    <script src="{{asseturl("js/page/index.js")}}"></script>
@stop
