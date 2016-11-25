/**
 * Created by srq on 2016/11/24.
 */

if (typeof(yamy) == "undefined") {
    var yamy = {};
}

yamy.main = (function () {
    var _start = function () {
        $(function () {
            $('#main-calendar').jalendar();
        });

        $.ajax ({
            url: "/main/get-platform-amount-ajax",
            type: "post",
            dataType: "json",
            data: {},
            success: function (responseData) {
                //初始化
                var chart1 = echarts.init(document.getElementById('platform-amount-chart'));
                // 指定图表的配置项和数据
                var option = {
                    title: {
                        text: '平台投资分布',
                        subtext: '呵呵',
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        //data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎']
                    },
                    series: [
                        {
                            name: '金额',
                            type: 'pie',
                            radius: '55%',
                            center: ['50%', '60%'],
                            data: responseData.data,
                            itemStyle: {
                                emphasis: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };
                // 使用刚指定的配置项和数据显示图表。
                chart1.setOption(option);
            }
        });

        $.ajax ({
            url: "/main/get-profit-ajax",
            type: "post",
            dataType: "json",
            data: {},
            success: function(responseData) {
                //初始化
                var chart2 = echarts.init(document.getElementById('profit-chart'));
                //app.title = '坐标轴刻度与标签对齐';
                option = {
                    color: ['#3398DB'],
                    title: {
                        text: '2016年收益'
                    },
                    tooltip: {},
                    legend: {
                        data:['收益']
                    },
                    xAxis: {
                        data: responseData.months
                    },
                    yAxis: {},
                    series: [{
                        name: '销量',
                        type: 'bar',
                        data: responseData.profits
                    }]
                };
                chart2.setOption(option);
            }
        });
    }

    return {
        init: function () {
            _start();
        }
    }
})();
