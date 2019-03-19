@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav($thisAction)}}
                <a class="go-back" href="javascript:history.go(-1)"><i class="layui-icon">&#xe65c;</i> 返回</a>
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{$title}}</div>
                <div class="layui-card-body">
                    <form class="layui-form">
                        {{csrf_field()}}
                        <input type="hidden" name="roles_id" value="{{$roles_id}}" />
                        <blockquote class="site-text layui-elem-quote" id="menubox">
                            <input type="checkbox" name="" title="全选" lay-filter="menu" lay-skin="primary" @if($roles_id==1) disabled @endif>
                        </blockquote>
                        <div class="menu-auth-box" id="menuChild">
                            @foreach($menuLists as $key=>$data)
                                <div class="colla-item">
                                    <h2 class="colla-title">
                                        <input id="menuChildChildBox_{{$key}}" type="checkbox" data-key="{{$key}}" title="{{$data->title}}" lay-skin="primary" lay-filter="menuChild"
                                               @if($roles_id==1) disabled @endif
                                               @if($data->thisIsChecked==1) checked @endif>
                                    </h2>
                                    <div class="layui-colla-content layui-show" id="menuChildChild_{{$key}}">
                                        <table>
                                            <colgroup>
                                                <col width="150">
                                                <col>
                                            </colgroup>
                                            <tbody>
                                            @if(count($data->twoMenu))
                                                @foreach($data->twoMenu as $val)
                                                    <tr>
                                                        <td>
                                                            <input class="menu_id" type="checkbox" name="menu_id[]" data-pkey="{{$key}}" title="{{$val->title}}" lay-skin="primary" lay-filter="menuItem" value="{{$val->id}}" @if(in_array($val->id,$roleMenuIdArr)) checked @endif @if($roles_id==1) disabled @endif>
                                                        </td>
                                                        <td>
                                                            @if(count($val->menu_action))
                                                                @foreach($val->menu_action as $v)
                                                                    <input type="checkbox" name="menu_action_id[{{$val->id}}][]" data-pkey="{{$key}}" title="{{$v->name}}" lay-skin="primary" lay-filter="menuAction" value="{{$v->id}}" @if($v->menu_action_checked==1) checked @endif @if($roles_id==1) disabled @endif>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td><input class="menu_id" type="checkbox" name="menu_id[]" data-pkey="{{$key}}" title="{{$data->title}}" lay-skin="primary" lay-filter="menuItem" value="{{$data->id}}" @if(in_array($data->id,$roleMenuIdArr)) checked @endif @if($roles_id==1) disabled @endif></td>
                                                    <td>
                                                        @if(count($data->menu_action))
                                                            @foreach($data->menu_action as $n)
                                                                <input type="checkbox" name="menu_action_id[{{$data->id}}][]" data-pkey="{{$key}}" title="{{$n->name}}" lay-skin="primary" lay-filter="menuAction" value="{{$n->id}}" @if($n->menu_action_checked==1) checked @endif @if($roles_id==1) disabled @endif>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="layui-form-btn">
                            @if($roles_id!=1)
                                <button type="submit" class="layui-btn layui-btn-big layui-btn" lay-submit lay-filter="saveAuth"><i class="layui-icon"></i>保存</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/manage/role.js")}}"></script>
@stop
