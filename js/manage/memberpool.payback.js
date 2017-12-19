function toManagePayBack(){
	var url='';
	var manage_id = $("#manage_list  option:selected").attr('id');
	var url = PAYBACK+'?id='+manage_id;
	location.href = url;
}
function checkA_send(status){
	 var url='';
	 var status = status;
	 var url = S_URL2+'&status='+status;
	 location.href = url;
}

function sendPayBack(){
	var at_arr="";
	var tw_arr=""; 
	var f_id = $('#f_id').val();

	var url = S_PAYBACK;
    $("input[name='checkthis']:checked").each(function()
    {
    	at_arr += $(this).val()+","
    	tw_arr += $(this).attr('tw_id')+","
 	});
  
  if (at_arr.length > 0) {
 //得到选中的checkbox值序列
 	var arr_at = at_arr.substring(0,at_arr.length - 1);
 	var arr_tw = tw_arr.substring(0,tw_arr.length - 1);
 	asyncbox.confirm('确定发布任务收益？','问题');
 	 
 	 $("#asyncbox_confirm_ok").click(function(){
 		
 		 $.post(url,{arr_at:arr_at,arr_tw:arr_tw,f_id:f_id},function(data){

 			var obj = (new Function("return " + data))();
 			if(obj.msg==1){
 				asyncbox.alert('发布成功！','asyncbox_Title');
 				location.reload();
 			}else if(obj.msg==0){
 				asyncbox.alert('发布失败！','asyncbox_Title');
 				return false;
 			}
 		 });
 	 })
  }else{
 	 asyncbox.alert('你还没有选择任何内容！','asyncbox_Title');
 	 return false;
  }
}
