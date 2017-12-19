$(function(){
    $("#member-info-grid-all").click(function () {
        $(":checkbox[name='checkthis']").filter(":enabled").attr("checked", this.checked);
    });
})
function checkout(json){
    //var obj = (new Function("return " + json))();
    alert(json);
}

function showWgae(){
    $('#show_wage').show();
    $('#send').hide();
    $('#hide').show();
}
function hideWgae(){
    $('#show_wage').hide();
    $('#send').show();
    $('#hide').hide();
}
function toManagePayBack() {
    var url = '';
    var manage_id = $("#manage_list  option:selected").attr('id');
    var url = MP_PAYBACK + '?id=' + manage_id;
    location.href = url;
}
function checkA_send(status) {
    var url = '';
    var status = status;
    var url = S_URL2 + '&status=' + status;
    location.href = url;
}
function checkPayBack(){
    var id_list = $('#error_id_list').val();
    var f_id = $('#f_id').val();
    var url = MP_PAYBACK;
    var stat = STAT;
    location.href = url+'?'+stat;
}
function sendPayBack() {
    var at_arr = "";
    var tw_arr = "";
    var id_list = $('#error_id_list').val();
    var f_id = $('#f_id').val();
    if(id_list.length>0){
        asyncbox.alert('无法发布任务收益，存在不相等任务收益', title.TITLE);
        return false;
    }
    var url = MP_S_PAYBACK;
    $("input[name='checkthis']:checked").each(function () {
        at_arr += $(this).val() + ","
        tw_arr += $(this).attr('tw_id') + ","
    });

    if (at_arr.length > base_parm.DEFAULT) {
        //得到选中的checkbox值序列
        var arr_at = at_arr.substring(0, at_arr.length - 1);
        var arr_tw = tw_arr.substring(0, tw_arr.length - 1);
        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_PAY_BACK, title.QUESTION);

        $("#asyncbox_confirm_ok").click(function () {

            $.post(url, {arr_at: arr_at, arr_tw: arr_tw, f_id: f_id}, function (data) {

                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE);
                    location.reload();
                } else if (obj.msg == 0) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE);
                    return false;
                }
            });
        })
    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
        return false;
    }
}
function checkPro() {
    var url = S_URL1;
    location.href = url;
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




