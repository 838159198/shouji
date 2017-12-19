<?php
$this->breadcrumbs = array(
    '周任务查看' => '',
);
?>
<?php

function getManageMenu($id)
{
    $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')), 'graphs' => CHtml::link('曲线图', array('memberinfo/graphs', 'uid' => $id), array('target' => '_blank')), 'gainadvert' => CHtml::link('业务管理', array('gainadvert/index', 'uid' => $id)), 'resetpwd' => CHtml::link('重置密码', array('memberinfo/resetpwd', 'uid' => $id)), 'mail' => CHtml::link('站内信', array('mail/index', 'uid' => $id)), 'update' => CHtml::link('修改用户信息', array('memberinfo/update', 'id' => $id)), 'price' => CHtml::link('设置资源单价', array('memberinfo/price', 'id' => $id)), 'log' => CHtml::link('修改信息历史记录', array('memberinfo/log', 'id' => $id)), 'memberbranch' => CHtml::link('用户网吧管理', array('memberbranch/index', 'id' => $id)));

    $menus = array();
    if (Auth::check('manage.advisoryrecords.index'))
        $menus [] = $urls ['advisory'];
    if (Auth::check('manage.memberinfo.graphs'))
        $menus [] = $urls ['graphs'];
    if (Auth::check('manage.gainadvert.index'))
        $menus [] = $urls ['gainadvert'];
    if (Auth::check('manage.memberinfo.resetpwd'))
        $menus [] = $urls ['resetpwd'];
    if (Auth::check('manage.mail.index'))
        $menus [] = $urls ['mail'];
    if (Auth::check('manage.memberinfo.update'))
        $menus [] = $urls ['update'];
    if (Auth::check('manage.memberinfo.price'))
        $menus [] = $urls ['price'];
    if (Auth::check('manage.memberinfo.log'))
        $menus [] = $urls ['log'];
    if (Auth::check('manage.memberbranch.index'))
        $menus [] = $urls ['memberbranch'];

    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;
}

?>


<div style="clear:both;margin-top:10px">
    <div style='float:right'>
        <?php
        echo $button['last'] = CHtml::Button('上一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(1,\'' . $WEEKTASKTIME['start'] . '\',\'' . $WEEKTASKTIME['end'] . '\')')));
        echo $button['next'] = CHtml::Button('下一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(3,\'' . $WEEKTASKTIME['start'] . '\',\'' . $WEEKTASKTIME['end'] . '\')')));
        ?>
    </div>
    <h4 class="text-center">周任务</h4>

    <div style='color:#0099ff;font-weight:bold'>
    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'>本年第<?php echo date('W', $WEEKTASKTIME['start']) ?>周</a>
    </span>
        至
    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'>本年第<?php echo date('W', $WEEKTASKTIME['end']) ?>周</a>
    </span>
    </div>


    <div style='color:#0099ff;font-weight:bold'>
    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'><?php echo date('Y-m-d l', $WEEKTASKTIME['start']) ?></a>
    </span>
        至
    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'><?php echo date('Y-m-d l', $WEEKTASKTIME['end']) ?></a>
    </span>
    </div>

    <div>
    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'>任务总量<?php echo WeekTask::TOTAL ?></a>
    </span>

    <span class="label label-info" style='margin-top:5px;'>
        <a style='color:white' href='#'>已标记<?php echo $WEEKTASKTIME['num'] ?></a>
    </span>

    <span class="label label-info" style='margin-top:5px;'>
	<a style='color:white' href='#'>有效任务<?php echo $WEEKTASKTIME['con_count'] ?></a>
    </span>
    </div>
</div>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>收益</th>
        <th>任务收益</th>
        <th>增长率</th>
        <th>任务状态</th>
        <th>用户信息</th>
        <th>相关信息</th>
    </tr>
    <?php foreach ($Weeklist AS $item) { ?>
        <tr>
            <td><?php echo $item['username']; ?></td>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>目标周收益：<?php echo $item['target_payback'] ?></div>
                    <div>对比周收益：<?php echo $item['contrast_payback'] ?></div>
                </div>
            </td>
            <td><?php echo $item['payback'] ?></td>
            <td><?php echo $item['growth'] ?></td>
            <td><?php echo WeekTask::getStatusName($item['status']); ?></td>
            <td>
                <?php
                echo CHtml::Button('信息', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'show(\'' . $item['m_id'] . '\')')));
                ?>
            </td>
            <td>
                <?php $arr_list = getManageMenu($item['m_id']); ?>
                <div class='btn-group' id=bt_<?php echo $item['m_id']; ?>>
                    <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $item['m_id']; ?>)";>
                    <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $item['m_id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>

<?php $this->renderPartial('/layouts/pop') ?>


<input type='hidden' id='wt_id' value=''>

<div id='weekmsg' title="收益列表" style="display:none;">
    <dl class="dl-horizontal">
        <dt>起始周期</dt>
        <dd>
            <span id='s_to_e' style='color:#00438A;margin-bottom: 10px'></span>
        </dd>
        <dt>对照日期</dt>
        <dd>
            <span id='s_at' style='color:#00438A;margin-bottom: 10px'></span>
        </dd>
        <dt>对照日期总收益:</dt>
        <dd>
            <?php echo CHtml::textField('s_w_data', ''); ?>
        </dd>
        <dt>对比周期</dt>
        <dd>
            <span id='b_s_to_e' style='color:#00438A;margin-bottom: 10px'></span>
        </dd>
        <dt>对照日期</dt>
        <dd>
            <span id='l_s_at' style='color:#00438A;margin-bottom: 10px'></span>
        </dd>
        <dt style='margin:10px'>对比周总收益:</dt>
        <dd style='margin:10px'>
            <?php echo CHtml::textField('b_s_w_data', ''); ?>
        </dd>
        <dt style='margin:10px'>提升比例</dt>
        <dd style='margin:10px'>
            <?php echo CHtml::textField('sal', ''); ?>

        </dd>
        <dt style='margin:10px'>可获收益</dt>
        <dd>
            <?php echo CHtml::textField('html', '起始周总收益—对比周总收益'); ?>
        </dd>
        <dd>
            <?php echo CHtml::textField('data', ''); ?>
        </dd>
        <dd>
            <?php echo CHtml::textField('pay_back', ''); ?>
        </dd>
        <dt style='margin:10px'>任务是否有效</dt>
        <dd style='margin:10px'><span style='color:red;font-weight:bold;' id='con'></span></dd>

        <dt style='margin:10px'>&nbsp;</dt>
        <dd style='margin:10px'><?php echo CHtml::Button('继续标记任务', array_merge(Bs::cls(Bs::BTN_INFO),
                array('onclick' => 'continue_week_task()'))) ?>
        </dd>
    </dl>
</div>

<script type="text/javascript">
    var MM_WAGE_LMSG_IST = '<?php echo $this->createUrl ( 'manageMessage/showWeekTaskMsgListByDate' )?>';

</script>

