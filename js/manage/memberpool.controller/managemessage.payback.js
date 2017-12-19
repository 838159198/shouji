$(function () {
    $("#content").dialog({autoOpen: false, width: 350, height: 200, modal: true});
    $("#real_wage_count").dialog({autoOpen: false, width: 550, height: 300, modal: true});
});

function thisMounth() {
    var this_mounth = $("#thismounth option:selected").val();
    var url = MP_PAYBACK
    var show_id = $('#show_id').val();
    location.href = url + '/id/' + show_id + '/mounth/' + this_mounth;
}

function searchMyVtask(kfid){
    var kfid1=kfid;
    var start1 = $('#starttime').val();
    var end1 = $('#endtime').val();
    var url1 = MP_TASK_VTASK;
    var result = "";
    if(start1==''){
        asyncbox.alert('请选择有效回访开始时间','asyncbox_Title');
        return false;
    }
    if(end1==''){
        asyncbox.alert('请选择有效回访结束时间','asyncbox_Title');
        return false;
    }
    $.post(url1,{start1:start1,end1:end1,kfid1:kfid1},function(data){
        if(data)
        {
            $("#countst").text(data);
        }
        else
        {
            alert("无数据显示!");
        }
     })

 }

function sendWage(id) {
    var uid = id;
    var base_wage = $('#base_wage').val();
    //工资比例
    var scale = $('#scale').val();
    //新用户任务收益
    var task_payback_new = $('#task_payback_new').val();
    //降量任务收益
    var task_payback_drop = $('#task_payback_drop').val();
    //周任务收益
    var week_payback = $('#week_payback').val();
    //回访任务收益
    var visit_payback = $('#visit_payback').val();
    //主管权限客服上报任务的提成10%
    var com = $('#com').val();
    //满勤奖金
    var bonus = $('#bonus').val();
    //扣款金额
    var deduct = $('#deduct').val();
    //应该支付
    var should_pay = $('#should_pay').val();
    //实际支付
    var total_pay = $('#total_pay').val();
    //查看月份
    var check_mounth = $('#check_mounth').val();

    var url = MA_MESSAGE_WAGE_COUNT;
    asyncbox.confirm(question_before_action.MAKE_SURE_SEND_WAGE, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {uid: uid, base_wage: base_wage, scale: scale, task_payback_new: task_payback_new,
                    task_payback_drop:task_payback_drop,visit_payback:visit_payback,
                    week_payback: week_payback,bonus: bonus, deduct: deduct,
                    should_pay: should_pay, total_pay: total_pay,com:com,
                    check_mounth: check_mounth}, function (data) {
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            } else if (obj.msg == data_back.DATA_ERROR_NO_PRO) {
                asyncbox.alert(data_back_msg.DATA_ERROR_MOUNTH, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_ERROR_AEXISTS) {
                asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);
            }else if (obj.msg == data_back.DATA_ERROR_MSG_EMPTY) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NO_SCALE, title.TITLE_ERROR);
            }
        });
    })
}
function countBaseWage() {
    var role = $("#managerole").val();

    var scale = $("#scale").val();
    var base_wage = $("#base_wage").val();
    if ((scale == '') || base_wage == '') {
        asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
        return false;
    }
    if ((scale >100)) {
        asyncbox.alert('输入数字 1至100 ', title.TITLE_ERROR);
        return false;
    }
    scale = parseInt($("#scale").val());             //提成比例
    $('#bonus').val(0);

    var k=4;
    for(var i=1;i<=100;i++){
        i=i+k;
        if(i==100){
            i==i;
        }

        if((scale==i)&&(scale == 100)){
        $('#bonus').val(100);                       //如果满勤100分，满勤奖金100元
        scale = parseInt(100);
        }else if((scale >= i-k)&&(scale <= i)){          //如果满勤96-99分，无满勤奖金
            scale = parseInt(i);
        }

    }

    base_wage = parseInt($("#base_wage").val());     //基本工资
    var resault = base_wage * (scale / 100);         //实际得到的基本工资

    $('#deduct').val(base_wage - resault);
    $('#rel_deduct').html(base_wage - resault);

    $('#base_wage_now').val(base_wage);
    $('#base_scale_now').val(scale + '%');
    $('#real_Wage_get').val(resault);

    var modal = $("#real_wage_count");
    modal.dialog("open");

    $('#to_send').click(function () {
        $('#rel_get').show();
        $('#countBaseWage').val(resault);
        if(role!=7){
            var task_payback_new = parseInt($('#task_payback_new').val());
            var task_payback_drop = parseInt($('#task_payback_drop').val());
            var week_payback = parseInt($('#week_payback').val());
            var bonus = parseInt($('#bonus').val());
            var deduct = parseInt($('#deduct').val());
            var total_wage = resault + task_payback_new +task_payback_drop + week_payback + bonus;
            var real_pay = total_wage - deduct;
            $('#task_pay_back').html(task_payback_new+ task_payback_drop+ week_payback + '元');
        }else if(role==7){
          //  var task_payback_new = parseInt($('#task_payback_new').val());
          //  var task_payback_drop = parseInt($('#task_payback_drop').val());
            var visit_payback = parseInt($('#visit_payback').val());
            var bonus = parseInt($('#bonus').val());
            var deduct = parseInt($('#deduct').val());
            var total_wage = resault + visit_payback + bonus;
            var real_pay = total_wage - deduct;
            $('#task_pay_back').html(visit_payback + '元');
        }
            $('#total_pay').val(total_wage);
            $('#should_pay').val(total_wage);
            $('#should_pay_count').html(base_wage + '元');
            $('#my_bonus').html(bonus+'元');

            $('#real_pay').html(total_wage + '元');

        modal.dialog("close");
    })
}

$("#week_payback").focus(function(){
    $("#week_task_payback").show('fast');
})
$("#week_payback").blur(function(){
    $("#week_task_payback").hide('fast');
})
