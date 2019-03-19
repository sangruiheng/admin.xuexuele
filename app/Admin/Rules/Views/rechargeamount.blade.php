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
                充值金额
            </blockquote>
           
           <form class="layui-form" action=""  method="post" id="formRechargeamount">
                <input id="id" type="hidden" name="id"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->id}}" >

                <input id="moneylist" type="hidden" name="moneylist"  lay-verify="" autocomplete="off" class="layui-input" value="{{$result->money}}">

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item" id="index-div">
                            <label class="layui-form-label" >充值金额：</label>
                            @foreach($result->moneyArr as $r)
                            <div class="layui-input-block inputnum" style="display: flex;margin-bottom: 10px;">
                               <input type="text" name="money"  lay-verify="number" autocomplete="off" class="layui-input" value="{{$r}}" >
                               <p style="display: flex;align-items: center;">元</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-input-block" style="margin-top: 10px;">
                            <p><em style="color: red;">*</em>人民币与智慧豆比例为1:100</p>
                        </div>
                        <div class="layui-form-item">
                             <div style="padding-left: 20%;">
                                <input type="button" class="layui-btn layui-btn-normal addInputReturn" value="添加金额">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formRechargeamount">确定</button>
                    <a class="layui-btn layui-btn-normal" href="javascript:history.go(-1)">返回</a> 
                </div>
            </form>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/rules/edit.js")}}"></script>
    
@stop
