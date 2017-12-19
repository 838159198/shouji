$(function () {
    $("#manage_msg").dialog({autoOpen: false, width: 550, height: 630, modal: true});
    $("#manage_leave_msg").dialog({autoOpen: false, width: 550, height: 550, modal: true});
    $("#Reply_Leave_Msg").dialog({autoOpen: false, width: 400, height: 300, modal: true});
    var name = $('#name').val();
});


function addmanage(id) {
    $('#manage_id').val(id);

    var url = MA_MESSAGE;
    $.post(url, {mid: id}, function (data) {
        var obj = (new Function("return " + data))();
        if (obj.sex == '0') {
            $('#Male').attr("checked", true);
        } else if (obj.sex == '1') {
            $('#Female').attr("checked", true);
        }
        if (obj.ismarry == '0') {
            $('#is').attr("checked", true);
        } else if (obj.ismarry == '1') {
            $('#isnot').attr("checked", true);
        }
        if (obj.picture != '') {
            $('#picture_show').attr('src', obj.picture);
            $('#picture_show').show();
        } else {
            $('#picture_show').parent().html('无相片');
        }
        $('#phone').val(obj.phone);
        $('#idcard').val(obj.idcard);
        $('#birthday').val(obj.birthday);
        $('#remark').val(obj.remark);
    });
    var modal = $("#manage_msg");
    modal.dialog("open");
}
function checkValIsEmpty(ob) {
    var maxlength = base_parm.MAX_TOTAL_INSERT;
    var value = $(ob).val();
    var name = $(ob).attr('name');
    if ((value.length == base_parm.DEFAULT ) || (value.length > maxlength )) {
        $(ob).next().html(data_back_msg.DATA_ERROR_HTML);
    } else if ((name == 'phone') && (isNaN(value) == true)) {
        $(ob).next().html(data_back_msg.DATA_ERROR_HTML);
    } else {
        $(ob).next().css('color', 'green').html('ok');
    }
}

function proRecord() {
    var name = $('#name').val();
    var sex = $('input:radio[name="sex"]:checked').val();
    var birthday = $('#birthday').val();
    var phone = $('#phone').val();
    var ismarry = $('input:radio[name="ismarry"]:checked').val();
    var role = $("#role option:selected").val();
    var remark = $('#remark').val();
    var idcard = $('#idcard').val();
    $('#manage_sex').val(sex);
    $('#manage_ismarry').val(ismarry);
    $('#manage_role').val(role);
    if ((name == '') || (sex == '') || (idcard == '') || (phone == '') || (role == '') || (ismarry == '') || (isNaN(phone) == true)) {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    } else {
        asyncbox.confirm(question_before_action.MAKE_SURE_UPLOAD_RECORD, title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            $('#check').hide();
            $('#sub').show();
        })
    }
}

function wage_msg(id) {
    var url = MA_MESSAGE_WAGE;
    var id = id;
    location.href = url + '/id/' + id;
}

function deduct(id) {
    var url = MA_MESSAGE_DEDUCT;
    var id = id;
    location.href = url + '/id/' + id;
}

function payback(id) {
    var url = MA_MESSAGE_PAYBACKL;
    var id = id;
    location.href = url + '/id/' + id;
}
function leave() {
    var modal = $("#manage_leave_msg");
    modal.dialog("open");
}
function ischeck(id, check) {
    var modal = $("#Reply_Leave_Msg");
    modal.dialog("open");
    var url = MA_MESSAGE_CHECK;

    $('#reply_leave_msg').click(function () {
        asyncbox.confirm(question_before_action.MAKE_SURE_MANAGE_LEAVE_TRUE, title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            var message = $('#my_reply').val();
            $.post(url, {did: id, check: check, message: message}, function (data) {
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_ERROR) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                } else if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                }
            })
        })
    })


}
