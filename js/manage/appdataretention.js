/**
 * Created by Peng on 2017/1/19.
 */
$(function () {

    $.fn.datetimepicker.dates['zh-CN'] = {
        days:       ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六","星期日"],
        daysShort:  ["日", "一", "二", "三", "四", "五", "六","日"],
        daysMin:    ["日", "一", "二", "三", "四", "五", "六","日"],
        months:     ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月","十二月"],
        monthsShort:  ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"],
        meridiem:    ["上午", "下午"],
        //suffix:      ["st", "nd", "rd", "th"],
        today:       "今天"
    };


    var now = new Date();
    var date = new Date(now.getTime() - 2 * 24 * 3600 * 1000);
    var ddd=dateChange(date);
    //日期控件
    $('.form_date').datetimepicker({
        language:'zh-CN',
        weekStart:1,
        todayBtn:0,
        autoclose:1,
        todayHighlight:1,
        startView:2,
        minView:2,
        forceParse:0,
        format: 'yyyy-mm-dd',
        endDate: ddd
    });
    $('.form_date').datetimepicker('update', date);

    $('#type_yw').change(function () {
        var pathname = $(this).val();
        if (pathname == 'qxzyw'){
            $('#type_fz').val('qxzyw');
            $("#type_md5_version").val('qxzyw');
            $("#type_md5_version>option").not(":first").remove();
            return;
        }

        var fz = $('#type_fz').val();
        if (fz == 'qxzyw'){
            return;
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/dhadmin/tongji/YWajax",
            data: {
                'pathname':pathname,
                'fz':fz
            },
            success: function(data){
                if (data.val == "empty"){
                    $('#type_yw').val('qxzyw');
                    $('#type_fz').val('qxzyw');
                    $("#md5_version").empty();
                    alert(data.message);
                }else {
                    var arr = data.val;
                    $("#md5_version").empty();
                    for (var i = 0;i<arr.length;i++){
                        var inp = "<input class='check' type='checkbox' value='"+arr[i]+"'>"+arr[i]+"<br>";
                        $('#md5_version').append(inp);
                        $(".pop").show();
                        $(".select-all").text('全选');
                    }
                    // $("#type_md5_version>option").not(":first").remove();
                    // for (var i = 0;i<arr.length;i++){
                    //     var opt = "<option value='"+arr[i]+"'>"+arr[i]+"</option>";
                    //     $('#type_md5_version').append(opt);
                    // }
                }
            },
            error : function() {
            }
        })
    })


    // 分组
    $('#type_fz').change(function () {
        var fz = $(this).val();
        if (fz == 'qxzyw'){
            $("#type_md5_version>option").not(":first").remove();
            return;
        }
        var pathname =  $('#type_yw').val();
        if(pathname == 'qxzyw'){
            alert('请先选择业务');
            $(this).val('qxzyw');
            return;
        }
        fz = fz.replace('fz','');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/dhadmin/tongji/FZajax",
            data: {
                'pathname':pathname,
                'fz':fz
            },
            success: function(data){
                if (data.val == "empty"){
                    $('#type_fz').val('qxzyw');
                    $("#md5_version").empty();
                    alert(data.message);
                }else {
                    var arr = data.val;
                    // $("#type_md5_version>option").not(":first").remove();
                    $("#md5_version").empty();
                    for (var i = 0;i<arr.length;i++){
                        var inp = "<input class='check' type='checkbox' value='"+arr[i]+"'>"+arr[i]+"<br>";
                        $('#md5_version').append(inp);
                        $(".pop").show();
                        $(".select-all").text('全选');
                    }
                }
            },
            error : function() {
            }
        })
    });

    // 数据处理
    $('.btn-deal').click(function () {
        var dateS = $('.input-small-date').val();
        var yw = $('#type_yw').val();
        var fz = $('#type_fz').val();
        var zq = $('#type_zq').val();
        var md5_array = new Array();  //定义数组
        $('#type_md5_version option').each(function () {
            md5_array.push($(this).val());
        });



        if ($.trim(dateS) == ''){
            alert('日期不能为空');
            return;
        }else if ($.trim(yw) =='qxzyw'){
            alert('业务不能为空');
            return;
        }else if ($.trim(fz) =='qxzyw'){
            alert('分组不能为空');
            return;
        }else if (md5_array.length ==1){
            alert('MD5和版本号不能为空');
            return;
        }
        $('.btn-deal').text('处理中...');
        var arr = {};
        arr['date']=dateS;
        arr['yw']=yw;
        arr['zq']=zq;
        arr['md5_version']=md5_array;
        

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/dhadmin/tongji/DealData",
            data: {
                'data':arr
            },
            success: function(data){
                if (data.val>0){
                    $('.btn-deal').hide();
                    $('.btn-deal').text('处理数据');
                    $('.btn-retention').show();
                }else if(data.val<=0) {
                    $('.btn-deal').text('处理数据');
                    alert('查询数据为空,请重新选择md5');
                    return;
                }
            },
            error : function() {
            }
            
        })
    })
    
   // 搜索数据
    $('.btn-retention').click(function () {
        $('.retention_table table thead tr').children().remove();
        $('.retention_table table tbody ').children().remove();

        var dateS = $('.input-small-date').val();
        var yw = $('#type_yw').val();
        var zq = $('#type_zq').val();
        var fg = $('#type_fg').val();
        var fz = $('#type_fz').val();
        //var md5_version = $('#type_md5_version option').val();
        var md5_array = new Array();  //定义数组
        $('#type_md5_version option').each(function () {
            md5_array.push($(this).val());
        });

        var username = $('.input-username').val();
        if ($.trim(dateS) == ''){
            alert('日期不能为空');
            return;
        }else if ($.trim(yw) =='qxzyw'){
            alert('业务不能为空');
            return;
        }else if ($.trim(fz) =='qxzyw'){
            alert('分组不能为空');
            return;
        }else if (md5_array.length ==1){
            alert('MD5和版本号不能为空');
            return;
        }else if($(".all-user").is(':checked')==true && $.trim(username)!=''){
            alert('用户名必须为空');
            return;
        }else if ($(".all-user").is(':checked')==true && $(".check-sign").is(':checked')==true){
            alert('不可选中单日');
            return;
        }
        if ($.trim(username)==''){
            username='';
        }
        var arr = {};
        arr['date']=dateS;
        arr['yw']=yw;
        arr['zq']=zq;
        arr['fg']=fg;
        arr['fz']=fz.replace('fz','');
        arr['md5_version']=md5_array;
        arr['username']=username;
        arr['check'] = $(".check-sign").is(':checked');
        arr['alluser'] = $(".all-user").is(':checked');
        if ($(".all-user").is(':checked')==false) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/dhadmin/tongji/CXajax",
                data: {
                    'data': arr
                },
                success: function (data) {
                    if (data.val == "fail") {
                        alert(data.message);
                        return;
                    }
                    var arr = data.val;
                    var dayzq = data.valzq;
                    var fg = data.valfg;
                    var dateStart = data.valDate;
                    for (var i = 0; i < dayzq; i++) {
                        var th;
                        if (i == 0) {
                            th = "<th>日期</th><th>第" + (i + 1) + "天</th>";
                        } else if (i == dayzq - 1) {
                            th = "<th>第" + (i + 1) + "天</th><th>日均</th>";
                        } else {
                            th = "<th>第" + (i + 1) + "天</th>";
                        }
                        $('.retention_table table thead tr').append(th);
                        var tr;
                        var da = dateStart + i * 24 * 3600;// 每天的时间戳

                        if ($(".check-sign").is(':checked') && i != 0) {

                            tr = "<tr style='display: none' id=" + da + "></tr>";
                        } else {
                            tr = "<tr id=" + da + "></tr>";
                        }
                        $('.retention_table table tbody ').append(tr);
                        var db = new Date(da * 1000);    //根据时间戳生成的时间对象
                        var dat = dateChange(db);
                        var idd = "#" + da;
                        $(idd).append("<td>" + dat + "</td>");
                        if (arr[da] == undefined) {
                            for (var k = 0; k < dayzq; k++) {
                                var aa = "<td>" + '' + "</td>";
                                $(idd).append(aa);
                            }
                        } else {
                            var maxindex;
                            $.each(arr[da], function (index, doms) {
                                var bb;
                                if (parseInt(doms) == 0) {
                                    bb = "<td>" + '' + "</td>";
                                } else {
                                    if (parseInt(doms) < parseInt(fg)) {
                                        bb = "<td style='color: red'>" + doms + "%</td>";
                                    } else {
                                        bb = "<td>" + doms + "%</td>";
                                    }
                                }

                                $(idd).append(bb);
                                if (index != 'p') {
                                    maxindex = index;
                                }

                            });
                            var pp = dayzq - maxindex;
                            for (var j = 0; j < pp; j++) {
                                var cc = "<td>" + ' ' + "</td>";
                                $(idd).append(cc);
                            }
                        }
                    }

                    var s = new Date(dateStart * 1000);
                    var ss = dateChange(s);
                    if (arr[dateStart] != undefined) {
                        zhuzhuangtu(dayzq, arr[dateStart], ss);
                    } else {
                        $('.zzt').children().remove();
                    }

                    $('.btn-retention').hide();
                    $('.btn-deal').show();

                },
                error: function () {
                }
            })
        }else {
            $("#container").empty();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/dhadmin/tongji/AllUserAjax",
                data: {
                    'data': arr
                },
                success: function (data) {
                    if (data.val == "fail") {
                        alert(data.message);
                        return;
                    }

                    var arr = data.val;
                    var dayzq = data.valzq;
                    var fg = data.valfg;
                    var d = new Date((data.valDate)*1000);
                    var dateStart = dateChange(d);
                    
                    for (var i = 0; i < dayzq; i++) {
                        var th;
                        if (i == 0) {
                            th = "<th>用户名</th><th>日期</th><th>第" + (i + 1) + "天</th>";
                        } else if (i == dayzq - 1) {
                            th = "<th>第" + (i + 1) + "天</th><th>日均</th>";
                        } else {
                            th = "<th>第" + (i + 1) + "天</th>";
                        }
                        $('.retention_table table thead tr').append(th);
                    }


                    var k=1;
                    $.each(arr, function (index, doms) {
                        var td;
                        td = "<tr id='"+k+"'><td>" + doms['name'] + "</td><td>"+dateStart+"</td>";
                        for($j=1;$j<=dayzq;$j++){
                           if (parseInt(doms[$j])==0){
                               td += "<td>" + '' + "</td>";
                           }else{
                               if (parseInt(doms[$j]) < parseInt(fg)) {
                                   td += "<td style='color: red'>" + doms[$j] + "%</td>";
                               } else {
                                   td += "<td>" + doms[$j] + "%</td>";
                               }
                           }
                        }
                        if (parseInt(doms['p']) < parseInt(fg)) {
                            td += "<td style='color: red'>" + doms['p'] + "%</td>";
                        } else {
                            td += "<td>" + doms['p'] + "%</td></tr>";
                        }
                        $('.retention_table table tbody ').append(td);
                        k++;
                    })

                    $('.btn-retention').hide();
                    $('.btn-deal').show();

                },
                error: function () {
                }
            })
        }
    });
});


//日期转换方法
function dateChange(date) {
    var ddd;
    var day;
    if (date.getDate()<10){
        day = '0'+date.getDate();
    }else {
        day = date.getDate();
    }
    if(date.getMonth()+1<10){
        ddd= date.getFullYear()+'-0'+(date.getMonth()+01)+'-'+day;
    }else {
        ddd= date.getFullYear()+'-'+(date.getMonth()+1)+'-'+day;
    }
    return ddd;
}


function zhuzhuangtu(zq,dataarr,ss) {
    var arr1=new Array();
    var arr2=new Array();
    for (var b = 1;b<=zq;b++){
        arr1[b-1] ='第'+b+'天,';
    }
    var maxindex2;
    $.each(dataarr,function (index,dom) {
        arr2[index-1] = dom;
        maxindex2 = index;
    });
    Highcharts.chart('container', {
        series: [{ //数据列选项
            name: '留存率',
            shared: false,
            color: '#2F7ED8',
//                showInLegend: false,//去掉图例
            //显示数据列的名称
            data: arr2 ,//数组或JSON，如：data:[0, 5, 3, 5]，或data: [{name: 'Point 1',y: 0}, {name: 'Point 2',y: 5}]
        }],
        chart: {
            type: 'column'
        },
        title: {
            text: ss+'留存柱状图',
            style:{
                color:"#000",
                fontWeight:"bold",
                fontSize:"30px",
            }
        },
        xAxis: { //X轴选项
            categories: arr1 //设置X轴分类名称

        },
        yAxis: {
            min: 0,//Y轴最小值
            max:100,
            tickInterval:10,
            labels: {
                formatter: function() { //格式化标签名称
                    return this.value + '%';
                },
                style: {
                    color: '#89A54E' //设置标签颜色
                }
            },
            allowDecimals: false,
            title: {
                text: '留存率(%)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:2px">{series.name}: </td>' + '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        credits: {
            text: '',
            href: ''
        }
    });
}
