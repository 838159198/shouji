
<style type="text/css">
    #AdvisoryRecords_content{width:800px;}
    .h-view .view {
        background-color: #F7F7F9;
        border: 1px solid #E1E1E8;
        margin: 2px;
        padding: 10px;
        border-radius: 6px 6px 6px 6px;
    }
    .h-view .view ul,li{list-style:none;list-style-type:none;}
</style>
<?php
/* @var $this AdvisoryrecordsController */
/* @var $model AdvisoryRecords */
/* @var $form CActiveForm */
?>

<div class="col-md-2" style="height:600px; margin-right:50px;">
    <div class="list-group">
        <li class="list-group-item active">用户管理</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/index");?>" class="list-group-item">用户管理</a>
    </div>
</div>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class'=>'form-horizontal'),
    )); ?>


    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', array('class'=>'control-label')); ?>
        <div class="controls" style="margin:20px;">
            <?php echo $form->textArea($model, 'content', array('class'=>'input-xxlarge') + array('rows' => 10)); ?>
            <?php echo $form->error($model, 'content', array('class'=>'label label-important')); ?>
        </div>
    </div>


    <div class="control-group">
        <div class="controls" style="margin:20px;">
            <?php echo $form->hiddenField($model, 'uid') ?>
            <?php echo CHtml::submitButton('提交', array('class'=>'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
