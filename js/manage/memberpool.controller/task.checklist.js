$(function () {

    $("#score_div").dialog({autoOpen: false, width: 450, height: 250, modal: true});


    //鼠标移入
    $("#showmanagelist").bind("mouseenter", function()
    {
        $('.managelist_show').show("fast");    //显示list
        //移入表单
        $(".managelist_show").bind("mouseenter", function()
        {
            $('.managelist_show').show("fast"); //显示list
        });
    });

    //移入名单显示任务类别
    $('.blur_this').children("div").children("span").bind("mouseenter", function()
    {
        var this_child = $(this).next('ul');
        this_child.show("fast");

        //移入任务类别周任务，显示周任务类别
        this_child.children("span").eq(1).bind("mouseenter", function()
        {
            var child_child = $(this).next('div');
            child_child.show("fast");//show_task_type

            //移入周任务类别，显示弹出框
            child_child.bind("mouseenter", function()
            {
                var child_child = $(this).next('div');
                child_child.show("fast");
            });

            child_child.bind("mouseleave", function()
            {
                $(this).hide("fast");//show_task_type
            });
        });
    });
    //鼠标移除div，隐藏弹出框
    $(".blur_this div").bind("mouseleave", function()
    {
        $(".show_task_type").hide("fast");//show_task_type
    });

    //鼠标移出客服列表，隐藏弹出框
    $(".managelist_show").bind("mouseleave", function()
    {
        $(".managelist_show").hide("fast");//show_task_type
    });
});


function toManage(ob) {
    var f_id = $("#manage_list option:selected").attr("id");  // 获取Select选中的id，被申请人
    $('#to_manage').attr('value', '');
    $('#to_manage').html('');
    $('#to_manage').attr('value', f_id);
    var f_id = $('#to_manage').val();
}

function toManagePool() {
    var f_id = $('#to_manage').val();
    if (f_id == '') {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }
    var url = MP_NOPRO;
    var a_id = $('#manager').val();

    $.post(url, {a_id: a_id}, function (data) {
        location.href = url + "?id=" + f_id;
    })
}


function checkdone() {
    var url = T_CHECK_URL;
    var is_done = base_parm.DEFAULT_ONE;
    $.post(url, {done: base_parm.DEFAULT_ONE}, function (data) {
        if (data == 'got') {
            location.href = url + "?is_done=" + is_done;
        }
    });
}


function checkfinish() {
    var url = T_CHECK_URL;
    var is_finish = base_parm.DEFAULT_ONE;
    $.post(url, {finish: base_parm.DEFAULT_ONE}, function (data) {
        if (data == 'got') {
            location.href = url + "/is_finish/" + is_finish;
        }
    });
}


function checkshow() {
    var url = T_CHECK_URL;
    var is_show = base_parm.DEFAULT_ONE;
    $.post(url, {show: base_parm.DEFAULT_ONE}, function (data) {
        if (data == 'got') {
            location.href = url + "/isshow/" + is_show;
        }
    });
}


function showall() {
    var url = T_CHECK_URL;
    var is_show = base_parm.DEFAULT_TWO;
    $.post(url, {show: base_parm.DEFAULT_TWO}, function (data) {
        if (data == 'got') {
            location.href = url;
        }
    });
}


function is_allow_all(allow,typeid) {

    var allow = allow;
    var t_id = "";
    var mid = '';
    var typeid=typeid;
    if(typeid=='5')
    {
        alert("列表中有有效回访任务类型，混合列表不可全部拒绝！");
        exit;
    }
    var maxlength = base_parm.MAX_TOTAL_INSERT;

    $("input[name='del']:checked").each(function () {
        t_id += $(this).val() + ",";
        mid += $(this).attr('atid') + ',';
    });
    var arr1 = t_id.substring(0, t_id.length - 1);
    var arr2 = mid.substring(0, mid.length - 1);
    var url = T_UPDATEALL;
    if (allow == task_isallow.ISALLOW_TRUE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_TRUE, title.QUESTION);
    } else if (allow == task_isallow.ISALLOW_FALSE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_FALSE, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        if (allow == task_isallow.ISALLOW_FALSE) {
            var model = $("#DelTaskMsg");
            model.dialog("open");

            $("#reply_task_msg").click(function () {
                var msg = $("#reply_msg").val();
                if (msg.length > maxlength) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
                    return false;
                } else {
                    $.post(url, {allow: allow, tid: arr1, mid: arr2, msg: msg}, function (data) {
                        var obj = (new Function("return " + data))();
                        if (obj.msg == data_back.DATA_ERROR_NOEXISTS) {
                            asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE_ERROR);
                        } else if (obj.msg == data_back.DATA_ERROR) {
                            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                        } else if (obj.msg == data_back.DATA_SUCCESS) {
                            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                            location.reload();
                        }
                    })
                }
            })
        } else {
            var msg = '';
            $.post(url, {allow: allow, tid: arr1, mid: arr2, msg: msg}, function (data) {
                var obj = (new Function("return " + data))();

                if (obj.msg == data_back.DATA_ERROR_NOEXISTS) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE_ERROR);
                } else if (obj.msg == data_back.DATA_ERROR) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                } else if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                }

            })
        }

    })
}


function is_allow(allow, at_id, mi_id) {

    var is_allow = allow;
    var at_id = at_id;
    var mi_id = mi_id;
    var url = T_UPDATEONE;
    var maxlength = base_parm.MAX_TOTAL_INSERT;
    if (is_allow == task_isallow.ISALLOW_TRUE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_TRUE, title.QUESTION);
    } else if (is_allow == task_isallow.ISALLOW_FALSE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_FALSE, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        if (allow == task_isallow.ISALLOW_FALSE) {
            var model = $("#DelTaskMsg");
            model.dialog("open");

            $("#reply_task_msg").click(function () {
                var msg = $("#reply_msg").val();
                if (msg.length > maxlength) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
                    return false;
                } else {
                    $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                        var obj = (new Function("return " + data))();
                        if (obj.msg == data_back.DATA_SUCCESS) {
                            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                            location.reload();
                        } else if ((obj.msg == data_back.DATA_ERROR )) {
                            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                        } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                            asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                        }
                    })
                }
            })
        } else {
            var msg = '';
            $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                } else if ((obj.msg == data_back.DATA_ERROR )) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                }
            })
        }
    })
}

//修改有效回访任务
function is_Vallow(allow, at_id, mi_id) {

    var is_allow = allow;
    var at_id = at_id;
    var mi_id = mi_id;
    var url = T_UPDATEVONE;
    var maxlength = base_parm.MAX_TOTAL_INSERT;
    if (is_allow == task_isallow.ISALLOW_TRUE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_TRUE, title.QUESTION);
    } else if (is_allow == task_isallow.ISALLOW_FALSE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_FALSE, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        if (allow == task_isallow.ISALLOW_FALSE) {
            var model = $("#DelTaskMsg");
            model.dialog("open");

            $("#reply_task_msg").click(function () {
                var msg = $("#reply_msg").val();
                if (msg.length > maxlength) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
                    return false;
                } else {
                    $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                        var obj = (new Function("return " + data))();
                        if (obj.msg == data_back.DATA_SUCCESS) {
                            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                            location.reload();
                        } else if ((obj.msg == data_back.DATA_ERROR )) {
                            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                        } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                            asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                        }
                    })
                }
            })
        } else {
            var msg = '';
            $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                } else if ((obj.msg == data_back.DATA_ERROR )) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                }
            })
        }
    })
}


function del_task() {
    var url = T_DELETE;
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
            else if (obj.msg == data_back.DATA_ERROR_NODONE) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NODONE, title.TITLE_ERROR);
            }
            else if (obj.msg == data_back.DATA_ERROR_OTHER) {
                asyncbox.alert(data_back_msg.DATA_ERROR_OTHER, title.TITLE_ERROR);
            }
            else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
            }

        })

    })
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


function updateWeekTaskEndtime() {
    var start_time = $('#this_monday').val();
    var end_time = $('#weekTask_endtime').val();
    var url1 = MT_UPWTENDTIME1;
    var url2 = MT_UPWTENDTIME2;
    if (end_time == '') {
        asyncbox.alert(data_back_msg.DATA_ERROR_CHOOSE_TIME, title.TITLE_ERROR);
        return false;
    }
    asyncbox.confirm(question_before_action.MAKE_SURE_CHANGE_END_TIME, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {

        $.post(url1, {end_time: end_time, start_time: start_time}, function (data) {
            var obj = (new Function("return " + data))();

            $('#start').html(obj.start);
            $('#start').val(obj.start);
            $('#end').html(obj.end);
            $('#end').val(obj.end);
        });
        var model = $("#upWeekEndTime");
        model.dialog("open");

        $('#updateendtime').click(function () {
            asyncbox.confirm(question_before_action.MAKE_SURE_CHANGE_END_TIME, title.QUESTION);
            $("#asyncbox_confirm_ok").click(function () {

                var end = $('#end').val();
                var start = $('#start').val();

                $.post(url2, {end: end, start: start}, function (data) {

                    var obj = (new Function("return " + data))();
                    if (obj.msg == data_back.DATA_SUCCESS) {
                        asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                        location.reload();
                    } else {
                        asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                    }
                });

            })
        })
    })
}


function check_fail(fail) {
    var url = T_CHECK_LIST;
    var fail = fail;
    var to_list = url + '?is_done=1&fail=' + fail;
    location.href = to_list;
}

function check_all_tsk(ob){
    var url = T_CHECK_ALL_TASK;
    var str = "";
    var mid_list = '';
    $("input[name='del']:checked").each(function () {
        str += $(this).val() + ","
        mid_list += $(this).attr('atid') + ","
    });
    if (str.length > base_parm.DEFAULT) {
        //得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);
        var mid_arr = mid_list.substring(0, mid_list.length - 1);
    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }

    var score = $(ob).val();
        asyncbox.confirm('确认批量审核任务，并全部评分为'+score+'分？', title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            $.post(url,{list:arr,mid_list:mid_arr,score:score},function(data){

                var obj = (new Function("return " + data))();

                if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                } else if(obj.msg == data_back.DATA_ERROR_NOPOWER) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);

                }else if(obj.msg == data_back.DATA_ERROR_NOSCORE) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NOSCORE, title.TITLE_ERROR);

                }else if(obj.msg == data_back.DATA_ERROR_NO_PRO) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NOPRO, title.TITLE_ERROR);

                }else{
                    asyncbox.alert('用户'+obj.msg+'出现异常，审核不成功。请联系管理员', title.TITLE_ERROR);
                }

            });
        })

}
function showScore(ob){

    var str = "";
    $("input[name='del']:checked").each(function () {
        str += $(this).val() + ","
    });
    if (str.length > base_parm.DEFAULT) {
        //得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);
        var model = $("#score_div");
        model.dialog("open");

    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }

}