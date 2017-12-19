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
    <dt>&nbsp;</dt>
    <dd>
        <?php
        $form = CHtml::beginForm(array('done'), 'post', array('id' => 'doneform'));
        $form .= CHtml::hiddenField('tid', $model->id);
        $form .= CHtml::link('任务已完成', 'javascript:done(1)', Bs::cls(Bs::BTN_SUCCESS));
        if (is_null($taskWhen) === false) {
            $form .= CHtml::hiddenField('twid', $taskWhen->id);
            $form .= '&nbsp;' . CHtml::link('打回重新进行', 'javascript:done(2)', Bs::cls(Bs::BTN_WARNING));
        }
        $form .= CHtml::hiddenField('type');
        $form .= CHtml::endForm();
        echo $form;
        ?>
    </dd>
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
        <dd>
            <?php
            $score = '<span class="label">' . $taskWhen->score . '</span>&nbsp;';
            if (Auth::check('manage.task.score')) {
                $score .= CHtml::link('评分', 'javascript:score(' . $taskWhen->id . ')', Bs::cls(Bs::BTN_INFO));
            }
            echo $score;
            ?>
        </dd>
    </dl>
<?php endif; ?>

<div id="modalscore" title="评分" style="display:none;">
    <?php echo CHtml::beginForm(array('score')) ?>
    <?php echo CHtml::hiddenField('t_id') ?>
    <?php echo CHtml::hiddenField('t_score', '0') ?>
    <div class="alert alert-info">请拖动滑块对此项进度完成情况进行评分。</div>
    <h4 id="amount"></h4>

    <div id="sliders"></div>
    <p>&nbsp;</p>
    <?php echo CHtml::submitButton('提交评分', Bs::cls(Bs::BTN_INFO)) ?>
    <?php echo CHtml::endForm() ?>
</div>

<script type="text/javascript">
    var DONE_URL = '<?php echo $this->createUrl('done') ?>';
</script>