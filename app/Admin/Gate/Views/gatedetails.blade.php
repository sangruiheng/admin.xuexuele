@extends(config('view.app.admin').'.Common.Views.main')

@section("content")
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
   .chooseline{
        display: flex;

   } 
   .chooseline2{
        display: flex;
   }
   .activeupload{
        background-color: #CCCCCC;
   }
   .showchoose{
        height: 40px;
        width: 40px;
   }
   .layui-row{background-color: #fff;padding-left: 10px;}
</style>

<div class="layui-body">
    <div class=" layui-tab-brief">
        <div class="layui-breadcrumb-box">
            {{adminNav($thisAction)}}
            <a class="go-back" href="{{adminurl("/gate")}}"><i class="layui-icon">&#xe65c;</i> 返回</a>
        </div>
    </div>
    <!-- 内容主体区域 -->
    
        
    <form class="layui-form" action=""  method="post" id="addGate">

        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                基本信息
            </blockquote>
            <input type="hidden" name="id" value="{{$result->id}}">
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 140px;">关卡名称：第{{$result->id}}关</label>
                      
                    </div>
                </div>
            </div>
        </div>













        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                答案提示
            </blockquote>
            <input  type="hidden"  class="layui-input" name="courserid" id="selectid" autocomplete="off" value="{{$result->courserid}}">

            <div class="layui-col-md12">
                
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label" >推荐内容：</label>
                        <div class="layui-input-inline" >
                            <input  class="layui-input" name="selectname" id="selectname" autocomplete="off" readonly="readonly" value="{{$result->coursename}}">
                            
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline" >
                            
                            <button class="layui-btn layui-btn-normal selectcourse" type="button">请选择</button>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" >智慧豆：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                             <input  class="layui-input" name="answerwisdombeanuse" id="answerwisdombeanuse" autocomplete="off" lay-verify="required" value="{{$result->answerwisdombeanuse}}" >
                        </div>
                        <div class="layui-form-mid ">个</div>
                    </div>
                    
                </div>
                
                    
                
            </div>
        </div>



        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                关卡弹窗
            </blockquote>

            <div class="layui-col-md12">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">成功弹窗：</label>
                                <div class="layui-input-inline">
                                    <select name="alert_id" id="alert_id" lay-filter="teshu" >
                                        <option value="">无弹窗</option>
                                        @foreach ($alertList as $key=>$value)
                                            <option @if($value->id==$result->alert_id) selected="selected" @endif value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-col-md12">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">失败弹窗：</label>
                                <div class="layui-input-inline">
                                    <select name="alert_errid" id="alert_errid" lay-filter="teshu" >
                                        <option value="">无弹窗</option>
                                        @foreach ($alertList as $key=>$value)
                                            <option @if($value->id==$result->alert_errid) selected="selected" @endif value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                奖励信息
            </blockquote>
        
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">智慧豆：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                            <input type="text" name="rewardbeans" id="rewardbeans"  lay-verify="required" autocomplete="off" class="layui-input"  value="{{$result->rewardbeans}}">
                        </div>
                        <div class="layui-form-mid ">个 每关卡最多奖励（9999个智慧豆）</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">PK值：</label>
                        <div class="layui-input-inline" style="width: 80px;">
                            <input type="text" name="pk"  id="pk" lay-verify="required" autocomplete="off" class="layui-input"  value="{{$result->pkvalue}}">
                        </div>
                        <div class="layui-form-mid ">分</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                          <label class="layui-form-label">特殊奖励：</label>
                          <div class="layui-input-inline">
                            <select name="teshu" id="teshu" lay-filter="teshu">
                              <option value="0" @if($result->specialreward==2) selected="selected" @endif>无奖励</option>
                              <option value="1" @if($result->specialreward==1 and $result->type==1) selected="selected" @endif>奖励文章</option>
                              <option value="2" @if($result->specialreward==1 and $result->type==2) selected="selected" @endif>奖励音频</option>
                              
                            </select>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <input  type="hidden" class="layui-input" name="gaterewordid" id="gaterewordid" autocomplete="off" value="{{$result->gaterewordid}}">

            <div class="layui-form-item showreword" @if($result->specialreward==2)  style="display: none;" @endif>
                <div class="layui-inline">
                    <label class="layui-form-label" ></label>
                    <div class="layui-input-inline" >
                        <input  class="layui-input selectcontent" name="selectcontent" id="selectcontent" autocomplete="off" readonly="readonly" placeholder="" value="{{$result->heading}}">
                        
                    </div>
                </div>
                <div class="layui-inline" >
                    <div class="layui-input-inline" >
                        
                        <button class="layui-btn layui-btn-normal selectreword" type="button">请选择</button>
                    </div>
                </div>
                
            </div>
            <div class="layui-inline" style="padding-left: 20%;">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addGate">确定</button>
                <a  href="{{adminurl("/gate")}}"><button type="button" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">
                取消</button></a>
            </div>
            
        </div>
        
    </form>

</div>
   
@stop
@section("javascript")
  
    
    <script src="{{asseturl("js/gate/edit.js")}}"></script>  
@stop
