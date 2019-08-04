jQuery(document).ready(function() {
    'use strict';

    require.config({
        paths: {
            echarts: base_url + "public/assets/js/echarts/"
        }
    }), require(["echarts", "echarts/chart/bar", "echarts/chart/chord", "echarts/chart/eventRiver", "echarts/chart/force", "echarts/chart/funnel", "echarts/chart/gauge", "echarts/chart/heatmap", "echarts/chart/k", "echarts/chart/line", "echarts/chart/map", "echarts/chart/pie", "echarts/chart/radar", "echarts/chart/scatter", "echarts/chart/tree", "echarts/chart/treemap", "echarts/chart/venn", "echarts/chart/wordCloud"], function(a) {
        
        var c = a.init(document.getElementById("echarts_line"));
        c.setOption({
            title: {
                text: "",
                subtext: ""
            },
            tooltip: {
                trigger: "axis"
            },
            legend: {
                data: ["EMKL", "Container", "UOI", "CY Belawan"]
            },
            toolbox: {
                show: !0,
                orient: "vertical",
                feature: {
                    mark: {
                        show: !0
                    },
                    dataView: {
                        show: !0,
                        readOnly: !1
                    },
                    magicType: {
                        show: !0,
                        type: ["line", "bar"]
                    },
                    restore: {
                        show: !0
                    },
                    saveAsImage: {
                        show: !0
                    }
                }
            },
            calculable: !0,
            xAxis: [{
                type: "category",
                boundaryGap: !1,
                data: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"]
            }],
            yAxis: [{
                type: "value",
                axisLabel: {
                    formatter: "{value} %"
                }
            }],
            series: [
            {
                name: "EMKL",
                type: "line",
                data: [8, 3, 13, 15, 11, 12, 10, 2, 1, 4, 3, 2],
                markPoint: {
                    data: [{
                        name: "Lowest",
                        value: -2,
                        xAxis: 1,
                        yAxis: -1.5
                    }]
                },
                markLine: {
                    data: [{
                        type: "average",
                        name: "Mean"
                    }]
                }
            }, 
            {
                name: "Container",
                type: "line",
                data: [1, 2, 1, 4, 2, 3, 0, 1, 2, 1, 4, 2],
                markPoint: {
                    data: [{
                        name: "Lowest",
                        value: -2,
                        xAxis: 1,
                        yAxis: -1.5
                    }]
                },
                markLine: {
                    data: [{
                        type: "average",
                        name: "Mean"
                    }]
                }
            },
            {
                name: "UOI",
                type: "line",
                data: [2, 10, 13, 5, 11, 4, 10, 2, 8, 9, 3, 1],
                markPoint: {
                    data: [{
                        name: "Lowest",
                        value: -2,
                        xAxis: 1,
                        yAxis: -1.5
                    }]
                },
                markLine: {
                    data: [{
                        type: "average",
                        name: "Mean"
                    }]
                }
            }, 
            {
                name: "CY Belawan",
                type: "line",
                data: [1, 2, 1, 4, 2, 3, 0, 1, 7, 5, 4, 1],
                markPoint: {
                    data: [{
                        name: "Lowest",
                        value: -2,
                        xAxis: 1,
                        yAxis: -1.5
                    }]
                },
                markLine: {
                    data: [{
                        type: "average",
                        name: "Mean"
                    }]
                }
            }]
        });
        
    })
});