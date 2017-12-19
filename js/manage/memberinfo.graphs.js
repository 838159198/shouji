$(function () {
    var dateRegExp = /\d{4}-\d{2}-\d{2}/;
    $("#searchgraphs").click(function () {
        var first = $("#datefirst").val();
        var last = $("#datelast").val();
        var id = $("#searchid").val();

        if (first == '' || !dateRegExp.test(first)) {
            $("#datefirst").val("请选择开始时间");
            return;
        }
        if (last == '' || !dateRegExp.test(last)) {
            $("#datelast").val("请选择结束时间");
            return;
        }

        document.location.href = thisUrl + "/?uid=" + id + "&firstDate=" + first + "&lastDate=" + last;
    });

    if (json) {
        show(json, types,false);

    }
});

/**
 * @param data
 * @param type 是否弹出dialog
 */
function show(data,types, type) {
    if (type) {
        $("#searchdate").show();
        $("#dialog").dialog({ modal: true, width: 1000, height: 500});
    }

    //var categories = [] , ucllq = [],xfsrf = [],bdllq = [],ppzs = [],baidu = [],kyls = [],szdh = [],ysdq360 = [],sdxw = [],zhwnl = [],wnl = [],ayd = [],zssq = [],tq = [],kxxxl = [],dzdp = [],jd = [],bfyy = [],zkns = [],azllq = [],zmtq = [],yysd = [],wdj = [],txxw = [],txsp = [],wzdq = [],yyb = [],gddt = [],yyzx = [],llq = [],zysck = [],aqws360 = [],bdsjzs = [],jjddz = [],ysdq2345 = [],sjzs360 = [],meituan = [],shsp = [],jrtt = [],kwyy = [],llq360 = [],jyyxzx = [],bddt = [];
    //
    //$.each(data, function (i, v) {
    //
    //    var utc = Date.UTC(v.y, v.m - 1, v.d);
    //    //for(var key in v){
    //    //    if( key!='d' && key!='m'&& key!='y'){
    //    //        var array = [];
    //    //        var arr=[];
    //    //
    //    //        array.push(utc, parseFloat(v.key));
    //    //        arr[key]=array;
    //    //    }
    //    //}
    //
    //    //加if判断 去掉没有收入的业务数据
    //    if(parseFloat(v.ucllq)>0){
    //        ucllq.push([utc, parseFloat(v.ucllq)]);
    //    }
    //    if(parseFloat(v.xfsrf)>0){
    //        xfsrf.push([utc, parseFloat(v.xfsrf)]);
    //    }
    //    if(parseFloat(v.bdllq)>0){
    //        bdllq.push([utc, parseFloat(v.bdllq)]);
    //    }
    //    if(parseFloat(v.ppzs)>0){
    //        ppzs.push([utc, parseFloat(v.ppzs)]);
    //    }
    //    if(parseFloat(v.baidu)>0){
    //        baidu.push([utc, parseFloat(v.baidu)]);
    //    }
    //    if(parseFloat(v.kyls)>0){
    //        kyls.push([utc, parseFloat(v.kyls)]);
    //    }
    //    if(parseFloat(v.szdh)>0){
    //        szdh.push([utc, parseFloat(v.szdh)]);
    //    }
    //    if(parseFloat(v.sdxw)>0){
    //        sdxw.push([utc, parseFloat(v.sdxw)]);
    //    }
    //    if(parseFloat(v.zhwnl)>0){
    //        zhwnl.push([utc, parseFloat(v.zhwnl)]);
    //    }
    //    if(parseFloat(v.wnl)>0){
    //        wnl.push([utc, parseFloat(v.wnl)]);
    //    }
    //    if(parseFloat(v.ayd)>0){
    //        ayd.push([utc, parseFloat(v.ayd)]);
    //    }
    //    if(parseFloat(v.zssq)>0){
    //        zssq.push([utc, parseFloat(v.zssq)]);
    //    }
    //    if(parseFloat(v.tq)>0){
    //        tq.push([utc, parseFloat(v.tq)]);
    //    }
    //    if(parseFloat(v.kxxxl)>0){
    //        kxxxl.push([utc, parseFloat(v.kxxxl)]);
    //    }
    //    if(parseFloat(v.dzdp)>0){
    //        dzdp.push([utc, parseFloat(v.dzdp)]);
    //    }
    //    if(parseFloat(v.jd)>0){
    //        jd.push([utc, parseFloat(v.jd)]);
    //    }
    //    if(parseFloat(v.bfyy)>0){
    //        bfyy.push([utc, parseFloat(v.bfyy)]);
    //    }
    //    if(parseFloat(v.zkns)>0){
    //        zkns.push([utc, parseFloat(v.zkns)]);
    //    }
    //    if(parseFloat(v.azllq)>0){
    //        azllq.push([utc, parseFloat(v.azllq)]);
    //    }
    //    if(parseFloat(v.zmtq)>0){
    //        zmtq.push([utc, parseFloat(v.zmtq)]);
    //    }
    //    if(parseFloat(v.yysd)>0){
    //        yysd.push([utc, parseFloat(v.yysd)]);
    //    }
    //    if(parseFloat(v.wdj)>0){
    //        wdj.push([utc, parseFloat(v.wdj)]);
    //    }
    //    if(parseFloat(v.yyb)>0){
    //        yyb.push([utc, parseFloat(v.yyb)]);
    //    }
    //
    //    if(parseFloat(v.yyzx)>0){
    //        yyzx.push([utc, parseFloat(v.yyzx)]);
    //    }
    //    if(parseFloat(v.gddt)>0){
    //        gddt.push([utc, parseFloat(v.gddt)]);
    //    }
    //    if(parseFloat(v.llq)>0){
    //        llq.push([utc, parseFloat(v.llq)]);
    //    }
    //    if(parseFloat(v.zysck)>0){
    //        zysck.push([utc, parseFloat(v.zysck)]);
    //    }
    //    if(parseFloat(v.aqws360)>0){
    //        aqws360.push([utc, parseFloat(v.aqws360)]);
    //    }
    //    if(parseFloat(v.bdsjzs)>0){
    //        bdsjzs.push([utc, parseFloat(v.bdsjzs)]);
    //    }
    //    if(parseFloat(v.jjddz)>0){
    //        jjddz.push([utc, parseFloat(v.jjddz)]);
    //    }
    //    if(parseFloat(v.ysdq2345)>0){
    //        ysdq2345.push([utc, parseFloat(v.ysdq2345)]);
    //    }
    //    if(parseFloat(v.sjzs360)>0){
    //        sjzs360.push([utc, parseFloat(v.sjzs360)]);
    //    }
    //
    //    if(parseFloat(v.meituan)>0){
    //        meituan.push([utc, parseFloat(v.meituan)]);
    //    }
    //    if(parseFloat(v.shsp)>0){
    //        shsp.push([utc, parseFloat(v.shsp)]);
    //    }
    //    if(parseFloat(v.jrtt)>0){
    //        jrtt.push([utc, parseFloat(v.jrtt)]);
    //    }
    //    if(parseFloat(v.kwyy)>0){
    //        kwyy.push([utc, parseFloat(v.kwyy)]);
    //    }
    //    if(parseFloat(v.jyyxzx)>0){
    //        jyyxzx.push([utc, parseFloat(v.jyyxzx)]);
    //    }
    //    if(parseFloat(v.llq360)>0){
    //        llq360.push([utc, parseFloat(v.llq360)]);
    //    }
    //    if(parseFloat(v.bddt)>0){
    //        bddt.push([utc, parseFloat(v.bddt)]);
    //    }
    //    if(parseFloat(v.wzdq)>0){
    //        wzdq.push([utc, parseFloat(v.wzdq)]);
    //    }
    //    if(parseFloat(v.txxw)>0){
    //        txxw.push([utc, parseFloat(v.txxw)]);
    //    }
    //    if(parseFloat(v.txsp)>0){
    //        txsp.push([utc, parseFloat(v.txsp)]);
    //    }
    //});
    //
    //var bb=[
    //    { name: 'UC浏览器', data: ucllq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '讯飞输入法', data: xfsrf, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '百度浏览器', data: bdllq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: 'PP助手', data: ppzs, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '手机百度', data: baidu, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '酷音铃声', data: kyls, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '神指电话', data: szdh, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '360影视大全', data: ysdq360, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '今日十大新闻', data: sdxw, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '中华万年历', data: zhwnl, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '万年历', data: wnl, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '爱阅读', data: ayd, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '追书神器', data: zssq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '天气', data: tq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '开心消消乐', data: kxxxl, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '大众点评', data: dzdp, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '京东', data: jd, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '暴风影音', data: bfyy, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: 'zaker新闻', data: zkns, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '安卓浏览器', data: azllq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '最美天气', data: zmtq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '应用商店', data: yysd, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '豌豆荚', data: wdj, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '高德地图', data: gddt, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '应用中心', data: yyzx, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '浏览器', data: llq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '卓易市场', data: zysck, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '360安全卫士', data: aqws360, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '百度手机助手', data: bdsjzs, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: 'JJ斗地主', data: jjddz, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '影视大全', data: ysdq2345, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '360手机助手', data: sjzs360, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '美团', data: meituan, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '搜狐视频', data: shsp, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '今日头条', data: jrtt, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '酷我音乐', data: kwyy, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '百度地图', data: bddt, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '九游游戏中心', data: jyyxzx, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '360浏览器', data: llq360, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '应用宝', data: yyb, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '浏览器2', data: wzdq, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '腾讯新闻', data: txxw, type: 'spline', tooltip: { valueDecimals: 2 }},
    //    { name: '腾讯视频', data: txsp, type: 'spline', tooltip: { valueDecimals: 2 }},
    //];

    var chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'highcharts',
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
        yAxis: { title: { text: '元' } },
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
        series: aa
    });
}
