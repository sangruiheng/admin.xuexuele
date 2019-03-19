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
                        <div class="layui-input-block">
                            <div class="layui-input-inline">
                                <p><img src="{{URL::asset($result->albumimg) }}" style="width:180px;height:180px;" id="iconsrc"></p>
                            </div>
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


        </div>
    </div>
@stop

