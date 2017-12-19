<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $taskWhen TaskWhen */

$this->breadcrumbs = array(
    '任务管理' => array('index'),
    '任务内容',
);
?>

    <h4 class="text-center">任务内容</h4>
    <dl class="dl-horizontal">
        <dt><?php echo $model->getAttributeLabel('createtime') ?></dt>
        <dd><?php echo date('Y-m-d', $model->createtime) ?></dd>
        <dt><?php echo $model->getAttributeLabel('title') ?></dt>
        <dd><?php echo $model->title ?></dd>
        <dt><?php echo $model->getAttributeLabel('content') ?></dt>
        <dd><?php echo Common::strFormat($model->content) ?>&nbsp;</dd>
        <dt><?php echo $model->getAttributeLabel('status') ?></dt>
        <dd><?php echo $model->getStatusName($model->status) ?></dd>
    </dl>

    <h4 class="text-center">执行内容</h4>
<?php if (is_null($taskWhen) === false): ?>
    <dl class="dl-horizontal">
        <dt><?php echo $taskWhen->getAttributeLabel('createtime') ?></dt>
        <dd><?php echo date('Y-m-d', $taskWhen->createtime) ?></dd>
        <dt><?php echo $taskWhen->getAttributeLabel('content') ?></dt>
        <dd><?php echo Common::strFormat($taskWhen->content) ?>&nbsp;</dd>
        <dt><?php echo $taskWhen->getAttributeLabel('status') ?></dt>
        <dd><?php echo $taskWhen->getStatusName($taskWhen->status) ?></dd>
        <dt><?php echo $taskWhen->getAttributeLabel('isfail') ?></dt>
        <dd><?php echo $taskWhen->getIsFailName($taskWhen->isfail) ?></dd>
        <dt><?php echo $taskWhen->getAttributeLabel('score') ?></dt>
        <dd><?php echo '<span class="label">' . $taskWhen->score . '</span>'; ?></dd>
        <dt>&nbsp;</dt>
        <dd>
            <p>&nbsp;</p>
            <?php if (Auth::check('manage.mytask.update') && ($model->status < Task::STATUS_DONE)) {
                echo CHtml::link('修改内容', array('update', 'id' => $taskWhen->id), Bs::cls(Bs::BTN_INFO));
            } ?>
        </dd>
    </dl>
<?php else: ?>
    <?php echo CHtml::link('添加进度', array('create', 'id' => $model->id), Bs::cls(Bs::BTN_INFO, Bs::BTN_LARGE)) ?>
<?php endif; ?>