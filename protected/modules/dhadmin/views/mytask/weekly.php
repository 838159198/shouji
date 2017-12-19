<?php

$button = array();
$week = '';
switch ($title) {
    case WeekTask::LAST_WEEK:
        $week = '上一周';
        $button['last'] = CHtml::button('上一周', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'checkweek(\'' . WeekTask::LAST_WEEK . '\')')));
        $button['this'] = CHtml::Button('本周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::THIS_WEEK . '\')')));
        $button['next'] = CHtml::Button('下一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::NEXT_WEEK . '\')')));

        break;
    case WeekTask::THIS_WEEK:
        $week = '本周';
        $button['last'] = CHtml::Button('上一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::LAST_WEEK . '\')')));
        $button['this'] = CHtml::button('本周', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'checkweek(\'' . WeekTask::THIS_WEEK . '\')')));
        $button['next'] = CHtml::Button('下一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::NEXT_WEEK . '\')')));

        break;
    case WeekTask::NEXT_WEEK:
        $week = '下一周';
        $button['last'] = CHtml::Button('上一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::LAST_WEEK . '\')')));
        $button['this'] = CHtml::Button('本周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::THIS_WEEK . '\')')));
        $button['next'] = CHtml::button('下一周', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'checkweek(\'' . WeekTask::NEXT_WEEK . '\')')));

        break;
    default:
        $week = '上一周';
        $button['last'] = CHtml::button('上一周', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'checkweek(\'' . WeekTask::LAST_WEEK . '\')')));
        $button['this'] = CHtml::Button('本周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::THIS_WEEK . '\')')));
        $button['next'] = CHtml::Button('下一周', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkweek(\'' . WeekTask::NEXT_WEEK . '\')')));

}

$this->breadcrumbs = array(
    '我的周任务' => array('weekly'),
    $week => array('weekly'),
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
<div style='margin-top:20px;'>
</div>

<?php foreach ($button AS $but) { ?>
    <span>
<?php echo $but ?>
</span>

<?php } ?>

<div style="clear:both;margin-top:10px">
    <h4 class="text-center"><?php echo $week ?>周任务</h4>


    <div style='color:#0099ff;font-weight:bold'>
<span class="label label-info" style='margin-top:5px;' >

    <?php $DATE_L = $WEEKTASKTIME['start'];
          $url =  $this->createUrl ( 'mytask/showlastWeekTask' );
    ?>
	<a style='color:white' href='<?php echo $url.'/lastweek/'.$DATE_L?>'>本年第<?php echo date('W', $WEEKTASKTIME['start']) ?>周</a>
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
	<a style='color:white' href='#'>任务总量<?php echo $WEEKTASKTIME['count']['total'] ?></a>
</span>
<span class="label label-info" style='margin-top:5px;'>
	<a style='color:white' href='#'>已标记<?php echo $WEEKTASKTIME['count']['now_total'] ?></a>
</span>



   <?php if ($title == WeekTask::LAST_WEEK){ ?>
        <span class="label label-info" style='margin-top:5px;'>
	<a style='color:white' href='#'>有效任务<?php echo $WEEKTASKTIME['count']['con'] ?></a>
</span>
<span class="label label-info" style='margin-top:5px;'>
	<a style='color:white' href='#'>任务有效率<?php echo $WEEKTASKTIME['count']['conformity'] ?></a>
</span>
    </div>
    <?php } ?>
</div>
<span style="float: right;">

    <a href="/manage/Mytask/UpdateWeekTask" target="_blank"><font color="#ff0000" class="btn btn-danger"> 周任务统计数据==>>重新计算</font></a>
</span>
<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <?php if ($title == WeekTask::THIS_WEEK) { ?>
            <th>收益</th>
            <th>增长率</th>
            <th onclick="what_is_it()">目标收益(show)</th>
            <th>增长率</th>
        <?php } else if ($title == WeekTask::LAST_WEEK) { ?>
            <th>收益</th>
            <th>任务收益</th>
            <th>增长率</th>
            <th>任务状态</th>
            <th>收益详情</th>
            <?php if($now==1){?>
            <th>继续标记</th>
             <?php }?>
        <?php } ?>

        <th>用户信息</th>
        <th>相关信息</th>
        <!--  <th>上报/删除</th>  -->
    </tr>
    <?php foreach ($list AS $item) { ?>
        <tr>
            <td><?php echo $item['username']; ?></td>
            <!-- 如果是本周的任务信息  start-->

            <?php if ($title == WeekTask::THIS_WEEK) { ?>
                <?php
                $sat = $sat;
                $sat_data = WeekTask::model()->getPayBackByMidInLastSat($sat['sat_time'], $sat['last_sat_time'], $item['m_id']);
                ?>
                <td>
                    <div style='float:left;margin-right:5px;'>
                        <div>昨天：<?php echo round($sat_data['yesterday'], 3) ?></div>
                        <div>前天：<?php echo round($sat_data['b_yesterday'], 3) ?></div>
                    </div>
                    <div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
                        <img src='<?php echo $sat_data['y_img'] ?>';>
                    </div>
                </td>
                <td><?php echo $sat_data['y_sal']; ?></td>
                <td>
                    <div style='float:left;margin-right:5px;'>
                        <div>目标日期：<?php echo round($sat_data['sat'], 3) ?></div>
                        <div>对比日期：<?php echo round($sat_data['last_sat'], 3) ?></div>
                    </div>
                    <div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
                        <img src='<?php echo $sat_data['sat_img'] ?>';>
                    </div>
                </td>
                <td><?php echo $sat_data['sal']; ?></td>
                <!-- 如果是本周的任务信息 end-->

                <!-- 如果是上一周的任务信息  start-->
            <?php } else if ($title == WeekTask::LAST_WEEK) { ?>
                <?php
                    $pay_back = WeekTask::model()->getPayBcakWhereTaskTypeIsWeek($item['createtime'], $item['endtime'], $item['m_id'], $item['id']);
                ?>
                <td>
                    <div style='float:left;margin-right:5px;'>
                        <div>前天收益：<?php echo round($pay_back['data'], 3) ?></div>
                        <div>对比收益：<?php echo round($pay_back['b_data'], 3) ?></div>
                    </div>
                    <div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
                        <img src='<?php echo $pay_back['y_img'] ?>';>
                    </div>
                </td>
                <td><?php echo $pay_back['subtract'] ?></td>
                <td><?php echo $pay_back['y_sal'] ?><img src='<?php echo $pay_back['y_img'] ?>';></td>
                <td><?php echo WeekTask::getStatusName($item['status']); ?></td>
                <td>
                    <?php $date_c = date('Y-m-d l', $item['createtime']);
                    $date_e = date('Y-m-d l', $item['endtime']);
                    ?>
                    <div class='btn-group'>
                        <a class='btn btn-info btn-mini dropdown-toggle'
                           onclick='weekpaybacklist(<?php echo '"' . $date_c . '"' ?>,
                           <?php echo '"' . $date_e . '"' ?>,<?php echo '"' . $pay_back['old-c'] . '"' ?>,
                           <?php echo '"' . $pay_back['old-e'] . '"' ?>,<?php echo '"' . $pay_back['data'] . '"' ?>,
                           <?php echo '"' . $pay_back['b_data'] . '"' ?>,<?php echo '"' . $pay_back['subtract'] . '"' ?>,
                           <?php //echo '"' . $pay_back['conformity'] . '"' ?>,<?php echo '"' . $pay_back['y_sal'] . '"' ?>,
                           <?php echo '"' . $item['id'] . '"' ?>,<?php echo '"' . $last_sat['sat_date'] . '"' ?>,
                           <?php echo '"' . $last_sat['last_sat_date'] . '"' ?>)';>
                        <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    </div>
                </td>
            <?php if($now==1){?>
                <td>
                    <?php if($item['is_continue']==DefaultParm::DEFAULT_ONE){
                         echo CHtml::Button('已标记', array_merge(Bs::cls(Bs::BTN_DANGER)));
                    }else{
                        echo CHtml::Button('继续标记', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'continue_week_task_show(\''.$item['id'].'\')')));
                    }
                    ?>
                </td>
            <?php } ?>
            <?php } ?>
            <!-- 如果是上一周的任务信息 end-->

            <td>
                <?php echo CHtml::link(Bs::ICON_SEARCH, 'javascript:show(' . $item ['m_id'] . ')') ?>
            </td>
            <?php $arr_list = getManageMenu($item ['m_id']); ?>
            <td>
                <div class='btn-group' id=bt_
                    <?php
                    echo $item ['m_id'];
                    ?>><a class='btn btn-info btn-mini dropdown-toggle'
                          onclick="mylist(<?php
                          echo $item ['m_id'];
                          ?>)";> <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu"
                        id='msg_<?php
                        echo $item ['m_id'];
                        ?>'>
                        <?php
                        echo $arr_list;
                        ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>

<?php $this->renderPartial('/layouts/pop') ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $pages,
)) ?>

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

        <?php if($now==1){?>
        <dt style='margin:10px'>&nbsp;</dt>
        <dd style='margin:10px'><?php echo CHtml::Button('继续标记任务', array_merge(Bs::cls(Bs::BTN_INFO),
                array('onclick' => 'continue_week_task()'))) ?>
        </dd>
        <?php }?>
    </dl>
</div>



