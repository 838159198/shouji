<?php
/* @var $this MailController */
/* @var $model Mail */
/* @var $member MemberInfo */

$this->breadcrumbs = array(
    '站内信' => array('index', 'uid' => $member->id),
    '发送站内信',
);
$this->menu = array(
    array('label' => '返回列表', 'url' => array('index', 'uid' => $member->id)),
);
?>
    <h4 class="text-center">给用户（<?php echo $member->username ?>）发送站内信</h4>
<?php $this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
)); ?>
<?php $this->renderPartial('_form', array('model' => $model)); ?>