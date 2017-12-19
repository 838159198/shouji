<?php
$this->breadcrumbs = array(
    '员工信息列表' => array('index'),
    '应付工资列表' => array('payback'),
);
$date_list = ManageDeduct::model()->getMounthByThisYear();
?>

<div style='margin-top: 20px'>
    <select id='thismounth'>
        <?php foreach ($date_list AS $key => $mou) { ?>
            <option value='<?php echo $mou ?>'><?php echo $mou ?></option>
        <?php } ?>
    </select>
</div>
<?php $uid = Yii::app()->session['show_id'] ?>
<input type='hidden' value='<?php echo $uid ?>' id='show_id'>

<?php echo CHtml::button('查看', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'thisMounth()'))); ?>

<h4 class="text-center"><span style="color: #0099FF"><?php echo $manage_msg->name ?> 的工资信息（<?php echo $mounth ?>
        月份）</span></h4>

<div>
    <div id="leave_list" style='float:left;' title="工资信息">
        <dl class="dl-horizontal">
            <dt style='margin-bottom: 10px;'>用户名</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('user_name', $manage_msg->name); ?>
            </dd>

            <dt style='margin-bottom: 10px;'>用户等级</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $role = Role::model()->findByPk($manage_msg->role); ?>
                <?php $base_wage = isset($wage_msg->base_wage) ? $wage_msg->base_wage : $role->base_wage; ?>

                <?php echo CHtml::textField('role', $role->name); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>基本工资</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('base_wage', $base_wage); ?>
            </dd>

            <dt style='margin-bottom: 10px;'>输入工资比例</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $scale = isset($wage_msg->scale) ? $wage_msg->scale : DefaultParm::DEFAULT_ZERO; ?>
                <?php echo CHtml::textField('scale', $scale); ?>
            </dd>

            <div style='display: <?php if (!isset($wage_msg->scale)) {
                echo 'display';
            } else {
                echo 'none';
            } ?>'>
                <dt style='margin-bottom: 10px;'>&nbsp;</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php  echo CHtml::Button('计算收益', array_merge(Bs::cls(Bs::BTN_INFO),
                        array('onclick' => 'countBaseWage()')))?>
                </dd>
            </div>
            <div id='rel_get' style='display: <?php if (isset($wage_msg->scale)) {
                echo 'display';
            } else {
                echo 'none';
            } ?>'>
                <dt style='margin-bottom: 10px;'>实得基本工资</dt>
                <dd style='margin-bottom: 10px;'>

                    <?php
                    //提成比例默认100%，
                    $should_pay = isset($wage_msg->scale) ? $wage_msg->scale : DefaultParm::DEFAULT_HUNDRED;
                    $should_pay = ($base_wage * $should_pay) / 100;
                    ?>
                    <?php echo CHtml::textField('countBaseWage', $should_pay); ?>
                </dd>
            </div>
            <dt style='margin-bottom: 10px;'>普通任务收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $task_payback = isset($wage_msg->task_payback) ? ($wage_msg->task_payback-$taskweek) : $tasknew ?>
                <?php echo CHtml::textField('task_payback', $task_payback) ?>
            </dd>
            <dt style='margin-bottom: 10px;'>周任务收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $week_payback = isset($wage_msg->task_payback) ? ($wage_msg->task_payback-$tasknew) : $taskweek ?>
                <?php //$week_payback = isset($salary->week_payback) ? $salary->week_payback : DefaultParm::DEFAULT_ZERO ?>
                <?php echo CHtml::textField('week_payback', $week_payback); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>奖金</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $bonus = isset($salary->bonus) ? $salary->bonus : DefaultParm::DEFAULT_ZERO ?>
                <?php echo CHtml::textField('bonus', $bonus); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>工资收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php //$should = isset($wage_msg->should_pay)?$wage_msg->should_pay:$base_wage;?>
                <?php $should = $should_pay + $task_payback + $week_payback + $bonus ?>
                <?php echo CHtml::textField('should_pay', $should); ?>
            </dd>
        </dl>
    </div>


    <div id="real_wage_count" style='float:left;display: none;' title="工资计算">
        <dl class="dl-horizontal">
            <dt style='margin-bottom: 10px;'>基本工资</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('base_wage_now', ''); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>比例收入</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $scale_get = isset($wage_msg->scale) ? $wage_msg->scale : DefaultParm::DEFAULT_ZERO; ?>
                <?php echo CHtml::textField('base_scale_now', $scale_get); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>实际收入</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $real_pay = isset($wage_msg->total_pay) ? $wage_msg->total_pay : DefaultParm::DEFAULT_ZERO; ?>
                <?php echo CHtml::textField('real_Wage_get', ''); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>&nbsp;</dt>
            <dd style='margin-bottom: 10px;'>
                <?php  echo CHtml::Button('确定', array_merge(Bs::cls(Bs::BTN_INFO),
                    array('id' => 'to_send')))?>
            </dd>
        </dl>
    </div>


    <div id="leave_list" style='float:left;' title="工资信息">
        <dl class="dl-horizontal">
            <dt style='margin-bottom: 10px;'>保险(未完成)</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('insurance', ''); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>请假(未完成)</dt>
            <dd style='margin-bottom: 10px;'>
                <?php //$nextname = Manage::model()->getNameById($msg['next_checkid']);?>
                <?php echo CHtml::textField('manage_leave', '0'); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>迟到/早退(未完成)</dt>
            <dd style='margin-bottom: 10px;'>
                <?php //$nextname = Manage::model()->getNameById($msg['next_checkid']);?>
                <?php echo CHtml::textField('manage_late', '0'); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>扣款金额(未完善)</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $deduct = isset($wage_msg->deduct) ? $wage_msg->deduct : DefaultParm::DEFAULT_ZERO; ?>
                <?php echo CHtml::textField('deduct', $deduct); ?>
            </dd>
        </dl>
    </div>
</div>

<div style='clear:both;float:right;font-size: 16px;font-weight: bold'>
    <div>

        （<span>工资收益：</span><span id='should_pay_count' style='color:#ff0000'><?php echo $base_wage ?>元</span>）
        +
        （<span>任务提成：</span><span id='task_pay_back' style='color:#ff0000'><?php echo $task_payback + $week_payback ?>
            元</span>）
        +
        （<span>满勤奖金：</span><span id='my_bonus' style='color:#ff0000'><?php echo $bonus ?>
            元</span>）
        —
        （<span>扣款金额: </span><span id='rel_deduct' style='color:#ff0000'><?php echo $deduct ?>元</span>）
        =
        （<span>应付工资：</span><span style='color:#ff0000' id='real_pay'><?php echo $should ?>元</span>）
    </div>
    <div style='float:right;'>
        <input type='hidden' value='<?php echo $should ?>' id='total_pay'>
        <input type='hidden' value='<?php echo $mounth ?>' id='check_mounth'>
    </div>
</div>

<div style='clear: both'>
    <?php
    echo CHtml::Button('发布工资条', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'sendWage(\'' . $uid . '\')')))
    ?>
</div>

<script type="text/javascript">
    var MA_MESSAGE_PAYBACK = '<?php echo $this->createUrl('manageMessage/payBack') ?>';
    var MA_MESSAGE_WAGE_COUNT = '<?php echo $this->createUrl('manageMessage/wageCount') ?>';
</script>
<?php
$this->renderPartial('/layouts/pop')?>

