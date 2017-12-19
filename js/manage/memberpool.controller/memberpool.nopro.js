$(function () {

    $("#hidethis th").bind("click", function()
    {
        var num = $(this).index();
        $(this).hide('fast');

        var next = $("#hidethis").nextAll("tr");

        next.each(function () {
            $(this).children('td').eq(num).hide('fast');
        });
    })



    $("#showMyPower").dialog({autoOpen: false, width: 600, height: 400, modal: true});

    $(document).keydown(function (event) {
        if (event.keyCode == 13) {
            var to = $('#towhere').val();
            searchMyMember(to);
        }
    })
})
$(function () {
    $(document).keydown(function (event) {
        if (event.keyCode == 13) {
            $("#asyncbox_alert_ok").click();
        }
    })
    $(".btn-group2").click(function () {

        asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE);
    })
})
function a_pro() {
    asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE);
}
function proweekly(at_id, this_count, next_count, role) {
    var totalcount = base_parm.TOTAL_WEEK_TASK_NUM;
    var power = manage_role.SUPPORT_STAFF;
    var this_count = this_count;
    var next_count = next_count;
    var role = role;
    if (role > power) {
        asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
        return false;
    }
    if (next_count >= totalcount) {
        asyncbox.alert(data_back_msg.DATA_ERROR_ENOUGHT, title.TITLE_ERROR);
        return false;
    }

    var url = MT_PROWEEK;
    var at_id = at_id;
    asyncbox.confirm(question_before_action.MAKE_SURE_ASK_FOR_WEEK_TASK, title.QUESTION);
    $("#asyncbox_confirm_ok").click(function () {
        // alert($("#week_type").val());return false;
        var week_type = $("#week_type").val();
        if (week_type == week_task_type.NEXT_WEEK) {
            asyncbox.confirm(question_before_action.MAKE_SURE_ASK_FOR_WEEK_TASK_TO_NEXT_WEEK, title.QUESTION);
            $("#asyncbox_confirm_ok").click(function () {
                $.post(url, {at_id: at_id}, function (data) {
                    // alert(data);
                    var obj = (new Function("return " + data))();
                    if (obj.msg == data_back.DATA_ERROR_AEXISTS) {
                        asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);

                    } else if (obj.msg == data_back.DATA_SUCCESS) {
                        asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                        location.reload();

                    } else if (obj.msg == data_back.DATA_ERROR) {
                        asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                    }
                })
            })
        } else if (week_type == week_task_type.THIS_WEEK) {
            if (this_count >= totalcount) {
                asyncbox.alert(data_back_msg.DATA_ERROR_ENOUGHT, title.TITLE_ERROR);
                return false;
            }
            $.post(url, {at_id: at_id}, function (data) {
                //    alert(data);
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_ERROR_AEXISTS) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_AEXISTS, title.TITLE_ERROR);

                } else if (obj.msg == data_back.DATA_ERROR_CEILING) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_CEILING, title.TITLE_ERROR);

                } else if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();

                } else if (obj.msg == data_back.DATA_ERROR) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                }
            })
        }
    })

}
function toSpare() {
    var url = S_URL1;
    location.href = url;
}
function checkAllow(allow) {
    var allow = allow;
    var url = MP_REFUSE;
    location.href = url + '/allow/' + allow;

}
function checkPro() {
    var url = S_URL1;
    location.href = url;
}
function tovisit() {
    var url = MP_VISITE;
    location.href = url
}
function remindlist() {
    var remind = base_parm.DEFAULT_ONE;
    var url = MP_REFUSE;
    location.href = url + '/remind/' + remind;
}
function searchMyMember(to) {
    var type = '';
    //type = $("#search_type").find("option:selected").val();

    type = $('#searchtype').val();
    if(type==''){
        type = 'name';
    }
    if(to==0){
        var url = MP_NOPRO;
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

    if (type == 'youxiao') {
        var you = $('#youxiao_time').val();
        var you1 = $('#youxiao_time1').val();
        var che = checkDate(you);
        var che1 = checkDate(you1);

        if ((che == true) || (che1 == true)) {
            if ((you.length == base_parm.DEFAULT) && (you1.length == base_parm.DEFAULT)) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
                return false;
            } else if (((you.length != base_parm.DEFAULT) && (you1.length == base_parm.DEFAULT))) {
                location.href = url + "/start/1/you/" + you;
            } else if (((you.length == base_parm.DEFAULT) && (you1.length != base_parm.DEFAULT))) {
                location.href = url + "/start/1/you/" + you1;
            } else {
                location.href = url + "/you/" + you + "/you1/" + you1;
            }

        } else {
            asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE);
            return false;
        }
    }

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
function toSpare(twid,to,mid){
    var url = MP_CHANGE_POOL;
    //移入备选池，移出主池
    if(to==1){
        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_TO_SPARE, title.QUESTION);
    }
    //移出备选池，移入主池
    else if(to==0){
        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_TO_MEMBERPOOL, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url,{twid:twid,to:to,mid:mid},function(data){

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }else if (obj.msg == data_back.DATA_ERROR_NOEXISTS) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NOEXISTS, title.TITLE_ERROR);
            }else if (obj.msg == data_back.DATA_ERROR_NOPOWER) {
                asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
            }

        })
    })
}

function changeFromPoolToSpace(to) {
    var to = to;
    var str = "";
    var url = MP_CHANGE_POOL_ALL;
    $("input[name='tospace']:checked").each(function () {
        str += $(this).val() + ","
    });
    if (str.length > base_parm.DEFAULT) {
        $("#modaltask").show();
        // 得到选中的checkbox值序列
        var arr = str.substring(0, str.length - 1);
        $.post(url,{twid_list:arr,to:to},function(data){
            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }
        })
    } else {
        asyncbox.alert(data_back_msg.DATA_ERROR_NOCHOOSE, title.TITLE_ERROR);
        return false;
    }
}
function showCatalog(mid){
    var mid         = mid;
    var url_set     = MI_SET_CAT;
    var url_del     = MI_DEL_CAT;
    var url_cata_id_list = MI_SHOW_FATHER;
    $.post(url_cata_id_list,{mid:mid},function(data){

        var obj = (new Function("return " + data))();

        if(obj.msg['error']==0){
            $("#theTopTree").val('');
            $("#theSecTree").val('');
            $("#theThrTree").val('');
        }else if(obj.msg['error']==1){
            $("#theTopTree").val(obj.msg['name']);
            $("#theSecTree").val('');
            $("#theThrTree").val('');
        }else if(obj.msg['error']==2){
            $("#theTopTree").val(obj.msg['name']);
            $("#theSecTree").val(obj.msg['name1']);
            $("#theThrTree").val('');
        }else if(obj.msg['error']==3){
            $("#theTopTree").val(obj.msg['name']);
            $("#theSecTree").val(obj.msg['name1']);
            $("#theThrTree").val(obj.msg['name2']);
        }
    });

    var modal = $("#showMyPower");
    modal.dialog("open");

    $("#setCatalogue").click(function(){

        var catid = $('input[name="setThis"]:checked').val();
        if(catid==''){
            asyncbox.alert('未选中状态，无法设置', title.TITLE_ERROR);
            return false;
        }else{
        $.post(url_set,{mid:mid,catid:catid},function(data){

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            }
        });
        }
    });
    $("#DelThisMemberCatalogue").click(function(){
        $.post(url_del,{mid:mid},function(data){
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            }
        });
    });
}
function delMemberCata(){
}

