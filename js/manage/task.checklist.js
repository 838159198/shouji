

function show(mid){
	var url = MEMBERPOOL_INFO;
	var mid = mid;
	var modal = $("#membermsg");
	modal.dialog("open");
	$.post(url,{mid:mid},function(data){
		modal.html(data);
	});
}

function toManagePool(){
	var f_id = $('#to_manage').val();
	if(f_id == ''){
		asyncbox.alert('请重新选择要查看的客服','asyncbox_Title');
		return false;
	}

	var url = MEMBERPOOL_NOPRO;
	var a_id = $('#manager').val();
	$.post(url,{a_id:a_id},function(data){
		location.href = url+"?id="+f_id;
		
	})
}


function checkdone(){
	var url = CHECK_URL;
	var is_done = 1;
	$.post(url,{done:1},function(data){
		if(data=='got'){
			location.href = url+"/is_done/"+is_done;
		}
	});
}
function checkfinish(){
	var url = CHECK_URL;
	var is_finish = 1;
	$.post(url,{finish:1},function(data){
		if(data=='got'){
			location.href = url+"/is_finish/"+is_finish;
		}
	});
}
function checkshow(){
	var url = CHECK_URL;
	var is_show = 1;
	$.post(url,{show:1},function(data){
		if(data=='got'){
			location.href = url+"/isshow/"+is_show;
		}
	});
}
function showall(){
	var url = CHECK_URL;
	var is_show = 2;
	$.post(url,{show:2},function(data){
		if(data=='got'){
			location.href = url;
		}
	});
}
function is_allow_all(allow){

	var allow = allow;
	var t_id="";
	var mid = '';
	var maxlength = 100;
	
    $("input[name='del']:checked").each(function()
    {
    	t_id += $(this).val()+",";
    	mid += $(this).attr('atid')+',';
 	});
    var arr1 = t_id.substring(0,t_id.length - 1);
    var arr2 = mid.substring(0,mid.length - 1);
    var url = UPDATEALL;
    if(allow==1){
		asyncbox.confirm('确定准许该任务？','问题');
	}else if(allow==0){
		asyncbox.confirm('确定拒绝该任务？','问题');
		}
    $("#asyncbox_confirm_ok").click(function(){
    	if(allow==0){
    		var model = $("#DelTaskMsg");
    		model.dialog("open");
    	
		$("#reply_task_msg").click(function(){
			var msg = $("#reply_msg").val();
			if(msg.length > maxlength){
				asyncbox.alert('回复信息过长','asyncbox_Title');
				return false;
			}else{
	    	$.post(url,{allow:allow,tid:arr1,mid:arr2,msg:msg},function(data){
	    		var obj = (new Function("return " + data))();
				if(obj.msg=='em'){
					asyncbox.alert('没有需要审核的任务','asyncbox_Title');
				}else if(obj.msg=='0'){
					asyncbox.alert('批量准许任务失败','asyncbox_Title');
				}else if(obj.msg=='1'){
					asyncbox.alert('批量准许任务成功','asyncbox_Title');
					location.reload();
				}else if(obj.msg=='2'){
					asyncbox.alert('批量拒绝任务成功','asyncbox_Title');
					location.reload();
				}else if(obj.msg=='3'){
					asyncbox.alert('批量拒绝任务失败','asyncbox_Title');
				}
	    		
	        })
		  }
        })
    	}else{
    		var msg = '';
    		$.post(url,{allow:allow,tid:arr1,mid:arr2,msg:msg},function(data){
    		var obj = (new Function("return " + data))();
			if(obj.msg=='em'){
				asyncbox.alert('没有需要审核的任务','asyncbox_Title');
			}else if(obj.msg=='0'){
				asyncbox.alert('批量准许任务失败','asyncbox_Title');
			}else if(obj.msg=='1'){
				asyncbox.alert('批量准许任务成功','asyncbox_Title');
				location.reload();
			}else if(obj.msg=='2'){
				asyncbox.alert('批量拒绝任务成功','asyncbox_Title');
				location.reload();
			}else if(obj.msg=='3'){
				asyncbox.alert('批量拒绝任务失败','asyncbox_Title');
			}

        })	
    	} 
        
    })
}

function is_allow(allow,at_id,mi_id){
	
	var is_allow = allow;
	var at_id = at_id;
	var mi_id = mi_id;
	var url = UPDATEONE;
	var maxlength = 100;
	if(is_allow==1){
		asyncbox.confirm('确定准许该任务？','问题');
	}else if(is_allow==0){
		asyncbox.confirm('确定拒绝该任务？','问题');
		}
	$("#asyncbox_confirm_ok").click(function(){
		if(allow==0){
    		var model = $("#DelTaskMsg");
    		model.dialog("open");
    		
    		$("#reply_task_msg").click(function(){
    			var msg = $("#reply_msg").val();
    			if(msg.length > maxlength){
    				asyncbox.alert('回复信息过长','asyncbox_Title');
    				return false;
    			}else{
    				$.post(url,{is_allow:is_allow,at_id:at_id,mi_id:mi_id,msg:msg},function(data){
    					var obj = (new Function("return " + data))();
    					if(obj.msg==0){
    						asyncbox.alert('成功拒绝该任务','asyncbox_Title');
    						location.reload();
    					}else if((obj.msg==1)){
    						asyncbox.alert('任务已经批准','asyncbox_Title');
    						location.reload();
    					}else if((obj.msg==2)){
    						asyncbox.alert('任务批准失败','asyncbox_Title');
    						location.reload();
    					}else if((obj.msg=='no_power')){
    						asyncbox.alert('没有权限这么做','asyncbox_Title');
    						location.reload();
    					}
    				})
    			}
    		})
    	}else{
    		var msg = '';
    		$.post(url,{is_allow:is_allow,at_id:at_id,mi_id:mi_id,msg:msg},function(data){
    			var obj = (new Function("return " + data))();
				if(obj.msg==0){
					asyncbox.alert('成功拒绝该任务','asyncbox_Title');
					location.reload();
				}else if((obj.msg==1)){
					asyncbox.alert('任务已经批准','asyncbox_Title');
					location.reload();
				}else if((obj.msg==2)){
					asyncbox.alert('任务批准失败','asyncbox_Title');
					location.reload();
				}else if((obj.msg=='no_power')){
					asyncbox.alert('没有权限这么做','asyncbox_Title');
					location.reload();
				}
			})
    		
    	}
		
			
	})
}

 
function del_task(){

	 var url = DELETE;
     var str="";
     $("input[name='del']:checked").each(function()
       {
    	 str += $(this).val()+","
    	});
   if (str.length > 0) {

  //得到选中的checkbox值序列
  	var arr = str.substring(0,str.length - 1);
  	
   }else{
	 asyncbox.alert('你还没有选择任何内容！','asyncbox_Title');
  	 return false;
   }
   asyncbox.confirm('确定清除该任务？','问题');

	$("#asyncbox_confirm_ok").click(function(){
		
		$.post(url,{at_id:arr},function(data){
				
			var obj = (new Function("return " + data))();
			 
			 if(obj.msg =='1'){
				 
				 asyncbox.alert('成功清除任务','asyncbox_Title');
				 location.reload();
			 }
			 else if(obj.msg =='0'){
				 
				 asyncbox.alert('清除任务失败','asyncbox_Title');
			 }
			 else if(obj.msg =='no_done'){
				 
				 asyncbox.alert('存在未完成的任务，不能删除','asyncbox_Title');
			 }else if(obj.msg =='fail'){
				 
				 asyncbox.alert('任务清除失败','asyncbox_Title');
			 }else if((obj.msg=='no_power')){
					asyncbox.alert('没有权限这么做','asyncbox_Title');
					location.reload();
			}
			 
		})
		
	})
}
function back_task(tid,twid){
	var tw_id = twid;
	var t_id = tid;
	var maxlength = 100;
	var url = BACKTASK;
	
	asyncbox.confirm('确定驳回该任务？','问题');
	$("#asyncbox_confirm_ok").click(function(){
		var model = $("#DelTaskMsg");
		model.dialog("open");
		$("#reply_task_msg").click(function(){
			var msg = $("#reply_msg").val();
			if(msg.length > maxlength){
				asyncbox.alert('回复信息过长','asyncbox_Title');
				return false;
			}else{
		$.post(url,{tw_id:tw_id,t_id:t_id,msg:msg},function(data){
			var obj = (new Function("return " + data))();
			
				if((obj.msg=='rback')){
					asyncbox.alert('任务已驳回修改','asyncbox_Title');
					location.reload();
				}else if(obj.msg=='all'){
					asyncbox.alert('任务还未上报，驳回失败','asyncbox_Title');
					return;
				}else if(obj.msg=='0'){
					asyncbox.alert('驳回失败','asyncbox_Title');
					return false;
				}else if(obj.msg=='a_done'){
					asyncbox.alert('任务已经完成，无法驳回','asyncbox_Title');
					return false;
				}
		})
		}
		})
	})
	
}
function updateWeekTaskEndtime(){
	var start_time = $('#this_monday').val();
	var end_time = $('#weekTask_endtime').val();
	var url1 = UPWTENDTIME1;
	var url2 = UPWTENDTIME2;
	if(end_time==''){
		asyncbox.alert('请选择本周任务结束时间','asyncbox_Title');
		return false;
	}
	asyncbox.confirm('确定修改任务结束时间？','问题');
	$("#asyncbox_confirm_ok").click(function(){
		
		$.post(url1,{end_time:end_time,start_time:start_time},function(data){
			var obj = (new Function("return " + data))();
			$('#start').html(obj.start);
			$('#start').val(obj.start);
			$('#end').html(obj.end);
			$('#end').val(obj.end);
		});
		var model = $("#upWeekEndTime");
		model.dialog("open");
		$('#updateendtime').click(function(){
			asyncbox.confirm('确定修改任务结束时间？本周的周任务的结束时间都将更改','问题');
			$("#asyncbox_confirm_ok").click(function(){
				var end = $('#end').val();
				var start = $('#start').val();
				$.post(url2,{end:end,start:start},function(data){
					if(data == 'success'){
						asyncbox.alert('结束时间修改成功','asyncbox_Title');
						location.reload();
					}else{
						asyncbox.alert('修改失败','asyncbox_Title');
					}
				});
				
			})
		})
	})
	
	
}
