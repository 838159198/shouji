<?php
$this->breadcrumbs = array('我的用户池' => array('index'));

$url = $this->createUrl('myTask/weekly');
$status = $_SERVER["QUERY_STRING"]; //路径参数
$changepool = $this->createUrl('memberPool/indexSpare');
$topool = $changepool . '?' . $status;
$MP_VISITE = $this->createUrl('memberPool/visit');
$MP_DROP = $this->createUrl('memberPool/dropTask');
$MT_WEEKLY = $this->createUrl('mytask/weekly');
$TASK_TYPE = $this->createUrl('memberPool/taskType');
?>
<?php

function getManageMenu($id, $tw_id, $category, $now)
{
    if ($now == 1) {
        $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
            'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank'), array('target' => '_blank')),
            'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
            'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
            'changetype' => '<a href="#" onclick =category(' . $id . ',' . $category . ')>修改用户类型</a>',
            'remind' => CHtml::submitButton('我的备注信息', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'remind(\'' . $id . '\',\'' . $tw_id . '\')'), array('target' => '_blank'))));

    } else {
        $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
            'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
            'update' => CHtml::link('修改用户信息', array('member/update', 'id' => $id), array('target' => '_blank')),
            'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank'))
        );
    }

    $menus = array();

    if (Auth::check('advisoryrecords_index'))
        $menus [] = $urls ['advisory'];
    if (Auth::check('member_graphs'))
        $menus [] = $urls ['graphs'];
    if (Auth::check('member_edit'))
        $menus [] = $urls ['update'];
    if (Auth::check('member_log'))
        $menus [] = $urls ['log'];
    if ($now == 1) {
        $menus [] = $urls ['changetype'];
        $menus [] = $urls ['remind'];
    }
    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}

?>

<div class="bs-docs-example">
    <ul class="nav nav-pills">

        <li class="dropdown active">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                页面快捷导航
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <!--<li class="blur_this" value='5'><a href='<?php /*echo $topool */?>'>我的备选用户池</a></li>-->
                <li class="blur_this" value='10'><a href='<?php echo $TASK_TYPE; ?>'>任务提醒列表</a></li>
                <li class="blur_this" value='5'><a href='<?php echo $MP_VISITE; ?>'>回访任务</a></li>
                <!--<li class="blur_this" value='2'><a href='<?php /*echo $MP_DROP; */?>'>降量任务</a></li>
                <li class="blur_this" value='6'><a href='<?php /*echo $MT_WEEKLY; */?>'>周任务</a>-->
<!--                    <ul class="blur_this" id='showweeklist' style=" display: none">
                        <li><a href='<?php /*echo $MT_WEEKLY; */?>/week/1'>上周</a></li>
                        <li><a href='<?php /*echo $MT_WEEKLY; */?>/week/2'>本周</a></li>
                        <li><a href='<?php /*echo $MT_WEEKLY; */?>/week/3'>下周</a></li>
                    </ul>-->
                </li>
            </ul>
        </li>
                <?php switch ($fail) {

                case TaskWhen::IS_FAIL_FALSE:
                ?>
                   <li class="blur_this active" value="6"><a href="#" onclick="checkIsFail(<?php echo TaskWhen::IS_FAIL_TRUE ?>)">只看上报失败的任务</a></li>;
               <?php
                    break;
                case TaskWhen::IS_FAIL_TRUE:
                ?>
                    <li class="blur_this active" value="6"><a href="#" onclick="checkIsFail(<?php echo TaskWhen::IS_FAIL_FALSE ?>)">只看上报成功的任务</a></li>;
                <?php
                    break;
                }?>



        <?php if ($now == 1) { ?>
            <?php $url = $this->createUrl('IndexPro');
            if ($rank == 0) {?>
                <li class = 'active'><a href='<?php echo $url . '/rank/1' ?>'>按用户等级排序</a></li>
            <?php } else { ?>
                <li class = 'active'><a href='<?php echo $url . '/rank/0' ?>'>按提醒时间排序</a></li>
            <?php } ?>
            <li class="blur_this active" value='6'><a href='#' onclick="checkNoPro()">查看未上报任务</a></li>
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
                    <button class="btn btn-primary" type="button" onclick="searchMyMember(0)">Go!</button>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>


<div style="clear: both; margin-top: 10px">
    <h3 class="text-center"><span style="color: #0099FF"><?php echo $name ?></span>的用户池</h3>
    <hr>
    <span style='float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>
</div>


<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>提醒时间</th>
        <th>用户排名</th>
        <th>信息</th>
        <th>任务执行</th>
        <th>任务类别</th>
        <th>上报管理员</th>
        <th>任务收益</th>
        <th>发布时间</th>
        <?php
        if ($role < Role::ADVANCED_STAFF) {
            ?>
            <th>是否驳回</th>
        <?php
        }
        ?>
        <th>任务详情</th>
        <?php if ($now == 1) { ?>
            <th>相关信息</th>
        <?php } ?>
    </tr>
    <input type='hidden' value='<?php echo $now; ?>' id='now'>
    <?php foreach ($data as $member) { ?>
        <tr>
            <td><?php echo $member ['username'] ?></td>
            <?php if (isset ($member ['remind']) && ($member ['remind'] != 0)) { ?>
                <td><?php echo date('Y-m-d', $member ['remind']); ?></td>
            <?php } else { ?>
                <td>无</td><?php } ?>
            <td><?php echo $member ['important']; ?></td>
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $member['mid'] ?>)"><span id="clickst"></span></i>
            </td>
            <td>
                <?php if ($member ['isfail'] == TaskWhen::IS_FAIL_FALSE) {
                    echo "可继续";
                } elseif ($member ['isfail'] == TaskWhen::IS_FAIL_TRUE) {
                    echo '任务失败';
                }?>
            </td>
            <td>
                <?php echo Task::getTypeName($member ['type']); ?>
            </td>
            <td>
                <?php switch ($member ['tw_status']) {
                    case TaskWhen::STATUS_NORMAL:
                        echo "未上报";
                        break;
                    case TaskWhen::STATUS_SUBMAIT:
                        echo "已上报";
                        break;
                    case  TaskWhen::STATUS_ROLLBACK:
                        echo "打回修改";
                        break;
                }?>
            </td>
            <td>
                <?php switch ($member ['tw_status']) {
                    case TaskWhen::STATUS_NORMAL:
                        echo "未上报";
                        break;
                    case "1" :
                        echo $member ['pay_back'] . '元';
                        break;
                    case TaskWhen::STATUS_ROLLBACK :
                        echo "打回修改";
                        break;
                }?>
            </td>

            <td><?php echo date("Y-m-d", $member ['createtime']) ?></td>
            <?php if (($role < Role::ADVANCED_STAFF) &&
                (($member ['tw_status'] == TaskWhen::STATUS_SUBMAIT) || ($member ['tw_status'] == TaskWhen::STATUS_ROLLBACK))
                && ($member ['score'] == TaskWhen::ZERO_STAR)
            ) {
                ?>
                <td>
                    <?php echo CHtml::button('驳回任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'back_task(\'' . $member ['tid'] . '\',\'' . $member ['tw_id'] . '\')'))); ?>
                </td>
            <?php } else if (($role < Role::ADVANCED_STAFF) && (($member ['t_status'] == Task::STATUS_DONE) || ($member ['at_status'] == AskTask::STATUS_ADONE))) { ?>
                <td>已完成</td>
            <?php } ?>

            <td>
                <?php $root_url = $this->createUrl('mytask'); ?>
                <a target="_blank" href="<?php echo $root_url ?>/tid/<?php echo $member['tid']; ?>/uid/<?php echo $id; ?>">
                    <i class="glyphicon glyphicon-search"></i>
                </a>
            </td>
            <?php if ($now == 1) { ?>
                <?php $arr_list = getManageMenu($member ['mid'], $member ['tw_id'], $member ['category'], $now); ?>
                <td>
                    <div class='btn-group' id=bt_
                        <?php echo $member ['mid']; ?>>
                        <a class='btn btn-info btn-mini dropdown-toggle'
                           onclick="mylist(<?php echo $member ['mid']; ?>)";>
                        <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                        <ul class="dropdown-menu" id='msg_<?php echo $member ['mid']; ?>'>
                            <?php echo $arr_list; ?>
                        </ul>
                    </div>
                </td>
            <?php } ?>
            <?php if (($member ['type'] == 5) && ($now == DefaultParm::DEFAULT_ONE)) { ?>
                <td>
                    <span class="label label-info" style='margin-top: 5px;'>
                        <a style='color: white' href='#'
                           onclick='askforvisitetask(<?php echo $member ['at_id']; ?>)'>申请</a>
                    </span>
                </td>
                <td>
                    <span class="label label-info" style='margin-top: 5px;'>
                        <a style='color: white' href='#'
                           onclick='delvisitetask(<?php echo $member ['at_id']; ?>)'>无效</a>
                    </span>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>


<div class="pager">
    <?php
    $this->widget("CLinkPager", array('pages' => $pages, 'firstPageLabel' => '首页', 'lastPageLabel' => '末页', 'maxButtonCount' => 15));
    ?>
</div>
<?php $this->renderPartial('/layouts/pop') ?>
