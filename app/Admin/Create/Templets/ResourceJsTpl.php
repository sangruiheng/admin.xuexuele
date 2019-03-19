<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 添加视图模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class ResourceJsTpl extends Controller
{
    /**
     * @return string  字符串
     */
    public function tplCont($menuResult,$action)
    {
        if($action == 'lists'){
            return $this->tplLists($menuResult);
        }elseif($action == 'add' || $action == 'edit'){
            return $this->tplAddEdit($menuResult);
        }
    }

    protected function tplLists($menuResult){
        $url = $menuResult->data->url;
        $str = <<<startData
layui.use(['element','jquery','layer','form','laydate','laypage'], function(){
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    var laydate = layui.laydate;
    laypage = layui.laypage;
    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });
    var active = {
        reFormSearch: function(){
            getDataLists(1,"","");
        },

    };
    $('body .handle').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    //数据搜索
    form.on('submit(formSearch)', function(data){
        if(data.field.keyword=="" && data.field.datetime==""){
            layer.msg("搜索内容不能为空！");
        }else{
            getDataLists(1,data.field.keyword,data.field.datetime);
        }
        return false;
    });

    getDataLists(1,"",""); //初始化表格数据
    function getDataLists(page,keyword,datetime){
        ychl.dataList({
            url: adminurl + "/$url/lists",  //分页地址
            data: {
                page:page,
                pageSize:15,//分页参数，默认为每一页，每页显示15条
                keyword:keyword,
                datetime:datetime
            },   
            method: 'get',
            listbox:'listbox',  //数据插入绑定区域
            elemPage:'pages',    //数据插入分页区域
            dataTpl:function (res,page) {
                layui.each(res.data.lists, function (index, item) {
                    innerData +="<tr>";
                    innerData +='<td>'+((index + 1) + (page-1)*15)+'</td>';
                    innerData +='<td>'+item.name+'</td>';
                    innerData +='<td>'+item.roles+'</td>';
                    innerData +='<td>'+item.country+'</td>';
                    innerData +='<td>'+item.city+'</td>';
                    innerData +='<td>'+item.ip+'</td>';
                    innerData +='<td>'+item.create_date+'</td>';
                    innerData +='<td>'+item.content+'</td>';
                    innerData +='</tr>';
                });
                return innerData;
            }
        });
    }
});

startData;

        return $str;
    }

    protected function tplAddEdit($menuResult){
        $url = $menuResult->data->url;
        $str = <<<startData
layui.use(['form', 'jquery'], function () {
    var form = layui.form;
    var $ = layui.jquery;
    //监听提交
    form.on('submit(addForm)', function () {
        ychl.ajax({
            url:adminurl + '/$url',
            data:$("form").serialize(),
            method:'POST',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    //编辑信息
    form.on('submit(editForm)', function () {
        ychl.ajax({
            url:adminurl + '/$url/edit',
            data:$("form").serialize(),
            method:'PUT',
            done:function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }else{
                    layer.msg(res.msg);
                    return false;
                }
            }
        });
        return false;
    });
    var active = {
        
    };
    $('body .handle').on('click', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
});
startData;

        return $str;
    }

}
