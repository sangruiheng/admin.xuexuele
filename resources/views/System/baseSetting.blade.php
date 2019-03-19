@extends('Public.main')
@section('page_title',"后台主页")
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="menu-action" class="layui-row" style="padding:10px;">
            <div class="layui-col-md6 setting-base">
                <form class="layui-form" action="">
                    {{csrf_field()}}
                    <div class="layui-col-md6 layui-col-space5">
                        <div class="layui-form-item">
                            <label class="layui-form-text">选择设置区域</label>
                        </div>
                        @foreach($settingLists as $data)
                            @if($data->type=="text")
                                <div class="layui-form-item">
                                    <div class="layui-form-text">{{$data->title}}</div>
                                    <input type="text" name="{{$data->key}}" placeholder="请输入{{$data->title}}"
                                           autocomplete="off" value="{{$data->value}}" class="layui-input">
                                </div>
                                @elseif($data->type=="textarea")
                                <div class="layui-form-item">
                                    <label class="layui-form-text">{{$data->title}}</label>
                                    <textarea name="{{$data->key}}" rows="4" class="layui-textarea" placeholder="请输入{{$data->title}}">{{$data->value}}</textarea>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if($roleid==1)<!----只有超级管理员可以修改--->
                    <!--------LOGO上传-------->
                    <div class="layui-col-md6">
                        <div class="layui-form-item up-logo-box">
                            <div class="layui-upload">
                                <div class="layui-upload-list up-logo-show">
                                    <!--<input type="hidden" id="putavator" name="logo" value="images/default.png">-->
                                    <img id="logo" class="layui-upload-img" src="@if(fileExists($logo)){{$logo}}@else{{url("images/nopic.png")}} @endif">
                                    <p id="demoText"></p>
                                </div>
                                <input id="logoUpload" type="file" name="imgpath" onchange="uploadLogo(this,'logo')" value="" style="display: none">
                                <label for="logoUpload"  type="button" class="layui-btn" id="logo">上传LOGO</label>
                            </div>
                        </div>
                        <!--------二维码上传------>
                        <div class="layui-form-item up-qrcode-box">
                            <div class="layui-upload">
                                <div class="layui-upload-list up-qrcode-show">
                                    <!--<input type="hidden" id="putavator" name="logo" value="images/default.png">-->
                                    <img id="qrcode" class="layui-upload-img" src="@if(fileExists($qrcode)){{$qrcode}}@else{{url("images/nopic.png")}} @endif">
                                    <p id="demoText"></p>
                                </div>
                                <input id="qrcodeUpload" type="file" name="imgpath" onchange="uploadQrcode(this,'qrcode')" value="" style="display: none">
                                <label for="qrcodeUpload"  type="button" class="layui-btn" id="qrcode">上传微信二维码</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="layui-form-item">
                        <button class="layui-btn" lay-submit lay-filter="formBase">立即保存</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/Admin/admin.js")}}"></script>
@stop
