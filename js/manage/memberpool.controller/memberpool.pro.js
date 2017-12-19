$(function () {
    $(document).keydown(function (event) {
        if (event.keyCode == 13) {
            searchMyMember();
        }
    })
})
function checkNoPro() {
    var url = S_URL;
    location.href = url;

}
function checkIsFail(fail) {
    var url = S_URL1;
    if (fail == task_isfail.ISFAIL_FALSE) {
        location.href = url + '&fail=' + task_isfail.ISFAIL_FALSE;
    } else if (fail == task_isfail.ISFAIL_TRUE) {
        location.href = url + '&fail=' + task_isfail.ISFAIL_TRUE;
    }


}
function searchMyMember(to) {
    var type = '';
    type = $('#searchtype').val();
    if(type==''){
        type = 'name';
    }
    if(to==0){
        var url = MP_PRO;
    }else if(to==1){
        var url = MP_SPACE;
    }
    if (type == 'name') {
        var member = $("#member_info").val();

        if (member.length == base_parm.DEFAULT) {
            asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
            return false;
        } else {
            location.href = url + "/member/" + member;
        }
    }
    if (type == 'remind') {
        var rem = $('#remind_time').val();
        var rem1 = $('#remind_time1').val();
        var che = checkDate(rem);
        var che1 = checkDate(rem1);

        if ((che == true) || (che1 == true)) {
            if ((rem.length == base_parm.DEFAULT) && (rem1.length == base_parm.DEFAULT)) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
                return false;
            } else if (((rem.length != base_parm.DEFAULT) && (rem1.length == base_parm.DEFAULT))) {
                location.href = url + "/start/1/rem/" + rem;
            } else if (((rem.length == base_parm.DEFAULT) && (rem1.length != base_parm.DEFAULT))) {
                location.href = url + "/start/1/rem/" + rem1;
            } else {
                location.href = url + "/rem/" + rem + "/rem1/" + rem1;
            }

        } else {
            asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
            return false;
        }
    }

}

function back_task(tid, twid) {
    var tw_id = twid;
    var t_id = tid;
    var maxlength = base_parm.MAX_TOTAL_INSERT;
    var url = MP_BACKTASK;

    asyncbox.confirm(question_before_action.MAKE_SURE_SEND_REJECT_TASK, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {
        var model = $("#DelTaskMsg");
        model.dialog("open");
        $("#reply_task_msg").click(function () {
            var msg = $("#reply_msg").val();
            if (msg.length > maxlength) {
                asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
                return false;
            } else {
                $.post(url, {tw_id: tw_id, t_id: t_id, msg: msg}, function (data) {
                    var obj = (new Function("return " + data))();

                    if ((obj.msg == data_back.DATA_SUCCESS)) {
                        asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                        location.reload();
                    } else if (obj.msg == data_back.DATA_ERROR) {
                        asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                        return false;
                    } else if (obj.msg == data_back.DATA_ERROR_ADONE) {
                        asyncbox.alert(data_back_msg.DATA_ERROR_ADONE, title.TITLE_ERROR);
                        return false;
                    }
                })
            }
        })
    })

}
function remind(id, twid) {
    var mid = id;
    var tw_id = twid;
    var modal = $("#modaltask");
    modal.dialog("open");
    var url = MP_SETMSG;
    $('#mid').val(mid);
    $('#tw_id').val(tw_id);
    $.post(url, {id: tw_id}, function (data) {
        var obj = (new Function("return " + data))();
        var rema = obj.remark;
        var remi = obj.remind;
        var imp = obj.important;
        $("#my_remark").val(rema);
        $("#date").val(remi);
        $("#level").val(imp);
    });
}

function remark() {
    var mid = $("#mid").val();
    var tw_id = $("#tw_id").val();
    var remark = $("#my_remark").val();
    var remind_time = $("#date").val();
    var level = $("#level").val();
    var le = ''
    var val = base_parm.DEFAULT;
    var url = MP_USERMSG;

    if (level.length == base_parm.DEFAULT) {
        le = base_parm.DEFAULT;
    } else {
        le = level.length;
    }
    if (isNaN(level) == true) {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOT_NUM, title.TITLE_ERROR);
        return false;
    } else if ((isNaN(level) == false) && (le != base_parm.DEFAULT)) {
        val = level;
        asyncbox.confirm(question_before_action.MAKE_SURE_PRO_TASK_REMARK, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {mid: mid, tw_id: tw_id, remark: remark, remind_time: remind_time, val: val}, function (data) {
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }

        })
    })

}
