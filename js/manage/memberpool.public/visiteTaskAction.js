
function askforvisitetask(at_id){
	var at_id = at_id;
	var url = MP_VISITE_TASK;
	asyncbox.confirm(question_before_action.MAKE_SURE_CHANGE_TASK_TYPE,title.QUESTION);
	$("#asyncbox_confirm_ok").click(function(){
		var modal = $("#tasktype");
		modal.dialog("open");
		$("#visi_type").click(function(){
			var type = $("#task_type").find("option:selected").val() ;
			var task_type = '';

			if(type == task_type.TYPE_NEW){
				task_type = task_type_name.TYPE_NEW;
			}else if(type == task_type.TYPE_DROP){
				task_type = task_type_name.TYPE_DROP;
			}
			asyncbox.confirm(question_before_action.MAKE_SURE_DEL_AND_CHANGE+'-'+task_type+'？',title.QUESTION);
			$("#asyncbox_confirm_ok").click(function(){
				$.post(url,{at_id:at_id,type:type},function(data){
					var obj = (new Function("return " + data))();
					if((obj.msg == data_back.DATA_SUCCESS)){
						asyncbox.alert(data_back_msg.DATA_SUCCESS+'--'+task_type,title.TITLE_SUCCESS);
						location.reload();
					}else if(obj.msg == data_back.DATA_ERROR){
						asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
						return;
					}
				});
			})
		})
	})
}

function askforvisitetask2(at_id){
    var at_id = at_id;
    var url = MP_VISITE_VTASK;
    asyncbox.confirm(question_before_action.MAKE_SURE_YOUX_AND_CHANGE,title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            $.post(url,{at_id:at_id,availability:1},function(data){
                var obj = (new Function("return " + data))();
                if((obj.msg == data_back.DATA_SUCCESS)){
                    asyncbox.alert(data_back_msg.DATA_SUCCESS,title.TITLE_SUCCESS);
                    location.reload();
                }else if(obj.msg == data_back.DATA_ERROR){
                    asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
                    return;
                }
            });
        })
}


function delvisitetask(at_id){
	var at_id = at_id;
	var url = MP_DELVISITETASK;

	asyncbox.confirm(question_before_action.MAKE_SURE_PRO_TASK_FOR_FALSE,title.QUESTION);
	$("#asyncbox_confirm_ok").click(function(){
		$.post(url,{at_id:at_id},function(data){

			var obj = (new Function("return " + data))();

			if((obj.msg == data_back.DATA_SUCCESS)){
				asyncbox.alert(data_back_msg.DATA_SUCCESS,title.TITLE_SUCCESS);
				location.reload();
			}else if(obj.msg == data_back.DATA_ERROR){
				asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
				return;
			}
		})
	})
}

function delvisitetaskall(at_id){
    var at_id = at_id;
    var url = MP_DELVISITETASK1;

    asyncbox.confirm(question_before_action.MAKE_SURE_PRO_TASK_FOR_FALSE,title.QUESTION);
    $("#asyncbox_confirm_ok").click(function(){
        $.post(url,{at_id:at_id},function(data){

            var obj = (new Function("return " + data))();

            if((obj.msg == data_back.DATA_SUCCESS)){
                asyncbox.alert(data_back_msg.DATA_SUCCESS,title.TITLE_SUCCESS);
                location.reload();
            }else if(obj.msg == data_back.DATA_ERROR){
                asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
                return;
            }
        })
    })
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

