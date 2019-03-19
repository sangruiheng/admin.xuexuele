layui.use(['jquery', 'layer', 'form', 'laydate','ychl'], function () {

    var form = layui.form;
    var $ = layui.jquery;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var ychl = layui.ychl;
    //按时间搜索
    laydate.render({
        elem: '#dateSelect' //指定元素
        , range: true
    });
    //数据搜索
    form.on('submit(formSearch)', function (data) {
        if (data.field.keyword == "" && data.field.region_id == "" && data.field.status == "" && data.field.datetime=="") {
            layer.msg("搜索内容不能为空！");
        } else {
            getDataLists(1, data.field.region_id, data.field.status,data.field.datetime,data.field.keyword);
        }
        return false;
    });
    var active = {
        view:function(){
            var _id    = $(this).data("id");
            var _title = $(this).data("title");
            layer.open({
                type: 2,
                title: _title,
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '90%'],
                content: adminurl + '/pages/view/'+_id //iframe的url
            });
        },
    };
    $('body').on('click', '.handle', function () {
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    getDataLists(1); //初始化表格数据
    function getDataLists(page) {
        ychl.table.static({
            url: adminurl + "/pages/lists",  //分页地址
            data: {page:page,pageSize:15},   //分页参数，默认为每一页，每页显示15条
            method: 'get',
            listbox:'listbox',  //数据插入绑定区域
            elemPage:'pages',    //数据插入分页区域
            dataTpl:function (res,page) {
                layui.each(res.data.lists, function (index, item) {
                    innerData += "<tr>";
                    innerData += '<td>' + item.title + '</td>';
                    innerData += '<td>' + item.create_date + '</td>';
                    innerData += '<td>' + item.update_date + '</td>';
                    innerData += '<td>';
                    if(view_pages){
                        innerData += '<button data-method="view" data-id="'+item.id+'" data-title="'+item.title+'" class="layui-btn layui-btn-sm handle">查看</button>';
                    }
                    if(eidt_pages){
                        innerData += '<a href="'+adminurl+'/pages/edit/'+item.id+'" class="layui-btn layui-btn-sm layui-btn-normal loadHref">编辑</a>';
                    }
                    innerData += '</td>';
                    innerData += '</tr>';
                });
                return innerData;
            }
        });
    }
})