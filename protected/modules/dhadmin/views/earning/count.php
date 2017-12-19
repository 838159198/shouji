<?php
/**
 *
 * @var $member MemberInfo
 * @var EarningController $this
 * @var IDataProvider $dataProvider
 * @var IDataProvider $paylogData
 * @var $sum float
 * @var $memberBill MemberBill
 */
$this->breadcrumbs = array('收入合计');
echo '<h4 class="text-center">用户『' . $member->username . '』的收入合计</h4>';
$columns = array();
$columns[] = array('name' => 'date', 'header' => '日期');
foreach (Ad::getAdList() as $k => $v) {
    $columns[] = array('name' => $k, 'header' => $v, 'type' => 'html');
}
$columns[] = array('name' => 'count', 'header' => '合计');
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'columns' => $columns,
));

echo '<div class="label">合计：' . $sum . '</div>';

echo '<h4 class="text-center">用户『' . $member->username . '』的付款记录</h4>';

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $paylogData,
    'columns' => array(
        'sums',
        array(
            'name' => 'ask_time',
            'value' => "DateUtil::getDate(strtotime(\$data->ask_time))"
        ),
        array(
            'name' => 'answer_time',
            'value' => "DateUtil::getDate(strtotime(\$data->answer_time))"
        ),
        array(
            'name' => 'status',
            'value' => "\$data->status==0?((date('Y-m')==date('Y-m',strtotime(\$data->ask_time)))?'等待支付':'申请成功'):'支付成功'"
        )
    )
));

echo '<div class="label">已支付：' . $memberBill->paid . '&nbsp;余额：' . $memberBill->surplus . '</div>';
echo '<p>&nbsp;</p>';
