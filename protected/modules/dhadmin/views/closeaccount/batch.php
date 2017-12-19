<?php
/* @var $this CloseaccountController */

$this->breadcrumbs = array('封号管理');
$this->menu = array(array('label' => '单个封号', 'url' => array('index')),);
?>
<h4 class="text-center">封号管理</h4>
<?php $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));?>

<div class="alert alert-danger">强烈建议：每天导入业务数据后进行封号处理</div>

<?php echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')) ?>
<dl class="dl-horizontal">
    <dt>业务类别</dt>
    <dd><?php echo CHtml::dropDownList('type', '', Ad::getAdList()) ?></dd>
    <dt>编号Excel文件：</dt>
    <dd><?php echo CHtml::fileField('account') ?></dd>
    <dt>封号日期：</dt>
    <dd><?php echo CHtml::textField('closedate', '', Bs::dateInput()) ?></dd>
    <dt>&nbsp;</dt>
    <dd><?php echo CHtml::submitButton('提交封号', Bs::cls(Bs::BTN_PRIMARY)) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

