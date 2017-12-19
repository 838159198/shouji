<?php
/* @var $this MailController */
/* @var $dataProvider CActiveDataProvider */
/* @var $member Member */

$this->breadcrumbs = array(
    '站内信',
);

$this->menu = array(
    /*array('label' => '添加站内信', 'url' => array('create', 'uid' => $member->id)),*/
);
?>

    <h4 class="text-center">站内信历史记录</h4>

<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'mail-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'send',
            'value' => '$data->manage->name',
        ),
        array(
            'name' => '标题',
            'value' => '$data->MailContent->title',
        ),
        'jointime',
        array(
            'name' => 'status',
            'value' => 'Mail::getStatusName($data->status)',
        ),
        array(
            'class' => 'CButtonColumn',
            'buttons' => array(
                'delete' => array(
                    'visible' => '$data->status <' . Mail::STATUS_READ
                ),
            ),
            'template' => '{view} {delete}'
        ),
    ),
));
