<?php
/**
 * @var CloseaccountController $this
 */

$this->breadcrumbs = array('底边条特殊封号');
?>
<h4 class="text-center">底边条特殊封号</h4>

<div class="alert alert-danger">只注销底边条业务编号的当日收入，对其他时间无影响</div>

<?php echo CHtml::beginForm() ?>
<dl class="dl-horizontal">
    <dt>要封闭的业务编号</dt>
    <dd><?php echo CHtml::textField('account') ?>&nbsp;<span class="label label-info">多个编号可用“,”分隔</span></dd>
    <dt>封号日期</dt>
    <dd><?php echo CHtml::textField('closedate', '', Bs::dateInput()) ?></dd>
    <dt>&nbsp;</dt>
    <dd><?php echo CHtml::submitButton('提交封号', Bs::cls(Bs::BTN_PRIMARY)) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

