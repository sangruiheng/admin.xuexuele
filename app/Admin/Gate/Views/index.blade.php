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
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入关卡名称">
                        </div>
                        <div class="layui-inline">
                            <input id="id" type="text" name="id" class="layui-input" placeholder="可输入关卡ID">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                        class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" id="addgate" style="margin-left: 10px;">
                        <a  class="layui-btn layui-btn-normal srh" target="_blank"><i class="layui-icon">&#xe608;</i>新增关卡</a>
                    </div>
                </div>
            </blockquote>

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
                        <th>关卡</th>
                        <th>奖励智慧豆</th>
                        <th>PK值</th>
                        <th>特殊奖励</th>
                        <th>题目数量</th>
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
    <script src="{{asseturl("js/gate/index.js")}}"></script> 
@stop
