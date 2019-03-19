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
            <blockquote class="site-text layui-elem-quote searchBox">
                <div class="layui-inline" style="margin-left: 20px;">
                    <a href="{{adminurl("/album/addalbumcourse/".$id)}}" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新增课程</a> 
                </div>
            </blockquote>
            <div class="layui-form news_list">
                <table class="layui-table" lay-filter="test" >
                    <colgroup>
                        <col width="90">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>课程封面</th>
                        <th>课程名称</th>
                        <th>智慧豆</th>
                        <th>学习量</th>
                        <th>课程时间</th>
                        <th>留言数量</th>
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
    <script src="{{asseturl("js/album/albumcourseindex.js")}}"></script>
    <script>
        var _id = '{{$id}}';
    </script>
@stop
