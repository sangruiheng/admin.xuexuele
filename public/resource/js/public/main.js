layui.use(['element','jquery','layer','ychl'], function(){

    var $ = layui.jquery;
    var layer = layui.layer;
    var ychl = layui.ychl;

    $(".logout").on('click', function(){
        ychl.logout();
    });

    $("body").on("click",".home",function(){
        layer.closeAll();
    });

    $('.layui-logo').on("click",".iconfont",function(){
        if($(this).hasClass('fold')){
            $(this).removeClass('fold').html('&#xe622;');
            $('.layui-side').animate({left:"0px"},100);
            $('.layui-body,.layui-footer').animate({left:"200px"},100);
        }else{
            $(this).addClass('fold').html('&#xe624;');
            $('.layui-side').animate({left:"-200px"},100);
            $('.layui-body,.layui-footer').animate({left:"0px"},100);
        }
    });
    //监控报错信息
    // setInterval(function () {
    //     ychl.ajax({
    //         url: adminurl + "/abnormal/call",
    //         data:{},
    //         method:"get",
    //         loadding:false,
    //         done:function (res) {
    //             if(res.code==1){
    //                 layer.msg(res.msg);
    //             }else{
    //                 return false;
    //             }
    //         }
    //     });
    // },3000);
});
