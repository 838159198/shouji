function reply_task(status) {

    var is_fail = status;
    var my_reply = $("#my_reply").val();  // 文本域的值
    var tid = $("#tid").val();  // 任务的id
    var type = $("#type").val();  // 任务类别，降量任务，普通任务
    var mid = $("#mid").val();  // 任务用户的id
    var tw_status = $("#tw_status").val();  // 任务状态
    var url = MP_REPLY;
    var tw_id = $('#tw_id').val();
    var len = ''
    len = getStrActualLen(my_reply);
    if (len > base_parm.MAX_TOTAL_INSERT) {
        asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
        return false;
    } else if (len == '') {
        asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
        return false;
    }

    if (is_fail == task_isfail.ISFAIL_FALSE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_PRO_TASK_FOR_TRUE, title.QUESTION);
    } else if (is_fail == task_isfail.ISFAIL_TRUE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_PRO_TASK_FOR_FALSE, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {

        $.post(url, {is_fail: is_fail, my_reply: my_reply, tw_id: tw_id, len: len, tid: tid, type: type, tw_status: tw_status, mid: mid}, function (data) {

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_ERROR_MSG_EMPTY) {
                asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            } else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                return false;
            } else if (obj.msg == data_back.DATA_ERROR_APRO) {
                asyncbox.alert(data_back_msg.DATA_ERROR_APRO, title.TITLE_ERROR);
                return false;
            }
        });
    })
}



function show_fail_reason() {
    var msg = $("#fail_type  option:selected").html();
    $('#my_reply').val(msg);
}