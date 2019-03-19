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
                    <form class="layui-form" action="">
                        <div class="layui-inline wth100">
                            <select id="type" name="type" class="layui-input" lay-filter="state" lay-verify="" placeholder="">
                                <option value="">全部</option>
                                <option value="1">文章</option>
                                <option value="2">音频</option>
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input id="id" type="text" name="id" class="layui-input" placeholder="可输入内容ID">
                        </div>
                        <div class="layui-inline">
                            <input id="heading" type="text" name="heading" class="layui-input" placeholder="可输入内容名称">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" style="margin-left: 20px;">
                        <a href="{{adminurl("/rewardcontent/addcontentview")}}" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新建文章奖励</a> 
                    </div>
                    <div class="layui-inline" style="margin-left: 20px;">
                        <a href="{{adminurl("/rewardcontent/addvoiceview")}}" class="layui-btn layui-btn-normal" target="_blank"><i class="layui-icon">&#xe608;</i>新建音频奖励</a> 
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
                        <col width="350">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>内容名称</th>
                        <th>奖励类型</th>
                        <th>新建时间</th>
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
    <script src="{{asseturl("js/rewardcontent/index.js")}}"></script> 
  
@stop
