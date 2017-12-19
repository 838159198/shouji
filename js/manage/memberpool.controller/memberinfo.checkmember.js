$(function(){
    $("#sendtask").dialog({autoOpen: false, width: 600, height: 400, modal: true});
    $("#mark_mem_Msg").dialog({autoOpen: false, width: 450, height: 300, modal: true});

    $("#member-info-grid-all").click(function () {
        $(":checkbox[name='selectdsend']").filter(":enabled").attr("checked", this.checked);
    });
})

function checkmember(type){
    var url = MI_CHECK_MEMBER_BY_CATEGORY;
    location.href = url+'?category='+type;
}
function orderByJoinTime(type){
    var url = newUrl;
    var url1 = url+'/type/jt/jt/'+type;
    location.href = url1;
}
function orderByOverTime(type){
    var url = newUrl;
    var status = STAT;
    var url1 = url+'/type/ot/ot/'+type;
    location.href = url1;
}
function orderByManageJoinTime(type){
    var url = newUrl;
    var status = STAT;
    var url1 = url+'/type/mjt/mjt/'+type;
    location.href = url1;
}


function GetCheckbox (){

    var data=new Array();

    $("input[name='selectdsend']:checked").each(function()
    {
        data.push($(this).val());
    });

    $('#member_id_list').val(data);

    var modal = $("#sendtask");

    if(data.length > 0){
        modal.dialog("open");
    }else{
        asyncbox.alert('请选择用户!','asyncbox_Title');
        return;
    }
    return data;

}

function registertimetofind(){

    var start   = $("#start").val()?$("#start").val():'';
    var end     = $("#end").val()?$("#end").val():'';
    var stat = STAT;
    var url = URL;
    if(stat != ''){
        url = url+'&start='+start+'&end='+end;
    }else{
        url = url+'?start='+start+'&end='+end;
    }

    location.href = url;

}


function checkoutCategory(type){
    $("#catego").html(type);
    $("#category_t").val(type);

}


function checkoutMounth(mounth){
    $("#mounth").html(mounth);
    $("#mounth_t").val(mounth);
}


function checkoutRegisterTime(){
    var start   = $("#start").val()?$("#start").val():'';
    var end     = $("#end").val()?$("#end").val():'';
    if(((start=='')&&(end !=''))){

        $("#timecheck_s").html(end);
        $("#timecheck_st").val(end);
    }else if(((start!='')&&(end ==''))){

        $("#timecheck_s").html(start);
        $("#timecheck_st").val(start);
    }else if(((start!='')&&(end !=''))){

        $("#timecheck_s").html(start);
        $("#timecheck_e").html(end);

        $("#timecheck_st").val(start);
        $("#timecheck_et").val(end);
    }else if(((start =='')&&(end ==''))){
        alert('搜索时间为空');
        return false;
    }
}

function chekcStatus(){


    var cate   = $("#category_t").val();
    var mounth = $("#mounth_t").val();
    var time_s   = $("#timecheck_st").val();
    var time_e   = $("#timecheck_et").val();
    var username_c   = $("#username").val();
    var workType   = $("#workType").val();

    if((cate=='') && (mounth=='') && (time_s=='') && (time_e=='') && (username_c=='')  && (workType=='')){
        alert('没有选择查询项');
    }else{

        var url = teamCheck;
        url = url+'/cate/'+cate+'/mounth/'+mounth+'/time_s/'+time_s+'/time_e/'+time_e+'/username_c/'+username_c+'/workType/'+workType
        location.href = url;
    }

}

//function showMarkMsg(){
//    var modal = $("#mark_mem_Msg");
//    modal.dialog("open");
//
//
//    $("#reply_mark_msg").click(function(){
//
//        var msg = $("#mark_msg").val();
//        if(msg.length>100){
//
//            asyncbox.alert('备注内容过长','asyncbox_Title');
//        }else{
//            var url = MI_UPMARKMSG;
//            $.post(url,{mid:mid,uid:uid,msg:msg},function(data){
//
//                alert(data);
//            });
//        }
//
//    })
//
//
//}




$("#t_sbtn2").click(function () {

    //接受任务客服的等级
    var role = $("#manage_list").find("option:selected").attr('role') ;
    //见习客服的等级
    var practice_staff = manage_role.PRACTICE_STAFF;

    //任务类型
    var type = $("#type").find("option:selected").val() ;
    //标题
 //   var title = $("#t_title1").val();
    //用户id
    var memberList = $("#member_id_list").val();
    //接受任务客服id
    var accept = $("#manage_list").find("option:selected").attr('id') ;
    //任务内容
 //   var content = $("#t_content1").val();


    if((role ==practice_staff)&& (type != task_type.TYPE_VISIT)){
        asyncbox.alert('见习客服只能发布回访任务','asyncbox_Title');
        return false;
    }
//    if ($("#t_title1").val() == "") {
//        asyncbox.alert('请填写标题 ','asyncbox_Title');
//        return false;
//    }
    if ($("#member_id_list").val() == "") {
        asyncbox.alert('请选择用户 ','asyncbox_Title');
        return false;
    }
    if (accept == '') {
        asyncbox.alert('请选择接收人 ','asyncbox_Title');
        return false;
    }

    var url = SEND_TASK;
    asyncbox.confirm(question_before_action.MAKE_SURE_SEND_TASK,title.QUESTION);
    $("#asyncbox_confirm_ok").click(function(){
        $.post(url,{t_uname:memberList,t_accept:accept,type:type},function(data){
//            alert(data);return false;
            if(data =='success'){
                asyncbox.alert('任务发布成功!','asyncbox_Title');
                location.reload();
                $("#modaltask").dialog("close");
            }else if(data == 'a_in'){
                asyncbox.alert('任务已存在，请检查','asyncbox_Title');
            }else if(data == 'no_power'){
                asyncbox.alert('没有权限这么做','asyncbox_Title');
            }else{
                asyncbox.alert('申请失败','asyncbox_Title');
            }

        });

    })

});

function give_up_this_member(mid){
    var url = Mi_GUM;
    asyncbox.confirm('确认放弃追踪此用户？？？',title.QUESTION);
    $("#asyncbox_confirm_ok").click(function(){
        $.post(url,{mid:mid},function(data){
            var obj = (new Function("return " + data))();

            if(obj.msg == 'success'){
                asyncbox.alert('成功放弃追踪此用户','success_Title');
                location.reload();
            }else{
                asyncbox.alert('放弃失败，请联系管理员','error_Title');
            }

        });

    })
}