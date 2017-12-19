<?php
/* @var $this PayController */
/* @var $type string */
/* @var $date string */
/* @var $data IDataProvider */

$this->breadcrumbs = array(
    '财务管理' => 'index',
);

echo '<h4 class="text-center">用户收入峰值对比</h4>';

echo CHtml::beginForm('spike', 'get');
echo CHtml::dropDownList('type', $type, Ad::getAdList() + array('all' => '全部'), Bs::cls(Bs::INPUT_SMALL)) . '&nbsp;';
echo CHtml::textField('date', $date, Bs::dateInput()) . '&nbsp;';
echo CHtml::submitButton('统计', Bs::cls(Bs::BTN_INFO));
echo CHtml::endForm();

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'member-income-grid',
    'dataProvider' => $data,
    'columns' => array(
        array('name' => 'username', 'value' => '$data["username"]', 'header' => '用户名'),
        array('name' => 'holder', 'value' => '$data["holder"]', 'header' => '开户人'),
        array('name' => 'fdata', 'value' => '$data["fdata"]', 'header' => '峰值'),
        array('name' => 'data', 'value' => '$data["data"]', 'header' => '收入'),
        array('name' => 'cha', 'value' => '$data["cha"]', 'header' => '差值'),
        array('name' => 'ftime', 'value' => '$data["ftime"]', 'header' => '峰值时间'),
    )
));
