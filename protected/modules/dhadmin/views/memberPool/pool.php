<?php 
$this->breadcrumbs = array(
    '用户池' => array('pool'),
);
?>
<h4 class="text-center">用户池</h4>
<table class="table table-hover table-striped table-condensed">
    <tr>
    	<th>任务申请</th>
    	<?php if($role!=3){?>
        <th><label><input type="checkbox" value="1" id="member-info-grid-all"/></label></th>
        <?php }?>
        <th>用户名</th>
        <th>联系电话</th>
        <th>收款人姓名</th>
        <th>邮箱</th>
        <th>注册时间</th>
        <th>用户身份</th>
        <th>终端数量</th>
        <th>用户类型</th>
    </tr>
    <?php foreach ($list as $member){?>
    <tr>
    	<th><?php echo CHtml::submitButton('申请', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'add_mission( \''.$member['meid'].'\')')));  ?></th>
    	
    	<?php if($role!=3){?>
    		<td><label>
    			<input name="checkbox" type="checkbox" value="<?php echo $member['meid'];?>" id="member-info-grid-all"/>
    			</label>
    		</td>
    	<?php }?>
    	<td><?php echo $member['username']?></td>
    	<td><?php echo $member['tel']?></td>
    	<td><?php echo $member['holder']?></td>
    	<td><?php echo $member['mail']?></td>
    	<td><?php echo date("Y-m-d H:i",$member['jointime'])?></td>
    	<?php if($member['type'] ==0){
    		echo "<td>普通用户</td>";
    	}elseif($member['type'] ==1){
    		echo "<td>代理商</td>";
    	}?>
    	<td><?php echo $member['clients']?></td>
    	<td><?php echo $member['mname']?></td>
    </tr>
    <?php }?>
</table>

‭<div class="pager">  
    <?php $this->widget("CLinkPager", array(  
        'pages' => $pages  
    ));?>  
</div> 
<?php if($role!=3){?>
<?php echo CHtml::button('发布任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'task()'))); ?>
<?php }?>
<div id="ask_for_task" title="申请任务" style="display:none;width:300px;border:1px solid #e7e7e7;margin:auto;">
<?php echo CHtml::label('申请任务：', 'm_category')?>
		<input type='hidden' value='' id = 'hide_uid'>
		<select onchange="ask_for_task(this)" id = 'a_task'>
		<?php foreach($manage_list2 AS $tiem){?>
    			<option id="<?php echo $tiem['id']?>" a_id ='<?php echo $tiem['id']?>'  value = "<?php echo $tiem['name']?>">
    				<?php echo $tiem['name']?>
    			</option >
    	<?php }?>
    	</select>
<?php echo CHtml::button('申请', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'ask_for_up()'))); ?>
</div>

<div id="modaltask" title="发布任务" style="display:none;">
    <?php echo CHtml::hiddenField('t_uname') ?>
    <?php echo CHtml::hiddenField('t_mid') ?>
    <label id="suname"></label>
    <dl class="dl-horizontal">
        <dt>接收人：</dt>
        <dd>
	        <select id = 'a_list'>
		        <?php foreach($manage_list AS $list){?>
		        	<option id = '<?php echo $list['id']?>'><?php echo $list['name'];?></option>
		        <?php }?>
	        </select>
        </dd>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::button('确认发布',  array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'makeSureAskTask()'))) ?></dd>
    </dl>
</div>



