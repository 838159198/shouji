<?php
/** @var $manage_list array */

$this->breadcrumbs = array(
    '员工信息列表' => array('index'),
);
$url = $this->createUrl('manageMseeage/record');
?>

<div style='margin:10px;float:right;'>
    <?php echo CHtml::submitButton('假条列表', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'leave()'))) ?>
</div>
<div style='clear:both'></div>


<h4 class="text-center"><span style="color: #0099FF">员工信息录入</span></h4>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户姓名</th>
        <th>用户等级</th>
        <th>注册时间</th>
        <th>完善个人资料</th>
        <th>请假信息（未完善）</th>
<!--        <th>工资查看与发布</th>-->
    </tr>
    <?php foreach ($manage_list AS $item) { ?>
        <tr>
            <td><?php echo $item['name'] ?></td>
            <?php $role = Role::model()->findByPk($item['role']); ?>
            <td><?php echo $role->name ?></td>

            <td><?php echo date('Y-m-d', $item['jointime']) ?></td>
            <td>
                <?php echo CHtml::submitButton('个人信息', array_merge(Bs::cls(Bs::BTN_INFO),
                    array('onclick' => 'addmanage(\'' . $item['id'] . '\')'))) ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('查看列表', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'deduct(\'' . $item['id'] . '\')'))) ?>
            </td>
<!--            <td>-->
<!--                --><?php //echo CHtml::submitButton('查看', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'payback(\'' . $item['id'] . '\')'))) ?>
<!--            </td>-->

        </tr>
    <?php } ?>
</table>

<div id="manage_msg" title="员工信息" style='display:none;'>
    <?php $url = $this->createUrl('manageMessage/record') ?>
    <form action='<?php echo $url ?>' method="post" enctype="multipart/form-data">
        <input type='hidden' value='' name='manage_sex' id=manage_sex>
        <input type='hidden' value='' name='manage_ismarry' id='manage_ismarry'>
        <input type='hidden' value='' name='manage_role' id='manage_role'>
        <input type='hidden' value='' name='manage_id' id='manage_id'>
        <dl class="dl-horizontal">
            <dt>联系电话</dt>
            <dd>
                <?php echo CHtml::textField('phone', '', array('onblur' => 'checkValIsEmpty(this)')); ?>
                <span style='color:red'></span>
            </dd>
            <dt>有效证件号</dt>
            <dd>
                <?php echo CHtml::textField('idcard', '', array('onblur' => 'checkValIsEmpty(this)')); ?>
                <span style='color:red'></span>
            </dd>
            <dt>出生年月</dt>
            <dd>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'attribute' => 'p_title',
                    'value' => '', //设置默认值
                    'name' => 'birthday',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                    ),
                ));
                ?>
                <span style='color:red'></span>
            </dd>

            <dt style='margin:15px;'>员工性别</dt>
            <dd style='margin:15px;'>
                <?php //echo CHtml::radioButtonList('sex','',array('0'=>'Male','1'=>'Female'),array('separator'=>'')); ?>
                <input type='radio' name='sex' value='0' id='Male'>Male
                <input type='radio' name='sex' value='1' id='Female'>Female
                <span style='color:red'></span>
            </dd>

            <dt style='margin:15px;'>是否结婚</dt>
            <dd style='margin:15px;'>
                <?php //echo CHtml::radioButtonList('ismarry','',array('0'=>'未婚','1'=>'已婚'),array('separator'=>'')); ?>
                <input type='radio' name='ismarry' value='0' id='is'>未婚
                <input type='radio' name='ismarry' value='1' id='isnot'>已婚
                <span style='color:red'></span>
            </dd>

            <dt>上传相片</dt>
            <dd>
                <img src='' id='picture_show' style='display:none' WIDTH="200" HEIGHT="200" BORDER="0" ALT="">
            </dd>
            <dd>
                ‭<?php echo CHtml::fileField('picture'); ?>
            </dd>

            <dt>备注信息</dt>
            <dd>
                <textarea rows="5" cols="10" name='remark' id='remark'></textarea>
            </dd>

            <dt>&nbsp;</dt>

            <dd>
                <div
                    id='check'><?php echo CHtml::Button('验证', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'proRecord()'))) ?></div>
            </dd>


            <dd>
                <div id='sub'
                     style='display:none'><?php echo CHtml::submitButton('提交', array_merge(Bs::cls(Bs::BTN_INFO))) ?></div>
            </dd>

        </dl>
    </form>
</div>

<div id="manage_leave_msg" title="员工请假列表" style='display:none;'>
    <dl class="dl-horizontal">
        <?php foreach ($leave_msg AS $item) { ?>
            <dt><?php echo $item['name'] ?></dt>
            <dd>
                <?php $leave_name = ManageDeduct::model()->getLeaveNameByType($item['leave']); ?>
                <?php echo CHtml::textField('leave_type', '' . $leave_name . '', array('onblur' => 'checkValIsEmpty(this)')); ?>
            </dd>
            <dd>
                <textarea><?php echo $item['reason'] ?></textarea>
            </dd>
            <dd>
                <?php echo CHtml::textField('leave_start_', '' . date('Y-m-d H:i', $item['start_time']) . '  开始', array('onclick' => 'ischeck(\'' . $item['id'] . '\')')); ?>
            </dd>
            <dd>
                <?php echo CHtml::textField('leave_end', '' . date('Y-m-d H:i', $item['end_time']) . '  终止', array('onclick' => 'ischeck(\'' . $item['id'] . '\')')); ?>
            </dd>
            <dd>
               <span>
                      <?php echo CHtml::submitButton('批准', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'ischeck(\'' . $item['id'] . '\',1)'))) ?>
               </span>
               <span>
                       <?php echo CHtml::submitButton('拒绝', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'ischeck(\'' . $item['id'] . '\',2)'))) ?>
               </span>
            </dd>
        <?php } ?>
    </dl>
</div>
<div id="Reply_Leave_Msg" title="回复信息" style="display:none;">
    <dt>回复内容</dt>
    <dd>
        <textarea rows="5" cols="10" id='my_reply'></textarea>
    </dd>

    <?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'reply_leave_msg'))) ?>
</div>

<script type="text/javascript">
    var MA_MESSAGE_RECORD = '<?php echo $this->createUrl('manageMessage/record') ?>';
    var MA_MESSAGE_INDEX = '<?php echo $this->createUrl('manageMessage/index') ?>';
    var MA_MESSAGE = '<?php echo $this->createUrl('manageMessage/messgae') ?>';
    var MA_MESSAGE_WAGE = '<?php echo $this->createUrl('manageMessage/wageMessgae') ?>';
    var MA_MESSAGE_DEDUCT = '<?php echo $this->createUrl('manageMessage/deduct') ?>';
    var MA_MESSAGE_PAYBACKL = '<?php echo $this->createUrl('manageMessage/payback') ?>';
    var MA_MESSAGE_CHECK = '<?php echo $this->createUrl('manageMessage/checkManageLeave') ?>';

</script>

