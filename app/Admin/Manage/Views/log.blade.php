@extends(config('view.app.admin').'.Common.Views.main')
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
                <div class="layui-card-header header-action-btn">
                    <form class="layui-form" action="">
                        <div class="layui-inline wth180">
                            <input type="text" name="datetime" class="layui-input" id="searchSelect"
                                   placeholder="开始时间 - 结束时间">
                        </div>
                        <div class="layui-inline">
                            <input type="text" name="keyword" value="" class="layui-input" placeholder="请输入搜索关键词">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal handle" lay-submit lay-filter="formSearch"><i
                                        class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm handle" data-method="reFormSearch">
                                <i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-filter="test">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>用户名</th>
                            <th>角色</th>
                            <th>国家</th>
                            <th>城市</th>
                            <th>IP地址</th>
                            <th>操作时间</th>
                            <th>操作内容</th>
                        </tr>
                        </thead>
                        <tbody id="logbox"></tbody>
                    </table>
                    <div id="pages"></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        var _total = "{{$total}}";
    </script>
    <script src="{{asseturl("js/manage/log.js")}}"></script>
@stop
