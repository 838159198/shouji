<?php
/* @var $this DefaultController */
/* @var $dataProvider IDataProvider */
/* @var $point float */
/* @var $date string */
/* @var $sum string */
/* @var $adList array */
$this->breadcrumbs = array('收益明细');

echo '<h4 class="text-center">收益明细</h4>';

/*$this->widget('zii.widgets.CMenu', array(
    'items' => array(array('label' => '网吧收益明细', 'url' => array('branch'))),
    'htmlOptions' => array('class' => 'breadcrumb')
));*/

echo CHtml::beginForm('income', 'get', array('class'=>'form-inline')),
CHtml::label('选择月份', 'm'),
CHtml::dropDownList('m', $date, Common::getDateMonthList()),
CHtml::submitButton('查询', array('class'=>'btn btn-info')),
CHtml::endForm();

function _date_format($date)
{
    return $date . ' ' . DateUtil::getWeek(date('w', strtotime($date)));
}

$columns = array();
$columns[] = array('name' => 'dates', 'headerHtmlOptions' => array('style' => 'color:#33ef94'),'value' => '$data[\'dates\']','type'=>'html', 'header' => '激活量/收益');
$columns[] = array('name' => 'amount','value' => '$data[\'id\']', 'header' => '总激活量');
$columns[] = array('name' => 'amount','value' => 'number_format($data[\'amount\'],2)', 'header' => '总收益');


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'summaryText' => $date,
    'columns' => $columns,
));
?>
<tr></tr>
<!--<table id="fsdfsd" style="width: 100%"><tr></tr></table>-->

<br>
<label class="btn btn-info" style="margin-bottom: 10px;"><span style="font-weight: bold;">合计：</span><?php echo "业务收益".number_format($sum, 2) ?><?php if($agentincome>0){echo " + 推广奖金".$agentincome;} ?></label>

<div class="alert alert-info">
    <ul>
        <li>只显示最近三个月的收入数据</li>
        <li>提成按天与按月计算合计数有误差，实际金额以财务提现中的实际发生额为准</li>
    </ul>
</div>
<style type="text/css">
    .grid-view table.items th{background:none;background-color: #4A515B;color: #ffffff;line-height: 24px;}
    .grid-view table.items tr{line-height: 24px;}
    ul,li{list-style: none;list-style-type: none;padding: 0px;}
    input[type="submit"]{height: 22px; line-height: 22px;margin-left: 10px;margin-top: -2px;padding-top: 0px;}
    td{text-align: center}
    #fsdfsd td{text-align: center; font-size: 12px; font-weight: bold; padding: 0.3em; color: #000000;border: 1px #D0E3EF solid;}
    #fsdfsd td span{ color: #000000; }
</style>
