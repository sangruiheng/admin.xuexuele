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
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->coursename}}"   disabled>
                            </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">所属专辑：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->albumname}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">所属导师：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->albumuser}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">举报时间：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->create_time}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">举报用户：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->nickname}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend style="font-size: 15px;font-weight: bold;">专辑信息</legend>
                </fieldset>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">专辑名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->albumname}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">专辑封面：</label>
                        <div class="layui-input-inline">

                            <p><img src="{{URL::asset($result->albumimg) }}" style="width:180px;height:180px;" id="iconsrc"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">专辑介绍：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->albumcontent}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend style="font-size: 15px;font-weight: bold;">课程信息</legend>
                </fieldset>
            </div>
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
                        <div class="layui-input-inline">

                            <p><img src="{{URL::asset($result->courseimg) }}" style="width:180px;height:180px;" id="iconsrc"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程介绍：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->coursetxt}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程文字：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->coursecontent}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">课程内容：</label>
                        <div class="layui-input-block">
                            <audio controls="controls" src="{{$result->coursevoice}}"style="width:425px;height:36px;">
                                    <source type="audio/mp3" />
                                    <source type="audio/ogg" />
                            </audio>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend style="font-size: 15px;font-weight: bold;">认证审核</legend>
                </fieldset>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证状态：</label>
                        <div class="layui-input-block">
                           @if($result->state == 1)
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="待审核"   disabled>
                            @elseif($result->state == 2)
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="举报成功"   disabled>
                            @elseif($result->state == 3)
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="已驳回"   disabled>
                            @elseif($result->state == 4)
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="已恢复"   disabled>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证审核：</label>
                        <div class="layui-input-block">
                           @if($result->state == 2)
                                <a data-method="show3" class="layui-btn layui-btn-small layui-btn-blue handle"   data-id="{{$result->id}}" data-state="4">恢复</a>
                            @elseif($result->state == 1)
                                <a data-method="show1" class="layui-btn layui-btn-small layui-btn-blue handle"   data-id="{{$result->id}}" data-state="2">举报成功</a>
                                <a data-method="show2" class="layui-btn layui-btn-small layui-btn-blue handle" data-id="{{$result->id}}" data-state="3">举报驳回</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/reporting/detail.js")}}">
    </script>
@stop

