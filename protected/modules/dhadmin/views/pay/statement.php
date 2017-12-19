<style type="text/css">
    /*.grid-view{ margin: 10px;}*/
    .grid-view table.items th,.grid-view table.items td{ min-width: 70px; font-size: 14px;}
    .label{color:#000020; margin-top:20px;display:block; float:left; margin-left:228px;}
    .grid-view {padding:0px;}
    #member-income-grid{width:1000px;float:left;}
    #type-income-grid{width:1000px;float:left}
</style>
<?php
/* @var $this PayController */
/* @var $year string */
/* @var $month string */
/* @var $incomeSum string */
/* @var $data IDataProvider */
/* @var $dataxx IDataProvider */
/* @var $memberIncomeData IDataProvider */

$this->breadcrumbs = array(
    '财务管理' => 'index',
);
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">月财务统计</h1>
</div>
<div class="col-md-2" style="height: 400px;">
    <div class="list-group">
        <li class="list-group-item active">财务管理</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/index");?>" class="list-group-item">财务说明</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/stats");?>" class="list-group-item">统计上月收益至余额</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=0");?>" class="list-group-item">未支付记录</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=1");?>" class="list-group-item">已支付记录</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/statement");?>" class="list-group-item">月财务统计</a>
    </div>
</div>
<div class="col-md-10">
<?php
echo CHtml::beginForm('statement', 'get');
echo CHtml::dropDownList('year', $year, DateUtil::getYearList(), array('class'=>'input-small')) . ' 年 ';
echo CHtml::dropDownList('month', $month, DateUtil::getMonthList(), array('class'=>'input-small')) . ' 月 ';
echo "用户名：".CHtml::TextField('username', "", array('class'=>'input-small'))." ";
echo '业务排序：'.CHtml::dropDownList('typeas', $typeas, Ad::getAdList(),array("empty"=>"",'class'=>'input-small'));
echo CHtml::submitButton('统计', array('class'=>'btn btn-info'));
echo CHtml::endForm();

$typeList = Ad::getAdList();
$columns = array();
foreach ($typeList as $k => $v) {
    $columns[] = array(
        'name' => $v,
        //'value' => htmlspecialchars_decode("\$data['" . $k . "']")
       // 'value' => Yii::app()->format->formatHtml("\$data['$k']"),
        //'value' => "\$data['$k']",
        'value' => "\$data['" . $k . "']",
        //使用html代码格式输出
        'type'=>'html',
    );
}

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'member-income-grid',
    'dataProvider' => $memberIncomeData,
    'ajaxUpdate' => false,
    'columns' => array_merge(array(
        array('name' => '用户名', 'value' => '$data["username"]'),
        array('name' => '开户人', 'value' => '$data["holder"]'),
        array('name' => "类型", 'value' => '$data[\'type\']< 3 ? "线上":"<span style=color:red;>线下</span>"',"type"=>"html"),
        array('name' => '合计', 'value' => '$data["sum"]','type'=>'html'),
    ), $columns),
    'pager' => array(
        'class' => 'CLinkPager',
    )
));


$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'type-income-grid',
    'summaryText' => '<span style="color: green;font-weight: bold;margin: 60px;">线上合计</span>',
    'ajaxUpdate' => false,
    'dataProvider' => $data,
    'columns' => $columns
));
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'type-income-grid',
    'summaryText' => '<span style="color: green;font-weight: bold;margin: 60px;">线下合计</span>',
    'ajaxUpdate' => false,
    'dataProvider' => $dataxx,
    'columns' => $columns
));
echo '<span class="label">' . $incomeSum . '</span>';?>
</div>