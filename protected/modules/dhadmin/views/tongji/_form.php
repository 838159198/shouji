<?php
/* @var $this BindSampleController */
/* @var $model BindSample */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bind-sample-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'form-horizontal')
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">广告类型</label>
		<div class="col-sm-5">
			<?php if(empty($model->type)) {echo $form->textField($model,'type',array('class'=>"form-control"));} else {echo $form->textField($model,'type',array('readonly'=>'readonly','class'=>"form-control"))."（不能更改）";} ?>
		</div>
		<div class="col-md-5"><?php echo $form->error($model, 'type',array('class'=>"errorMessageTips"));?></div>
	</div>

	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">广告key </label>
		<div class="col-sm-5">
			<?php if(empty($model->val)) {echo $form->textField($model,'val',array('class'=>"form-control"));} else {echo $form->textField($model,'val',array('readonly'=>'readonly','class'=>"form-control"))."（不能更改）";} ?>
		</div>
		<div class="col-md-5"><?php echo $form->error($model, 'val',array('class'=>"errorMessageTips"));?></div>
	</div>

	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">状态</label>
		<div class="col-sm-5">
			<?php echo $form->dropDownList($model, 'status', array(0 => '已分配',1=>'未分配'),array('class'=>"form-control")) ?>

		</div>
		<div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
	</div>

	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">分配类型</label>
		<div class="col-sm-5">
			<?php echo $form->dropDownList($model, 'allot', array(0 => '自动分配',1=>'手动分配'),array('class'=>"form-control")) ?>
		</div>
		<div class="col-md-5"><?php echo $form->error($model, 'allot',array('class'=>"errorMessageTips"));?></div>
	</div>

	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">是否已被封</label>
		<div class="col-sm-5">
			<?php echo $form->dropDownList($model, 'closed', array(0 => '可用',1=>'已封号'),array('class'=>"form-control")) ?>
		</div>
		<div class="col-md-5"><?php echo $form->error($model, 'closed',array('class'=>"errorMessageTips"));?></div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary">确认提交</button>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->