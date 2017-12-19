<?php
$this->breadcrumbs = array('任务收益验证与工资发布' => array('payback'));

$date_list = ManageDeduct::model()->getMounthByThisYear();
$year_list = ManageDeduct::model()->getLastYear();
$mounth_list = ManageDeduct::model()->getMounth();

if ($role1 < Role::ADVANCED_STAFF) {?>
    <table class="table table-hover table-striped table-condensed"
           style='margin-top: 20px;'>
        <tr>
            <td><select id='manage_list' onchange="toManagePayBack()">
                    <?php foreach ($manage_list as $_list) { ?>
                        <option id="<?php echo $_list ['id'] ?>" value="<?php echo $_list ['name'] ?>"
                            <?php if($_list ['id']==$id){echo 'selected=selected';}?>>
                            <?php echo $_list ['name']; ?>(<?php echo $_list ['rname']; ?>)
                        </option>
                    <?php
                    }
                    ?>
                </select> <input type='hidden' id='to_manage' value=''> <input
                    type='hidden' id='manager' value='<?php
                echo $this->uid?>'>
            </td>
            <td>
                <div>
                    <select id='thismounth' onchange="thisMounth()">
                        <?php foreach ($date_list AS $key => $mou) { ?>
                            <option value='<?php echo $mou ?>'
                             <?php if($mou==$mounth){echo 'selected=selected';}?>>
                                <?php echo $mou ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php $uid = Yii::app()->session['show_id'] ?>
                <input type='hidden' value='<?php echo $uid ?>' id='show_id'>
            </td>
        </tr>

        <?php if($role==7){?>

        <tr>
            <td colspan="2">
                <span>
                有效回访
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'attribute' => 'p_title',
                        'value' => '', //设置默认值
                        'name' => 'starttime',
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                            'maxlength' => 8,
                        ),
                    ));
                    ?>--
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'attribute' => 'p_title',
                        'value' => '', //设置默认值
                        'name' => 'endtime',
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                            'maxlength' => 8,
                        ),
                    ));
                    ?>
                    <button style="margin-top:-8px;" class="btn btn-primary" type="button" onclick="searchMyVtask(<?php echo $manage_msg["id"];?>)">Go!</button>
                <span id="countst"></span>个
                    <br/>有效工作日数&nbsp;<input id="Vcount" style="width:50px;" type="text" name="vcount">&nbsp;&nbsp;单工作日应完成数量&nbsp;<input id="Vscount" style="width:50px;" type="text" name="vscount">

                </span>
                <span style="padding-left:20px;">
                    <button style="margin-top:-8px;" class="btn btn-primary" type="button" onclick="searchMyKpi()">计算</button>

                    KPI:&nbsp;&nbsp;<span id="baibi"></span>
                <?php  ?>
                </span>

            </td>
        </tr>
        <?php } ?>


    </table>
<?php
}
?>

<h4 class="text-center">
    <span style="color: #0099FF"><?php echo $name ?></span>的任务收益列表
    （<span style='color: red';>只显示有效任务收益</span>）
</h4>
<table class="table table-hover table-striped table-condensed"
       style='margin-top: 20px;'>
    <tr>
        <?php if(($status==0)&&($role!=7)){?>
            <th>check</th>
            <th><input type='checkbox' id='member-info-grid-all'></th>
        <?php }else if(($status==0)&&($role==7)){?>
            <th><input type='checkbox' id='member-info-grid-all'></th>
        <?php }?>
        <th>用户名</th>
        <th>发布/申请时间</th>
        <th>上报时间</th>
        <th>任务类别</th>
        <th>任务收益</th>
        <?php if ($status == DefaultParm::DEFAULT_ONE) { ?>
            <th>收益发布时间</th>
        <?php } ?>
    </tr>
    <?php
        $str = '';
        foreach ($pay_list as $key=>$item) { ?>
            <?php
                if($role!=7){
                    if($item ['type']==1){
                        $res[$key] = TaskWhen::model()->getPayBackForNewMemberTask($item ['a_time'], $item ['porttime'],
                                        $item ['m_id'], $pay['new']);
                    }elseif($item ['type']==2){
                        $res[$key] = TaskWhen::model()->getPayBackForDropTask($item ['a_time'], $item ['porttime'],
                                        $item ['m_id'], $pay['drop']);
                    }

                    $check      = round($res[$key]['pay_back'],2);
                    $pay_back   = round($item ['pay_back'],2);

                    //收益不相等
                    if(($check != $pay_back) &&($item ['ispay']==0)){
                        $str .=  $item ['tw_id'].' ';
                        //修改任务收益，并保存收益修改的时间
                        TaskEarnings::model()->updateTaskPaybackByCheck($check,$item ['tw_id'],$item ['te_id']);?>
                        <tr style="color:#FF6FB7;font-weight:bold;">
                        <td style = 'color: #344e59'>
                            <div style='float:left;margin-right:5px;'>
                                <div>验证收益 ：<?php echo $check ?></div>
                                <div>显示收益 ：<?php echo $pay_back ?></div>
                            </div>
                        </td>

                        <td><input type='checkbox' name='checkthis' disabled
                                   value='<?php echo $item ['at_id'] ?>'
                                   tw_id='<?php echo $item ['tw_id'] ?>'
                                   pro='<?php echo $item ['porttime'] ?>'
                                   atime='<?php echo $item ['a_time'] ?>'
                                   mid='<?php echo $item ['m_id'] ?>'>
                        </td>
                        <?php
                        //收益相等
                    } else if(($check == $pay_back) &&($item ['ispay']==0)){ ?>
                        <tr>
                        <td style = 'color: #88a0e2'>success</td>
                        <td><input type='checkbox' name='checkthis'
                                   value='<?php echo $item ['at_id'] ?>'
                                   tw_id='<?php echo $item ['tw_id'] ?>'
                                   pro='<?php echo $item ['porttime'] ?>'
                                   atime='<?php echo $item ['a_time'] ?>'
                                   mid='<?php echo $item ['m_id'] ?>'>
                        </td>
                    <?php }
                 //如果是见习客服
                }elseif($role==7){?>
                    <tr>
                    <td><input type='checkbox' name='checkthis'
                               value='<?php echo $item ['at_id'] ?>'
                               tw_id='<?php echo $item ['tw_id'] ?>'
                               pro='<?php echo $item ['porttime'] ?>'>
                    </td>
                <?php }?>
                    <td><?php echo $item ['username'] ?></td>
                    <td><?php echo date('Y-m-d H:i', $item ['a_time']) ?></td>
                    <td><?php echo date('Y-m-d H:i', $item ['porttime']) ?></td>
                    <td>
                        <?php
                        if ($item ['type'] == Task::TYPE_NEW) {
                            echo '新用户任务';
                        } else if ($item ['type'] == Task::TYPE_DROP) {
                            echo '降量任务';
                        } else if ($item ['type'] == Task::TYPE_VISIT) {
                            echo '回访任务';
                        }
                        ?>
                    </td>
                    <td><?php echo $item ['pay_back'] ?>元</td>
                    <?php if ($status == DefaultParm::DEFAULT_ONE) { ?>
                        <td><?php echo date('Y-m-d H:i', $item ['paytime']) ?></td>
                    <?php } ?>
                    </tr>
        <?php } ?>
</table>
<input type='hidden' id='error_id_list' value='<?php echo $str ?>'>
<input type='hidden' id='f_id' value='<?php echo $id ?>'>

<?php if (($role1 < 4)&&($status==0)) { ?>
    <span id = 'check'>
<?php echo CHtml::Button('验证收益', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE),
        array('onclick' => 'checkPayBack()'))) ?>
    </span>
    <span id = 'send'>
<?php echo CHtml::Button('显示工资条', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'showWgae()'))) ?>
    </span>
    <span id = 'hide' style = 'display: none'>
<?php echo CHtml::Button('隐藏工资条', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'hideWgae()'))) ?>
    </span>
    <div style='float: right;'>
        <?php if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
        <span style='font-size: 16px; font-weight: bold;'>职务提成 ：</span>
            <span style='margin-right: 10px; font-size: 16px; color: red'><?php echo $com; ?>元</span>
            <span style='font-size: 16px; font-weight: bold;'>总收益 ：</span>
            <span style='margin-right: 10px; font-size: 16px; color: red'>
                职务(<?php echo $com?>)+任务(<?php echo $total; ?>) = <?php echo $total+$com ?>元
            </span>
        <?php }else{?>
        <span style='font-size: 16px; font-weight: bold;'>总收益 ：</span>
        <span style='margin-right: 10px; font-size: 16px; color: red'><?php echo $total; ?>元</span>
        <?php }?>
    </div>
<?php
}
?>

‭
<div class="pager">
    <?php
    $this->widget("CLinkPager", array('pages' => $pages, 'firstPageLabel' => '首页', 'lastPageLabel' => '末页', 'maxButtonCount' => 15));
    ?>
</div>
<?php
$time = time();
$this_year = date('Y', $time);
?>

<!----------------工资单开始------------------>
<!----------------工资单开始------------------>

<div id = 'show_wage' style="display: none">
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
                <?php $role2 = Role::model()->findByPk($manage_msg->role); ?>
                <?php $base_wage = isset($wage_msg->base_wage) ? $wage_msg->base_wage : $role2->base_wage; ?>
                <?php echo CHtml::textField('role', $role2->name); ?>
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

            <div style='display: <?php if (!isset($wage_msg->scale)) {echo 'display';} else {echo 'none';} ?>'>
                <dt style='margin-bottom: 10px;'>&nbsp;</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php  echo CHtml::Button('计算收益', array_merge(Bs::cls(Bs::BTN_INFO),
                        array('onclick' => 'countBaseWage()')))?>
                </dd>
            </div>

            <div id='rel_get'
                 style='display: <?php if (isset($wage_msg->scale)) {echo 'display';} else {echo 'none';} ?>'>
                <dt style='margin-bottom: 10px;'>实得基本工资</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php
                    //提成比例默认100%，
                    $scale = isset($wage_msg->scale) ? $wage_msg->scale : DefaultParm::DEFAULT_HUNDRED;
                    $should_pay = ($base_wage * $scale) / 100;
                    ?>
                    <?php echo CHtml::textField('countBaseWage', $should_pay); ?>
                </dd>
            </div>
            <!--见习以上客服权限开始-->
            <!--见习以上客服权限开始-->
            <?php if($role!=7){?>
            <dt style='margin-bottom: 10px;'>新用户任务收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $task_payback = isset($wage_msg->task_payback) ? ($wage_msg->task_payback) : $tasknew ?>
                <?php echo CHtml::textField('task_payback_new', $tasknew) ?>
            </dd>
            <dt style='margin-bottom: 10px;'>降量任务收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $tdr_payback = isset($wage_msg->tdr_payback) ? ($wage_msg->tdr_payback) : $taskdrop ?>
                <?php echo CHtml::textField('task_payback_drop', $taskdrop) ?>
            </dd>
            <dt style='margin-bottom: 10px;'>周任务收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $week_payback = isset($wage_msg->week_payback) ? ($wage_msg->week_payback) : $taskweek ?>
                <?php //$week_payback = isset($salary->week_payback) ? $salary->week_payback : DefaultParm::DEFAULT_ZERO ?>
                <?php echo CHtml::textField('week_payback', $week_payback); ?>
            </dd>
            <div id="week_task_payback" style="display: none;border: 1px solid #00ffcc">
            <?php foreach($weekEarnings AS $kk=>$val){?>
                <p>结束时间：<?php echo date("Y-m-d l",$val['endtime']);?></p>
                <p>任务收益：<?php echo $val['payback'];?></p>
            <?php }?>
            </div>
            <dt style='margin-bottom: 10px;'>奖金</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $bonus = isset($wage_msg->bonus) ? $wage_msg->bonus : DefaultParm::DEFAULT_ZERO ?>
                <?php echo CHtml::textField('bonus', $bonus); ?>
            </dd>
            <?php if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
                <dt style='margin-bottom: 10px;'>职务提成</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php $com = $com ?>
                    <?php echo CHtml::textField('com', $com); ?>
                </dd>
            <?php }?>
            <dt style='margin-bottom: 10px;'>工资收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php //$should = isset($wage_msg->should_pay)?$wage_msg->should_pay:$base_wage;?>
                <?php $should = $should_pay + $task_payback + $tdr_payback + $week_payback + $bonus +$com ?>
                <?php echo CHtml::textField('should_pay', $should); ?>
            </dd>
        </dl>
    </div>
            <!--见习以上客服权限结束-->

            <!--见习客服权限开始-->
            <?php }elseif($role==7){?>
                <dt style='margin-bottom: 10px;'>回访任务收益</dt>
                <dd style='margin-bottom: 10px;'>
                    <?php $visit_payback = isset($wage_msg->visit_payback) ? ($wage_msg->visit_payback) : $taskvisit; ?>
                    <?php //$week_payback = isset($salary->week_payback) ? $salary->week_payback : DefaultParm::DEFAULT_ZERO ?>
                    <?php echo CHtml::textField('visit_payback', $visit_payback); ?>
                </dd>

            <dt style='margin-bottom: 10px;'>奖金</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $bonus = isset($wage_msg->bonus) ? $wage_msg->bonus : DefaultParm::DEFAULT_ZERO ?>
                <?php echo CHtml::textField('bonus', $bonus); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>工资收益</dt>
            <dd style='margin-bottom: 10px;'>
                <?php //$should = isset($wage_msg->should_pay)?$wage_msg->should_pay:$base_wage;?>
                <?php $should = $should_pay + $visit_payback + $bonus; ?>
                <?php echo CHtml::textField('should_pay', $should); ?>
            </dd>
            </dl>
        </div>
    <?php }?>
            <!--见习客服权限结束-->


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
                <?php echo CHtml::textField('manage_leave', '0'); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>迟到/早退(未完成)</dt>
            <dd style='margin-bottom: 10px;'>
                <?php echo CHtml::textField('manage_late', '0'); ?>
            </dd>
            <dt style='margin-bottom: 10px;'>扣款金额</dt>
            <dd style='margin-bottom: 10px;'>
                <?php $deduct = isset($wage_msg->deduct) ? $wage_msg->deduct : DefaultParm::DEFAULT_ZERO; ?>
                <?php echo CHtml::textField('deduct', $deduct); ?>
            </dd>
        </dl>
    </div>
</div>

<!----------------工资单结束------------------>
<!----------------工资单结束------------------>



<div style='clear:both;float:right;font-size: 16px;font-weight: bold'>
    <div>
        （<span>工资收益：</span><span id='should_pay_count' style='color:#ff0000'><?php echo $base_wage ?>元</span>）
        +
        <!--见习以上客服权限结束-->

        <!--见习客服权限开始-->
        （<span>任务提成：</span><span id='task_pay_back' style='color:#ff0000'>
            <?php if($role!=7){?>
                <?php echo $task_payback + $week_payback + $tdr_payback ;?>元</span>）
            <?php }elseif($role==7){?>
                <?php echo $visit_payback ;?>元</span>）
            <?php }?>
        +
        （<span>满勤奖金：</span><span id='my_bonus' style='color:#ff0000'><?php echo $bonus ?>
            元</span>）
        <br/>
        <?php if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
            +
        （<span>职务提成：</span><span id='my_bonus' style='color:#ff0000'><?php echo $com ?>
                元</span>）
        <?php }?>
        —
        （<span>扣款金额: </span><span id='rel_deduct' style='color:#ff0000'><?php echo $deduct ?>元</span>）

        <br/>
        =
        （<span>应付工资：</span><span style='color:#ff0000' id='real_pay'><?php echo $should ?>元</span>）
    </div>
    <div style='float:right;'>
        <input type='hidden' value='<?php echo $should ?>' id='total_pay'>
        <input type='hidden' value='<?php echo $role ?>' id='managerole'>
        <input type='hidden' value='<?php echo $mounth ?>' id='check_mounth'>
    </div>
</div>

<div style='clear: both;margin:40px;'>
    <?php echo CHtml::Button('发布工资条', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'sendWage(\'' . $uid . '\')')))
    ?>
</div>
</div>
<script type="text/javascript">
    var MA_MESSAGE_PAYBACK = '<?php echo $this->createUrl('manageMessage/payBack') ?>';
    var MA_MESSAGE_WAGE_COUNT = '<?php echo $this->createUrl('manageMessage/wageCount') ?>';
</script>
<script type="text/javascript">
    function searchMyKpi()
    {
        var countv=$("#countst").text();
        var counts=$("#Vcount").val();
        var countsv=$("#Vscount").val();
        var tongji=counts*countsv;
        var baibi= Math.round(countv / tongji * 10000) / 100.00 + "%";

        $("#baibi").text(baibi);

    }

</script>

<?php $this->renderPartial('/layouts/pop')?>
