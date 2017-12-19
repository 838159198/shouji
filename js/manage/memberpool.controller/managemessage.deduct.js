$(function () {
    $("#add_deduct").dialog({autoOpen: false, width: 550, height: 450, modal: true});
    $("#content").dialog({autoOpen: false, width: 350, height: 200, modal: true});

    $('#deduct_list').change(function () {
        var deduct_list = $("#deduct_list option:selected").val();
        if ((deduct_list == manage_deduct_type.DEDUCT_LEAVE_AFFAIR) ||
            (deduct_list == manage_deduct_type.DEDUCT_LEAVE_ILL) ||
            (deduct_list == manage_deduct_type.DEDUCT_LEAVE_ABSENTEEISM)) {
            $('#none').show();

        } else if ((deduct_list == manage_deduct_type.DEDUCT_LEAVE_LATE) ||
            (deduct_list == manage_deduct_type.DEDUCT_LEAVE_INSURANCE) ||
            (deduct_list == manage_deduct_type.DEDUCT_LEAVE_GAME) ||
            (deduct_list == manage_deduct_type.DEDUCT_LEAVE_OTHER)) {
            $('#none').hide();
        }
    })
});
function showmsg(ob) {
    var modal = $("#content");
    modal.dialog("open");
    var msg = $(ob).attr('value');
    modal.html(msg);
}
function thisMounth() {
    var this_mounth = $("#thismounth option:selected").val();
    var url = MA_MESSAGE_DEDUCT;
    var show_id = $('#show_id').val();
    location.href = url + '/id/' + show_id + '/mounth/' + this_mounth;

}
function addDeduct() {
    var modal = $("#add_deduct");
    modal.dialog("open");
}
function makeSureAddDeduct(id) {
    var start = $('#start').val();
    var end = $('#end').val();
    var check_message = $('#check_message').val();
    var deduct_list = $("#deduct_list option:selected").val();
    var s_time = $("#s_time option:selected").val();
    var e_time = $("#e_time option:selected").val();

    if ((deduct_list == manage_deduct_type.DEDUCT_LEAVE_AFFAIR) ||
        (deduct_list == manage_deduct_type.DEDUCT_LEAVE_ILL) ||
        (deduct_list == manage_deduct_type.DEDUCT_LEAVE_ABSENTEEISM)) {
        if ((start == '') || (end == '') || (check_message == '')) {
            asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
            return false;
        }
    } else if ((deduct_list == manage_deduct_type.DEDUCT_LEAVE_LATE) ||
        (deduct_list == manage_deduct_type.DEDUCT_LEAVE_INSURANCE) ||
        (deduct_list == manage_deduct_type.DEDUCT_LEAVE_GAME) ||
        (deduct_list == manage_deduct_type.DEDUCT_LEAVE_OTHER)) {
        if ((start == '') || (check_message == '')) {
            asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
            return false;
        }
    }

    var url = MA_MESSAGE_ADD_DEDUCT;
    asyncbox.confirm(question_before_action.MAKE_SURE_ADD_DEDUCT, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {id: id, check_message: check_message, start: start, end: end, deduct_list: deduct_list, s_time: s_time, e_time: e_time}, function (data) {
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }
        });
    })


}
