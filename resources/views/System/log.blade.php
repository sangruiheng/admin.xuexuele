@extends('Public.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="log-action" class="layui-row" style="padding:10px;">
            <blockquote class="site-text layui-elem-quote">
                <form class="layui-form" action="">
                    <div class="layui-inline wth180">
                        <input type="text" name="datetime" class="layui-input" id="searchSelect" placeholder="开始时间 - 结束时间">
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="keyword" value="" class="layui-input" placeholder="请输入搜索关键词">
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                    class="layui-icon">&#xe615;</i>搜索
                        </button>
                        <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                        </button>
                    </div>
                </form>
            </blockquote>
            <div class="layui-form table-list">
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
                        <th>操作时间</th>
                        <th>操作内容</th>
                    </tr>
                    </thead>
                    <tbody id="logbox">

                    </tbody>
                </table>
            </div>
            <div id="pages"></div>
        </div>
    </div>
@stop
@section("javascript")
    <script>
        var _total = "{{$total}}";
    </script>
    <script src="{{asseturl("js/System/log.js")}}"></script>
@stop
