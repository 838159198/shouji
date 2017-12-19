<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $form CActiveForm */
/* @var $down array */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'task-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => Bs::cls(Bs::FORM_HORIZONTAL),
    )); ?>

    <div class="alert">带（*）的内容是必须填写的</div>

    <?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'isfail', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'isfail', TaskWhen::getIsFailList(), Bs::cls(Bs::INPUT_MINI)); ?>
            <span class="label label-info">如果此任务已无法进行，请选择是</span>
            <?php echo $form->error($model, 'isfail', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', Bs::textArea()); ?>
            <?php echo $form->error($model, 'content', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::submitButton('提交', Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE)); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
