@extends(config('view.app.admin').'.Common.Views.mainview')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asseturl('css/plug/plug.css') }}">
@stop
@section('content')
    <div class="layui-row">
        <!-- 内容主体区域 -->
        <div id="mainbox" class="layui-row">
            <blockquote class="site-text layui-elem-quote">
                插件详情
            </blockquote>
            <div class="layui-row">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                    <legend>基本信息</legend>
                </fieldset>
                <div class="layui-row">
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">插件名称：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->name)){{$dataContent->name}}@endif</div>
                    </div>
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">插件类型：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->type_name)){{$dataContent->type_name}}@endif</div>
                    </div>
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">插件作者：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->author)){{$dataContent->author}}@endif</div>
                    </div>
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">最新版本：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->version))V{{$dataContent->version}}@endif</div>
                    </div>
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">插件状态：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->status_name)){{$dataContent->status_name}}@endif</div>
                    </div>
                    <div class="layui-col-md3 layui-col-xs12">
                        <label class="layui-form-label wth100">提交时间：</label>
                        <div class="layui-input-block detail-txt">@if(isset($dataContent->create_date)){{$dataContent->create_date}}@endif</div>
                    </div>
                </div>
            </div>
            <div class="layui-row">
                <fieldset class="layui-elem-field">
                    <legend>插件描述</legend>
                    <div class="layui-field-box">
                        {!! $dataContent->description !!}
                    </div>
                </fieldset>
            </div>
            <div class="layui-row">
                <fieldset class="layui-elem-field">
                    <legend>插件详情</legend>
                    <div class="layui-field-box">
                        {!! $dataContent->details !!}
                    </div>
                </fieldset>
            </div>
            <div class="layui-row">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                    <legend>版本记录</legend>
                </fieldset>
                <table class="layui-table" lay-filter="test">
                    <colgroup>
                        <col width="80">
                        <col width="">
                        <col width="">
                        <col width="200">
                        <col width="100">
                        <col width="100">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>版本号</th>
                        <th>版本描述</th>
                        <th>更新时间</th>
                        <th>版本状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="listbox">
                    @if(isset($dataContent->versions))
                        @foreach($dataContent->versions as $key=>$data)
                            <tr>
                                <td>{{($key + 1)}}</td>
                                <td>{{$data->version}}</td>
                                <td>{{$data->version_desc}}</td>
                                <td>{{$data->create_date}}</td>
                                <td>{{$status[$data->status]}}</td>
                                <td>
                                    @if($data->status != 0)
                                        <a class="layui-btn layui-btn-sm" href="{{$data->down_url}}">下载</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
