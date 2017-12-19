<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $down array */

$this->breadcrumbs = array(
    '任务管理' => array('index'),
    '添加任务',
);
echo '<h4 class="text-center">添加任务</h4>';
$this->renderPartial('_form', array('model' => $model, 'down' => $down)); ?>