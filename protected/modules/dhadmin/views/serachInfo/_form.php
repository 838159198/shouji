<style type="text/css">
    form{width:1100px;margin: 0 auto;}
	.row{float:left;width:300px; padding-left:50px; padding-bottom: 20px;}
    .note{margin-bottom: 30px;}
	.buttons{width:1100px;float:left; text-align:center;padding-top:30px;}
	.buttons input[type="submit"]{width:120px; height:30px; font-weight:bold; letter-spacing:4px;}
	.respan{ color:red; font-size:15px; font-weight:bold;padding-left:30px;}
	.errorSummary ul{color:red; padding-bottom:20px;padding-left:6px;list-style: none;list-style-type: none;padding-left: 30px;}
	.errorSummary p{ padding-left:30px;}

</style>
<?php
/* @var $this SerachInfoController */
/* @var $model SerachInfo */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'serach-info-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="respan">*QQ号码与电话号码做为用户注册比对条件，请按照数据规则填写</span></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mail'); ?>
		<?php echo $form->textField($model,'mail',array('size'=>20,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qq'); ?>
		<?php echo $form->textField($model,'qq',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<?php echo $form->hiddenField($model,'reg_status', array('value'=>'0'));?>
	<?php echo $form->hiddenField($model,'status', array('value'=>'0'));?>
	<?php echo $form->hiddenField($model,'manage_id', array('value'=>$uid));?>
	<?php echo $form->hiddenField($model,'search_id', array('value'=>$uid));?>
	<?php echo $form->hiddenField($model,'createtime', array('value'=>date('Y-m-d H:i:s',time())));?>
	<?php echo $form->hiddenField($model,'motifytime', array('value'=>date('Y-m-d H:i:s',time())));?>
	<?php echo $form->hiddenField($model,'id');?>

	<div class="row">
		<?php echo $form->labelEx($model,'com'); ?>
		<?php echo $form->textField($model,'com',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area'); ?>
		<?php echo $form->textField($model,'area',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'area'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source'); ?>
		<?php echo $form->textField($model,'source',array('size'=>20,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textField($model,'content',array('size'=>20,'maxlength'=>200)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'提醒时间'); ?>
		<?php echo $form->textField($model,'tixingtime',array('size'=>20,'maxlength'=>200)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建信息' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(function () {
        $("#SerachInfo_tixingtime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#SerachInfo_tixingtime").datepicker("option", "minDate", selectedDate);
            }
        });
    })
</script>