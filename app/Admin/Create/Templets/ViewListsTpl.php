<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 列表视图模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class ViewListsTpl extends Controller
{
    /**
     * @return string  字符串
     */
    public function tplCont($moduleResult)
    {
        $moduleDir = strtolower($moduleResult->data->model_dir);
        $moduleName = strtolower($moduleResult->data->name);
        $str=<<<startData
@extends(config('view.app.admin').'.Common.Views.main')
@section("content")
    <div class="layui-body">
        <div class=" layui-tab-brief">
            <div class="layui-breadcrumb-box">
                {{adminNav(\$thisAction)}}
            </div>
        </div>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-header">{{\$title}}</div>
                <div class="layui-card-header header-action-btn">
                    <div class="layui-inline">
                        <form class="layui-form" action="">
                            <div class="layui-inline wth180">
                                <input type="text" name="datetime" class="layui-input" id="searchSelect"
                                       placeholder="开始时间 - 结束时间">
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="keyword" value="" class="layui-input" placeholder="请输入搜索关键词">
                            </div>
                            <div class="layui-inline">
                                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSearch"><i
                                            class="layui-icon">&#xe615;</i>搜索
                                </button>
                                <button type="reset" class="layui-btn layui-btn-warm reset">
                                    <i class="layui-icon">&#x1006;</i>重置
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>列1</th>
                            <th>列2</th>
                            <th>列3</th>
                            <th>列4</th>
                            <th>列5</th>
                            <th>列6</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="listbox"></tbody>
                    </table>
                    <div id="pages"></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/$moduleDir/$moduleName.js")}}"></script>
@stop
startData;

        return $str;
    }
}
