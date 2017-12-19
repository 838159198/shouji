<?php
/* @var $this MytaskController */

$this->breadcrumbs = array(
    '我的任务',
    '列表'
);
$this->menu = array(
    array('label' => '进行中', 'url' => array('index')),
    array('label' => '已回复', 'url' => array('index', 'status' => Task::STATUS_SUBMIT)),
    array('label' => '已完成', 'url' => array('index', 'status' => Task::STATUS_DONE)),
);
?>
    <h4 class="text-center">我的任务</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'admin-grid',
    'dataProvider' => $data,
    'columns' => array(
        array(
            'name' => 'publish',
            'value' => '$data->managePublish->name'
        ),
        'title',
        array(
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
            'template' => '{view}',
        ),
    ),
));