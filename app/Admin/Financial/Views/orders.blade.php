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
                            <div class="layui-input-inline" style="width: 200px;">
                                <input  type="text" class="layui-input" id="searchSelect" name="datetime" placeholder="开始时间 - 结束时间">
                            </div>
                        </div>
                        
                        <div class="layui-inline">
                            <input id="id" type="text" name="id" class="layui-input" placeholder="可输入订单ID">
                        </div>
                        <div class="layui-inline">
                            <input id="name" type="text" name="name" class="layui-input" placeholder="可输入购买用户">
                        </div>
                        <div class="layui-inline">
                            <input id="albumname" type="text" name="albumname" class="layui-input" placeholder="可输入专辑名称">
                        </div>
                        <div class="layui-inline">
                            <input id="nickname" type="text" name="nickname" class="layui-input" placeholder="可输入所属导师">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                        class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button type="reset" class="layui-btn layui-btn-warm log-action reset" data-method="reFormSearch"><i class="layui-icon">&#x1006;</i>重置
                            </button>
                        </div>
                    </form>
                    <div class="layui-inline" style="margin-left: 10px;">
                        <button class="layui-btn layui-btn-normal export" ><i class="layui-icon">&#xe601;</i>数据导出</button>
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
                        <col width="">
                        <col width="">
                        <col width="">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>订单时间</th>
                        <th>购买用户</th>
                        <th>电话号码</th>
                        <th>专辑名称</th>
                        <th>购买课时数量</th>
                        <th>所属导师</th>
                        <th>订单金额</th>
                        <th>打赏平台</th>
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
    <script src="{{asseturl("js/financial/orders.js")}}"></script>
@stop
