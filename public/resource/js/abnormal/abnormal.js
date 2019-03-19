layui.use(['element','jquery','layer','form','laydate','laypage','ychl'], function(){
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;
    var ychl = layui.ychl;
    //console.log(ychl);
    laydate.render({
        elem: '#searchSelect' //指定元素
        ,range: true
    });
    //重置搜索
    $("body").on("click", ".reset", function () {
        getDataLists(1, "", "", "", "");
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
    function getDataLists(page){
        ychl.table.static({
            url: adminurl + "/abnormal/lists",  //分页地址
            data: {page:page,pageSize:15},   //分页参数，默认为每一页，每页显示15条
            method: 'get',
            listbox:'listbox',  //数据插入绑定区域
            elemPage:'pages',    //数据插入分页区域
            dataTpl:function (res,page) {
                layer.closeAll();
                layui.each(res.data.lists, function (index, item) {
                    innerData +="<tr>";
                    innerData +='<td>'+((index + 1) + (page-1)*15)+'</td>';
                    innerData +='<td>'+item.message+'</td>';
                    innerData +='<td>'+item.app+'</td>';
                    innerData +='<td>'+item.file+'</td>';
                    innerData +='<td>'+item.line+'</td>';
                    innerData +='<td>'+item.create_date+'</td>';
                    innerData +='</tr>';
                });
                $("#total").html(res.data.count);
                return innerData;
            }
        });
    }
})
