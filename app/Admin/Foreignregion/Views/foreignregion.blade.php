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
                <div class="layui-inline" style="margin-left: 20px;">
                        <a href="{{adminurl("/foreignregion/addforeignregion")}}" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新建国家</a> 
                    </div>
            </blockquote>
           
            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" id="test">
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>国家</th>
                        <th>城市</th>
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
    <script src="{{asseturl("js/foreignregion/foreignregion.js")}}"></script>
    
@stop
