<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{url("layui/css/layui.css")}}">
    <link rel="stylesheet" type="text/css" href="{{url("static/themev1.0/css/style.css")}}">
</head>


<div id="action1" class="layui-row layui-form" style="padding:10px;">
<blockquote class="site-text layui-elem-quote">
    用户信息
</blockquote>
    @if(isset($result->name)&&isset($result->phone))
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->name}}" id="user_name" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->phone}}" id="user_phone" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">邀&nbsp;&nbsp;请&nbsp;&nbsp;码：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->invite_code}}" id="user_code" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">加入时间：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->creation_time}}" id="user_code" disabled>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        暂无信息！
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<div id="action1" class="layui-row layui-form" style="padding:10px;">
<blockquote class="site-text layui-elem-quote">
    上级推荐人
</blockquote>
    @if(isset($result->up_name)&&isset($result->up_phone))
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->up_name}}" id="user_name_up" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->up_phone}}" id="user_phone_up" disabled>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        暂无推荐人！
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<div id="action1" class="layui-row layui-form" style="padding:10px;">
<blockquote class="site-text layui-elem-quote">
    数据信息
</blockquote>
    @if(isset($result->name)&&isset($result->phone))
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">推荐总数：</label>
                    <div class="layui-input-block">
                        <input type="text" name="agent_name"  lay-verify="required" autocomplete="off" class="layui-input" value="{{$result->recommend_count}}" id="user_t_count" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <label class="layui-form-label">购买总数：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{$result->buy_count}}" id="user_g_count" disabled>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        暂无数据！
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<div id="action1" class="layui-row layui-form" style="padding:10px;">
<blockquote class="site-text layui-elem-quote">
    收货地址
</blockquote>
    @if(isset($result->site)&&count($result->site))
        @foreach($result->site as $data)
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <div style="width: 80px;">
                        <label class="layui-form-label">收货地址：</label>
                        @if($data->is_default==1)
                        <label class="layui-form-label" style="text-align: center">（默认）</label>
                        @endif
                    </div>

                    <div class="layui-input-block">
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="姓名：  {{$data->name}}" id="user_g_count" disabled>
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="电话：  {{$data->phone}}" id="user_g_count" disabled>
                        <input type="text" name="phone"  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="地址：  {{$data->site}}" id="user_g_count" disabled>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="" style="width:70%">
            <div class="layui-col-md6" style="margin-right:20%">
                <div class="layui-form-item">
                    <div class="layui-input-block" >
                        暂未设置收货地址！
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
