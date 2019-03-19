<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 添加视图模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class ViewAddTpl extends Controller
{
    /**
     * @return string  字符串
     */
    public function tplCont($moduleResult)
    {
        $moduleDir = strtolower($moduleResult->data->model_dir);
        $moduleName = strtolower($moduleResult->data->name);
        $str = <<<startData
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
                <div class="layui-card-header">响应式组合</div>
                <div class="layui-card-body">
                    <form class="layui-form" action="" lay-filter="component-form-element">
                        <div class="layui-row layui-col-space10 layui-form-item">
                            <div class="layui-col-lg6">
                                <label class="layui-form-label">员工姓名：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="fullname" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-lg6">
                                <label class="layui-form-label">技术工种：</label>
                                <div class="layui-input-block">
                                    <select name="type" lay-verify="required" lay-filter="aihao">
                                        <option value=""></option>
                                        <option value="0">前端工程师</option>
                                        <option value="1">Node.js工程师</option>
                                        <option value="2">PHP工程师</option>
                                        <option value="3">Java工程师</option>
                                        <option value="4">运维</option>
                                        <option value="4">视觉设计师</option>
                                    </select>
                                    <div class="layui-unselect layui-form-select">
                                        <div class="layui-select-title">
                                            <input type="text" placeholder="请选择" value="" readonly="" class="layui-input layui-unselect"><i class="layui-edge"></i>
                                        </div>
                                        <dl class="layui-anim layui-anim-upbit">
                                            <dd lay-value="" class="layui-select-tips">请选择</dd>
                                            <dd lay-value="0" class="">前端工程师</dd>
                                            <dd lay-value="1" class="">Node.js工程师</dd>
                                            <dd lay-value="2" class="">PHP工程师</dd>
                                            <dd lay-value="3" class="">Java工程师</dd>
                                            <dd lay-value="4" class="">运维</dd>
                                            <dd lay-value="4" class="">视觉设计师</dd>
                                        </dl>
                                    </div>
                              </div>
                            </div>
                          </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">兴趣爱好：</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="interest[write]" title="写作"><div class="layui-unselect layui-form-checkbox"><span>写作</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="interest[read]" title="阅读"><div class="layui-unselect layui-form-checkbox"><span>阅读</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="interest[code]" title="代码" checked=""><div class="layui-unselect layui-form-checkbox layui-form-checked"><span>代码</span><i class="layui-icon layui-icon-ok"></i></div>
                                <input type="checkbox" name="interest[dreaming]" title="做梦"><div class="layui-unselect layui-form-checkbox"><span>做梦</span><i class="layui-icon layui-icon-ok"></i></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否婚姻：</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="marriage" lay-skin="switch" lay-text="是|否"><div class="layui-unselect layui-form-switch" lay-skin="_switch"><em>否</em><i></i></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属职称：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="role" value="" title="经理"><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>经理</div></div>
                                <input type="radio" name="role" value="" title="主管"><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>主管</div></div>
                                <input type="radio" name="role" value="" title="码农" checked=""><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><div>码农</div></div>
                                <input type="radio" name="role" value="" title="端水"><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>端水</div></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">其它信息：</label>
                            <div class="layui-input-block">
                                <textarea name="other" placeholder="" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"> </label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="agreement" title="同意" lay-skin="primary" checked=""><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary"><span>同意</span><i class="layui-icon layui-icon-ok"></i></div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="component-form-element">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section("javascript")
    <script src="{{asseturl("js/$moduleDir/add.edit.js")}}"></script>
@stop
startData;

        return $str;
    }
}
