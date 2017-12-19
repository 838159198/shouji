$(function(){
    $("#showMyPower").dialog({autoOpen: false, width: 600, height: 400, modal: true});
    $("#modalcategory").dialog({autoOpen: false, width: 500, height: 150, modal: true});

})




function del_this_task(mid, atid, tid, twid, isfail) {
    var m_id = mid;
    var at_id = atid;
    var t_id = tid;
    var tw_id = twid;
    var isfail = isfail;
    var url = T_DELBYMSG;
    if (isfail == task_isfail.ISFAIL_TRUE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_DEL_TASK, title.QUESTION);
    } else if (isfail == task_isfail.ISFAIL_FALSE) {
        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_DEL_TASK_fail_false, title.QUESTION);
    }
    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {m_id: m_id, at_id: at_id, t_id: t_id, tw_id: tw_id}, function (data) {

            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR) {

                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {

                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_ERROR);
                location.reload();
            } else if (obj.msg == data_back.DATA_ERROR_NOSCORE) {

                asyncbox.alert(data_back_msg.DATA_ERROR_NOSCORE, title.TITLE_SUCCESS);
            }
        });

    })
}




/******************任务搜索功能开始*********************/
/******************任务搜索功能开始*********************/
/******************任务搜索功能开始*********************/
/******************任务搜索功能开始*********************/


/**
 *
 * @param parames
 * @param mname
 * @param option
 * @returns {string}
 */
function getparameters(parames, mname, option) {

    var value = '';
    var par = parames + '_show';
    var pa = parames + '_';


    $("#" + par).show('fast');
    $("#" + pa).html(mname);
    $("#" + parames).val(option);
    value = $("#" + parames).val();


    return value;
}

/**
 * 设置参数
 */
function settimesval(parame_type){

    var times = '';
    if(parame_type == 'task_sendtime'){

        times = 'time_send';
    }else if(parame_type == 'task_protime'){

        times = 'time_pro';
    }

    $("#"+times+"_s").change(function () {

        var vals = $("#"+times+"_s").val();
        $("#" + parame_type + '_show').show('fast');
        $("#" + parame_type + '_s').html(vals);
        $("#" + parame_type + '_start').val(vals);
    })

    $("#"+times+"_e").change(function () {

        var vals = $("#"+times+"_e").val();
        $("#" + parame_type + '_show').show('fast');
        $("#" + parame_type + '_e').html(vals);
        $("#" + parame_type + '_end').val(vals);
    })

}
/**
 *
 * @param parame_type
 * @param mname
 * @param option
 */
function showAndhide(parame_type){

    if(parame_type == 'member_name' ){

        $("#member_info").show();
        $("#time_send_s").hide();
        $("#time_send_e").hide();
        $("#time_pro_s").hide();
        $("#time_pro_e").hide();
    }else if(parame_type == 'task_sendtime'){

        $("#member_info").hide();
        $("#time_pro_s").hide();
        $("#time_pro_e").hide();
        $("#time_send_s").show();
        $("#time_send_e").show();
    }else if(parame_type == 'task_protime'){

        $("#member_info").hide();
        $("#time_pro_s").show();
        $("#time_pro_e").show();
        $("#time_send_s").hide();
        $("#time_send_e").hide();
    }

}


function delThisOp(ob){

    var parame_type = $(ob).attr('class');
    $("#" + parame_type + '_show').hide('fast');
    $("#" + parame_type ).val('');
    if(parame_type == 'task_sendtime'){

        $("#" + parame_type + "_start").val('');
        $("#" + parame_type + "end").val('');
    }else if(parame_type == 'task_protime'){

        $("#" + parame_type + "_start").val('');
        $("#" + parame_type + "end").val('');
    }else{

        $("#" + parame_type ).val('');
    }
    alert('清除选项');

}
/**
 * 页面设置显示
 */
function sendMsg(parame_type, mname, option) {

    var value = '';

    //如果输入用户名
    if (parame_type == 'member_name') {
        showAndhide(parame_type);

        $("#member_info").change(function () {

            var member_name = $("#member_info").val();

            if (member_name.length == 0) {

                $("#" + parame_type).val('');
            } else {

                var val = $("#member_info").val();
                $("#" + parame_type + '_show').show('fast');
                $("#" + parame_type + '_').html(val);
                $("#" + parame_type).val(val);
            }
        })
        //如果输入发布时间
    } else if (parame_type == 'task_sendtime') {

        showAndhide(parame_type);
        settimesval(parame_type);


        //如果是上报任务时间
    } else if (parame_type == 'task_protime') {

        showAndhide(parame_type);
        settimesval(parame_type);

    } else {

        value = getparameters(parame_type, mname, option);
    }

}

$("#member_info").blur(function(){

    var value = $("#member_info").val();
    var parame_type = 'member_name';

    if(value.length==0){
        $("#" + parame_type).val('');
    }else {

        $("#" + parame_type + '_show').show('fast');
        $("#" + parame_type + '_').html(value);
        $("#" + parame_type).val(value);
    }





})




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
        $.post(url_set,{mid:mid,catid:catid},function(data){

            var obj = (new Function("return " + data))();

            if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }else if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            }
        });

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