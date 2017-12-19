<?php
/* @var $this SerachInfoSaleController */
/* @var $data SerachInfo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tel')); ?>:</b>
	<?php echo CHtml::encode($data->tel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mail')); ?>:</b>
	<?php echo CHtml::encode($data->mail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qq')); ?>:</b>
	<?php echo CHtml::encode($data->qq); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reg_status')); ?>:</b>
	<?php echo CHtml::encode($data->reg_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('tixingtime')); ?>:</b>
	<?php echo CHtml::encode($data->tixingtime); ?>
	<br />
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('com')); ?>:</b>
	<?php echo CHtml::encode($data->com); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area')); ?>:</b>
	<?php echo CHtml::encode($data->area); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source')); ?>:</b>
	<?php echo CHtml::encode($data->source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manage_id')); ?>:</b>
	<?php echo CHtml::encode($data->manage_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createtime')); ?>:</b>
	<?php echo CHtml::encode($data->createtime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('motifytime')); ?>:</b>
	<?php echo CHtml::encode($data->motifytime); ?>
	<br />

	*/ ?>

</div>