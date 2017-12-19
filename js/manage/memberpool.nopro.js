
$(function(){
		$(document).keydown(function(event){
			if(event.keyCode == 13){
				searchMyMember();
			}
	})
})
$(function(){
		$(document).keydown(function(event){
			if(event.keyCode == 13){
				$("#asyncbox_alert_ok").click();
			}
	})
	$(".btn-group2").click(function(){
		asyncbox.alert('该任务已被标记为周任务，周任务进行期间，关于此用户的其他类型任务无法提交','asyncbox_Title');
	})
})
function a_pro(){
	asyncbox.alert('该任务已被标记为周任务','asyncbox_Title');

}
function proweekly(at_id,this_count,next_count,role){
	
	var totalcount = 20;
	var power = 5;
	var this_count = this_count;
	var next_count = next_count;
	var role = role;
	
	if(role>power){
		asyncbox.alert('你没有权限申请周任务','asyncbox_Title');
		return false;
	}
	if(this_count>=totalcount){
		asyncbox.alert('本周周任务已达到标记上限','asyncbox_Title');
		return false;
	}
	if(next_count>=totalcount){
		asyncbox.alert('下周周任务已达到标记上限','asyncbox_Title');
		return false;
	}
	
	var url = PROWEEK;
	var week_url = WEEKTIME;
	var at_id = at_id;
	asyncbox.confirm('确定标记为周任务？周任务进行期间，关于此用户的其他类型任务无法提交','问题');
	$("#asyncbox_confirm_ok").click(function(){
		var week_type = $("#week_type").val();
		if(week_type == 3){
			asyncbox.confirm('已超过本周周任务标记时间，继续标记将把此任务放入下周周任务列表内','问题');
			$("#asyncbox_confirm_ok").click(function(){
				$.post(url,{at_id:at_id},function(data){
					var obj = (new Function("return " + data))();
					if(obj.msg =='a_done'){
						asyncbox.alert('此任务已被申请','asyncbox_Title');
					}else if(obj.msg ==1){
						asyncbox.alert('申请成功，在周任务列表查看任务信息','asyncbox_Title');
						location.reload();
					}else if(obj.msg ==0){
						asyncbox.alert('申请失败，联系管理员','asyncbox_Title');
					}
				})
			})
		}else if(week_type == 2){
		$.post(url,{at_id:at_id},function(data){
			var obj = (new Function("return " + data))();
			if(obj.msg =='a_done'){
				asyncbox.alert('此任务已被申请','asyncbox_Title');
			}else if(obj.msg ==1){
				asyncbox.alert('申请成功，在周任务列表查看任务信息','asyncbox_Title');
				location.reload();
			}else if(obj.msg ==0){
				asyncbox.alert('申请失败，联系管理员','asyncbox_Title');
			}
		})
		}
	})
	
}
function checkPro(){
	var url = S_URL1;
	location.href = url;
}
/*
function checkAllow(allow){
	var allow = allow;
	var url = REFUSE;
	location.href = url+'/allow/'+allow;
	
}*/
function tovisit(){
	var url = VISITE;
	location.href = url
}
function remindlist(){
	var remind = 1;
	var url = REFUSE;
	location.href = url+'/remind/'+remind;
}

function askforvisitetask(at_id){
	var at_id = at_id;

	var url = VISITE_TASK;

	asyncbox.confirm('确定将此回访任务变更为其他任务类型？','问题');
	$("#asyncbox_confirm_ok").click(function(){
		var modal = $("#tasktype");
		modal.dialog("open");
		$("#visi_type").click(function(){
			var type = $("#task_type").find("option:selected").val() ;
			var task_type = '';

			if(type == 1){
				task_type = '新用户任务';
			}else if(type == 2){
				task_type = '降量任务';
			}
			asyncbox.confirm('确定清除此回访任务变更为'+task_type+'？','问题');
			$("#asyncbox_confirm_ok").click(function(){
				$.post(url,{at_id:at_id,type:type},function(data){
					var obj = (new Function("return " + data))();
					if((obj.msg=='1')){
						asyncbox.alert('此回访任务清除变更为'+task_type+'成功','asyncbox_Title');
						location.reload();
					}else if(obj.msg=='0'){
						asyncbox.alert('变更失败','asyncbox_Title');
						return;
					}
				});
			})
		})
	})
}
function delvisitetask(at_id){
	var at_id = at_id;
	var url = DELVISITETASK;
	asyncbox.confirm('确定放弃此用户？','问题');
	$("#asyncbox_confirm_ok").click(function(){
		$.post(url,{at_id:at_id},function(data){
			
			var obj = (new Function("return " + data))();

			if((obj.msg=='1')){
				asyncbox.alert('此回访任务清除成功','asyncbox_Title');
				location.reload();
			}else if(obj.msg=='0'){
				asyncbox.alert('清除失败','asyncbox_Title');
				return;
			}
		})
	})
	
}
function checkNoPro(){
	var url = DELVISITETASK;
	location.href = NOPRO;
	
}



function searchMyMember(){
	var type = '';
	type = $("#search_type").find("option:selected").val() ;
	var url = NOPRO;
	if(type =='name')
	{
		var member = $("#member_info").val();
		
		if(member.length==0)
		{
			asyncbox.alert('查找内容不能为空','asyncbox_Title');
			return false;
		}else
		{
			location.href = url+"/member/"+member;
		}
	}if(type =='remind')
	{
		var rem = $('#remind_time').val();
		var rem1 = $('#remind_time1').val();
		var che = checkDate(rem);
		var che1 = checkDate(rem1);
		
			if((che==true)||(che1==true))
			{
				if((rem.length==0)&&(rem1.length==0))
				{
					asyncbox.alert('查找内容不能为空','asyncbox_Title');
					return false;
				}else if(((rem.length!=0)&&(rem1.length==0))){
					location.href = url+"/start/1/rem/"+rem;
				}else if(((rem.length==0)&&(rem1.length!=0))){
					location.href = url+"/start/1/rem/"+rem1;
				}else
				{
					location.href = url+"/rem/"+rem+"/rem1/"+rem1;
				}
				
			}else
			{
				asyncbox.alert('查找内容不能为空','asyncbox_Title');
				return false;
			}
		
	}
	
}


function back_task(tid,twid){
	var tw_id = twid;
	var t_id = tid;
	var url = BACKTASK;
	
asyncbox.confirm('确定驳回该任务？','问题');
$("#asyncbox_confirm_ok").click(function(){
	$.post(url,{tw_id:tw_id,t_id:t_id},function(data){
		
		var obj = (new Function("return " + data))();
		
			if((obj.msg=='rback')){
				asyncbox.alert('任务已打回修改','asyncbox_Title');
				location.reload();
			}else if(obj.msg=='all'){
				asyncbox.alert('任务还未上报，驳回失败','asyncbox_Title');
				return;
			}else if(obj.msg=='0'){
				asyncbox.alert('提交失败','asyncbox_Title');
				location.reload();
				return;
			}
	})
})

}
function remind(id,twid){
	var mid = id;
	var tw_id = twid;
	var modal = $("#modaltask");
	modal.dialog("open");
	var url = SETMSG;
	
	$('#mid').val(mid);
	$('#tw_id').val(tw_id);
	$.post(url,{id:tw_id},function(data){
		var obj = (new Function("return " + data))();
		var rema = obj.remark;
		var remi = obj.remind;
		var imp = obj.important;
		$("#my_remark").val(rema);
		$("#date").val(remi);
		$("#level").val(imp);
	});
}

function remark(){
	var mid = $("#mid").val();
	var tw_id = $("#tw_id").val();
	var remark = $("#my_remark").val();
	var remind_time = $("#date").val();
	var level = $("#level").val();
	var le = ''
	var val=0;
	var url = USERMSG;
	
	if(level.length==0){
		le = 0;
	}else{
		le = level.length;
	}
	if(isNaN(level)== true){
		
		asyncbox.alert('等级提交失败，只能键入数字','asyncbox_Title');
		return false;
	}else if((isNaN(level)== false)&&(le ==0)){
		asyncbox.confirm('未输入用户等级，默认等级0.</br>确定提交备注信息？','问题');
	}else if((isNaN(level)== false)&&(le !=0)){
		val = level;
		asyncbox.confirm('确定提交备注信息？','问题');
	}
	 $("#asyncbox_confirm_ok").click(function(){
		 $.post(url,{mid:mid,tw_id:tw_id,remark:remark,remind_time:remind_time,val:val},function(data){
			 var obj = (new Function("return " + data))();
				if(obj.msg =='0'){
					asyncbox.alert('备注提交失败','asyncbox_Title');
				}else if(obj.msg =='1'){
					asyncbox.alert('备注提交成功','asyncbox_Title');
					location.reload();
				}
				
			})
	 })
	
}
function show(mid){
	var url = INFO;
	var mid = mid;
	var modal = $("#membermsg");
	modal.dialog("open");
	$.post(url,{mid:mid},function(data){
		modal.html(data);
		});
}




