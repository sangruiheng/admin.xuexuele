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
                        <label class="layui-form-label">用户ID：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->id}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户昵称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->nickname}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">联系电话：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->phone}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>智慧豆信息</b></label>
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
                        <label class="layui-form-label">信用值：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->creditscore}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">PK值：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->pk}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>



            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>认证信息</b></label>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证状态：</label>
                        <div class="layui-input-block">
                            @if($result->certificationstate == 1)
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="未认证"   disabled>
                            @else
                                <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="已认证"   disabled>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>详细信息</b></label>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">性&nbsp;&nbsp;&nbsp;别：</label>
                        <div class="layui-input-block">
                            @if($result->sex == 1)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="男" id="user_phone_up" disabled>
                            @elseif($result->sex == 2)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="女" id="user_phone_up" disabled>
                            @elseif($result->sex == 3)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="保密" id="user_phone_up" disabled>
                            @else
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="双性" id="user_phone_up" disabled>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">生&nbsp;&nbsp;&nbsp;日：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->birthday}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">星&nbsp;&nbsp;&nbsp;座：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->constellation}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><b>实名信息</b></label>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户地区：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->city}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">真实姓名：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->name}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">身份证号：</label>
                        <div class="layui-input-block">
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->identitycard}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">手持身份证照片：</label>
                        <div class="layui-input-inline">

                            <p><img src="{{URL::asset($result->identityimg) }}" style="width:180px;height:180px;" id="iconsrc"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学校名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->schoolname}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学历：</label>
                        <div class="layui-input-block">
                            @if($result->education == 1)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="小学" id="user_phone_up" disabled>
                            @elseif($result->education == 2)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="初中" id="user_phone_up" disabled>
                            @elseif($result->education == 3)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="高中" id="user_phone_up" disabled>
                             @elseif($result->education == 4)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="大专" id="user_phone_up" disabled>
                             @elseif($result->education == 5)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="本科" id="user_phone_up" disabled>
                             @elseif($result->education == 6)
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="研究生" id="user_phone_up" disabled>
                            @else
                                <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="博士" id="user_phone_up" disabled>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-col-md6">
                    <div class="layui-form-item">
                        <label class="layui-form-label">专业：</label>
                        <div class="layui-input-block">

                            <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->profession}}" id="user_phone_up" disabled>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@stop

