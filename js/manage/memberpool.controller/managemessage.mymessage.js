$(function () {
    $("#manage_leave").dialog({autoOpen: false, width: 550, height: 550, modal: true});
    //$('#showyear').
});

function changePwd(){
    var url = ADMIN_PWD;
    location.href = url;
}
function manage_leave(id) {
    var modal = $("#manage_leave");
    modal.dialog("open");

}
function member_pay_back(){
    var url = MM_WAGE_LIST_POWER;
    location.href = url;
}

function member_wage(){
    var url = MA_WAGE_LIST;
    location.href = url;
}

function send_leave() {

    var leave_type = $("#leave_type").find("option:selected").val();

    var s_y_m_d = $('#starttime').val();
    var s_h = $("#s_h").find("option:selected").val();
    var s_m = $("#s_m").find("option:selected").val();
    (s_h == '') ? (s_m = '') : s_h;

    var e_y_m_d = $('#endtime').val();
    var e_h = $("#e_h").find("option:selected").val();
    var e_m = $("#e_m").find("option:selected").val();
    (e_h == '') ? (e_m = '') : e_h;

    var reason = $('#reason').val();

    if ((s_y_m_d == '') || (s_h == '') || (s_m == '') || (e_y_m_d == '') || (e_h == '') || (e_m == '') || (reason == '') || (leave_type == '')) {
        asyncbox.alert(data_back_msg.DATA_ERROR_EMPTY, title.TITLE_ERROR);
        return false;
    }
    var url = MA_MANAGE_LEAVE;
    $.post(url, {leave_type: leave_type, s_y_m_d: s_y_m_d, s_h: s_h,
        s_m: s_m, e_y_m_d: e_y_m_d, e_h: e_h, e_m: e_m, reason: reason}, function (data) {
        var obj = (new Function("return " + data))();
        if (obj.msg == data_back.DATA_ERROR) {
            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
        } else if (obj.msg == data_back.DATA_SUCCESS) {
            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
            location.reload();
        }
    })
}
function showmsg(){
    var url = MM_WAGE_LIST_POWER;
    location.href = url;
}
