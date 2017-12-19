<?php
/* @var $this CloseaccountController */

$this->breadcrumbs = array('封号管理');
$this->menu = array(
    /*array('label' => '批量封号', 'url' => array('batch'), 'visible' => Auth::check('manage.closeaccount.batch')),*/
);
?>
<h4 class="text-center">封号管理</h4>
<?php $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));?>

<div class="alert alert-danger">注意：每天导入业务数据后进行封号处理，封号后用户业务不能使用，封号日期内数据无效</div>

<?php echo CHtml::beginForm() ?>
<dl class="dl-horizontal">
    <dt>业务类别</dt>
    <dd><?php echo CHtml::dropDownList('type', '', Ad::getAdList()) ?></dd>
    <dt>要封闭的资源编号</dt>
    <dd><?php echo CHtml::textField('account') ?>&nbsp;<span class="label label-info">多个编号可用“,”分隔</span></dd>
    <dt>散量业务所属全部用户</dt>
    <dd><?php echo CHtml::textField('accountall') ?>&nbsp;<span class="label label-info">默认空，如全部封号输入“1”（全部封号为此业务官方不再做）</span></dd>

    <dt>封号开始日期</dt>
    <dd>
        <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input lang="date" class = "input-small" id="closedate"  name="closedate" size="10"  data-rule="required" type="text" >
        </div>
    </dd>

    <dt>&nbsp;</dt>
    <dd><?php echo CHtml::submitButton('提交封号', array('class' => 'btn btn-primary')) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

<script type="text/javascript">
$(function () {
    //日期控件
    $('.form_date').datetimepicker({
    language:'zh-CN', weekStart:1,todayBtn:1,
    autoclose:1,
    todayHighlight:1,
    startView:2,
    minView:2,
    forceParse:0
    });
});
</script>
<style type="text/css">
    dd{padding-top: 5px;padding-bottom: 5px;}
</style>

