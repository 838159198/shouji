<?php
/* @var $this MailController */
/* @var $model Mail */

$this->breadcrumbs = array('站内信');
$this->menu = array(array('label' => '回到列表', 'url' => array('index', 'uid' => $model->recipient)));
?>

    <h4 class="text-center"><?php echo $model->MailContent->title; ?></h4>

<?php $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
)); ?>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name' => 'send',
            'value' => $model->manage->name,
        ),
        array(
            'name' => '标题',
            'value' => $model->MailContent->title,
        ),
        array(
            'name' => 'content',
            'value' => $model->MailContent->content,
        ),
        'jointime',
        array(
            'name' => 'status',
            'value' => Mail::getStatusName($model->status),
        ),
    ),
));