<?php
/* @var $this MytaskController */
/* @var $model TaskWhen */

$this->breadcrumbs = array(
    '任务管理' => array('index'),
    '添加进度',
);
echo '<h4 class="text-center">添加进度</h4>';
$this->renderPartial('_form', array('model' => $model)); ?>
