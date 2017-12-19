$(function () {

//
//    $("#hidethis th").bind("click", function()
//    {
//        var num = $(this).index();
//        $(this).hide('fast');
//
//        var next = $("#hidethis").nextAll("tr");
//
//        next.each(function () {
//            $(this).children('td').eq(num).hide('fast');
//        });
//    })




		var t_type=$("#workType option:selected").val();  //获取Select选中的
		$("#t_type").val(t_type);
		
		$("#workType").change(function(){
			var val = $("#workType option:selected").val();  //获取Select选中的
			$("#t_type").val(val);
		})
	
	$("#member-info-grid-all").click(function () {
        $(":checkbox[name='uids']").filter(":enabled").attr("checked", this.checked);
    });
    $("#modaltask").dialog({autoOpen: false, width: 600, height: 400, modal: true});
    $("#modalcategory").dialog({autoOpen: false, width: 500, height: 150, modal: true});
    $("#modalsure").dialog({autoOpen: false, width: 600, height: 400, modal: true});

    $("#ShowManageList").dialog({autoOpen: false, width: 450, height: 550, modal: true});
    $("#showMyPower").dialog({autoOpen: false, width: 600, height: 400, modal: true});
    $("#ShowADVREC").dialog({autoOpen: false, width: 550, height: 400, modal: true});

    init();
});

	 $("#t_sbtn").click(function () {
		 	var role = $("#t_accept").find("option:selected").attr('role') ;
		 	var practice_staff = manage_role.PRACTICE_STAFF;
		 	var type = $("#type").find("option:selected").val() ;

		 	if((role ==practice_staff)&& (type != task_type.TYPE_VISIT)){
		 		asyncbox.alert('见习客服只能发布回访任务','asyncbox_Title');
	            return false;
		 	}
		 	
//	        if ($("#t_title").val() == "") {
//	        	asyncbox.alert('请填写标题 ','asyncbox_Title');
//	            return false;
//	        }
	        if ($("#t_uname").val() == "") {
	        	asyncbox.alert('请选择用户 ','asyncbox_Title');
	            return false;
	        }
	        if ($("#t_accept").val() == null) {
	        	asyncbox.alert('请选择接收人 ','asyncbox_Title');
	            return false;
	        }
	        
	        asyncbox.confirm(question_before_action.MAKE_SURE_SEND_TASK,title.QUESTION);
	        $("#asyncbox_confirm_ok").click(function(){
		        $.post(TASK_POST_URL, $("#t_formtask").serialize(), function (data) {
//                    $.post(SEND_TASK, $("#t_formtask").serialize(), function (data)  {

		        	if (data == "success") {
		            	asyncbox.alert('发布成功','asyncbox_Title');
		                location.reload();
		                $("#modaltask").dialog("close");
		            } else if(data == 'a_done'){
		            	asyncbox.alert('此任务已分配过，请重新分配 ','asyncbox_Title');
		            }else {
		            	asyncbox.alert('发布失败，请重试 ','asyncbox_Title');
		            }
		        });
	        })
	        return false;
	    });

function repeal_last_action(role){
	var role = role;
	if(role > manage_role.PRACTICE_VISOR){
		asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER,title.TITLE_ERROR);
		return false;
	}
	asyncbox.confirm(question_before_action.MAKE_SURE_REPEAL_LAST_ACTION,title.QUESTION);
	$("#asyncbox_confirm_ok").click(function(){
		var modal = $("#last_action");
		 modal.dialog("open");
	})
	$('#t_repeal').click(function(){
		asyncbox.confirm(question_before_action.MAKE_SURE_REPEAL_LAST_ACTION,title.QUESTION);
		$("#asyncbox_confirm_ok").click(function(){
			var url = REPEAL_TASK;
			var last_date = $('#last_time').val();
			var last_time_id_list = $('#last_time_id_list').val();
			var id_list = last_time_id_list.substring(0,last_time_id_list.length - 1);
			$.post(url,{id_list:id_list},function(data){
				var obj = (new Function("return " + data))();
				if(obj.msg==data_back.DATA_SUCCESS){
					asyncbox.alert(data_back_msg.DATA_SUCCESS,title.TITLE_SUCCESS);
					 location.reload();
				}else if((obj.msg==data_back.DATA_ERROR)){
					asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
					return false;
				}
			})
		})
	})
	
}

/**
 * Init
 */
function init() {
    $(".dropdown-toggle").dropdown();
    //disableHaveCs()
}

//提交申请
function ask_for_up(mid,role){
	var m_id = mid;		//选取的用户的id		
	var type = task_type.TYPE_NEW;	//新用户任务
	var power = manage_role.PRACTICE_STAFF;
	if(role==power){
		asyncbox.alert('你没有权限申请任务','asyncbox_Title');
		return false;
	}
	
	var modal = $("#modalsure");
	
	asyncbox.confirm('确定申请该任务？','问题');
   
	$("#asyncbox_confirm_ok").click(function(){
		$.post('askfortask',{m_id:m_id,type:type},function(data){
			var obj = (new Function("return " + data))();
			if(obj.msg==0){
				
				asyncbox.alert('申请成功,等待批准','asyncbox_Title');
                location.reload();
                return false;
			}else if((obj.msg==1)){
				
				asyncbox.alert('任务申请失败','asyncbox_Title');
				return false;
			}else if((obj.msg==2)){
				
				asyncbox.alert('任务已经存在','asyncbox_Title');
				return false;
			}else if((obj.msg==9)){
                asyncbox.alert(obj.message,'asyncbox_Title');
                return false;
            }
		})
		
	})
	
}


/**
 * disable有客服用户的checkbox
 */
function disableHaveCs() {
    var checks = $(":checkbox[name='uids']");
    checks.each(function () {
        var check = $(this);
        var td = check.parent().parent().find("td:eq(3)");
         if (td.html()) {
            check.attr("disabled", true);
            td.find("a").attr("href", "javascript:;").attr("title", "取消客服锁定").click(function () {
                check.attr("disabled", false);
                td.html("");
            });
        }
    });
}

function task() {
    var uname = '';
    var mid = '';
    //用户姓名
    $("input[name='uids']:checked").each(function () {
        uname += $(this).attr("value") + " ";
    });
    //用户id 数组格式
    $("input[name='uids']:checked").each(function () {
        mid += $(this).attr("mid") + ",";
    });

    if (uname == "") {
    	asyncbox.alert('请选择用户','asyncbox_Title');
        return;
    }
    var t_type = $("#t_type").val();

    $.getJSON(TASK_INFO_URL, function (data) {
        if (data) {
            $("#t_uname").val(uname);
            $("#t_mid").val(mid);
            $("#suname").html("选择用户：" + uname);

            var modal = $("#modaltask");
            modal.dialog("open");
            $("#t_title").val("新注册用户");
            $("#t_accept").empty();
            $.each(data.downlist, function (i, n) {
                $("#t_accept").append('<option value="' + n.id + '" role = "'+n.role+'">' + n.name + '</option>');
            });
        } else {
        	asyncbox.alert('获取数据失败 ','asyncbox_Title');
        }
    });
}


function category(id, cate) {
    var modal = $("#modalcategory");
    modal.find("#m_category").val(cate);
    modal.find("#m_uid").val(id);
    modal.dialog("open");
}


function mark(mid){
    var url = MEMBER_MARK;
    var mid = mid;
    var modal = $("#MarkMemMsg");
    asyncbox.confirm('确定关注此用户？','问题');
    $("#asyncbox_confirm_ok").click(function(){

        modal.dialog("open");
        $.post(url,{mid:mid},function(data){

            var obj = (new Function("return " + data))();
            if(obj.msg==0){

                asyncbox.alert('标记失败，请联系管理员！','error-msg');
                return false;
            }else if(obj.msg=='more'){

                asyncbox.alert('超过追踪上限（最多只能同时追踪20个用户）','error-msg');
                return false;
            }else if((obj.msg==1)){

                asyncbox.alert('标记成功！！','success-msg');
                location.reload();
            }else if((obj.msg==2)){

                asyncbox.alert('已经标记过此用户','error-msg');
                return false;
            }else if((obj.msg==10)){

                asyncbox.alert('sql执行失败','error-msg');
                return false;
            }
        })
    })
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


function showManageList(mid){
    var url = MI_SHOW_MA_LIST;
    var dt = '';
    var dd = '';
    $.post(url,{mid:mid},function(data){

        var obj = (new Function("return " + data))();
        if(obj.msg=='error'){

            asyncbox.alert('此用户没有任何客服记录', title.TITLE_ERROR);
        }else{
            var modal = $("#ShowManageList");
            modal.dialog("open");

            for(var i=0;i<obj.length;i++){

                var managename=obj[i].managename;
                var jointime= obj[i].jointime;
                var content = obj[i].con;
                var arid = obj[i].arid;

                dd += '<dt>跟进客服：</dt><dd><input  type = "text" value = '+managename+'></dd>' +
                      '<dt>最后关注时间：</dt><dd><input type = "text" value = '+jointime+'></dd>'+
                      '<dt>修改内容：</dt><dd><input onclick="showthismsg('+arid+')"  type = "text" value = '+content+'></dd><hr>'
                ;

            }
            $('#sendin').html(dd);

        }
    });
}

function showthismsg(arid){
    var url = MI_SHOW_ADV_REC;
    $.post(url,{arid:arid},function(data){

        var obj = (new Function("return " + data))();
        if(obj.msg=='error'){

            alert('bu ok');
        }else{

            var modal = $("#ShowADVREC");
            modal.dialog("open");
            $('#msg_content_adv').val(obj.msg);
        }

    });

}
