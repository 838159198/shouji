<?php
$this->breadcrumbs = array(
    '我的工资单' => array('mywagelist')
);
$date_list = ManageDeduct::model()->getMounthByThisYear();
?>

<div style='margin-top: 20px'>
    <select id='thismounth' onchange="thisMounth()">
        <?php foreach ($date_list AS $key => $mou) { ?>
            <option value='<?php echo $mou ?>' <?php if($mou==$mounth){echo 'selected=selected';}?>><?php echo $mou ?></option>
        <?php } ?>
    </select>
</div>
<?php $uid = Yii::app()->session['show_id'] ?>
<input type='hidden' value='<?php echo $uid ?>' id='show_id'>

<?php //echo CHtml::button('查看', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'thisMounth()'))); ?>

<h4 class="text-center"><span style="color: #0099FF"><?php echo $manage_msg->name ?> 的工资信息（<?php echo $mounth ?>
        月份）</span></h4>
<div>
    <div id="leave_list" style='float:left;' title="工资信息">
        <dl class="dl-horizontal">
            <dt style='margin-bottom: 10px;'>用户名</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $id = isset($my_wage_msg[0]['uid']) ? $my_wage_msg[0]['uid'] : Yii::app()->user->manage_id;
                $role = Manage::model()->getRoleByUid($id); ?>
                <?php $name = Manage::model()->getNameById($id); ?>
                <?php echo CHtml::textField('user_name', $name); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>基本工资</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $wage = Role::model()->getBaseWageByRole($role); ?>
                <?php $base_wage = isset($my_wage_msg[0]['base_wage']) ? $my_wage_msg[0]['base_wage'] : $wage ?>
                <?php echo CHtml::textField('base_wage', $base_wage); ?>
            </dd>
            <div id='rel_get'>
                <dt style='margin-bottom: 10px;'>任务收益</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php
                   // $res = Salary::model()->getSalaryByMounth($id, $mounth);
                    $week_payback = isset($my_wage_msg[0]['week_payback']) ? $my_wage_msg[0]['week_payback'] : $wage_msg['week'];
                    $task_payback_new = isset($my_wage_msg[0]['task_payback']) ? $my_wage_msg[0]['task_payback'] : $wage_msg['new'];
                    $task_payback_drop = isset($my_wage_msg[0]['tdr_payback']) ? $my_wage_msg[0]['tdr_payback'] : $wage_msg['drop'];
                    $task_payback_visit = isset($my_wage_msg[0]['visit_payback']) ? $my_wage_msg[0]['visit_payback'] : $wage_msg['visit'];
                    $task_payback = round($week_payback + $task_payback_new + $task_payback_drop+$task_payback_visit, 3);

                    ?>
                    <?php //$task_payback = isset($my_wage_msg[0]['task_payback']) ? $my_wage_msg[0]['task_payback'] : $task_payback ?>
                    <?php echo CHtml::textField('task_pay_back', $task_payback); ?>
                </dd>
            </div>
            <?php if(($manage_msg->role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
            <dt style='margin-bottom: 10px;'>职务提成</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $com = isset($my_wage_msg[0]['com']) ? $my_wage_msg[0]['com'] : $wage_msg['com']  ?>
                <?php echo CHtml::textField('com', $com) ?>
            </dd>
            <?php }?>

            <dt style='margin-bottom: 10px;'>奖金</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $bonus = isset($my_wage_msg[0]['bonus']) ? $my_wage_msg[0]['bonus'] : 0 ?>
                <?php echo CHtml::textField('bonus', $bonus) ?>
            </dd>
            <dt style='margin-bottom: 10px;'>扣款金额</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $deduct = isset($my_wage_msg[0]['deduct']) ? $my_wage_msg[0]['deduct'] : 0 ?>
                <?php echo CHtml::textField('deduct', $deduct); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>应付工资</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $should_pay = isset($my_wage_msg[0]['should_pay']) ?
                    $my_wage_msg[0]['should_pay'] :
                    ($base_wage + $bonus + $com + $task_payback - $deduct)?>
                <?php echo CHtml::textField('should_pay', $should_pay); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>实付工资</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $total_pay = isset($my_wage_msg[0]['total_pay']) ? $my_wage_msg[0]['total_pay'] : '还未发布' ?>
                <?php echo CHtml::textField('total_pay', $total_pay); ?>
            </dd>
        </dl>
    </div>
    <div style='float:left;'>
        <dl class="dl-horizontal">
            <dt style='margin-bottom: 10px;'>所属月份</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $date = isset($my_wage_msg[0]['date']) ? $my_wage_msg[0]['date'] : $mounth ?>
                <?php echo CHtml::textField('date', $date); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>发布日期</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $publish_time = isset($my_wage_msg[0]['publish_time']) ? $my_wage_msg[0]['publish_time'] : '还未发布' ?>
                <?php echo CHtml::textField('publish_time', $publish_time); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>发布人</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $name = isset($my_wage_msg[0]['publish']) ? Manage::model()->getNameById($my_wage_msg[0]['publish']) : '还未发布' ?>
                <?php echo CHtml::textField('publish', $name); ?>
            </dd>
    </div>
</div>
<div style='clear:both'>
    <?php echo ManageDeduct::model()->button(1, '查看详情', 'onclick', 'showmsg'); ?>

</div>

<script type="text/javascript">
    var MM_WAGE_LIST_POWER = '<?php echo $this->createUrl ( 'manageMessage/wageListPower' )?>';
    var MM_MY_WAGE = '<?php echo $this->createUrl ( 'manageMessage/myWageList'); ?>';
    function thisMounth() {
        var this_mounth = $("#thismounth option:selected").val();
        var url = MM_MY_WAGE
        location.href = url + '/mounth/' + this_mounth;
    }
</script>















