$(function () {
    var dateRegExp = /\d{4}-\d{2}-\d{2}/;
    $("#mf").click(function () {
        var first = $("#datefirst").val();
        var last = $("#datelast").val();
        //var id = $("#searchid").val();

        if (first == '' || !dateRegExp.test(first)) {
            $("#datefirst").val("请选择开始时间");
            return;
        }
        if (last == '' || !dateRegExp.test(last)) {
            $("#datelast").val("请选择结束时间");
            return;
        }
        document.location.href = thisUrl + "/?uid=" + 8888 + "&firstDate=" + first + "&lastDate=" + last;
    });
    if (json) {
        show(json, false);
    }
});

/**
 * @param data
 * @param type 是否弹出dialog
 */
function show(data, type) {
    if (type) {
        $("#container").show();
        $("#dialog").dialog({ modal: true, width: 1000, height: 500});
    }

    var categories = [] , ucllq = [];
    $.each(data, function (i, v) {
        console.log(v);
        var utc = Date.UTC(v.y, v.m - 1, v.d);
        ucllq.push([utc, parseInt(v.counts)]);

    });

    var chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'container',
            type: 'line',
            marginRight: 5,
            marginBottom: 5
        },
        title: { },
        xAxis: {
            tickPixelInterval: 200,
            type: 'datetime',
            labels: {
                formatter: function () {
                    var d = new Date(this.value);
                    return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
                }
            }
        },
        yAxis: { title: { text: '安装数量（个）' } },
        tooltip: {
            xDateFormat: '%Y-%m-%d',
            shared: true,
            crosshairs: true
        },
        rangeSelector: {
            buttons: [
                {type: 'month', count: 1, text: '1月'},
                {type: 'month', count: 3, text: '3月'},
                {type: 'month', count: 6, text: '6月'},
                {type: 'all', text: '全部'}
            ],
            selected: 3,
            inputDateFormat: '%Y-%m-%d'
        },
        credits: {
            position: {
                align: 'right',
                verticalAlign: 'bottom'
            },
            text: 'sutuiapp.com',
            href: 'http://sutuiapp.com'
        },
        series: [
            { name: '安装量', data: ucllq, type: 'line', tooltip: { valueDecimals: 0 }},
        ]
    });
}
