@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote">
                基本信息
            </blockquote>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->coursename}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程封面：</label>
                        <div class="layui-input-block">
                            <div class="layui-input-inline">
                                <p><img src="{{URL::asset($result->courseimg) }}" style="width:180px;height:180px;" id="iconsrc"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程介绍：</label>
                        <div class="layui-input-block" style="width: 300px;padding-top: 10px;">
                            
                            {!!$result->coursetxt!!}
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程文字：</label>
                        <div class="layui-input-block" style="width: 300px;padding-top: 10px;">
                            {!!$result->coursecontent!!}
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程内容：</label>
                        <div class="layui-input-block">
                            
                            <audio controls="controls">
                                <source type="audio/mp3" src="{{$result->coursevoice}}" />
                                <source type="audio/ogg" src="{{$result->coursevoice}}"/>
                           
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">智慧豆：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->wisdombean}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学习量：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->studysum}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">评分：</label>
                        <div class="layui-input-block" >
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->coursescore}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block" >
                            <img src="{{URL::asset('images/152.png') }}" style="height: 40px;">

                            <span style="margin-right: 10px;">
                                @foreach($score as $scoreone)
                                    @if($scoreone->coursescore==3)
                                        {{$scoreone->total}}
                                    
                                    @endif
                                @endforeach
                                
                                
                            </span>
                            <img src="{{URL::asset('images/153.png') }}" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                @foreach($score as $scoreone)
                                    @if($scoreone->coursescore==5)
                                        {{$scoreone->total}}
                                    
                                    @endif
                                @endforeach
                            </span>
                            <img src="{{URL::asset('images/154.png') }}" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                @foreach($score as $scoreone)
                                    @if($scoreone->coursescore==7)
                                        {{$scoreone->total}}
                                    
                                    @endif
                                @endforeach
                            </span>
                            <img src="{{URL::asset('images/155.png') }}" style="height: 40px;">
                            <span style="margin-right: 10px;">
                                @foreach($score as $scoreone)
                                    @if($scoreone->coursescore==10)
                                        {{$scoreone->total}}
                                    
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>留言信息</b></label>
                    </div>
                </div>
            </div>
            <!-- 内容主体区域 -->
            <div id="mainbox" class="layui-row">

                <div class="layui-form news_list">
                    <table class="layui-table" lay-filter="test" >
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>留言时间</th>
                            <th>用户名称</th>
                            <th>留言内容</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="listbox"></tbody>

                    </table>
                </div>
                <div id="pages"></div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/user/albumcoursedetail.js")}}"></script>
    <script>
        var _id = '{{$id}}';
    </script>
@stop
