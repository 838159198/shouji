<style type="text/css">
    .alert {
        padding: 8px 35px 8px 14px;
        margin-bottom: 20px;
        text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        background-color: #fcf8e3;
        border: 1px solid #fbeed5;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    input[type="text"], input[type="password"], .ui-autocomplete-input, textarea, .uneditable-input {
        display: inline-block;
        padding: 4px;
        font-size: 13px;
        line-height: 18px;
        color: #808080;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-top:10px; margin-bottom:10px;
    }
    select{
        width: 220px;
        background-color: #fff;
        border: 1px solid #ccc;
        height: 30px;
        line-height: 30px;
        display: inline-block;
        padding: 4px 6px;
        margin-bottom: 10px;
        font-size: 14px;
        color: #555;
        vertical-align: middle;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        margin-top:10px; margin-bottom:10px;
    }
</style>
<?php
/* @var $this MailController */
/* @var $model Mail */
/* @var $member Member */
/* @var $suser */

$this->breadcrumbs = array(
    '群发站内信'
);

?>
    <h4 class="text-center">给用户群发站内信<span style="font-size: 14px;color: red;font-weight: bold;"><a href="logmail" target="_blank">（查看所有站内信）</a> </span></h4>
<?php
    $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
)); ?>
<?php $this->renderPartial('_formMailToUidList', array('model' => $model,'suser' => $suser)); ?>