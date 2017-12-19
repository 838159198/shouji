<?php
/* @var $this CloseaccountController */
/* @var $type string */

$this->breadcrumbs = array('封号管理');
?>
<h4 class="text-center">导入用户解封后数据</h4>

<?php echo CHtml::beginForm() ?>
<dl class="dl-horizontal">
    <dt>业务类型</dt>
    <dd><?php echo CHtml::dropDownList('type', $type, Ad::getAdList()) ?></dd>
    <dt>要导入的业务编号</dt>
    <dd><?php echo CHtml::textField('account') ?></dd>
    <dt>导入日期</dt>
    <dd><?php echo CHtml::textField('closedate', '', Bs::dateInput()) ?></dd>
    <dt>&nbsp;</dt>
    <dd><?php echo CHtml::submitButton('导入', Bs::cls(Bs::BTN_PRIMARY)) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

