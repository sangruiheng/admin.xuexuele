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
                <div style="display: flex;">
                    <form class="layui-form" action="" id="formSearch">

                        <div class="layui-inline">
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入题目名称">
                        </div>
                        {{--<div class="layui-inline">--}}
                            {{--<input id="id" type="text" name="id" class="layui-input" placeholder="可输入题目ID">--}}
                        {{--</div>--}}
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                        class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" style="margin-left: 10px;">
                        <a href="{{adminurl("/subject/addsubject/$gate_id")}}" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新增题目</a>
                    </div>
                    <a href="{{adminurl("/gate")}}" style="margin-left: 1%" class="layui-btn layui-bg-orange"><i class="layui-icon">&#xe608;</i>返回关卡</a>
                </div>
            </blockquote>

            <input type="hidden" name="gate_id" value="{{$gate_id}}">
            <div class="layui-form news_list">
               <table class="layui-table" lay-filter="test" >
                    <colgroup>
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>题目名称</th>
                        <th>提示文案</th>
                        <th>正确答案</th>
                        <th>排序</th>
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
    <script src="{{asseturl("js/subject/index.js")}}"></script>
@stop