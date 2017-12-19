<?php
/* @var $this AdvisoryrecordsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model AdvisoryRecords */

$this->breadcrumbs = array(
    '用户列表' => array('member/index'),
    '用户咨询记录',
);
?>

    <h4 class="text-center" style="margin-left:-310px;">添加咨询记录</h4>
    <?php $this->renderPartial('_form', array('model' => $model)); ?>

    <h4><span style="padding-left:366px;">用户咨询记录</span></h4>
    <div class="h-view" style="margin-left: 15%;margin-bottom: 30px;">
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'htmlOptions' => array(
                'class' => 'bs-docs-example',
            ),
        )); ?>
    </div>
