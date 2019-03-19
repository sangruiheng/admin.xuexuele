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
               <table class="layui-table" lay-filter="test">
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>规则名称</th>
                        <th>规则详情</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="listbox"></tbody>
                </table>
            </div>
            <!-- <div id="pages"></div> -->
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/rules/index.js")}}"></script>
    
@stop
