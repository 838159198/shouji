<?php
/* @var $this BindSampleController */
/* @var $data BindSample */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('val')); ?>:</b>
	<?php echo CHtml::encode($data->val); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('allot')); ?>:</b>
	<?php echo CHtml::encode($data->allot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closed')); ?>:</b>
	<?php echo CHtml::encode($data->closed); ?>
	<br />


</div>