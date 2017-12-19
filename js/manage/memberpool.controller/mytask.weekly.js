function checkweek(week) {
    var week = week;
    var url = MT_WEEKLY;
    var status = STAT;
    location.href = url + '?'+status+'&week=' + week;
}

function weekpaybacklist(c, e, b_c, b_e, da, b_da, sub, con, sal, id, sat, l_sat) {
    var co = '';
    if (con == 0) {
        co = base_parm.VOID;
    } else if (con == 1) {
        co = base_parm.VALID;
    }
    $('#s_to_e').html(c + '  至  ' + e);
    $('#b_s_to_e').html(b_c + '  至  ' + b_e);
    $('#s_at').html(sat);
    $('#l_s_at').html(l_sat)
    $("#s_w_data").val(da + '元');
    $('#b_s_w_data').val(b_da + '元');
    $('#sal').val(sal);
    $('#data').val(da + '元      -    ' + b_da + '元');
    $('#pay_back').val(sub + '元');
    $('#con').html(co);
    $('#wt_id').val(id);
    var modal = $("#weekmsg");
    modal.dialog("open");

}
function continue_week_task() {

    var wt_id = $('#wt_id').val();
    var url = MT_CONTINUE;
    var this_week = 'this_week';
    var next_week = 'next_week';
    asyncbox.confirm(question_before_action.MAKE_SURE_CONTINUE_THIS_TASK, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {wt_id: wt_id}, function (data) {
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR_CEILING) {
                asyncbox.alert(data_back_msg.DATA_ERROR_CEILING, title.TITLE);
                return false;
            } else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_ERROR_AEXISTS) {
                asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                if (obj.week == this_week) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS_ADD_THIS, title.TITLE_SUCCESS);
                    location.reload();
                } else if (obj.week == next_week) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS_ADD_NEXT, title.TITLE_SUCCESS);
                    location.reload();
                }

            }

        })
    })
}

function continue_week_task_show(id) {

    var id = id;
    var url = MT_CONTINUE;
    var this_week = 'this_week';
    var next_week = 'next_week';
    asyncbox.confirm(question_before_action.MAKE_SURE_CONTINUE_THIS_TASK, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {

        $.post(url, {wt_id: id}, function (data) {

            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR_CEILING) {
                asyncbox.alert(data_back_msg.DATA_ERROR_CEILING, title.TITLE);
                return false;
            } else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_ERROR_AEXISTS) {
                asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                if (obj.week == this_week) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS_ADD_THIS, title.TITLE_SUCCESS);
                    location.reload();
                } else if (obj.week == next_week) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS_ADD_NEXT, title.TITLE_SUCCESS);
                    location.reload();
                }

            }

        })
    })
}
/*
function checkAllow(allow) {
    var allow = allow;
    var url = MP_REFUSE;
    location.href = url + '/allow/' + allow;
}*/

function tovisit() {
    var url = MP_VISITE;
    location.href = url
}
function remindlist() {
    var remind = base_parm.DEFAULT_ONE;
    var url = MP_REFUSE;
    location.href = url + '/remind/' + remind;
}
function what_is_it() {
    asyncbox.alert('目标日期：任务结束时间（默认周一）前移两天（默认周六）。</br>对比日期：任务开始时间前移两天（默认周六）。', title.TITLE_ERROR);
}



