<?php

$this->breadcrumbs = array(
    '我的周任务' => array('weekly'),
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

 $url =  $this->createUrl ( 'mytask/showlastWeekTask' );

 $crea =  date("Y-m-d",$model[0]['createtime']);
 $end  =  date("Y-m-d",$model[0]['endtime']);

$con_num = '';
$total = '';
foreach($model AS $ke => $ite) {
    if($ite['isqualified'] == 1){

        $total =  $total+1;
    }
}
$total = isset($total) && ($total!='') ?$total:0;
if($total == 0){

    $conformity3 = '0';
}else{

    $conformity3 = round(($total/$num) * 100,3);
}
echo '当前有效任务数量'. $total.'</br>';
echo '当前任务总有效率'.$conformity3.'%'.'</br>';


echo '验证有效任务数量'. $WEEKTASKTIME['count']['con'].'</br>';
echo '验证任务总有效率'. $WEEKTASKTIME['count']['conformity'];

?>


<div style="clear:both;margin-top:10px">
    <h4 class="text-center">
        <a href='<?php echo $url.'/lastweek/'.$model[0]['createtime']?>'>上一周</a>
        （<?php echo $crea?> 至 <?php echo $end?>）
        <a href='<?php echo $url.'/nextweek/'.$model[0]['endtime']?>'>下一周</a>
    </h4>
    <div>共 <?php echo $num?>条任务</div>

</div>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>收益(<span style = 'font-size: 10px'>四舍五入取整</span>)</th>
        <th>实际收益</th>
        <th>对比收益</th>
        <th>是否合格</th>
        <th>增长率</th>
        <th>用户信息</th>
        <th>相关信息</th>
    </tr>
    <?php
    foreach ($model AS $key => $item) { ?>
        <?php  $pay_back = WeekTask::model()->getPayBcakWhereTaskTypeIsWeek2($item['createtime'], $item['endtime'], $item['m_id'], $item['id']);
        ?>
        <tr>
            <td><?php echo $item['username']; ?></td>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>当前：<?php echo round($item['payback'], 3) ?></div>
                    <div>验证：<?php echo round($pay_back['subtract'], 0) ?></div>
                </div>
            </td>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>实际收益：<?php echo round($pay_back['data'], 3) ?></div>
                </div>
            </td>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>对比收益：<?php echo round($pay_back['b_data'], 3) ?></div>
                </div>
            </td>
            <?php switch($item['isqualified']){
                case '1':
                    $isqualified = '合格任务';
                    break;
                case '0':
                    $isqualified = '不合格';
                    break;
                case '':
                    $isqualified = '未计算';
                    break;
            }
            switch($pay_back['conformity']){
                case '1':
                    $conformity = '合格任务';
                    break;
                case '0':
                    $conformity = '不合格';
                    break;
                case '':
                    $conformity = '未计算';
                    break;
            }
            ?>
            <td>
                <div>当前：<?php echo $isqualified; ?></div>
                <div>验证：<?php echo $conformity; ?></div>
            </td>

            <td>
                <div style='float:left;margin-right:5px;'>
                    <div><?php echo $pay_back['y_sal']; ?></div>
                </div>

            </td>

            <td>
                <div class='btn-group' id='bt_<?php echo $item ['m_id']; ?>'>
                    <a class='btn btn-info btn-mini dropdown-toggle'
                       onclick="show(<?php echo $item ['m_id']; ?>)";>
                    <i class='icon-cog icon-white'></i>
                    <span class='caret'></span>
                    </a>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
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
            <?php //echo CHtml::textField('s_w_data', ''); ?>
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
            <?php //echo CHtml::textField('b_s_w_data', ''); ?>
        </dd>
        <dt style='margin:10px'>提升比例</dt>
        <dd style='margin:10px'>
            <?php //echo CHtml::textField('sal', ''); ?>

        </dd>
        <dt style='margin:10px'>可获收益</dt>
        <dd>
            <?php //echo CHtml::textField('html', '起始周总收益—对比周总收益'); ?>
        </dd>
        <dd>
            <?php //echo CHtml::textField('data', ''); ?>
        </dd>
        <dd>
            <?php //echo CHtml::textField('pay_back', ''); ?>
        </dd>
        <dt style='margin:10px'>任务是否有效</dt>
        <dd style='margin:10px'><span style='color:red;font-weight:bold;' id='con'></span></dd>

    </dl>
</div>



