<?php
/* @var $this PayController */
/* @var $log array */

$this->breadcrumbs = array(
    '财务管理' => 'index',
);
?>

    <div class="page-header app_head">
        <h1 class="text-center text-primary">统计收入数据 <small></small></h1>
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
<div class="hero-unit h-hero">
<div style="margin-left:50px; height:80px;width:950px; float:left;background-color: #eee; padding-top:20px;padding-left:20px; margin-bottom:20px;">
    <?php echo CHtml::beginForm() ?>
    <?php echo empty($log) ? CHtml::submitButton('开始统计上月收益至用户余额', array('class'=>'btn btn-danger btn-large')) : '' ?>
    <?php echo CHtml::label(empty($log) ? '尚未统计' : '上月收益已经统计', '', array('class'=>'label label-info')) ?>
    <?php echo CHtml::endForm() ?>
</div>
    <div class="alert alert-danger" style="margin-left:50px; width:950px; margin-right:50px; float:left;">
        <h4>注意：统计用户收入数据</h4>
        <ol>
            <li>需在上月收入数据全部导入后再统计</li>
            <li>此功能为统计上个月用户所有业务收益至用户余额</li>
            <li>每月只能统计1次</li>
            <li>如果在结算日期前提前统计造成收益不准</li>
        </ol>
    </div>
</div>
