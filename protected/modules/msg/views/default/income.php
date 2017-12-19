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


    $user = Yii::app()->user;
    $uid=$this->uid;
    $manageid=$user->getState('member_manage');
    $member=Member::model()->find("id=$uid");
    $agent=$member["agent"];


$columns = array();
$columns[] = array('name' => 'dates', 'headerHtmlOptions' => array('style' => 'color:#33ef94'),'value' => '$data[\'date\']','type'=>'html', 'header' => '日期');
if($agent==99 || $manageid==1)
{
    $columns[] = array('name'=>'install','value' => 'number_format($data[\'install_income\'],2)', 'header' => '安装收益');
    $columns[] = array('name'=>'arrive','value' => 'number_format($data[\'arrive_income\'],2)', 'header' => '到达收益');
}

$columns[] = array('name'=>'activate','value' => 'number_format($data[\'activate_income\'],2)', 'header' => '激活收益');
//$columns[] = array('name' => 'amount','value' => '$data[\'id\']', 'header' => '总激活量');
if($agent==96)
{
    $columns[] = array('name'=>'activate','value' => 'number_format($data[\'activate_income\'],2)', 'header' => '总收益');
}
else
{
    $columns[] = array('name' => 'amount','value' => 'number_format($data[\'all_income\'],2)', 'header' => '总收益');
}


$columns[] =array(
    'header'=>'操作',
    'htmlOptions'=>array('class'=>'button-column','style'=>"width:120px;text-align:center;"),
    'class' => 'CButtonColumn',
    'updateButtonUrl'=>'Yii::app()->createUrl("");',
    'template'=>'{update}',
    'afterDelete'=>'function(link,success,data){alert(data) }',
    'buttons'=>array(
        'update'=>array(
            'label'=>'查看详情',
            'imageUrl'=>null,
            'options' => array('class'=>'btnclick btn btn-primary'), // HTML 标签属性设置
        ),
    ),
);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'summaryText' => $date,
    'columns' => $columns,
));
?>
<tr></tr>
<br>
<label class="btn btn-info" style="margin-bottom: 10px;"><span style="font-weight: bold;">合计：</span><?php echo "业务收益".number_format($sum, 2) ?><?php if($agentincome>0){echo " + 推广奖金".$agentincome;} ?></label>

<div class="alert alert-info">
    <ul>
        <li>只显示最近三个月的收入数据</li>
        <li>提成按天与按月计算合计数有误差，实际金额以财务提现中的实际发生额为准</li>
    </ul>
</div>
<script type="text/javascript" src="/css/msg/default.js"></script>
<link rel="stylesheet" type="text/css" href="/css/newdt/default.css">
