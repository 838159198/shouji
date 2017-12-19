<?php
/* @var $this SerachInfoController */
/* @var $data SerachinfoRecords */
?>

<div class="view">
    <ul>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('sid')); ?>:
            <?php echo CHtml::encode($data->u->name) ?>
        </li>
        <li>
            <?php echo CHtml::encode($data->getAttributeLabel('mid')); ?>:
            <?php echo CHtml::encode($data->m->username) ?>
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