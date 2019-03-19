@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                国家信息
            </blockquote>
           
           <form class="layui-form" action=""  method="post" id="formAdd">
                
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">国家名称：</label>
                            <div class="layui-input-block">
                                    <input type="text" name="country"  lay-verify="" autocomplete="off" class="layui-input" value=""   >
                                </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><b>城市信息</b></label>
                        </div>
                    </div>
                </div>
                <input id="citylist" type="hidden" name="citylist"  lay-verify="" autocomplete="off" class="layui-input" value="">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item" id="index-div">
                            <label class="layui-form-label">城市名称：</label>
                            <div class="layui-input-block" style="display: flex;margin-bottom: 10px;">
                                 <input type="text" name="city"  lay-verify="" autocomplete="off" class="layui-input" value="">
                                 <input type="button"  class="layui-btn layui-btn-normal delInput" value="删除"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                             <div style="padding-left: 20%;">
                                <input type="button" class="layui-btn layui-btn-normal addInputReturn" value="新增城市">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formAdd">确定</button>
                    <button type="reset" class="layui-btn layui-btn-normal log-action reset" data-method="reFormEdit">重置</button>
                    <!-- <a href="{{adminurl("/foreignregion")}}" class="layui-btn layui-btn-normal" target="_blank">返回</a> --> 
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/foreignregion/add.js")}}"></script>
    
@stop
