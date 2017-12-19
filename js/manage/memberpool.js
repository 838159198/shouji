$(function () {
    $("#highcharts").dialog({autoOpen: false, width: 550, height: 400, modal: true});
    if (json) {
        show(json, false);
    }
});
function showquxian(){
    var modal = $("#highcharts");
    modal.dialog("open");
}
/**
 * @param data
 * @param type 是否弹出dialog
 */
function show(data, type) {

////    if (type) {
////        $("#searchdate").show();
////      //  $("#dialog").dialog({ modal: true, width: 1000, height: 500});
////    }
    var aaaa=[];
    var categories = [] , sgss = [], sgdh = [], sgtp = [], sgts = [], t123 = [], ycgg = [];
    var ttgg = [], zmtb = [], eda = [], ttgg2 = [], jsdh = [], ttgg3 = [], zmtb2 = [], gda = [];
    $.each(data, function (i, v) {
       // var utc = Date.UTC(v.y, v.m - 1, v.d);
        aaaa.push( [i,parseFloat(v.data)]);

        //aaaa.push([utc, parseFloat(v.sgss)]);
//        var utc = Date.UTC(v.y, v.m - 1, v.d);
//        sgss.push([utc, parseFloat(v.sgss)]);
//        sgdh.push([utc, parseFloat(v.sgdh)]);
    });
    //alert(aaaa);
//
//    var chart = new Highcharts.StockChart({
//        chart: {
////            renderTo: 'highcharts',
////            type: 'line',
////            marginRight: 5,
////            marginBottom: 5
//            renderTo: 'highcharts',      //作用div  ID
//            type: 'Column',                      //类型
//            marginRight: 130,
//            marginBottom: 25
//        },
//
//        title: {
//            text: 'Monthly Average Temperature',    //图表标题
//            x: -20 //center
//        },
//        subtitle: {
//            text: 'Source: WorldClimate.com',         //说明
//            x: -20         //margin-left
//        },
////        xAxis: {
////            tickPixelInterval: 200,
////            type: 'datetime',
////            labels: {
////                formatter: function () {
////                    var d = new Date(this.value);
////                    return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
////                }
////            }
////        },
//        xAxis: {
//            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
//        },
//
//       // yAxis: { title: { text: '元' } },
//        yAxis: {     //Y轴
//            title: {
//                text: 'Temperature (°C)'
//            },
//            plotLines: [{          //Y轴开始，宽度，颜色
//                value: 10,
//                width: 10,
//                color: '#808080'
//            }]
//        },
//        tooltip: {  //移到某个坐标点时提示内容
//            xDateFormat: 'bububu',
//            shared: true,
//            crosshairs: true
//        },
////        rangeSelector: {
////            buttons: [
////                {type: 'year', count: 5, text: '5月'},
////                {type: 'year', count: 8, text: '15月'},
////                {type: 'year', count: 10, text: '8月'},
////                {type: 'all', text: '全部'}
////            ],
////            selected: 5,
////            inputDateFormat: '%Y-%m-%d'
////        },
////        legend: {
////            layout: 'vertical',               //实体说明样式，当鼠标移到坐标点上时，出现坐标值，实体指下面的series的                                                             //name值
////            align: 'right',
////            verticalAlign: 'top',
////            x: -10,
////            y: 100,
////            borderWidth: 0
////        },
//        credits: {
//            position: {
//                align: 'right',
//                verticalAlign: 'bottom'
//            },
//            text: 'awangba.com',
//            href: 'http://www.awangba.com'
//        },
////        plotOptions: {        //是不是在图表上直接显示坐标具体值
////            line: {
////                dataLabels: {
////                    enabled: true      //true:显示     false:不显示
////                },
////                enableMouseTracking: false      //上面   legend  效果
////            }
////        },
//        series: [
//           {name:'zhe s sha',data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
//                color: 'blue',
//               type: 'spline',
//                zIndex: 2
//          //     tooltip: { valueDecimals: 2 }
//           },
//            { name:'byebyebeautiful',
//                data: aaaa,
//                type: 'spline'
//          //      tooltip: { valueDecimals: 2 }
//            }
////            { name: '搜狗搜索', data: sgss, type: 'spline', tooltip: { valueDecimals: 2 }},
////            { name: '搜狗导航', data: sgdh, type: 'spline', tooltip: { valueDecimals: 2 }},
////            { name: '搜狗天平', data: sgtp, type: 'spline', tooltip: { valueDecimals: 2 }},
//        ]
//    });
}
