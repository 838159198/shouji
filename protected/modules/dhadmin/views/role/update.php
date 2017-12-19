<?php
/* @var $this RoleController */
/* @var $model Role */

$this->breadcrumbs = array(
    '用户角色管理' => array('index'),
    '修改角色',
);
echo '<h4 class="text-center">修改角色</h4>';
$this->renderPartial('_form', array('model' => $model)); ?>