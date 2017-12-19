$(function(){
	var type = $("#Param_type").val();
	$("#t_type").val(type);
	
	$("#modaltask").dialog({autoOpen: false, width: 600, height: 400, modal: true});
})

function category(id, cate) {
    var modal = $("#modalcategory");
    modal.find("#m_category").val(cate);
    modal.find("#m_uid").val(id);
    modal.dialog({autoOpen: true, width: 500, height: 150, modal: true});
}
function getTaskType(){
	var type=$("#Param_type option:selected").val();  
	$("#t_type").val(type);
	var t_type =$("#Param_type option:selected").val(); 

	return t_type;
}

function ask_for_up(mid,role){
	var m_id = mid;			
	var type = task_type.TYPE_DROP;	
	var power = manage_role.PRACTICE_STAFF;
	if(role == power){
		asyncbox.alert('你没有权限申请任务','asyncbox_Title');
		return false;
	}
	asyncbox.confirm('确定申请该任务？','问题');

	$("#asyncbox_confirm_ok").click(function(){
		$.post('askfortask',{m_id:m_id,type:type},function(data){
			var obj = (new Function("return " + data))();
			if(obj.msg==0){
				
				asyncbox.alert('申请成功,等待批准','asyncbox_Title');
				 location.reload();
			}else if((obj.msg==1)){
				
				asyncbox.alert('任务申请失败','asyncbox_Title');
				return false;
			}else if((obj.msg==2)){
				
				asyncbox.alert('任务已经存在','asyncbox_Title');
				return false;
			}
		})
		
	})
	
}


function GetCheckbox (){
	
	var data=new Array();

		    $("input[name='selectdsend']:checked").each(function()
		    	    {
		    	 	data.push($(this).val());
		    	    });

		    var modal = $("#modaltask");
		    
		    if(data.length > 0){
		    	 modal.dialog("open");
		    }else{
		    	asyncbox.alert('请选择用户!','asyncbox_Title');
		    	return;
		    }
    
    return data;
    
}

	$('#income-grid_c1_all').click(function(){
		 $('input[name="selectdsend"]').attr("checked",this.checked);		 
		 
	})
	  var $subBox = $("input[name='selectdsend']");
	      $subBox.click(function()
	       {
	    	  $("#income-grid_c1_all").attr("checked",$subBox.length == $("input[name='selectdsend']:checked").length ? true : false);
	       });


/**
 * 批量发布任务
 */
function makeSureAskTask(){
	
	var a_id =$("#a_list option:selected").attr("id"); 
	var title = $('#t_title').val();
	var content = $('#t_content').val();

	var role = $("#a_list").find("option:selected").attr('role') ;
 	var practice_staff = manage_role.PRACTICE_STAFF;

	var selectsend = GetCheckbox ();
	
	asyncbox.confirm('是否确认发布任务？','问题');
	$("#asyncbox_confirm_ok").click(function(){
		
		if((role ==practice_staff)){
	 		asyncbox.alert('发布失败！！见习客服只能发布回访任务','asyncbox_Title');
	        return false;
	 	}
		
		 $.post('sendtask',{selectsend:selectsend,a_id:a_id,title:title,content:content},function(data){
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
	
 	 
}





