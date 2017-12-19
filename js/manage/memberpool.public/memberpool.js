$(function () {
    $('#level').blur(function () {
        var level = $("#level").val();
        var le = ''
        if (level.length == base_parm.DEFAULT) {
            le = base_parm.DEFAULT;
        }
        if (isNaN(level) == true) {
            asyncbox.alert(data_back_msg.DATA_ERROR_NOT_NUM, title.TITLE_ERROR);
            return false;
        }
    })
    $("#howtogettaskpay").dialog({autoOpen: false, width: 550, height: 400, modal: true});
    $("#last_action").dialog({autoOpen: false, width: 550, height: 400, modal: true});
    $("#weektaskmsg").dialog({autoOpen: false, width: 550, height: 300, modal: true});
    $("#modaltask").dialog({autoOpen: false, width: 550, height: 400, modal: true});
    $("#membermsg").dialog({autoOpen: false, width: 550, height: 600, modal: true});
    $("#tasktype").dialog({autoOpen: false, width: 550, height: 150, modal: true});
    $("#modalcategory").dialog({autoOpen: false, width: 500, height: 150, modal: true});
    $("#DelTaskMsg").dialog({autoOpen: false, width: 400, height: 300, modal: true});
    $("#content").dialog({autoOpen: false, width: 400, height: 300, modal: true});
    $("#weekmsg").dialog({autoOpen: false, width: 550, height: 580, modal: true});
    $("#upWeekEndTime").dialog({autoOpen: false, width: 550, height: 300, modal: true});

})
$(function () {
    $("#download_now").tooltip({ effect: 'slide', direction: 'down', position: 'bottom center',
        relative: true, delay: '30'});
})
$(function () {
    //鼠标移入
    $("#checkwhere").bind("mouseenter", function()
    {
        $('#showlist').show("fast");    //显示list
        //移入表单
        $("#showlist").bind("mouseenter", function()
        {
            $('#showlist').show("fast"); //显示list
        });
    });
    //鼠标移出
    $("#showlist").bind("mouseleave", function()
    {
        //隐藏list
        $('#showlist').hide("fast");
    });

    $('.blur_this').bind("mouseenter", function()
    {
        var value = $(this).val();
        if(value == 6){
            $('#showweeklist').show("fast");
        }

    });
    $('.blur_this').bind("mouseleave", function()
    {
        $('#showweeklist').hide("fast");
    });
});

/**
 * 是否晋升高级客服
 * @param status
 */
function promotion(status){
    var status = status;
    var url = R_CHANGE_MANAGE_ROLE;

    if(status==0){
        asyncbox.confirm(question_before_action.MAKE_SURE_PROMOTION_FALSE, title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            //asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
            asyncbox.confirm(question_before_action.MAKE_SURE_PROMOTION_FALSE_MSG, title.QUESTION);
            $("#asyncbox_confirm_ok").click(function () {
                $.post(url,{status:status},function(data){
                    alert(data);
                })
            })
        })
    }else if(status==1){
        asyncbox.confirm(question_before_action.MAKE_SURE_PROMOTION_TRUE, title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            //asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
            asyncbox.confirm(question_before_action.MAKE_SURE_PROMOTION_TRUE_MSG, title.QUESTION);

            $("#asyncbox_confirm_ok").click(function () {
                $.post(url,{status:1},function(data){
                    var obj = (new Function("return " + data))();

                    if (obj.msg == data_back.DATA_SUCCESS) {

                        asyncbox.alert('晋升成功，任务提成升高！！本月开始升级为‘高级客服权限’', title.TITLE_SUCCESS);

                    } else if (data == data_back.DATA_ERROR_AEXISTS) {
                        asyncbox.alert('晋升失败', title.TITLE_ERROR);
                    }
                })
            })
        })
    }else if(status==2){
        asyncbox.confirm("确认晋升为客服主管？", title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            //asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
            asyncbox.confirm("本月开始，任务提成升高！！确认晋升为客服主管？", title.QUESTION);

            $("#asyncbox_confirm_ok").click(function () {
                $.post(url,{status:1},function(data){
                    var obj = (new Function("return " + data))();

                    if (obj.msg == data_back.DATA_SUCCESS) {

                        asyncbox.alert('晋升成功，本月开始升级为‘客服主管’', title.TITLE_SUCCESS);

                    } else if (data == data_back.DATA_ERROR_AEXISTS) {
                        asyncbox.alert('晋升失败', title.TITLE_ERROR);
                    }
                })
            })
        })
    }
    else if(status==3){
        asyncbox.confirm("确认晋升为见习客服主管？", title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            //asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
            asyncbox.confirm("本月开始，任务提成升高！！确认晋升为见习客服主管？", title.QUESTION);

            $("#asyncbox_confirm_ok").click(function () {
                $.post(url,{status:1},function(data){
                    var obj = (new Function("return " + data))();

                    if (obj.msg == data_back.DATA_SUCCESS) {

                        asyncbox.alert('晋升成功，本月开始升级为‘见习客服主管’', title.TITLE_SUCCESS);

                    } else if (data == data_back.DATA_ERROR_AEXISTS) {
                        asyncbox.alert('晋升失败', title.TITLE_ERROR);
                    }
                })
            })
        })
    }


}
/**
 *
 * @param id
 * @param 修改用户类型
 */
function category(id, cate) {

    var modal = $("#modalcategory");
    modal.find("#m_category").val(cate);
    modal.find("#m_uid").val(id);
    modal.dialog("open");
}


function selectAll(checkbox) {
    $('input[type=checkbox]').prop('checked', $(checkbox).prop('checked'));
}

function mylist(id) {
    var id = id;
    var msg_id = 'msg_' + id;
    var msg = $("#" + msg_id);
    var bt_id = 'bt_' + id;
    var bt = $('#' + bt_id);
    msg.show();
    msg.bind("mouseenter", function () {
        msg.show();
    });
    $("#time_hide").val(msg_id);

    msg.bind("mouseleave", function () {
        msg.hide();
    });
}


function task() {
    var str = "";

    $("input[name='del']:checked").each(function () {
        str += $(this).val() + ","
    });
    if (str.length > base_parm.DEFAULT) {
        $("#modaltask").show();
        // 得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);
        $("#t_mid").val(arr);

    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }
}


function searchtype(type) {
    var type = type;
    $('#searchtype').val(type);
   // type = $("#search_type  option:selected").val();
    if (type == 'name') {
        $('#member_info').show();
        $('#remind_time').hide();
        $('#remind_time1').hide();
        $("#show").hide();
    }
    if (type == 'remind') {
        $('#member_info').hide();
        $('#remind_time').show();
        $('#remind_time1').show();
        $("#show").show();
    }
    if (type == 'youxiao') {
        $('#member_info').hide();
        $('#youxiao_time').show();
        $('#youxiao_time1').show();
        $("#show").show();
    }
}

/*
 * 验证日期格式
 */
function checkDate(strValue) {
    var regTextTime = /^((\d{2}(([02468][048])|([13579][26]))[\-\/\s]?((((0?[13578] )|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9])))))|(\d{2}(([02468][1235679])|([13579][01345789]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))$/;
    return regTextTime.test(strValue);
}


function makeSureAskTask() {
    var member_id_list = $("#t_mid").val();
    var url = MP_GETTASK;
    var a_id = $("#a_list option:selected").attr("id");
    $.post(url, {val: member_id_list, a_id: a_id}, function (data) {
        var obj = (new Function("return " + data))();
        if (obj.msg == data_back.DATA_SUCCESS) {
            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
            location.reload();
        } else if (data == data_back.DATA_ERROR_AEXISTS) {
            asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);
        } else {
            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
        }

    });
}

//function show(mid) {
//    var url = MP_INFO;
//    var mid = mid;
//    var modal = $("#membermsg");
//    modal.dialog("open");
//    $.post(url, {mid: mid}, function (data) {
//        modal.html(data);
//    });
//}
function show(id) {
    var url = MEMBER_INFO_URL;

    $.get(url + "?mid=" + id, function (data) {
        if (data) {
            var modal = $("#clickst").eq(0);
            modal.attr("title", "用户详细信息");
            modal.html(data);
            modal.dialog({show: "blind",hide: "explode", resizable: true,modal: true, width: 510,height:700, buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }});
        }
    });
}




function sel() {
    var val = $('input[name="type"]:checked').val();
    return val;
}


function getStrActualLen(count) {
    var count = count.length / 1024;
    return Math.round(count * Math.pow(10, 4));
}

function showContactlist() {
    var url = MP_LASTCONTACT;
    location.href = url;
}
function showMarklist(){
    var url = Mi_MARK_LIST;
    location.href = url;
}

function HowTogetTaskPay(){
//    var modal = $("#howtogettaskpay");
//    modal.dialog("open");

}
function help(){
    var url = MP_HELP;
    location.href = url;
}