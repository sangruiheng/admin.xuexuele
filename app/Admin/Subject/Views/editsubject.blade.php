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
                <input type="hidden" name="id" value="{{$result->id}}">
                <input type="hidden" class="gate_id" name="gate_id" value="{{$result->gate_id}}">
            </div>






            <div class="layui-row">
                <blockquote class="site-text layui-elem-quote searchBox">
                    编辑题目
                </blockquote>

                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label" >题目名称：</label>
                            <div class="layui-input-block">
                                <input id="title" type="text" name="title"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->title}}"  >
                            </div>
                        </div>
                    </div>
                </div>

                <?php $optionsarry = explode(',',$result->options);$i = 1;?>
                <input type="hidden"  name="showchoosetext"  autocomplete="off" class="layui-input" id="contenttext" value="{{$result->options}}">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item" id="father">
                            <label class="layui-form-label">可选文字：</label>

                            @foreach ($optionsarry as $key=>$value)
                                @if($i==1)

                                    <div class="layui-input-block chooseline">
                                        <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="{{$value}}">
                                        <?php $i++;?>
                                        @elseif($i >=2 and $i<=6)

                                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="{{$value}}">

                                            <?php $i++;?>


                                        @elseif ($i == 7)

                                            <input type="text" name="choosetext"  autocomplete="off" class="layui-input changechoose" style="width: 40px;" maxlength ="1"  value="{{$value}}">
                                            @if($key>23)
                                                <button class="layui-btn layui-btn-primary delline" type="button">删除本行</button>
                                            @endif
                                    </div>
                                    <?php $i = 1;?>

                                @endif

                            @endforeach



                        </div>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary addline" type="button">增加一行</button>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12" style="margin-top: 10px;">
                    <input  type="hidden" name="pictureurl"  autocomplete="off" class="layui-input" id="pictureurl" value="{{$result->hintcontent}}">
                    <input  type="hidden" name="videourl"  autocomplete="off" class="layui-input" id="videourl"
                            value="{{$result->hintcontent}}">
                    <input  type="hidden" name="ispicvideo"  autocomplete="off" class="layui-input" id="ispicvideo" value="{{$result->contenttype}}">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">提示内容：</label>
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-primary picture @if($result->contenttype==1) activeupload @endif" type="button">图片</button>
                                <button class="layui-btn layui-btn-primary video @if($result->contenttype==2) activeupload @endif" type="button">语音</button>

                                <div class="layui-upload pictureupload"  @if($result->contenttype==2)style="display: none;" @else style="margin-top: 10px;" @endif>
                                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img" id="demo1" style="width: 200px;" @if($result->contenttype==1)  src="{{$result->hintcontent}}" @endif>
                                        <p id="demoText"></p>
                                    </div>
                                </div>

                                <div class="layui-upload videoupload"
                                     @if($result->contenttype==1) style="margin-top: 10px; display: none;" @else style="margin-top: 10px;"@endif >
                                    <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>上传音频</button>
                                    <div class="layui-upload-list">
                                        <audio controls="controls" id="demo2"  @if($result->contenttype==2)  src="{{$result->hintcontent}}" @endif>
                                            <source type="audio/mp3" />
                                            <source type="audio/ogg" />

                                        </audio>

                                        <p id="demoText2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">提示文案：</label>
                            <div class="layui-input-block">
                                <textarea placeholder="" class="layui-textarea" name="hintcontenttxt" lay-verify="required" id="hintcontenttxt">{{$result->hintcontent_txt}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="answer"  autocomplete="off" class="layui-input" id="answer" value="{{$result->answer}}">
                <div class="layui-col-md12">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">正确答案：</label>
                            <div class="layui-input-block" >
                                <div class="chooseline2" id="father2">
                                    <?php $answerarry = explode(',',$result->answer);?>
                                    @foreach($answerarry as $answerkey)
                                        <input type="text" name="answertext"  autocomplete="off" class="layui-input changeanswer" style="width: 40px;" maxlength ="1" readonly="readonly" lay-verify="required" value="{{$answerkey}}">
                                    @endforeach

                                </div>
                                <div style="margin-top: 10px;">
                                    <button class="layui-btn layui-btn-primary addanswer" type="button">+</button>
                                    <button class="layui-btn layui-btn-primary delanswer" type="button">-</button>

                                </div>
                                <div style="margin-top: 10px; width: 280px;" id="father3" >

                                    @foreach($optionsarry as $optionskey)<button class="layui-btn layui-btn-primary showchoose" type="button" style="margin-left: 0px;padding: 0 0px;">{{$optionskey}}</button>@endforeach
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
                                    <label class="layui-form-label">所属关卡：</label>
                                    <div class="layui-input-inline">
                                        <select name="gate_id" id="gate_id" lay-verify="required">
                                            <option value="">请选择</option>
                                            @foreach ($gate as $key=>$value)
                                                <option @if($value->id==$result->gate_id) selected="selected" @endif value="{{$value->id}}">{{$value->gatename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="layui-inline">
                    <label class="layui-form-label">排序：</label>
                    <div class="layui-input-inline" style="width: 80px;">
                        <input class="layui-input" name="sort" id="sort" autocomplete="off" lay-verify="required" value="{{$result->sort}}">
                    </div>

                </div>

            </div>







            <div class="layui-row">
                <div class="layui-inline" style="padding-left: 20%;">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="addGate">确定</button>
                    <a  href="{{adminurl("/subject/showsubject/$result->gate_id")}}"><button type="button" class="layui-btn layui-btn-warm log-action reset" data-method="reFormAdd">
                            取消</button></a>
                </div>

            </div>

        </form>

    </div>

@stop
@section("javascript")


    <script src="{{asseturl("js/subject/edit.js")}}"></script>
@stop
