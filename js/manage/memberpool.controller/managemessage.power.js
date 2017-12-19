$(function(){
})
//显示年份列表
function showyear(){
    $('#checkthisyear').show();
}
//显示月份列表
function showmounth(){
    $('#checkthismounth').show();
}
//隐藏下拉菜单
function thisHide(){
    $('#checkthisyear').hide();
    $('#checkthismounth').hide();
}

//ajax传送年份
function chooseyear(){
   var year = $("#thisyear").find("option:selected").val();
    $('#list_year').html(year);
   var mounth = $('#list_mounth').html();
   var url = MM_WAGE_LIST_CHANGE;
    $.post(url,{year:year,mounth:mounth},function(data){
       // alert(data);
        var obj = (new Function("return " + data))();
        $('#week').val(obj.week);
        $('#new').val(obj.new);
        $('#drop').val(obj.drop);
        $('#visit').val(obj.visit);
        $('#deduct').val(obj.deduct);
        $('#basewage').val(obj.basewage);
        $('#total').val(obj.total);
    })
    var type = 1;
    var isshow_week = $('#week_task_isshow').attr('isshow');
    var isshow_task = $('#task_new_isshow').attr('isshow');
    if(isshow_week==1){
        showweek();
    }else if(isshow_task==1){
        showtaskmsg(type);
    }
}
//ajax传送月份
function choosemounth(){
    var mounth = $("#thismounth").find("option:selected").val();
    $('#list_mounth').html(mounth);
    var year = $('#list_year').html();
    var url = MM_WAGE_LIST_CHANGE;
    $.post(url,{year:year,mounth:mounth},function(data){
      //  alert(data);
        var obj = (new Function("return " + data))();
        $('#week').val(obj.week);
        $('#new').val(obj.new);
        $('#drop').val(obj.drop);
        $('#visit').val(obj.visit);
        $('#deduct').val(obj.deduct);
        $('#basewage').val(obj.basewage);
        $('#basewages').val(obj.com);
        $('#total').val(obj.total);
    })
    var type = 1;
    var isshow_week = $('#week_task_isshow').attr('isshow');
    var isshow_task = $('#task_new_isshow').attr('isshow');
    if(isshow_week==1){
        showweek();
    }else if(isshow_task==1){
        showtaskmsg(type);
    }


}
//显示周任务
function showweek(){
  //  $('#week_task_isshow').hide();
    $('#task_new_isshow').hide();
    $('#task_new_isshow').attr('isshow',0);
    var mounth  = $('#list_mounth').html();
    var year    = $('#list_year').html();
    var url = MM_WEEK_TASK_EARNINGS;
    var url2 = MM_WAGE_LMSG_IST;
    var th = '';
    var thcon = '';
    var askcont = '';
    var sacle = '';
    var pay = '';
    $.post(url,{year:year,mounth:mounth},function(data){
            if (data == 0) {
                $('#week_task_isshow').hide();
                $('#task_new_isshow').hide();
                asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
                return false;
            } else{
                $('#week_task_isshow').show();
                $('#week_task_isshow').attr('isshow',1);
                var obj = (new Function("return " + data))();
                for(var i=0;i<obj.length;i++){
                    var createtime=obj[i].createtime;
                    var endtime=obj[i].endtime;
                    var ctime=obj[i].ctime;
                    var etime=obj[i].etime;
                    var percent=obj[i].percent;
                    var concount=obj[i].concount;
                    var askcount=obj[i].askcount;
                    var payback=obj[i].payback;
                    var role=obj[i].role;

                    th += '<th>'+
                            '<span class="label label-info" style="margin-top:5px;">'+
                        		        '<a target="_blank"  style="color:white" href="'+url2+'?createtime='+createtime+'">'+
                                            ctime
                                        +'</a>'+
                                        '至'+
                                       '<a  target="_blank" style="color:white" href="'+url2+'?endtime='+endtime+'">'+
                                            etime
                                        +'</a>'+
                            '</span>'+
                          '</td>';
                    thcon += '<td>'+
                        '<span class="label label-info" style="margin-top:5px;">'+
                        '<a style="color:white" href="#" value = "4-28">有效任务数量：</a>'+
                        '<a style="color:white" href="#" value = "4-28">'+concount+'</a>'+
                        '</span>'+
                        '</td>';
                    askcont += '<td>'+
                        '<span class="label label-info" style="margin-top:5px;">'+
                        '<a style="color:white" href="#" value = "4-28">申请任务数量：</a>'+
                        '<a style="color:white" href="#" value = "4-28">'+askcount+'</a>'+
                        '</span>'+
                        '</td>';

                    sacle += '<td>'+
                                '<span class="label label-info" style="margin-top:5px;">'+
                                '<a style="color:white" href="#" value = "4-28">合格率：</a>'+
                                '<a style="color:white" href="#" value = "4-28">'+percent+'%</a>'+
                                '</span>'+
                           '</td>';
                    pay += '<td>'+
                        '<span class="label label-info" style="margin-top:5px;">'+
                        '<a style="color:white" href="#" value = "4-28">周任务收益：</a>'+
                        '<a style="color:white" href="#" value = "4-28">'+payback+'</a>'+
                        '</span>'+
                        '</td>';

                }
                $('#week_time_list').html(th);
                $('#week_concount_list').html(thcon);
                $('#week_askcont_list').html(askcont);
                $('#week_scale_list').html(sacle);
                $('#week_payback_list').html(pay);

            }
    });
}
function checkweek(week,createtime,endtime){
    var week_type = '';
    var checkweek = '';
    if(week==1){
        week_type = 'endtime';
        checkweek = createtime;
    }else if(week==3){
        week_type = 'createtime';
        checkweek = endtime;
    }

    var url = MM_WAGE_LMSG_IST;
    location.href = url+'?'+week_type+'='+checkweek;
}

function showtaskmsg(type){

    var type = type;
    $('#week_task_isshow').hide();
    $('#week_task_isshow').attr('isshow',0);
    if(type==1){

        $('#show_title').html('新用户任务任务导航');
    }else if(type==2){

        $('#show_title').html('降量任务任务导航');
    }else if(type==5){

        $('#show_title').html('回访任务任务导航');
    }


    var mounth  = $('#list_mounth').html();
    var year    = $('#list_year').html();
    var tr = '';
    var url = MM_TASK_NEW_MSG;
    $.post(url,{mounth:mounth,year:year,type:type},function(data){
      //  alert(data);
        if (data == 0) {
            $('#week_task_isshow').hide();
            $('#task_new_isshow').hide();
            asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
            return false;
        } else{
                $('#task_new_isshow').show();
                $('#task_new_isshow').attr('isshow',1);
            tr += '<tr>'+
                    '<th>用户名</th>'+
                    '<th>申请/发布时间</th>'+
                    '<th>任务上报时间</th>'+
                    '<th>任务收益</th>'+
                    '<th>当时权限等级</th>'+
                    '<th>用户信息</th>'+
                   '</tr>';

            var obj = (new Function("return " + data))();
            for(var i=0;i<obj.length;i++){
                var username=obj[i].username;
                var createtime=obj[i].createtime;
                var endtime=obj[i].endtime;
                var ctime=obj[i].ctime;
                var etime=obj[i].etime;
                var pay_back=obj[i].payback;
                var power=obj[i].power;
                var mid=obj[i].mid;

                tr += '<tr>'+
                        '<td>'+username+'</td>'+
                        '<td>'+ctime+'</td>'+
                        '<td>'+etime+'</td>'+
                        '<td>'+pay_back+'</td>'+
                        '<td>'+power+'</td>'+
                        '<td>'+
                            '<i style = "cursor:pointer"  class="glyphicon glyphicon-search" onclick = "show('+mid+')">'+
                            '<span id="clickst"></span></i>'+
                        '</td>'+
                     '</tr>';
            }
            $('#Task_new_msg').html(tr);
        }
    });
}
