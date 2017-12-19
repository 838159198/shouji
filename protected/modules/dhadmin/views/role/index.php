<?php
/* @var $this RoleController */
/* @var $data CActiveDataProvider */

$this->breadcrumbs = array('用户角色管理');
$this->menu = array(array('label' => '添加角色', 'url' => array('create')));
?>
    <h4 class="text-center">用户角色管理</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));


$template = '';
$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'role-grid',
    'dataProvider' => $data,
    'columns' => array(
        'id',
        'name',
        array(
            'name' => '上级角色',
            'value' => 'empty($data->f)?\'\':$data->f->name'
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => $template,
        ),
    ),
));
?>