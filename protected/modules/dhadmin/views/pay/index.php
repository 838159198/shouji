<?php
/* @var $this PayController */

$this->breadcrumbs = array(
    '财务管理' => 'index',
);

?>
    <div class="page-header app_head">
        <h1 class="text-center text-primary">财务管理 <small></small></h1>
    </div>
    <div class="col-md-2">
        <div class="list-group">
            <li class="list-group-item active">财务管理</li>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/index");?>" class="list-group-item">财务说明</a>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/stats");?>" class="list-group-item">统计上月收益至余额</a>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=0");?>" class="list-group-item">未支付记录</a>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=1");?>" class="list-group-item">已支付记录</a>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/statement");?>" class="list-group-item">月财务统计</a>
        </div>
    </div>
    <div class="alert alert-danger" style="margin-left:286px; margin-right:174px;">
        <h4>注意：统计用户收入数据</h4>
        <ol>
            <li>需在上月收入数据全部导入后再统计</li>
            <li>此功能为统计上个月用户所有业务收益至用户余额</li>
            <li>每月只能统计1次</li>
            <li>如果在结算日期前提前统计造成收益不准</li>
        </ol>
    </div>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));