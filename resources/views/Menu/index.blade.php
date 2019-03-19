@extends('Public.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
            </div>
        </div>
        <!-- 内容主体区域 -->
        <div id="menu-action" class="layui-row" style="padding:10px;">
            <blockquote class="site-text layui-elem-quote">
                <button class="layui-btn menu-action" data-method="addMenu"><i class="layui-icon">&#xe61f;</i>添加菜单</button>
            </blockquote>
            <table class="layui-table">
                <colgroup>
                    <col>
                    <col width="200">
                    <col width="150">
                    <col width="230">
                </colgroup>
                <thead>
                <tr>
                    <th>菜单名称</th>
                    <th>创建时间</th>
                    <th>是否显示</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($menuLists as $data)
                <tr>
                    <td>{{$data->title}}</td>
                    <td>{{$data->create_date}}</td>
                    <td>
                        <div class="layui-form resetcss menu-action" data-method="showMenu">
                            <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" data-id="{{$data->id}}" @if($data->is_show) checked @endif>
                        </div>
                    </td>
                    <td>
                        @if(!count($data->child))
                            <button data-method="actionMenu" class="layui-btn layui-btn-small menu-action" data-id="{{$data->id}}" data-title="{{$data->title}}"><i class="layui-icon"></i>操作管理</button>
                        @endif
                        <button data-method="editMenu" class="layui-btn layui-btn-small layui-btn-normal menu-action"
                        data-id="{{$data->id}}"
                        data-title="{{$data->title}}"
                        data-parent_id="{{$data->parent_id}}"
                        data-url="{{$data->url}}"
                        data-icon_class="{{$data->icon_class}}"><i class="layui-icon"></i>编辑</button>
                        <button data-method="deleteMenu" class="layui-btn layui-btn-small layui-btn-danger menu-action" data-id="{{$data->id}}"><i class="layui-icon"></i>删除</button>
                    </td>
                </tr>
                    @if(count($data->child))
                        @foreach($data->child as $childData)
                            <tr>
                                <td>　　｜－{{$childData->title}}</td>
                                <td>{{$childData->create_date}}</td>
                                <td>
                                    <div class="layui-form resetcss menu-action" data-method="showMenu">
                                        <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" data-id="{{$childData->id}}" @if($childData->is_show) checked @endif>
                                    </div>
                                </td>
                                <td>
                                    <button data-method="actionMenu" class="layui-btn layui-btn-small menu-action" data-id="{{$childData->id}}" data-title="{{$childData->title}}"><i class="layui-icon"></i>操作管理</button>
                                    <button data-method="editMenu" class="layui-btn layui-btn-small layui-btn-normal menu-action"
                                            data-id="{{$childData->id}}"
                                            data-title="{{$childData->title}}"
                                            data-parent_id="{{$childData->parent_id}}"
                                            data-url="{{$childData->url}}"
                                            data-icon_class="{{$childData->icon_class}}"><i class="layui-icon"></i>编辑</button>
                                    <button data-method="deleteMenu" class="layui-btn layui-btn-small layui-btn-danger menu-action" data-id="{{$childData->id}}"><i class="layui-icon"></i>删除</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!----添加菜单----->
    <div  class="layui-form">
        <form id="addMenu" class="layui-form" style="display: none">
            <div class="layui-form-item" style="padding:0px 10px;">
                {{ csrf_field() }}
                <label class="layui-label">菜单名称：</label>
                <input id="menuId" type="hidden" name="id" value="" />
                <input id="menuname" type="text" name="title" required  lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">父亲级菜单(默认为顶级菜单)：</label>
                <div class="layui-form-item">
                    <select id="parent" name="parent_id" lay-filter="parent">
                        <option value="0">顶级菜单</option>
                        @foreach($firstMenuList as $data)
                        <option value="{{$data->id}}">{{$data->title}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="layui-label">菜单URL：</label>
                <input id="memuurl" type="text" name="url" required  lay-verify="required" placeholder="login" autocomplete="off" class="layui-input layui-form-danger">
                <label class="layui-label">菜单图标：<a href="{{url("static/themev1.0/font/demo_unicode.html")}}" target="_blank">查看图标</a> </label>
                <input id="menuicon" type="text" name="icon_class" required  lay-verify="required" placeholder="请输入菜单图标" autocomplete="off" class="layui-input layui-form-danger">
            </div>
        </form>
    </div>
    <!----添加菜单----->
@stop
@section("javascript")
<script src="{{asseturl("js/Menu/menu.js")}}"></script>
@stop
