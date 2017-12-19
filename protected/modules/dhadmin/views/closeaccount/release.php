<?php
/* @var $this CloseaccountController */

$this->breadcrumbs = array('封号管理');
$this->menu = array(array('label' => '单个封号', 'url' => array('index')),);
?>
<h4 class="text-center">解除封号</h4>
<?php $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));?>

<?php echo CHtml::beginForm() ?>
<dl class="dl-horizontal">
    <dt>业务类别</dt>
    <dd><?php echo CHtml::dropDownList('type', '', Ad::getAdList()) ?></dd>
    <dt>要解除的业务编号</dt>
    <dd><?php echo CHtml::textField('account') ?></dd>
    <dt>解除开始日期</dt>
    <dd><?php echo CHtml::textField('closedate', '', Bs::dateInput()) ?></dd>
    <dt>&nbsp;</dt>
    <dd><?php echo CHtml::submitButton('提交解除', Bs::cls(Bs::BTN_PRIMARY)) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

