<?php
$this->breadcrumbs = array(
    '我的用户池' => array('index'),
	'当前任务'=>array('mytask'),
);

?>
<script type="text/javascript">
var url_reply = '<?php echo $this->createUrl('reply');?>'; 
</script>

<h4 class="text-center"><span style="color:red"><?php echo $model[0]['title'];?></span>任务的信息</h4>

<div style = 'border:1px solid #e7e7e7;'>
<dl class="dl-horizontal"> 
	<div style="float:left;margin-left:100px">
	<label for="m_category" >任务申请时间<span class="required"></span></label>
	<input type = 'text' name='a_time' value='<?php echo date('Y-m-d H:i',$model[0]['a_time']);?>'>
	<label for="m_category" >任务上报时间<span class="required"></span></label>
	<input type = 'text' name='p_time' value='<?php if(empty($model[0]['porttime'])){echo '未上报';}else{echo date('Y-m-d H:i',$model[0]['porttime']);}?>'>
    <label for="m_category" >任务发布时间<span class="required"></span></label>
	<input type = 'text' name='s_time' value='<?php echo date('Y-m-d H:i',$model[0]['ttime']);?>'>
    ‭<label for="m_category" >任务内容 <span class="required"></span></label>
	<textarea rows="5" cols="20" value = ''><?php echo $model[0]['content'];?></textarea>
    <label for="m_category" >收款人姓名 <span class="required"></span></label>
	<input type = 'text' name='m_name' value='<?php echo $model[0]['holder'];?>'>

	</div>
	<div style="float:left;margin-left:100px">
	<label for="m_category" >用户注册时间<span class="required"></span></label>
	<input type = 'text' name='rej_time' value='<?php echo date('Y-m-d H:i',$model[0]['jointime']);?>'>
	<label for="m_category" >用户最后登录<span class="required"></span></label>
	<input type = 'text' name='last_time' value='<?php echo date('Y-m-d H:i',$model[0]['overtime']);?>'>
	<label for="m_category" >终端数量<span class="required"></span></label>
    <input type = 'text' name='clients' value='<?php echo $model[0]['clients'];?>'>
    <label for="m_category" >代理人(代理商，官网)<span class="required"></span></label>
    <input type = 'text' name='agent' value='<?php if($model[0]['agent']==0){echo "官网";}else{echo $model[0]['agent'];}?>'>
    <label for="m_category" >用户类型(普通用户，代理商)<span class="required"></span></label>
    <input type = 'text' name='m_type' value='<?php if($model[0]['type']==0){echo "普通用户";}elseif($model[0]['type']==1){echo "代理商";}?>'>
    <label for="m_category" >联系电话 <span class="required"></span></label>
    <input type = 'text' name='phone' value='<?php echo $model[0]['tel'];?>'>
    <label for="m_category" >注册电话 <span class="required"></span></label>
    <input type = 'text' name='regist_tel3' value='<?php echo $model[0]['regist_tel'];?>'>
    </div>
</dl>
</div>
<?php if($model[0]['type']!=5){?>
<div style = 'float:left;display:<?php if(($now==1)){echo 'display';}else{echo 'none';}?>'>
<div style = 'float:left'>
<div class="control-group">
		<div class="controls">
		      <textarea rows="5" cols="50" value = '任务回复' id = 'my_reply'></textarea>
		</div>
</div>
<input type = 'hidden' id = 'tid' value = '<?php echo $tid;?>'>
<?php if($task_msg['tw_status']==1){?>
<div class="controls" style="margin-left:5px;margin-bottom:10px;">
<span class="label label-info" style='margin-top:5px;'>任务已经提交</span></br>
</div>
<?php }elseif($task_msg['pro']==1){?>
<div class="controls" style="margin-left:5px;margin-bottom:10px;">
<span class="label label-info" style='margin-top:5px;'>任务可以提交</span></br>
</div>
<?php }else{?>
<div class="controls" style="margin-left:5px;margin-bottom:10px;">
<span class="label label-info" style='margin-top:5px;'>任务发布/申请到上报任务时间未满30天</span></br>
<span class="label label-info" style='margin-top:5px;'>任务不可以提交/任务失败可直接上报</span></br>
</div>
<?php }?>

		<div class="control-group" style = 'float:left;'>
		        <div class="controls">
		            <?php echo CHtml::submitButton('提交', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE),array('onclick' => 'reply_task(0)'))) ?>
		        </div>
		</div>
		<div class="control-group" style = 'float:left;margin-left:50px;'>
		        <div class="controls">
		            <?php echo CHtml::submitButton('失败', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE),array('onclick' => 'reply_task(1)'))) ?>
		        </div>
		</div>

</div>
</div>
<div style='float:left;border:0px solid #e7e7e7;margin-left:50px;display:<?php if($role!=4){echo 'display';}else{echo 'none';}?>'>
		<label for="m_category" style = 'margin-top:20px'>管理员评分<span class="required"></span></label>
		<fieldset class="rating">
		    <legend><?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']!=0)){echo "已评分/得分：".$task_msg['tw_score'];}else{echo "评分:";}?></legend>
		    <input onclick ="getScore(this)" type="radio" id="star5" name="rating" value="5" <?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']==5)){echo 'checked';}?>/><label for="star5" title="Rocks!">5 stars</label>
		    <input onclick ="getScore(this)" type="radio" id="star4" name="rating" value="4" <?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']==4)){echo 'checked';}?>/><label for="star4" title="Pretty good">4 stars</label>
		    <input onclick ="getScore(this)" type="radio" id="star3" name="rating" value="3" <?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']==3)){echo 'checked';}?>/><label for="star3" title="Meh">3 stars</label>
		    <input onclick ="getScore(this)" type="radio" id="star2" name="rating" value="2" <?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']==2)){echo 'checked';}?>/><label for="star2" title="Kinda bad">2 stars</label>
		    <input onclick ="getScore(this)" type="radio" id="star1" name="rating" value="1" <?php if(isset($task_msg['tw_score'])&&($task_msg['tw_score']==1)){echo 'checked';}?>/><label for="star1" title="Sucks big time">1 star</label>
		</fieldset>
</div>
<input type = 'hidden' id = 'accept' value = '<?php echo $task_msg['accept_id'];?>'>
<input type = 'hidden' id = 'publish' value = '<?php echo $task_msg['publish_id'];?>'>
<input type = 'hidden' id = 'tw_id' value = '<?php echo $task_msg['tw_id'];?>'>
<input type = 'hidden' id = 'tw_status' value = '<?php echo $task_msg['tw_status'];?>'>
<input type = 'hidden' id = 't_id' value = '<?php echo $task_msg['t_id'];?>'>
<input type = 'hidden' id = 'tw_isset' value = '<?php echo $task_msg['tw_isset'];?>'>
<input type = 'hidden' id = 'role' value = '<?php echo $role;?>'>
<input type = 'hidden' id = 't_sendtime' value = '<?php echo $task_msg['t_sendime'];?>'>

<input type = 'hidden' id = 'type' value = '<?php echo $model[0]['type'];?>'>
<input type = 'hidden' id = 'mid' value = '<?php echo $model[0]['mid'];?>'>

<input type = 'hidden' id = 't_mid' value = ''>
<input type = 'hidden' id = 'monday' value = '<?php echo $monday;?>'>
<?php }?>

<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $form CActiveForm */
/* @var $down array */
?>
<script type="text/javascript">
$(document).ready(function(){

    $('.item .delete').click(function(){

        var elem = $(this).closest('.item');

        $.confirm({
            'title'		: 'Delete Confirmation',
            'message'	: 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
            'buttons'	: {
                'Yes'	: {
                    'class'	: 'blue',
                    'action': function(){
                        elem.slideUp();
                    }
                },
                'No'	: {
                    'class'	: 'gray',
                    'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
                }
            }
        });

    });

});
</script>






