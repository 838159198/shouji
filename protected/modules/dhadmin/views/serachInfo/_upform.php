<style type="text/css">
	.row{float:left;width:300px; padding-left:50px;}
	.buttons{width:920px;float:left; text-align:center;padding-top:30px;}
	.buttons input[type="submit"]{width:120px; height:30px; font-weight:bold; letter-spacing:4px;}
	.respan{ color:red; font-size:15px; font-weight:bold;padding-left:30px;padding-top:20px; padding-bottom:30px;}
	.errorSummary ul{color:red; padding-bottom:20px;padding-left:6px;}
	.errorSummary p{ padding-left:30px;}
    .form{width: 980px;margin: 0 auto;}
    input[type="text"]{margin-bottom: 15px;margin-right: 30px;}
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
		<?php if(!empty($model->name)){
			echo $form->textField($model,'name',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tel'); ?>
		<?php if(!empty($model->tel)){
			echo $form->textField($model,'tel',array('readonly' => 'readonly','size'=>30,'maxlength'=>50));
		}
		else{
			echo $form->textField($model,'tel',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mail'); ?>
		<?php if(!empty($model->mail)){
			echo $form->textField($model,'mail',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'mail',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qq'); ?>
		<?php if(!empty($model->qq)){
			echo $form->textField($model,'qq',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'qq',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<?php echo $form->hiddenField($model,'motifytime', array('value'=>date('Y-m-d H:i:s',time())));?>
	<?php echo $form->hiddenField($model,'id');?>

	<div class="row">
		<?php echo $form->labelEx($model,'com'); ?>
		<?php if(!empty($model->com)){
			echo $form->textField($model,'com',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'com',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area'); ?>
		<?php if(!empty($model->area)){
			echo $form->textField($model,'area',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'area',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source'); ?>
		<?php if(!empty($model->source)){
			echo $form->textField($model,'source',array('readonly' => 'readonly','size'=>30,'maxlength'=>30));
		}
		else{
			echo $form->textField($model,'source',array('size'=>30,'maxlength'=>30));
		}?>

	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'提醒时间'); ?>
        <?php echo $form->textField($model,'tixingtime',array('size'=>30,'maxlength'=>30)); ?>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br>
	<div class="row" style="">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php if(!empty($model->content)){
		 echo $form->textField($model,'content',array('readonly' => 'readonly','size'=>69,'maxlength'=>200));
		}
		else{
		echo $form->textField($model,'content',array('size'=>70,'maxlength'=>200));
		}?>

	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '修改信息' : '修改信息'); ?>
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