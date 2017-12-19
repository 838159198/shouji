<?php
$this->breadcrumbs = array('盒子列表');
$this->menu = array(
    array('label' => '导入数据', 'url' => array('import/index')),
    array('label' => '用户收益', 'url' => array('earning/index')),
    array('label' => '用户业务', 'url' => array('earning/memberpro')),
);
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">用户收益</h1>
</div>

<style type="text/css">
    .grid-view{width:880px;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
</style>
<?php
/* @var $this EarningController */
/* @var $dataProvider CArrayDataProvider */
/* @var $totalData CArrayDataProvider */
/* @var $totalDataxx CArrayDataProvider */
/* @var $date string */


$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));
//$columns[] = 'username';
$columns[] = array('name' => "用户名", 'value' => '$data[\'username\']');
$columns[] = array('name' => "总计", 'value' => '$data[\'date\']');
$columns[] = array('name' => "类型", 'value' => '$data[\'type\']< 3 ? "线上":"<span style=color:red;>线下</span>"',"type"=>"html");
foreach (Ad::getAdList2() as $k => $v) {
    $columns[] = array('name' => $v, 'value' => '$data[\'' . $k . '\']');
}
//$columns[] = 'date';

echo CHtml::beginForm('index', 'get', array('class'=>'input-append')),
    '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
CHtml::endForm();

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => $columns,
));

$this->widget('zii.widgets.grid.CGridView', array(
    'summaryText' => '线上合计',
    'dataProvider' => $totalData,
    'columns' => $columns,
));
$this->widget('zii.widgets.grid.CGridView', array(
    'summaryText' => '线下合计',
    'dataProvider' => $totalDataxx,
    'columns' => $columns,
));
?>
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