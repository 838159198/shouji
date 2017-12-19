<?php
/* @var $this TaskController */
/* @var $data CActiveDataProvider */

$this->breadcrumbs = array(
    '任务管理',
    '任务列表',
);
$this->menu = array(
    array('label' => '发布任务', 'url' => array('create')),
    array('label' => '已发布', 'url' => array('index')),
    array('label' => '已回复', 'url' => array('index', 'status' => Task::STATUS_SUBMIT)),
    array('label' => '已完成', 'url' => array('index', 'status' => Task::STATUS_DONE)),
    array('label' => '已删除', 'url' => array('index', 'status' => Task::STATUS_DEL)),
);
?>
    <h4 class="text-center">任务列表</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$template = '';
$template .= Auth::check('manage.task.view') ? ' {view}' : '';
$template .= Auth::check('manage.task.update') ? ' {update}' : '';
$template .= Auth::check('manage.task.delete') ? ' {delete}' : '';

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'admin-grid',
    'dataProvider' => $data,
    'columns' => array(
        array(
            'name' => 'accept',
            'value' => '$data->manageAccept->name'
        ),
        'title', array(
            'name' => 'status',
            'value' => 'Task::getStatusName($data->status)'
        ),
        array(
            'name' => 'isshow',
            'value' => 'empty($data->isshow)?"×":"√"'
        ),
        array(
            'name' => 'createtime',
            'value' => 'date(\'Y-m-d\',$data->createtime)'
        ),
        array(
            'name' => 'motifytime',
            'value' => 'empty($data->motifytime)?\'\':date(\'Y-m-d\',$data->motifytime)'
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => $template,
        ),
    ),
));
?>