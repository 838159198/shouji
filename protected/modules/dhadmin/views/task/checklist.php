<style type="text/css">
    .label {line-height: 3;height: 3px;padding: .5em .9em .6em;}
    ul,li{list-style: none;list-style-type: none;}
</style>
<?php
$this->breadcrumbs = array('任务审核' => array('check'));
?>
<?php

function getManageMenu($id)
{
    $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')), 'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id),array('target' => '_blank'),
        array('target' => '_blank')),'edit' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')));

    $menus = array();
    if (Auth::check('advisoryrecords_index'))
        $menus [] = $urls ['advisory'];
    if (Auth::check('member_graphs'))
        $menus [] = $urls ['graphs'];
    if (Auth::check('member_edit'))
        $menus [] = $urls ['edit'];
    if (Auth::check('member_log'))
        $menus [] = $urls ['log'];

    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;
}
$url = $this->createUrl('myTask/weekly');
$status = $_SERVER["QUERY_STRING"]; //路径参数
$memberpool = $this->createUrl('memberPool/indexNoPro');
$changepool = $this->createUrl('memberPool/indexSpare');
$topool = $changepool . '?' . $status;
$MP_VISITE = $this->createUrl('memberPool/visit' );
$MP_DROP = $this->createUrl('memberPool/dropTask' );
$MT_WEEKLY =  $this->createUrl ( 'mytask/weekly' );
$TASK_TYPE =  $this->createUrl ( 'memberPool/taskType' );
?>

<!--客服导航开始-->
<div style = 'float:left;margin-left:20px;'>
        <span class="label label-primary" style='margin-top:5px;margin-bottom: 20px;height: 30px;line-height: 30px;' id = 'showmanagelist'>
            <a style='color:white' href='#' >客服任务查看</a>
        </span>
    <ul class="managelist_show"  style=" display: none">
        <?php foreach($manage_list as $_list){?>
        <li class="blur_this" value = '<?php echo $_list ['id']?>'>
            <div>
            <!--用户名列表开始-->
                <span class="label label-info" style='margin-top:5px;'>
                     <a style='color:white' href='<?php echo $memberpool.'?id='.$_list ['id']; ?>'>
                         <?php echo $_list ['name']; ?>(<?php echo $_list ['rname']; ?>)
                     </a>
                </span>
            <!--用户名列表结束-->
            <!--任务类别开始---->
                    <ul class="show_task_type"  style=" display: none">

                                <span class="label label-info" style='margin-top:1px;'>
                                    <a style='color:white' href='<?php echo $memberpool.'?id='.$_list ['id']; ?>'>用户池</a>
                                </span>

                                <!--<span class="label label-info" style='margin-top:5px;'>
                                    <a style='color:white' href='<?php /*echo $MT_WEEKLY.'?id='.$_list ['id'] ;*/?>'>周任务</a>
                                </span>-->

                            <!--<div class="week_task_week"  style=" display: none">
                                    <span class="label label-info" style='margin-top:5px;'>
                                        <a style='color:white' href='<?php /*echo $MT_WEEKLY.'?id='.$_list ['id'] ;*/?>&week=1'>上周</a>
                                    </span>

                                    <span class="label label-info" style='margin-top:5px;'>
                                        <a style='color:white' href='<?php /*echo $MT_WEEKLY.'?id='.$_list ['id'] ;*/?>&week=2'>本周</a>
                                    </span>

                                    <span class="label label-info" style='margin-top:5px;'>
                                        <a style='color:white' href='<?php /*echo $MT_WEEKLY.'?id='.$_list ['id'] ;*/?>&week=3'>下周</a>
                                    </span>
                            </div>-->
                        </li>
                    </ul>
            <!--任务类别结束-->
            </div>
        </li>
        <?php } ?>
    </ul>
</div>
<!--客服导航开始-->




<!--<h4 class="text-center">客服管理</h4>-->
<!--<table class="table table-hover table-striped table-condensed">-->
<!--    <tr>-->
<!--        <td><select onchange="toManage(this)" id='manage_list'>-->
<!--                --><?php //foreach ($manage_list as $_list) { ?>
<!--                    <option id="--><?php //echo $_list ['id'] ?><!--" value="--><?php //echo $_list ['name'] ?><!--">-->
<!--                        --><?php //echo $_list ['name']; ?><!--(--><?php //echo $_list ['rname']; ?><!--)-->
<!--                    </option>-->
<!--                --><?php //} ?>
<!--            </select>-->
<!--            <input type='hidden' id='to_manage' value=''>-->
<!--            <input type='hidden' id='manager' value='--><?php //echo $this->uid ?><!--'></td>-->
<!--        <td>-->
<!--            --><?php //echo CHtml::button('点击查看', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'toManagePool()'))); ?>
<!--        </td>-->
<!--    </tr>-->
<!---->
<!--</table>-->
<!--
<h4 class="text-center">周任务结束时间设置</h4>
<table class="table table-hover table-striped table-condensed">
    <tr>
        <td>
            <?php
/*            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value' => '', //设置默认值
                'name' => 'weekTask_endtime',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'maxlength' => 8,
                ),
            ));
            */?>
        </td>
        <td>
            <?php
/*            echo CHtml::button('点击确认', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'updateWeekTaskEndtime()')));
            */?>
        </td>
    </tr>
</table>-->
<div style = 'margin:40px;margin-left: 20px;margin-bottom: 0px;clear: both'>
<?php
$manid=Yii::app()->user->manage_id;
$manrule=Manage::model()->find('id=:id',array(':id'=>$manid));

if($manrule["role"]<=3)
{
    switch ($show_check) {
        case DefaultParm::DEFAULT_TWO:
            echo CHtml::button('显示全部任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'showall()')));
            echo CHtml::Button('显示已上报任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkdone()')));
            echo CHtml::Button('显示已完成任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkfinish()')));
            break;
        case DefaultParm::DEFAULT_THREE:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            echo CHtml::button('显示全部任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'showall()')));
            echo CHtml::Button('显示已完成任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkfinish()')));
            break;
        case DefaultParm::DEFAULT_FOUR:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            echo CHtml::Button('显示已上报任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkdone()')));
            echo CHtml::button('显示全部任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'showall()')));
            break;
        default:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            echo CHtml::Button('显示已上报任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkdone()')));
            echo CHtml::Button('显示已完成任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkfinish()')));
            break;
    }
}
elseif($manrule["role"]==4)
{
    switch ($show_check) {
        case DefaultParm::DEFAULT_TWO:
            echo CHtml::button('显示全部任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'showall()')));
            break;
        case DefaultParm::DEFAULT_THREE:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            break;
        case DefaultParm::DEFAULT_FOUR:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            break;
        default:
            echo CHtml::Button('显示待批准任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'checkshow()')));
            break;
    }
}
?>
</div>
<div style = 'margin:20px;float:right'>
    <?php
    if($show_check== DefaultParm::DEFAULT_THREE){
        echo CHtml::Button('只看成功任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'check_fail(\''.DefaultParm::DEFAULT_ZERO.'\')')));
        echo CHtml::Button('只看失败任务', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'check_fail(\''.DefaultParm::DEFAULT_ONE.'\')')));
    }

    ?>
</div>
<h4 class="text-center" style = 'clear: both'>任务审核</h4>
<span style='float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>
<table class="table table-hover table-striped table-condensed">
    <tr>
        <th><input type='checkbox' id='check_all' onclick="selectAll(this);">全选</th>
        <th>申请人</th>
        <th>申请时间</th>
        <th>任务状态</th>
        <th>任务类型</th>
        <th>请求状态</th>
        <th>用户姓名</th>
        <th>用户类别</th>
        <th>用户信息</th>
    <?php if(isset($show_check)&&($show_check!=2)){?>
        <th>查看</th>
    <?php }?>
        <th>相关信息</th>
    </tr>
    <?php foreach ($list as $member) { ?>
       <?php if(isset($member['isfail'])&&($member['isfail']==1)){?>
            <tr style="color:#FF6FB7;font-weight:bold;">
       <?php }elseif(isset($member['isfail'])&&($member['isfail']==0)){?>
            <tr style="color:#88a0e2;font-weight:bold;">
       <?php }?>
            <td><input type='checkbox' name='del' value='<?php echo $member ['id'] ?>'
                       atid='<?php echo $member ['member_id'] ?>'></td>
            <td><?php echo $member ['name'] ?></td>
            <td><?php echo date("Y-m-d H:i", $member ['a_time']) ?></td>
            <input type='hidden' id='type_<?php echo $member ['id'] ?>' value='<?php echo $member ['type'] ?>'>
            <td><?php if ($member ['t_status'] == AskTask::STATUS_CASK) {
                    echo "任务可申请";
                } elseif ($member ['t_status'] == AskTask::STATUS_AASK) {
                    echo "已被申请";
                } elseif ($member ['t_status'] == AskTask::STATUS_APRO) {
                    echo "已上报";
                } elseif ($member ['t_status'] == AskTask::STATUS_ADONE) {
                    echo "任务已完成";
                }?>
            </td>
            <td><?php if ($member ['type'] == Task::TYPE_NEW) {
                    echo "<span style='color:#3d773d;'>普通任务</span>";
                } elseif ($member ['type'] == Task::TYPE_DROP) {
                    echo "<span style='color:#bd362f;'>降量任务</span>";
                } elseif ($member ['type'] == Task::TYPE_WEEK) {
                    echo "<span style='color:#1006F1;'>周任务</span>";
                } elseif ($member ['type'] == Task::TYPE_OTHER) {
                    echo "<span style='color:#444444;'>其他任务</span>";
                } elseif ($member ['type'] == Task::TYPE_VISIT) {
                    echo "<span style='color:#0078e7'>有效回访</span>";
                }?>
            </td>
            <td>

                <?php if ($member ['type'] == Task::TYPE_VISIT) {


                     if ($member ['is_allow'] == AskTask::IS_ALLOW_WAIT)
                     {
                        echo CHtml::submitButton('准许', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'is_Vallow(1,\'' . $member ['id'] . '\',\'' . $member ['member_id'] . '\')')));
                        echo CHtml::submitButton('拒绝', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'is_Vallow(0,\'' . $member ['id'] . '\',\'' . $member ['member_id'] . '\')')));
                     } else {
                       echo "任务进行中";
                     }

                } else

                {

                    if ($member ['is_allow'] == AskTask::IS_ALLOW_WAIT) {
                        echo CHtml::submitButton('准许', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'is_allow(1,\'' . $member ['id'] . '\',\'' . $member ['member_id'] . '\')')));
                        echo CHtml::submitButton('拒绝', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'is_allow(0,\'' . $member ['id'] . '\',\'' . $member ['member_id'] . '\')')));
                     } else {
                        echo "任务进行中";
                     }

                }?>




            </td>
            <td><?php echo $member ['username'] ?></td>
            <td>
                <?php echo Member::getTypeName1($member ['category']); ?>
                <?php
                echo Bs::nbsp . CHtml::link(Bs::ICON_EDIT, 'javascript:;', array('onclick' => 'category(' . $member ['member_id'] . ',' . $member ['category'] . ')'))?>

            </td>
            <td><?php
                echo CHtml::link(Bs::ICON_SEARCH, 'javascript:show(' . $member ['member_id'] . ')')?>
            </td>
            <?php if(isset($show_check)&&($show_check!=2)){?>
            <td>
                <?php $root_url = $this->createUrl('checkout'); ?>
                <div class='btn-group'>
                    <?php
                    if ($show_check == 2) {
                    ?>
                    <a class='btn btn-info btn-mini dropdown-toggle' target="_blank"
                       href="<?php echo $root_url ?>/id/<?php echo $member ['t_id']; ?>">
                        <?php } else { ?>
                        <a class='btn btn-info btn-mini dropdown-toggle' target="_blank"
                           href="<?php echo $root_url ?>/id/<?php echo $member ['t_id']; ?>">
                            <?php } ?>
                            <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                        <ul class='dropdown-menu'></div>
            </td>
            <?php }?>
            <?php $arr_list = getManageMenu($member ['member_id']); ?>
            <td>
                <div class='btn-group' id='bt_<?php echo $member ['member_id']; ?>'>
                    <a class='btn btn-info btn-mini dropdown-toggle'
                       onclick="mylist(<?php echo $member ['member_id']; ?>)";>
                    <i class='icon-cog icon-white'></i>
                    <span class='caret'></span>
                    </a>
                    <ul class="dropdown-menu" id='msg_<?php echo $member ['member_id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>

<?php if(($show_check ==DefaultParm::DEFAULT_TWO)||($show_check ==DefaultParm::DEFAULT_ONE)){?>
    <span style='margin-left: 20px;'>
        <?php echo CHtml::button('全部准许', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'is_allow_all(1)')));
        ?>
    </span>
    <span style='margin-left: 20px;'>
        <?php echo CHtml::button('全部拒绝', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'is_allow_all(0,\'' . $type . '\')')));
        ?>
    </span>
<?php }?>
<?php if(($show_check ==DefaultParm::DEFAULT_THREE)||($show_check ==DefaultParm::DEFAULT_ONE)){?>
    <span style='margin-left: 20px;'>
    <?php echo CHtml::button('批量审核', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'showScore()')));
    ?>
    </span>
<?php }?>
<?php if(($show_check ==DefaultParm::DEFAULT_FOUR)||($show_check ==DefaultParm::DEFAULT_ONE)){?>
    <span style='margin-left: 20px;'>
    <?php echo CHtml::button('清除', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'del_task()')));
    ?>
    </span>
<?php }?>

<div class="pager">
    <?php $this->widget("CLinkPager", array('pages' => $pages, 'firstPageLabel' => '首页', 'lastPageLabel' => '末页', 'maxButtonCount' => 15));
    ?>
</div>

<?php $this->renderPartial('/layouts/pop') ?>
<?php
$id = $this->uid;
$this_week = WeekTask::model()->getThisWeekWtTaskDate($id);
?>
<input type='hidden' value='<?php echo $this_week['c_date']; ?>' id='this_monday'>

<!--评分弹窗-->
<!--<div  id= 'score_div' style='border:0px solid #e7e7e7;display:none'>-->
<div id='score_div' title="批量审批" style="display:none;">
    <label for="m_category" style='margin-top: 20px'>管理员评分<span class="required"></span> </label>
    <fieldset class="rating">

        <input onclick="check_all_tsk(this)" type="radio" id="star<?php echo TaskWhen::FIVE_STAR ?>"
               name="rating"
               value="<?php echo TaskWhen::FIVE_STAR ?>">
        <label for="star<?php echo TaskWhen::FIVE_STAR ?>" title="Rocks!">
            <?php echo TaskWhen::FIVE_STAR ?> stars
        </label>

        <input onclick="check_all_tsk(this)" type="radio" id="star<?php echo TaskWhen::FOUR_STAR ?>"
               name="rating"
               value="<?php echo TaskWhen::FOUR_STAR ?>">
        <label for="star<?php echo TaskWhen::FOUR_STAR ?>" title="Pretty good">
            <?php echo TaskWhen::FOUR_STAR ?> stars
        </label>

        <input onclick="check_all_tsk(this)" type="radio" id="star<?php echo TaskWhen::THREE_STAR ?>"
               name="rating"
               value="<?php echo TaskWhen::THREE_STAR ?>">
        <label for="star<?php echo TaskWhen::THREE_STAR ?>" title="Meh">
            <?php echo TaskWhen::THREE_STAR ?> stars
        </label>

        <input onclick="check_all_tsk(this)"
               type="radio" id="star<?php echo TaskWhen::TWO_STAR ?>" name="rating"
               value="<?php echo TaskWhen::TWO_STAR ?>">
        <label for="star<?php echo TaskWhen::TWO_STAR ?>" title="Kinda bad">
            <?php echo TaskWhen::TWO_STAR ?> stars
        </label>

        <input onclick="check_all_tsk(this)" type="radio" id="star<?php echo TaskWhen::ONE_STAR ?>" name="rating"
               value="<?php echo TaskWhen::ONE_STAR ?>">
        <label for="star<?php echo TaskWhen::ONE_STAR ?>" title="Sucks big time">
            <?php echo TaskWhen::ONE_STAR ?> star
        </label>

    </fieldset>
</div>
<script type="text/javascript">
    function is_Vallow(allow, at_id, mi_id) {

        var is_allow = allow;
        var at_id = at_id;
        var mi_id = mi_id;
        var url = T_UPDATEVONE;
        var maxlength = base_parm.MAX_TOTAL_INSERT;
        if (is_allow == task_isallow.ISALLOW_TRUE) {
            asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_TRUE, title.QUESTION);
        } else if (is_allow == task_isallow.ISALLOW_FALSE) {
            asyncbox.confirm(question_before_action.MAKE_SURE_ISALLOW_FALSE, title.QUESTION);
        }
        $("#asyncbox_confirm_ok").click(function () {
            if (allow == task_isallow.ISALLOW_FALSE) {
                var model = $("#DelTaskMsg");
                model.dialog("open");

                $("#reply_task_msg").click(function () {
                    var msg = $("#reply_msg").val();
                    if (msg.length > maxlength) {
                        asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
                        return false;
                    } else {
                        $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                            var obj = (new Function("return " + data))();
                            if (obj.msg == data_back.DATA_SUCCESS) {
                                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                                location.reload();
                            } else if ((obj.msg == data_back.DATA_ERROR )) {
                                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                            } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                                asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                            }
                        })
                    }
                })
            } else {
                var msg = '';
                $.post(url, {is_allow: is_allow, at_id: at_id, mi_id: mi_id, msg: msg}, function (data) {
                    var obj = (new Function("return " + data))();
                    if (obj.msg == data_back.DATA_SUCCESS) {
                        asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                        location.reload();
                    } else if ((obj.msg == data_back.DATA_ERROR )) {
                        asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                    } else if ((obj.msg == data_back.DATA_ERROR_NOPOWER)) {
                        asyncbox.alert(data_back_msg.DATA_ERROR_NO_POWEER, title.TITLE_ERROR);
                    }
                })
            }
        })
    }

</script>