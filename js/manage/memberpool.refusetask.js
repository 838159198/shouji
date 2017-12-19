function showmsg(ob){
	var modal = $("#content");
	modal.dialog("open");
	var msg = $(ob).attr('value');
	modal.html(msg);
}

function selectAll(checkbox){
	 $('input[type=checkbox]').prop('checked', $(checkbox).prop('checked'));
}

function del_asktask(){

	var url = DELNOALLOW;
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
			 
		})
		
	})
	
}