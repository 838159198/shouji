<?php
/* @var $this MemberController */
/* @var $json string */
/* @var $memberName string */
/* @var $first string */
/* @var $last string */
/* @var $uid string */
$this->breadcrumbs = array(
    '我的工资单' => array('mywagelist'),
    '工资详情' => array('wageListPower'),
);


$year_list = ManageDeduct::model()->getLastYear();
$mounth_list = ManageDeduct::model()->getMounth();
?>

<style>
    input {width: 70px;height: 50px; }
    select{width: 100px;height: 30px; }
    select option {width: 100px;height: 30px; }
</style>

<h4 class="text-center"><span style="color: #0099FF"> 工资查看导航</span></h4>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>
            <span class="label label-info" style='margin-top:5px;' id = 'showyear' onclick="showyear()">
		        <a style='color:white' href='#'>年   </a>
            </span>
            -
            <span class="label label-info" style='margin-top:5px;' id = 'showmounth' onclick="showmounth()">
		        <a style='color:white' href='#'>月</a>
            </span>
        </th>

<!--        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#' onclick="showweek()">周任务</a>
            </span>
        </th>-->

        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#' onclick="showtaskmsg(1)">新用户任务</a>
            </span>
        </th>

<!--        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#' onclick="showtaskmsg(2)">降量任务</a>
            </span>
        </th>-->

        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#' onclick="showtaskmsg(5)">回访任务</a>
            </span>
        </th>

        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#'>职务工资</a>
            </span>
        </th>
        <?php if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#'>职务提成</a>
            </span>
        </th>
        <?php }?>
        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#'>满勤奖金</a>
            </span>
        </th>
        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#'>扣款金额</a>
            </span>
        </th>
        <th>
            <span class="label label-info" style='margin-top:5px;'>
		        <a style='color:white' href='#'>总计</a>
            </span>

        </th>
    </tr>
<!-------------头结束---------------->
<!-------------列表开始---------------->
    <tr>
        <td>
            <span class="label label-info" style='margin-top:5px;' onclick="showyear()">
		        <a style='color:white' href='#' id = 'list_year'><?php echo date('Y',time())?></a>
            </span>
            -
            <span class="label label-info" style='margin-top:5px;'  onclick="showmounth()">
		        <a style='color:white' href='#' id = 'list_mounth'><?php echo date('m',time())?></a>
            </span>

            <div  id='checkthisyear' style='margin-top: 20px;display: none;z-index: 999'>
                <select id='thisyear' onblur="thisHide()" onchange="chooseyear()">
                    <?php foreach ($year_list AS $key => $year) { ?>
                        <option value='<?php echo $year ?>'><?php echo $year ?></option>
                    <?php } ?>
                </select>
            </div>
            <div  id='checkthismounth' style='margin-top: 20px;display: none;z-index: 999'>
                <select id='thismounth' onblur="thisHide()" onchange="choosemounth()">
                    <?php foreach ($mounth_list AS $key => $mou) { ?>
                        <option value='<?php echo $mou ?>'><?php echo $mou ?></option>
                    <?php } ?>
                </select>
            </div>
        </td>

<!--        <td>
            <?php /*echo CHtml::textField('week', $arr['week']); */?>
        </td>-->

        <td>
            <?php echo CHtml::textField('new', $arr['new']); ?>
        </td>

<!--        <td>
            <?php /*echo CHtml::textField('drop', $arr['drop']); */?>
        </td>-->

        <td>
            <?php echo CHtml::textField('visit', $arr['visit']); ?>
        </td>

        <td>
            <?php echo CHtml::textField('basewage', $arr['basewage']); ?>
        </td>
        <?php if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){?>
            <td>
                <?php echo CHtml::textField('basewage', $com); ?>
            </td>
        <?php }?>
        <td>
            <?php echo CHtml::textField('bonus', $arr['bonus']); ?>
        </td>

        <td>
            <?php echo CHtml::textField('deduct', $arr['deduct']); ?>
        </td>

        <td>
            <?php $total = $arr['week']+ $arr['new']+$arr['drop']+ $arr['basewage']+$com;
                $total = isset($arr['total'])?$arr['total']:$total;
            ?>
            <?php echo CHtml::textField('total', $total); ?>
        </td>
    </tr>
<!-------------列表结束---------------->
</table>


<!-------------显示周任务开始---------------->
<div id = 'week_task_isshow' isshow="0" style = 'display: none'>
<h4 class="text-center"><span style="color: #0099FF"> 周任务导航</span></h4>
<table class="table table-hover table-striped table-condensed" id = 'week_task_list'>
    <tr id = 'week_time_list'></tr>
    <tr id = 'week_concount_list'></tr>
    <tr id = 'week_askcont_list'></tr>
    <tr id = 'week_scale_list'></tr>
    <tr id = 'week_payback_list'></tr>
    <tr id = 'week_msg_list'></tr>
</table>
</div>
<!-------------显示周任务结束---------------->

<!-------------显示任务开始---------------->
<div id = 'task_new_isshow' isshow="0" style = 'display: none'>
    <h4 class="text-center"><span style="color: #0099FF" id="show_title"> </span></h4>
    <table class="table table-hover table-striped table-condensed" id = 'Task_new_msg'>
    </table>
</div>
<!-------------显示任务结束---------------->

<script type="text/javascript">
    var MM_WAGE_LIST_CHANGE     =  '<?php echo $this->createUrl ( 'manageMessage/showWageByDate' )?>';
    var MM_WEEK_TASK_EARNINGS   =  '<?php echo $this->createUrl ( 'manageMessage/showWeekTaskEarningsByDate' )?>';
    var MM_WAGE_LMSG_IST        =  '<?php echo $this->createUrl ( 'manageMessage/showWeekTaskMsgListByDate' )?>';
    var MM_TASK_NEW_MSG         =  '<?php echo $this->createUrl ( 'manageMessage/getTaskNewMsgByDate' )?>';

</script>
<?php $this->renderPartial('/layouts/pop') ?>