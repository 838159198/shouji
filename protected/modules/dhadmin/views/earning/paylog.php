<?php
/**
 *
 * @var EarningController $this
 * @var IDataProvider $data
 * @var MemberInfo $member
 */
$this->breadcrumbs = array(
    '付款记录',
);

echo '<h4 class="text-center">用户『' . $member->username . '』的付款记录</h4>';

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $data,
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