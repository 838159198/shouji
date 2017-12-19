<?php
/**
 * @var ImportController $this
 */
$this->breadcrumbs = array('清理已导入的业务数据' => array('index'));
echo '<h4 class="text-center">清理已导入的业务数据</h4>';
echo CHtml::beginForm($this->createUrl('clear'),'form-inline'),
CHtml::label('选择业务：', 'type'),
CHtml::dropDownList('type', '', Ad::getAdList()),
CHtml::label('日期：', 'date'),
CHtml::textField('date', DateUtil::getDate(strtotime('-1 day'))),
CHtml::submitButton('提交', array('class' => 'btn btn-info')),
CHtml::endForm();
?>
<div class="alert alert-danger">
    <p>使用此功能会直接删除数据库中相关表的数据，无法恢复。</p>

    <p>建议只清理前一天的业务数据，如果清理错误可以重新导入恢复</p>
</div>