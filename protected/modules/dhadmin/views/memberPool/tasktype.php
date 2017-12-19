<?php
$this->breadcrumbs = array(
    '我的用户池' => array('IndexNoPro'),
	'任务提醒列表' => array('TaskType'),
);
?>

<h4 class="text-center"><span style="color:black">查看任务类型</h4>


<div style='width:500px;height:500px;margin-top:40px;'>
<ul>
	<li style = 'margin:20px;color:black;font-size:16px;'>
		<span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('被拒绝任务列表', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'checkAllow(0)'))); 	?> 
		</span>
	</li>
	<li style = 'margin:20px;color:black;font-size:16px;'>
		<span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('待批准任务列表 ', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'checkAllow(2)'))); ?>	 
		</span>
	</li>
	<li style = 'margin:20px;color:black;font-size:16px;'>
		<span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('回访任务列表', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'tovisit()'))); 	 ?>
		</span>
	</li>
	<li style = 'margin:20px;color:black;font-size:16px;'>
		<span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('提醒时间到期任务列表', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'remindlist()'))); ?>	
		</span>
	</li>
    <li style = 'margin:20px;color:black;font-size:16px;'>
        <span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('逾期未联系用户-任务列表', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'showContactlist()'))); ?>
		</span>
    </li>
    <li style = 'margin:20px;color:black;font-size:16px;'>
        <span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php echo  CHtml::Button('用户追踪列表', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'showMarklist()'))); ?>
		</span>
    </li>
<!--    <li style = 'margin:20px;color:black;font-size:16px;'>
        <span>点击查看：</span>
		<span style = 'margin-left:20px;'>
			<?php /*echo  CHtml::Button('HowToUseIt？使用帮助', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'help()'))); */?>
		</span>
    </li>-->
</ul>
</div>

<?php $this->renderPartial('/layouts/pop') ?>
