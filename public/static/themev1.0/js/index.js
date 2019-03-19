layui.use(['jquery','layer', 'form'], function(){
    var layer = layui.layer;
    var $    = layui.jquery;
    var form = layui.form;
    //监听select选择
    form.on('select(region)', function(data){
        getDataContent(data.value);
    });



    function orderChart(chartData){
        var dom = document.getElementById("order-total");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = chartData;
        ;
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
    }
    function packCemeteryChart(chartData){
        var dom = document.getElementById("service-cemetery");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = chartData;
        ;
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
    }
    function detailTotal(chartData){
        var dom = document.getElementById("detail-total");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = chartData;
        ;
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
    }
    function regionUser(chartData){
        var dom = document.getElementById("region-user");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = chartData;
        ;
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
    }
});
