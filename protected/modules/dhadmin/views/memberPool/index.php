<?php
$this->breadcrumbs = array(
    '我的用户池' => array('index'),
);
?>
<?php 
 /** 任务进度 - 进行中 */
//const STATUS_NORMAL = 0;
    /** 任务进度 - 已有回复 */
//const STATUS_SUBMIT = 1;
    /** 任务进度 - 已完成 */
//const STATUS_DONE = 2;
    /** 任务进度 - 已删除 */
//const STATUS_DEL = 3;

function getManageMenu($id,$tw_id)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('memberinfo/graphs', 'uid' => $id), array('target' => '_blank')),
        'gainadvert' => CHtml::link('业务管理', array('gainadvert/index', 'uid' => $id)),
        'resetpwd' => CHtml::link('重置密码', array('memberinfo/resetpwd', 'uid' => $id)),
        'mail' => CHtml::link('站内信', array('mail/index', 'uid' => $id)),
        'update' => CHtml::link('修改用户信息', array('memberinfo/update', 'id' => $id)),
        'price' => CHtml::link('设置资源单价', array('memberinfo/price', 'id' => $id)),
        'log' => CHtml::link('修改信息历史记录', array('memberinfo/log', 'id' => $id)),
        'memberbranch' => CHtml::link('用户网吧管理', array('memberbranch/index', 'id' => $id)),
    	'remind' => CHtml::submitButton('我的备注信息', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'remind(\''.$id.'\',\''.$tw_id.'\')'))),
    
    );

    $menus = array();
    if (Auth::check('manage.advisoryrecords.index')) $menus[] = $urls['advisory'];
    if (Auth::check('manage.memberinfo.graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('manage.gainadvert.index')) $menus[] = $urls['gainadvert'];
    if (Auth::check('manage.memberinfo.resetpwd')) $menus[] = $urls['resetpwd'];
    if (Auth::check('manage.mail.index')) $menus[] = $urls['mail'];
    if (Auth::check('manage.memberinfo.update')) $menus[] = $urls['update'];
    if (Auth::check('manage.memberinfo.price')) $menus[] = $urls['price'];
    if (Auth::check('manage.memberinfo.log')) $menus[] = $urls['log'];
    if (Auth::check('manage.memberbranch.index')) $menus[] = $urls['memberbranch'];
    $menus[] = $urls['remind'];


  	$btn='';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}





?>
<!-- 
<div style="clear:both">
<div class="controls" style="float:left;margin-left:5px;">
<span class="label label-info" style='margin-top:5px;'>目前共领取任务:<?php echo $arr['count_all']?></span></br>
</div>
<div class="controls" style="float:left;margin-left:5px;">
<span class="label label-info" style='margin-top:5px;'>新用户任务:<?php echo $arr['count_new']?></span></br>
</div>
<div class="controls" style="float:left;margin-left:5px;">
<span class="label label-info" style='margin-top:5px;'>降量任务:<?php echo $arr['count_drop']?></span></br>
</div>
<div class="controls" style="float:left;margin-left:5px;">
<span class="label label-info" style='margin-top:5px;'>已上报周任务:<?php echo $arr['count_week']?></span></br>
</div>
<div class="controls" style="float:left;margin-left:5px;">
<span class="label label-info" style='margin-top:5px;'>还需上报周任务:<?php echo $arr['need_week']?></span></br>
</div>
-->


<?php if($now==1){?>
<div class="controls"  style = 'margin-top:20px; '>
<?php $url =  $this->createUrl('nopro') ?>
<?php if($rank==0){?>
	<span class="label label-info" style='margin-top:5px;'>
		<a style = 'color:white' href = '<?php echo $url.'/rank/1'?>'>按用户等级排序</a>
	</span>
<?php }else{?>
	<span class="label label-info" style='margin-top:5px;'>
		<a style = 'color:white' href = '<?php echo $url.'/rank/0'?>'>按提醒时间排序</a>
	</span>
<?php }?>
</div>
<div class="controls"  style = 'margin-top:20px; '>
<select id = 'search_type' onchange = 'searchtype()'>
	<option value = 'name' seleted='selected'>用户名查找</option>
	<option value = 'remind'>提醒时间查找</option>
</select>
<input type = 'text' name = 'member_info' id = 'member_info' value = '';>
<div id = 'show' style = 'display:none'>
<?php 
				
				    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				        'attribute' => 'p_title',  
				        'value'=>'',//设置默认值
				        'name'=>'remind_time',  
				        'options' => array(  
				        'showAnim' => 'fold',  
				        'dateFormat' => 'yy-mm-dd',  
						),
						'htmlOptions'=>array(  
                        'style'=>'display:none',  
                        'maxlength'=>8,  
           				 ),
					));
?>
--
<?php 
				
				    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				        'attribute' => 'p_title',  
				        'value'=>'',//设置默认值
				        'name'=>'remind_time1',  
				        'options' => array(  
				        'showAnim' => 'fold',  
				        'dateFormat' => 'yy-mm-dd',  
						),
						'htmlOptions'=>array(  
                        'style'=>'display:none',  
                        'maxlength'=>8,  
           				 ),
					));
?>
</div>
<?php echo  CHtml::Button('查找', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'searchMyMember()'))) ?>		 
</div>
<?php }?>
<div style = 'margin-top:10px;'>
<?php echo  CHtml::Button('查看已上报   /   已完成任务', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'checkPro()'))) ?>		 
</div>
<div style="clear:both;margin-top:10px">
<h4 class="text-center"><span style="color:#0099FF"><?php echo $name?></span>的用户池</h4>
</div>
<input type ='hidden' value = '1' id = 'wait_allow'>
<input type ='hidden' value = '<?php echo $w_a_task_count?>' id = 'w_a_t'>
<input type ='hidden' value = '<?php echo $r_task_count?>' id = 'r_t_c'>
<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>提醒</th>
        <th>排名</th>
        <th>信息</th>
         <th>类别</th>   
       <!--  <th>平均收益/天</th>  -->
        <th>当天收益</th>
        <th>可获收益</th>
        <th>发布时间</th>
       <?php if($role!=4){?>
        <th >是否驳回</th>
       <?php }?>
        <th>任务详情</th>
        <th>相关信息</th>
    </tr>
    <input type='hidden' value = '<?php echo $now ;?>' id = 'now'>
    
    <?php  foreach ($data as $member){?>
    <?php  $time = date('Y-m-d',time()); if(isset($member['remind'])&&($member['tw_status']!=1)&&($member['remind']!=0)&&($time >=date('Y-m-d',$member['remind']))){?>
    <tr style = "color:red;font-weight:bold;">
    <?php }else{?>
    <tr>
    <?php }?>
   
    	<td><?php echo $member['username']?></td>
    	
    	<?php if(isset($member['remind'])&&($member['remind']!=0)){?>
    		<td>
    		<?php echo date('Y-m-d',$member['remind']);?>
    		</td>
    	<?php }else{?>
    		<td>
    			无
    		</td>
    	<?php }?>
    	
    		<td>
    			<?php echo $member['important'];?>
    		</td>
    	
    	
    	<td> 
    	<?php //echo CHtml::link(Bs::ICON_SEARCH, array('onclick' => 'show(\''.$member['mid'].'\')')); ?>
    	
    	<?php echo CHtml::button('信息', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'show(\''.$member['mid'].'\')'))); ?>
    	</td>
    	<!--  <td ><?php if($member['isfail']==0){echo "可继续";}elseif ($member['isfail']==1){echo '任务失败';}?></td>-->
    	
    	<td ><?php echo Task::getTypeName($member['type']);?></td> 
    	<?php $data = TaskWhen::getDataByNow($member['mid'],$member['type'],$member['a_time']);?>
    	
    	<?php if($member['type']==5){?>
    	<td>无</td>	 
    	<?php }else{?>
    	<td>
    		<div style='float:left;margin-right:5px;'>
		    	<div>发布：<?php echo round($data['the_day'],3)?></div>
		    	<div>昨天：<?php echo round($data['yesterday'],3)?></div>
	    	</div>
	    	<div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
    			<img src = '<?php echo $data['img']?>';>
    		</div>
    	</td>
    	<?php }?>
    	<td>
    		<?php if($member['type']==5){echo '回访任务无收益';}
    			  else{
    				echo round($data['data'],3);
    			}?>
    	</td>
    	
    	<td><?php echo date("Y-m-d",$member['createtime'])?></td>
    	
    	<?php if(($role!='4')){?>
    	<td >
    	<?php echo CHtml::button('驳回', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'back_task(\''.$member['tid'].'\',\''.$member['tw_id'].'\')'))); ?>
    	</td>    	
    	<?php }?>
    	
		<td>
		<div class='btn-group'>
		<?php $root_url = $this->createUrl('mytask');?>
		<?php if($role==3 || ($now==1)){?>
	 		<a class='btn btn-info btn-mini dropdown-toggle' target="_blank" href="<?php echo $root_url?>/tid/<?php echo $member['tid']; ?>/uid/<?php echo $id; ?>" >
        <?php }else{?>
        	<a class='btn btn-info btn-mini dropdown-toggle' target="_blank" href="<?php echo $root_url?>/tid/<?php echo $member['tid']; ?>/uid/<?php echo $id; ?>" >
        <?php }?>
        	<i class='icon-cog icon-white'></i><span class='caret'></span></a>
      		<ul class='dropdown-menu'>
      			
      		</ul>
      	</div>
        </td>
        <?php $tw_id = 0;$arr_list = getManageMenu( $member['mid'] ,$member['tw_id']);?>
        <td>
	        <div class='btn-group' id = bt_<?php echo $member['mid'] ; ?>>
	        	<a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $member['mid'] ;?>)";>
	        	<i class='icon-cog icon-white'></i><span class='caret'></span></a>
	        	<ul class="dropdown-menu" id = 'msg_<?php echo $member['mid'] ;?>'>
	        		<?php echo $arr_list;?>
	        	</ul>
	      	</div>
	      	
        </td>
        <?php if($now==1){?>
         <?php if ($member['type']==5){?>
        <td>
      		<span class="label label-info" style='margin-top:5px;'>
				<a style = 'color:white' href = '#' onclick='askforvisitetask(<?php echo $member['at_id'];?>)'>申请</a>
			</span>
        </td>
        <td>
       		<span class="label label-info" style='margin-top:5px;'>
				<a style = 'color:white' href = '#' onclick='delvisitetask(<?php  echo $member['at_id'];?>)'>无效</a>
			</span>
        </td>
        <?php }?>
        <?php }?>
    </tr>
   
    <?php }?>
    
</table>
‭<div class="pager">  
    <?php $this->widget("CLinkPager", array(  
        'pages' => $pages ,
    	'firstPageLabel' => '首页',    
	    'lastPageLabel' => '末页', 
    	'maxButtonCount'=>15   
    ));?>  
</div> 

	<div id="tasktype" title="任务类别选择" style="display:none;">
   		<span style = 'margin-top:10px;'>
   		<select id = 'task_type'>
   			<option value = '1'>新用户任务</option>
   			<option value = '2'>降量任务</option>
   		</select>
   		</span>
   		<span style = 'margin-top:10px;margin-left:40px;'>
<?php echo  CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO),array('id'=>'visi_type'))) ?>		 
   		</span>
   		
   </div>

<input type = 'hidden' id = 'time_hide' value = ''>

   <div id="modaltask" title="我的备注" style="display:none;">
	    <?php echo CHtml::beginForm('task', 'post', array('id' => 't_formtask')) ?>
	    <label id="suname"></label>
	    <dl class="dl-horizontal">
	    
	   	   <dt>用户级别</dt>
			    <dd>
			    <input width="20px" type = 'text' name = 'level' id = 'level' value = '';>
			    </dd>
			    <dd>
			    <span style='color:red'>只能键入数字，越大排名越靠前</span>
			    </dd>
	    	<dt>备注信息</dt>
			    <dd>
			    <textarea rows="5" cols="10" id = 'my_remark'></textarea>
			    </dd>
	    	<dt>提醒时间</dt>
			    <dd>
				<?php 
				
				    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				        'attribute' => 'p_title',  
				        'value'=>'',//设置默认值
				        'name'=>'date',  
				        'options' => array(  
				        'showAnim' => 'fold',  
				        'dateFormat' => 'yy-mm-dd',  
					),
					));
				?>
			    </dd>
			<input type = 'hidden' id = 'mid' value = ''>
			<input type = 'hidden' id = 'tw_id' value = ''>
	 		<dt>&nbsp;</dt>
	        <dd><?php echo  CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'remark()'))) ?></dd>			 
	    </dl>
	    <?php echo CHtml::endForm() ?>
	</div>
	
   <div id="membermsg" title="用户信息" style="display:none;">
   
   </div>


<!-- trigger element. a regular workable link -->

<script type="text/javascript">
    var MEMBER_INFO_URL = '<?php echo $this->createUrl('info') ?>';
    var TASK_INFO_URL = '<?php echo $this->createUrl('taskinfo') ?>';
    var TASK_POST_URL = '<?php echo $this->createUrl('task') ?>';
    var THIS_URL = '<?php echo $this->createUrl('nopro') ?>';
</script>



<script type="text/javascript">

$(function(){
	$("#download_now").tooltip({ effect: 'slide',direction:'down',position:'bottom center',
		relative :true,delay:'30'});
})
function checkPro(){
	var stat = '<?php echo $_SERVER["QUERY_STRING"];?>';
	
	var url = '<?php echo $this->createUrl('index') ?>'+'?'+stat;
	//alert(url);return false;
	location.href = url;
	
}
function askforvisitetask(at_id){
	var at_id = at_id;
	var url = '<?php echo $this->createUrl('askforvisitetask') ?>';
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
	var url = '<?php echo $this->createUrl('delvisitetask') ?>';
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
	var url = '<?php echo $this->createUrl('nopro') ?>';
	location.href = url;
	
}



function searchMyMember(){
	var type = '';
	type = $("#search_type").find("option:selected").val() ;
	var url = '<?php echo $this->createUrl('nopro') ?>';
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
		//alert(rem1);return false;
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
var url = '<?php echo $this->createUrl('backtask') ?>';

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
	var url = '<?php echo $this->createUrl('setmsg') ?>';
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

	var url = '<?php echo $this->createUrl('usermsg') ?>';
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
	var url = '<?php echo $this->createUrl('info') ?>';
	var mid = mid;
	var modal = $("#membermsg");
	modal.dialog("open");
	$.post(url,{mid:mid},function(data){
		modal.html(data);
		});
}


</script>
