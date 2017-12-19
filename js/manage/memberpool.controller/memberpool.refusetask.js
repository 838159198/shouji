function showmsg(ob) {
    var modal = $("#content");
    modal.dialog("open");
    var msg = $(ob).attr('value');
    modal.html(msg);
}

function selectAll(checkbox) {
    $('input[type=checkbox]').prop('checked', $(checkbox).prop('checked'));
}
function del_waittoallow(){

    var url = MP_DEL_WAIT_ALLOW;

    var str = "";
    $("input[name='del']:checked").each(function () {
        str += $(this).val() + ","
    });
    if (str.length > base_parm.DEFAULT) {

        //得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);

    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }
    asyncbox.confirm(question_before_action.MAKE_SURE_SEND_DEL_TASK, title.QUESTION);

    $("#asyncbox_confirm_ok").click(function () {

        $.post(url, {at_id: arr}, function (data) {
            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }
            else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            }

        })

    })

}
function del_asktask() {

    var url = MP_DELNOALLOW;

    var str = "";
    $("input[name='del']:checked").each(function () {
        str += $(this).val() + ","
    });
    if (str.length > base_parm.DEFAULT) {

        //得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);

    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }
    asyncbox.confirm(question_before_action.MAKE_SURE_SEND_DEL_TASK, title.QUESTION);

    $("#asyncbox_confirm_ok").click(function () {

        $.post(url, {at_id: arr}, function (data) {

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }
            else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            }

        })

    })

}