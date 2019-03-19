@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class="layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{$title}}</div>
                <div class="layui-card-body">
                    <div class="layui-tab layui-tab-brief" lay-filter="component-tabs-brief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">错误信息（<span id="total">0</span>）</li>
                            <li class="">异常统计</li>
                        </ul>
                        <div class="layui-tab-content self-pad hig-600">
                            <div class="layui-tab-item layui-show">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="80">
                                        <col width="">
                                        <col width="90">
                                        <col width="120">
                                        <col width="90">
                                        <col width="180">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>序号</th>
                                        <th>错误信息</th>
                                        <th>应用</th>
                                        <th>错误文件</th>
                                        <th>错误行</th>
                                        <th>错误时间</th>
                                    </tr>
                                    </thead>
                                    <tbody id="listbox"></tbody>
                                </table>
                                <div id="pages"></div>
                            </div>
                            <div class="layui-tab-item">开发中，敬请期待</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/abnormal/abnormal.js")}}"></script>
@stop
