<?php
/* @var $this SerachInfoController */
/* @var $model SerachinfoRecords */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => Bs::cls(Bs::FORM_HORIZONTAL),
    )); ?>

    <?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', Bs::cls(Bs::INPUT_XXLARGE) + array('rows' => 5)); ?>
            <?php echo $form->error($model, 'content', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>


    <div class="control-group">
        <div class="controls">
            <?php echo $form->hiddenField($model, 'sid') ?>
            <?php echo CHtml::submitButton('提交', Bs::cls(Bs::BTN_PRIMARY)); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
