<?php
$this->breadcrumbs = array(
    '个人信息' => array('mymessage'),
);
?>

<h4 class="text-center"><span style="color: #0099FF">我的个人信息</span></h4>
<div style='float:right;margin-left: 50px;'>
    <?php echo CHtml::button('请假申请', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'manage_leave()'))); ?>
    <?php echo CHtml::button('工资查看', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'member_wage()'))); ?>
    <?php echo CHtml::button('修改密码', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'changePwd()'))); ?>

</div>
<div style='clear:both'></div>
<div id="manage_msg" style='float:left' title="员工信息">
    <input type='hidden' value='' name='manage_sex' id=manage_sex>
    <input type='hidden' value='' name='manage_ismarry' id='manage_ismarry'>
    <input type='hidden' value='' name='manage_role' id='manage_role'>
    <input type='hidden' value='' name='manage_id' id='manage_id'>
    <dl class="dl-horizontal">
        <div style='folat:left'>
            <dt style='margin-bottom: 10px;'>用户名</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('name', $manage->name); ?>
                <span style='color:red'></span>
            </dd>
            <dt style='margin-bottom: 10px;'>用户等级</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $role = Role::model()->findByPk($manage->role) ?>
                <?php echo CHtml::textField('name', $role->name); ?>
                <span style='color:red'></span>
            </dd>
            <dt style='margin-bottom: 10px;'>昵称</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('nickname', $manage->username); ?>
                <span style='color:red'></span>
            </dd>
            <dt style='margin-bottom: 10px;'>联系电话</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('phone', $manage->phone); ?>
                <span style='color:red'></span>
            </dd>

            <dt style='margin-bottom: 10px;'>出生年月</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('birthday', date('Y-m-d', $manage->birthday)); ?>
            </dd>

            <dt style='margin-bottom: 10px;'>员工性别</dt>
            <?php switch ($manage->sex) {
                case DefaultParm::DEFAULT_ONE:
                    echo '<dd style = "margin-bottom: 10px;">女</dd>';
                    break;
                case DefaultParm::DEFAULT_ZERO:
                    echo '<dd style = "margin-bottom: 10px;">男</dd>';
                    break;
            }?>



            <dt style='margin-bottom: 10px;'>是否结婚</dt>
            <?php switch ($manage->ismarry) {
                case DefaultParm::DEFAULT_ONE:
                    echo '<dd style = "margin-bottom: 10px;">已婚</dd>';
                    break;
                case DefaultParm::DEFAULT_ZERO:
                    echo '<dd style = "margin-bottom: 10px;">未婚</dd>';
                    break;
            }?>
        </div>
        <div style='folat:left'>
            <dt style='margin-bottom: 10px;'>我的照片</dt>
            <dd style="margin-bottom: 10px;">
                <img src='<?php echo $manage->picture ?>' id='picture_show' WIDTH="200" HEIGHT="200" BORDER="10" ALT="">
            </dd>

            <dt style='margin-bottom: 10px;'>备注信息</dt>
            <dd style="margin-bottom: 10px;">
                <textarea rows="5" cols="10" name='remark' id='remark' value=''><?php echo $manage->remark ?></textarea>
            </dd>
        </div>
    </dl>
</div>


<div id="leave_list" style='float:left;' title="请假历史记录">
    <dl class="dl-horizontal">
        <dt style='margin-bottom: 10px;'>上次请假时间</dt>
        <dd style='margin-bottom: 10px;'>
            <?php $last_stime = !empty($msg['last_stime']) ? date('Y-m-d H:i', $msg['last_stime']) : ''; ?>
            <?php echo CHtml::textField('start_time', $last_stime); ?>
        </dd>
        <dd style='margin-bottom: 10px;'>
            <?php $last_etime = !empty($msg['last_etime']) ? date('Y-m-d H:i', $msg['last_etime']) : ''; ?>
            <?php echo CHtml::textField('end_time', $last_etime); ?>
        </dd>
        <dt style='margin-bottom: 10px;'>批准人</dt>
        <dd style='margin-bottom: 10px;'>
            <?php $lastname = !empty($msg['last_checkid']) ? Manage::model()->getNameById($msg['last_checkid']) : ''; ?>
            <?php echo CHtml::textField('checkname', $lastname); ?>
        </dd>
        <span style='border-top: solid #0099ff 1px;';>
        <dt style='margin-bottom: 10px;'>预申请假期</dt>
        <dd style='margin-bottom: 10px;'>
            <?php $next_stime = !empty($msg['next_stime']) ? date('Y-m-d H:i', $msg['next_stime']) : ''; ?>
            <?php echo CHtml::textField('start_time', $next_stime); ?>
        </dd>
        <dd style='margin-bottom: 10px;'>
            <?php $next_etime = !empty($msg['next_etime']) ? date('Y-m-d H:i', $msg['next_etime']) : ''; ?>
            <?php echo CHtml::textField('end_time', $next_etime); ?>
        </dd>
        <dt style='margin-bottom: 10px;'>假条状态</dt>
        <dd style='margin-bottom: 10px;'>
            <?php
            $check_status = $msg['next_ischeck'] == '' ? '' : ManageDeduct::model()->getStatusNameByStatus($msg['next_ischeck']); ?>
            <?php echo CHtml::textField('check_status', $check_status); ?>
        </dd>
        <dt style='margin-bottom: 10px;'>批准人</dt>
        <dd style='margin-bottom: 10px;'>
            <?php $next_checkid = !empty($msg['next_checkid']) ? Manage::model()->getNameById($msg['next_checkid']) : ''; ?>
            <?php echo CHtml::textField('checkname', $next_checkid); ?>
        </dd>
        &nbsp</span>
    </dl>
</div>

<?php

$this->renderPartial('/layouts/manageleave')
?>
<?php $this->renderPartial('/layouts/pop') ?>
