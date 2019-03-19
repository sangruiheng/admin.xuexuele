layui.use(['element', 'jquery', 'layer', 'form', 'laypage', 'laydate'], function () {
    var element = layui.element;
    var form = layui.form;
    $ = layui.jquery;
    layer = layui.layer;
    laydate = layui.laydate;
    laypage = layui.laypage;

   

    
    form.on('submit(formEdit)', function (data) {

        var content= new Array();
        $("input[name='city']").each(function(){
            if($(this).val()!=''){
                content.push($(this).val());
            }
            
        });
        let contenttext = content.join(",")+',';
        
        

        $('#citylist').val(contenttext);


        if(data.field.country.length>8){
            layer.msg("最多输入8个字符");
            return false;
        }
        if(data.field.city.length>10){
            layer.msg("最多输入10个字符");
            return false;
        }

        

        $.ajax({
            url: adminurl + "/foreignregion/update",
            data: $("#formEdit").serialize(),
            type: "post",
            dataType: "json",
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (res) {
                //res=JSON.parse(res);
                //console.log('11111');
                if (res.code == 1) {
                    layer.msg(res.msg);
                    location.href = adminurl + "/foreignregion" ;
                } else if (res.code == 1002) { //未登录跳转到登录页
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.href = adminurl + "/login";
                    }, 500)
                } else {
                    layer.msg(res.msg);
                    return false;
                }
            },
            error: function () {
                layer.msg("操作失败!");
                return false;
            }
        });
        return false;
    });
    $("body").on("click", ".reset", function () {
        location.reload();
    });

    $("body").on('click','.addInputReturn',function () {
     
        var html = "";
       
        html += '<div class="layui-input-block" style="display: flex;margin-bottom: 10px;">';
        html += '<input type="text" name="city"  lay-verify="required" autocomplete="off" class="layui-input" value="">';
        html += '<input type="button"  class="layui-btn layui-btn-normal delInput" value="删除" "/>';
        //'html += '<input type="button" class="layui-btn layui-btn-normal addInputReturn" value="新增城市">';
        html += '</div>';
        
     
       $("#index-div").append(html);
    });

    $("body").on('click','.delInput',function () {
        $(this).parent().remove();
    });

    


});