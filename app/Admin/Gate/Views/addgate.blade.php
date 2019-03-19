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

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 140px;">关卡名称：第{{$newname}}关</label>

                    </div>
                </div>
            </div>
        </div>






    {{--<div class="box-container">--}}
            {{--<div class="layui-row srh-box">--}}
                {{--<blockquote class="site-text layui-elem-quote searchBox">--}}
                    {{--题目信息--}}
                    {{--<button class="layui-btn layui-btn-sm layui-btn-normal add-sbuject" type="button" style="float: right">增加题目</button>--}}
                {{--</blockquote>--}}
                {{--<input type="hidden"  name="showchoosetext"  autocomplete="off" class="layui-input" id="contenttext" >--}}
                {{--<div class="layui-col-md4">--}}
                    {{--<div class="layui-col-md4">--}}
                        {{--<div class="layui-form-item" id="father">--}}
                            {{--<label class="layui-form-label">可选文字：</label>--}}
                            {{--<div class="layui-input-block chooseline">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}

                            {{--</div>--}}
                            {{--<div class="layui-input-block chooseline">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}

                            {{--</div>--}}
                            {{--<div class="layui-input-block chooseline">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}
                                {{--<input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1" lay-verify="required">--}}

                            {{--</div>--}}

                        {{--</div>--}}
                        {{--<div class="layui-input-block">--}}
                        {{--<button class="layui-btn layui-btn-primary addline" type="button">增加一行</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<input type="hidden" name="answer"  autocomplete="off" class="layui-input" id="answer">--}}
                {{--<div class="layui-col-md4">--}}
                    {{--<div class="layui-col-md6">--}}
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label">正确答案：</label>--}}
                            {{--<div class="layui-input-block" >--}}
                                {{--<div class="chooseline2" id="father2">--}}
                                    {{--<input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required">--}}
                                    {{--<input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required">--}}

                                {{--</div>--}}
                                {{--<div style="margin-top: 10px;">--}}
                                    {{--<button class="layui-btn layui-btn-primary addanswer" type="button">+</button>--}}
                                    {{--<button class="layui-btn layui-btn-primary delanswer" type="button">-</button>--}}

                                {{--</div>--}}
                                {{--<div style="margin-top: 10px; width: 280px;" id="father3"  >--}}

                                {{--</div>--}}
                            {{--</div>--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="layui-col-md4" style="margin-top: 10px;margin-left: 10%; width: 280px;" id="father3"  >--}}
                    {{--<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button><button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;"></button>--}}
                {{--</div>--}}


                {{--<div class="layui-col-md4" style="margin-top: 10px;">--}}
                    {{--<input  type="hidden" name="pictureurl"  autocomplete="off" class="layui-input" id="pictureurl" >--}}
                    {{--<input  type="hidden" name="videourl"  autocomplete="off" class="layui-input" id="videourl" >--}}
                    {{--<input  type="hidden" name="ispicvideo"  autocomplete="off" class="layui-input" id="ispicvideo" value="1">--}}
                    {{--<div class="layui-col-md12">--}}
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label">提示内容：</label>--}}
                            {{--<div class="layui-input-block">--}}
                                {{--<button class="layui-btn layui-btn-primary picture activeupload" type="button">图片</button>--}}
                                {{--<button class="layui-btn layui-btn-primary video" type="button">语音</button>--}}

                                {{--<div class="layui-upload pictureupload" style="margin-top: 10px;">--}}
                                    {{--<button type="button" class="layui-btn srh-upload"  style="display: inline-block">上传图片</button>--}}
                                    {{--<div class="layui-upload-list" style="display: inline-block">--}}
                                        {{--<img class="layui-upload-img"  id="demo1" style="width: 50px;">--}}
                                        {{--<p id="demoText"></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="layui-upload videoupload" style="margin-top: 10px; display: none;">--}}
                                    {{--<button type="button" class="layui-btn" id="test2" style="display: inline-block;margin-top: -20px"><i class="layui-icon"></i>上传音频</button>--}}
                                    {{--<div class="layui-upload-list" style="display: inline-block">--}}
                                        {{--<audio controls="controls" id="demo2" style="width: 200px" >--}}
                                            {{--<source type="audio/mp3" />--}}
                                            {{--<source type="audio/ogg" />--}}

                                        {{--</audio>--}}

                                        {{--<p id="demoText2"></p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="layui-col-md4">--}}
                    {{--<div class="layui-col-md12">--}}
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label">提示文案：</label>--}}
                            {{--<div class="layui-input-block">--}}
                                {{--<textarea placeholder="" class="layui-textarea" name="hintcontenttxt" lay-verify="required" id="hintcontenttxt"></textarea>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}


                {{--<div class="layui-col-md4" style="margin-top: 1%;">--}}
                    {{--<label class="layui-form-label" style="padding-left: 20%" >排序：</label>--}}
                    {{--<div class="layui-input-inline" style="width: 80px;">--}}
                        {{--<input  class="layui-input" name="answerwisdombeanuse" id="answerwisdombeanuse" autocomplete="off" lay-verify="required">--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}

    {{--</div>--}}












        <div class="layui-row">
            <blockquote class="site-text layui-elem-quote searchBox">
                答案提示
            </blockquote>
            <input  type="hidden"  class="layui-input" name="courserid" id="selectid" autocomplete="off">

            <div class="layui-col-md12">

                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label" >推荐内容：</label>
                        <div class="layui-input-inline" >
                            <input  class="layui-input" name="selectname" id="selectname" autocomplete="off" readonly="readonly">

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
                             <input  class="layui-input" name="answerwisdombeanuse" id="answerwisdombeanuse" autocomplete="off" lay-verify="required">
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
            {{--<input  type="hidden"  class="layui-input" name="courserid" id="selectid" autocomplete="off">--}}

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
                                        <option value="{{$value->id}}">{{$value->title}}</option>
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
                                            <option value="{{$value->id}}">{{$value->title}}</option>
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
                            <input type="text" name="rewardbeans" id="rewardbeans"  lay-verify="required" autocomplete="off" class="layui-input"  >
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
                            <input type="text" name="pk"  id="pk" lay-verify="required" autocomplete="off" class="layui-input"  >
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
                              <option value="0">无奖励</option>
                              <option value="1">奖励文章</option>
                              <option value="2">奖励音频</option>

                            </select>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <input  type="hidden" class="layui-input" name="gaterewordid" id="gaterewordid" autocomplete="off" >

            <div class="layui-form-item showreword" style="display: none;">
                <div class="layui-inline">
                    <label class="layui-form-label" ></label>
                    <div class="layui-input-inline" >
                        <input  class="layui-input selectcontent" name="selectcontent" id="selectcontent" autocomplete="off" readonly="readonly" placeholder="">

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


    <script src="{{asseturl("js/gate/add.js")}}"></script>
@stop
