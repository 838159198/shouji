<?php
/* @var $this RoleController */
/* @var $model Role */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => Bs::cls(Bs::FORM_HORIZONTAL),
    )); ?>

    <div class="alert">带（*）的内容是必须填写的</div>

    <?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'name', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'name', array('size' => 32, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'name', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'fid', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'fid', $model->getDownList()) ?>
            <?php echo $form->error($model, 'password', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::submitButton('提交', Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE)); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
