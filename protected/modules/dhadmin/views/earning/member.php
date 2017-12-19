<?php
/** @var $this EarningController */
/** @var $dataProvider IDataProvider */
/** @var $point float */
/** @var $date string */
/** @var $sum string */
/** @var $adList array */
/** @var $uid int */
/** @var $member MemberInfo */

$this->breadcrumbs = array('收益明细');
echo '<h4 class="text-center">用户『' . $member->username . '』的收益明细记录</h4>';

echo CHtml::beginForm('member', 'get', Bs::cls(Bs::FORM_INLINE)),
CHtml::label('选择月份', 'date'),
CHtml::textField('date', $date, Bs::dateInput()),
CHtml::hiddenField('uid', $uid),
CHtml::submitButton('查询', Bs::cls(Bs::BTN_INFO)),
CHtml::endForm();

function _date_format($date)
{
    return $date . ' ' . DateUtil::getWeek(date('w', strtotime($date)));
}

$columns = array();
$columns[] = array('name' => 'dates', 'value' => '_date_format($data[\'dates\'])', 'header' => '日期');
foreach ($adList as $k => $v) {
    $columns[] = array('name' => $k, 'header' => $v, 'type' => 'html');
}
$columns[] = array('name' => 'amount', 'header' => '收益总额');


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'summaryText' => $date,
    'columns' => $columns,
));
?>

<label class="label label-info">合计：<?php echo number_format($sum, 2) ?></label>
