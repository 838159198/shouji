
function show(mid){
	var url = MEMBERPOOL_INFO;
	var mid = mid;
	var modal = $("#membermsg");
	modal.dialog("open");
	$.post(url,{mid:mid},function(data){
		modal.html(data);
		});
}
function checkweek(week){
	var week = week;
	var url = WEEKLY;
	location.href = url+'/week/'+week;
	
}
function is_pro(allow,wt_id){
	
	var is_allow = allow;
	var wt_id = wt_id;
	
	var url = PROWEEKTASK;
	
	var maxlength = 100;
	if(is_allow==1){
		asyncbox.confirm('确定上报该周任务？','问题');
	}else if(is_allow==4){
		asyncbox.confirm('确定删除该周任务？','问题');
		}
	$("#asyncbox_confirm_ok").click(function(){
    				$.post(url,{is_allow:is_allow,wt_id:wt_id},function(data){
    					alert(data);
    					var obj = (new Function("return " + data))();
    					if(obj.msg==1){
    						asyncbox.alert('操作成功','asyncbox_Title');
    						location.reload();
    					}else if((obj.msg==0)){
    						asyncbox.alert('操作失败','asyncbox_Title');
    						location.reload();
    					}
    					
    				})
    	
	})
}
function weekpaybacklist(c,e,b_c,b_e,da,b_da,sub,con,sal,mid){

	var co = '';
	if(con==0){
		co = '无效任务';
	}else if(con==1){
		co = '有效任务';
	}
	
		$('#s_to_e').html(c+'  至  '+e);
		$('#b_s_to_e').html(b_c+'  至  '+b_e);
		$("#s_w_data").html(da+'元');
		$('#b_s_w_data').html(b_da+'元');
		$('#sal').html(sal);
		$('#data').html(da+'元             -    '+b_da+'元');
		$('#pay_back').html(sub+'元');
		$('#con').html(co);

	var modal = $("#weekmsg");
	modal.dialog("open");
	
}
