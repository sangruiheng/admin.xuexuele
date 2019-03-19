layui.use(['jquery','layer','form','laydate','ychl'], function(){

    var form    = layui.form;
    var $ = layui.jquery;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var ychl    = layui.ychl;
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
        ychl.table.static({
            url: adminurl + "/logs/lists",  //分页地址
            data: {
                page:page,
                pageSize:15,
                keyword:keyword,
                datetime:datetime
            },   //分页参数，默认为每一页，每页显示15条
            method: 'get',
            listbox:'logbox',  //数据插入绑定区域
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
