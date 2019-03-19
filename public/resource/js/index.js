layui.use(['jquery','layer', 'form'], function(){
    var layer = layui.layer;
    var $    = layui.jquery;
    var form = layui.form;

    var names = [];   // 设置两个变量用来存变量
    var ttls = [];
    var time = Date.parse(new Date()).toString().substr(0, 10);   // 获取当前时间，精确到秒，但因为是毫秒级的，会多3个0，变成字符串后去掉
    time = parseInt(time);
    function getData()
    {
        $.ajax({
            url: adminurl + "/axis",
            method:'post',
            data:{
                _token: _token
            }, 
            function(data) {
                $.each(data, function(i, item) {
                    names.push(item.create_time);
                    if((wisdombean = (parseInt(item.wisdombean) + parseInt(item.create_time) - time)) > 0) {   // 小于0就==0，
                        ttls.push(wisdombean);
                    } else {
                        ttls.push(0);
                    }
                });
            }
        });
    }
    getData();
    function chart() {
            var myChart = echarts.init(document.getElementById("contain"));
 
            /*option = {
                title : {
                    text: '用户数量变化表'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['时间']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        axisLine: {
                            lineStyle: { color: '#333' }
                        },
                        axisLabel: {
                            rotate: 30,
                            interval: 0
                        },
                        type : 'category',
                        boundaryGap : false,
                        data : names    // x的数据，为上个方法中得到的names
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        axisLabel : {
                            formatter: '{value} 秒'
                        },
                        axisLine: {
                            lineStyle: { color: '#333' }
                        }
                    }
                ],
                series : [
                    {
                        name:'智慧豆',
                        type:'line',
                        smooth: 0.3,
                        data: wisdombeans   // y轴的数据，由上个方法中得到的ttls 
                    } 
                ]
            };*/
            option = {
    title : {
        text: '未来一周气温变化',
        subtext: '纯属虚构'
    },
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['最高气温','最低气温']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            boundaryGap : false,
            data : ['周一','周二','周三','周四','周五','周六','周日']
        }
    ],
    yAxis : [
        {
            type : 'value',
            axisLabel : {
                formatter: '{value} °C'
            }
        }
    ],
    series : [
        {
            name:'最高气温',
            type:'line',
            data:[11, 11, 15, 13, 12, 13, 10],
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    {type : 'average', name: '平均值'}
                ]
            }
        },
        {
            name:'最低气温',
            type:'line',
            data:[1, -2, 2, 5, 3, 2, 0],
            markPoint : {
                data : [
                    {name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
                ]
            },
            markLine : {
                data : [
                    {type : 'average', name : '平均值'}
                ]
            }
        }
    ]
};
                    
            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);
        }
  
        setTimeout('chart()', 1000);
});

