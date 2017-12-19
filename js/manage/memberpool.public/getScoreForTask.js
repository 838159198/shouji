function getScore(ob) {
    var score = $(ob).val();
    var publish = $("#publish").val();
    var tw_id = $("#tw_id").val();
    var t_id = $("#t_id").val();
    var tw_isset = $("#tw_isset").val();
    var role = $("#role").val();
    var f_id = $("#f_id").val();

    if (role <= manage_role.PRACTICE_VISOR) {
        var url = T_GETSCORE;

    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
        return false;
    }

    asyncbox.confirm(question_before_action.MAKE_SURE_GET_SCORE, title.QUESTION);

    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {score: score, publish: publish, t_id: t_id, tw_id: tw_id, tw_isset: tw_isset, f_id: f_id}, function (data) {

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            } else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_ERROR_ADONE) {
                asyncbox.alert(data_back_msg.DATA_ERROR_ADONE, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_ERROR_NO_PRO) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NOPRO, title.TITLE_ERROR);
                return false;
            }
        })

    })

}




