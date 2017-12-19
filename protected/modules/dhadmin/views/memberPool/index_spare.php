<?php
$this->breadcrumbs = array(
    '我的备选用户池' => array('indexSpare'),
);

$url = $this->createUrl('myTask/weekly');
$status = $_SERVER["QUERY_STRING"]; //路径参数
$changepool =  $this->createUrl('memberPool/indexNoPro');
$topool = $changepool.'?'.$status;
$MP_VISITE = $this->createUrl('memberPool/visit');
$MP_DROP = $this->createUrl('memberPool/dropTask');
$MT_WEEKLY = $this->createUrl('mytask/weekly');
$TASK_TYPE = $this->createUrl('memberPool/taskType');
?>

<?php
function getManageMenu($id, $tw_id,$category)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('memberinfo/graphs', 'uid' => $id), array('target' => '_blank')),
        'gainadvert' => CHtml::link('业务管理', array('gainadvert/index', 'uid' => $id)),
        'resetpwd' => CHtml::link('重置密码', array('memberinfo/resetpwd', 'uid' => $id)),
        'mail' => CHtml::link('站内信', array('mail/index', 'uid' => $id)),
        'update' => CHtml::link('修改用户信息', array('memberinfo/update', 'id' => $id)),
        'price' => CHtml::link('设置资源单价', array('memberinfo/price', 'id' => $id)),
        'log' => CHtml::link('修改信息历史记录', array('memberinfo/log', 'id' => $id)),
        'memberbranch' => CHtml::link('用户网吧管理', array('memberbranch/index', 'id' => $id)),
        // 'remind' => CHtml::submitButton('我的备注信息', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'remind(\'' . $id . '\',\'' . $tw_id . '\')'))),
        'remind' =>'<a href="#" onclick =remind(\'' . $id . '\',\'' . $tw_id . '\') >我的备注信息</a>',
        'changetype' =>'<a href="#" onclick =category(' . $id . ',' . $category . ')>修改用户类型</a>',
        'remove'=> CHtml::Button('移入主用户池', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'toSpare(\''.$tw_id .'\',0)'))),
        //'remove' =>'<a href="#" onclick =toSpare(\''.$tw_id .'\',1) >移入备选用户池</a>',
    );

    $menus = array();
    if (Auth::check('manage.advisoryrecords.index')) $menus[] = $urls['advisory'];
    if (Auth::check('manage.memberinfo.graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('manage.gainadvert.index')) $menus[] = $urls['gainadvert'];
    if (Auth::check('manage.memberinfo.resetpwd')) $menus[] = $urls['resetpwd'];
    if (Auth::check('manage.mail.index')) $menus[] = $urls['mail'];
    if (Auth::check('manage.memberinfo.update')) $menus[] = $urls['update'];
    if (Auth::check('manage.memberinfo.price')) $menus[] = $urls['price'];
    if (Auth::check('manage.memberinfo.log')) $menus[] = $urls['log'];
    if (Auth::check('manage.memberbranch.index')) $menus[] = $urls['memberbranch'];
    $menus[] = $urls['remind'];
    $menus[] = $urls['changetype'];
    $menus[] = $urls['remove'];


    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}

?>

<div class="bs-docs-example">
    <ul class="nav nav-pills">
        <li class="dropdown active" >
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                周任务信息
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href='<?php echo $url . '/week/' . WeekTask::THIS_WEEK ?>'>需标记<?php echo $kind_num['least'] ?>
                        条任务为本周周任务</a>
                </li>
                <li>
                    <a href='<?php echo $url . '/week/' . WeekTask::THIS_WEEK ?>'>已标记<?php echo $kind_num['this'] ?>条本周周任务</a>
                </li>
                <li>
                    <a href='<?php echo $url . '/week/' . WeekTask::NEXT_WEEK ?>'>已标记<?php echo $kind_num['next'] ?>条下一周周任务</a>
                </li>
            </ul>
        </li>
        <li class="dropdown active">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                页面快捷导航
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li class="blur_this" value='5'><a href='<?php echo $topool ?>'>我的主用户池</a></li>
                <li class="blur_this" value='10'><a href='<?php echo $TASK_TYPE; ?>'>任务提醒列表</a></li>
                <li class="blur_this" value='5'><a href='<?php echo $MP_VISITE; ?>'>回访任务</a></li>
                <li class="blur_this" value='2'><a href='<?php echo $MP_DROP; ?>'>降量任务</a></li>
                <li class="blur_this" value='6'><a href='<?php echo $MT_WEEKLY; ?>'>周任务</a>
                    <ul class="blur_this" id='showweeklist' style=" display: none">
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/1'>上周</a></li>
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/2'>本周</a></li>
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/3'>下周</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="active"><a href='<?php echo $topool ?>'>到我的主用户池</a></li>
        <?php if ($now == 1) { ?>
            <?php $url = $this->createUrl('IndexSpare');
            if ($rank == 0) {?>
                <li class = 'active'><a href='<?php echo $url . '/rank/1' ?>'>按用户等级排序</a></li>
            <?php } else { ?>
                <li class = 'active'><a href='<?php echo $url . '/rank/0' ?>'>按提醒时间排序</a></li>
            <?php } ?>
            <li>
                <div class="input-prepend">
                    <div class="btn-group">
                        <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                            条件查找
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="blur_this" value='5'><a href='#' onclick='searchtype("name")' >按用户名查找</a></li>
                            <li class="blur_this" value='2'><a href='#' onclick='searchtype("remind")'>提醒时间查找</a></li>
                            <input type = 'hidden' id = 'searchtype' value="">
                        </ul>
                    </div>

                    <input type='text' class="span2" style = 'width:200px' name='member_info' id='member_info' value='';>
                    <input type = 'text' style = 'display: none' lang="date" name = 'remind_time' id = 'remind_time'>
                    <input type = 'text' style = 'display: none' lang="date" name = 'remind_time1' id = 'remind_time1'>
                    <button class="btn btn-primary" type="button" onclick="searchMyMember(1)">Go!</button>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>


<div style="clear:both;margin-top:10px">
    <h4 class="text-center"><span style="color:#0099FF"><?php echo $name ?></span>的备选用户池</h4>
    <hr>
    <span style='float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>
</div>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>
            <span class="label label-info" style='margin-top:5px;' onclick="changeFromPoolToSpace(0)">
                    <a style='color:white' href='#'>主选</a>
            </span>
        </th>
        <th>用户名</th>
        <th>提醒</th>
        <th>排名</th>
        <th>信息</th>
        <th>类别</th>
        <th>当天收益</th>
        <th>可获收益</th>
        <th>发布时间</th>
        <th>任务详情</th>
        <th>相关信息</th>
        <?php if ($now == 1) { ?>
            <th>上报/申请</th>
        <?php } ?>
    </tr>
    <input type='hidden' value='<?php echo $now; ?>' id='now'>

    <?php foreach ($data as $member) { ?>
        <?php $time = date('Y-m-d', time());
        if (isset($member['remind']) && ($member['tw_status'] != 1) && ($member['remind'] != 0) && ($time >= date('Y-m-d', $member['remind']))) { ?>
            <tr style="color:#FF6FB7;font-weight:bold;">
        <?php } else if ($member['wt_id'] != 0) { ?>
            <tr style="color:#C9BEE2;font-weight:bold;">
        <?php } else { ?>
            <tr>
        <?php } ?>

        <td><input type = 'checkbox' name = 'tospace' value = '<?php echo $member['tw_id']?>'></td>
        <td><?php echo $member['username'] ?></td>

        <?php if (isset($member['remind']) && ($member['remind'] != 0)) { ?>
            <td>
                <?php echo date('Y-m-d', $member['remind']); ?>
            </td>
        <?php } else { ?>
            <td>
                无
            </td>
        <?php } ?>


        <td>
            <?php echo $member['important']; ?>
        </td>


        <td>
            <?php echo CHtml::button('信息', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'show(\'' . $member['mid'] . '\')'))); ?>
        </td>
        <td><?php echo Task::getTypeName($member['type']); ?></td>
        <?php $data = TaskWhen::getDataByNow($member['mid'], $member['type'], $member['a_time'], $role); ?>

        <?php if ($member['type'] == 5) { ?>
            <td>无</td>
        <?php } else { ?>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>发布：<?php echo round($data['the_day'], 3) ?></div>
                    <div>昨天：<?php echo round($data['yesterday'], 3) ?></div>
                </div>
                <div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
                    <img src='<?php echo $data['img'] ?>';>
                </div>
            </td>
        <?php } ?>
        <td>
            <?php if ($member['type'] == 5) {
                echo '无收益';
            } else {
                echo round($data['data'], 3);
            }?>
        </td>

        <td><?php echo date("Y-m-d", $member['createtime']) ?></td>

        <td>
            <?php if ($member['wt_id'] != 0) { ?>

                <div class='btn-group2'>
                    <img src='/images/memberpool/lock1.jpg'>
                </div>
            <?php } else { ?>
                <div class='btn-group'>
                    <?php $root_url = $this->createUrl('MyTask'); ?>
                    <a class='btn btn-info btn-mini dropdown-toggle' target="_blank"
                       href="<?php echo $root_url ?>/tid/<?php echo $member['tid']; ?>/uid/<?php echo $id; ?>">
                        <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                </div>
            <?php } ?>
        </td>
        <?php $arr_list = getManageMenu($member['mid'], $member['tw_id'], $member['category']); ?>
        <td>
            <div class='btn-group' id=bt_<?php echo $member['mid']; ?>>
                <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $member['mid']; ?>)";>
                <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                <ul class="dropdown-menu" id='msg_<?php echo $member['mid']; ?>'>
                    <?php echo $arr_list; ?>
                </ul>
            </div>
        </td>
        <?php if ($now == 1) { ?>
            <?php if ($member['type'] == 5) { ?>
                <td>
      		<span class="label label-info" style='margin-top:5px;'>
				<div style='color:white' onclick='askforvisitetask(<?php echo $member['at_id']; ?>)'>申请</div>
			</span>
			<span class="label label-info" style='margin-top:5px;'>
				<div style='color:white' onclick='delvisitetask(<?php echo $member['at_id']; ?>)'>无效</div>
			</span>
                </td>

            <?php } else { ?>

                <td>
       		<span class="label label-info" style='margin-top:5px;'>
       		<?php if ($member['wt_id'] == 0) { ?>
                <div style='color:white'
                     onclick='proweekly(<?php echo $member['at_id']; ?>,<?php echo $kind_num['this'] ?>,
                     <?php echo $kind_num['next'] ?>,<?php echo $role ?>)'>
                    标记周任务
                </div>
            <?php } else { ?>
                <div style='color:white' onclick='a_pro()'>已被标记</div>
            <?php } ?>
			</span>
                </td>
            <?php } ?>
        <?php } ?>
        </tr>
    <?php } ?>
</table>

<?php $this->renderPartial('/layouts/pop') ?>

<div class="pager">
    <?php $this->widget("CLinkPager", array(
        'pages' => $pages,
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'maxButtonCount' => 15
    ));?>
</div>

<input type = 'hidden' value='<?php echo $week_type ?>' id='week_type'>
<input type = 'hidden' value = '<?php echo $res?>' id = 'promotion'>
<input type = 'hidden' value = '<?php echo $role?>' id = 'role'>
<input type='hidden' value='1' id='towhere'>

