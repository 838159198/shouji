<?php
/* @var $this AdvisoryrecordsController */
/* @var $data AdvisoryRecords */
?>

<div class="view">
    <ul>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:
            <?php echo CHtml::encode($data->u->username . ' ' . $data->u->holder) ?>
        </li>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('mid')); ?>:
            <?php echo CHtml::encode($data->m->username . ' ' . $data->m->name) ?>
        </li>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:
            <?php echo CHtml::encode($data->content); ?>
        </li>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('jointime')); ?>:
            <?php echo CHtml::encode(date('Y-m-d H:i:s', $data->jointime)); ?>
        </li>
    </ul>
</div>