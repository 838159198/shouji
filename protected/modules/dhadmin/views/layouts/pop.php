<script type="text/javascript">
    var MEMBER_INFO_URL = '<?php echo $this->createUrl('member/info') ?>';

    var MM_WAGE_LIST_POWER = '<?php echo $this->createUrl ( 'manageMessage/wageListPower' )?>';

    var STAT = '<?php echo $_SERVER["QUERY_STRING"];?>';		//路径参数

    var ADMIN_PWD = '<?php echo $this->createUrl ( 'admin/pwd' )?>';

    var R_CHANGE_MANAGE_ROLE = '<?php echo $this->createUrl('role/updateManageRoleByWeekTaskCallBack') ?>';

    var MA_WAGE_LIST = '<?php echo $this->createUrl ( 'manageMessage/myWageList' )?>';

    var Mi_MARK_LIST = '<?php echo $this->createUrl('member/checkMark') ?>';
    var Mi_GUM = '<?php echo $this->createUrl('member/giveUpThisMember') ?>';

    var MP_LASTCONTACT = '<?php echo $this->createUrl('memberPool/lastContactList') ?>';
    var MP_CHANGE_POOL_ALL = '<?php echo $this->createUrl('memberPool/changePoolAll') ?>';
    var MP_DEL_WAIT_ALLOW = '<?php echo $this->createUrl('memberPool/delWaitToAllowTask') ?>';
    var MP_SPACE = '<?php echo $this->createUrl('memberPool/indexSpare') ?>';
    var MP_CHANGE_POOL = '<?php echo $this->createUrl('memberPool/changePool') ?>';
    var MP_VISITE_TASK = '<?php echo $this->createUrl('memberPool/askForVisiteTask') ?>';
    var MP_VISITE_VTASK = '<?php echo $this->createUrl('memberPool/askForVisiteVTask') ?>';
    var MP_TASK_VTASK = '<?php echo $this->createUrl('memberPool/taskCount') ?>';
    var MP_DELVISITETASK = '<?php echo $this->createUrl('memberPool/delVisiteTask') ?>';
    var MP_DELVISITETASK1 = '<?php echo $this->createUrl('memberPool/delVisiteTaskall') ?>';
    var MP_REFUSE = '<?php echo $this->createUrl('memberPool/refuseTask' )?>';
    var MP_VISITE = '<?php echo $this->createUrl('memberPool/visit' )?>';
    var MP_DELNOALLOW = '<?php echo $this->createUrl('memberPool/delNotAllowTask' )?>';
    var MP_INFO = '<?php echo $this->createUrl('memberPool/info' )?>';
    var MP_BACKTASK = '<?php echo $this->createUrl('memberPool/backTask') ?>';
    var MP_SETMSG = '<?php echo $this->createUrl('memberPool/setMsg') ?>';
    var MP_USERMSG = '<?php echo $this->createUrl('memberPool/userMsg') ?>';
    var MP_NOPRO = '<?php echo $this->createUrl('memberPool/indexNoPro') ?>';
    var MP_PRO = '<?php echo $this->createUrl('memberPool/indexPro') ?>';
    var MP_PAYBACK = '<?php echo $this->createUrl('memberPool/payBack'); ?>';
    var MP_S_PAYBACK = '<?php echo $this->createUrl('memberPool/sendPayBack'); ?>';
    var MP_GETTASK = '<?php echo $this->createUrl('memberPool/gettask'); ?>';
    var MP_TASKSSTATUS = '<?php echo $this->createUrl('memberPool/taskstatus'); ?>';
    var MP_GETSCORE = '<?php echo $this->createUrl('memberPool/getScore'); ?>';
    var MP_REPLY = '<?php echo $this->createUrl('memberPool/reply'); ?>';
    var MP_HELP = '<?php echo $this->createUrl('memberPool/help'); ?>';

    var S_URL = MP_NOPRO + '?' + STAT;
    var S_URL1 = MP_PRO + '?' + STAT;
    var S_URL2 = MP_PAYBACK + '?' + STAT;

    var T_CHECK_ALL_TASK = '<?php echo $this->createUrl ( 'task/checkAllTask' )?>';
    var T_CHECK_URL = '<?php echo $this->createUrl ( 'task/checklist' )?>';
    var T_UPDATEALL = '<?php echo $this->createUrl ( 'task/updeTaskTypeAll' )?>';
    var T_UPDATEONE = '<?php echo $this->createUrl ( 'task/updeTaskType' )?>';
    var T_UPDATEVONE = '<?php echo $this->createUrl ( 'task/updeTaskVType' )?>';
    var T_DELETE = '<?php echo $this->createUrl ( 'task/delTask' )?>';
    var T_DELBYMSG = '<?php echo $this->createUrl ( 'task/delTaskByMsg' )?>';
    var T_GETSCORE = '<?php echo $this->createUrl ( 'task/getScore'); ?>';
    var T_CHECK_LIST = '<?php echo $this->createUrl ( 'task/checkList'); ?>';
    var T_CHECK_PAY_BACK = '<?php echo $this->createUrl ( 'task/checkPayBack'); ?>';

    var MT_WEEKLY = '<?php echo $this->createUrl ( 'mytask/weekly' )?>';
    var MT_CONTINUE = '<?php echo $this->createUrl ( 'mytask/continue' )?>';
    var MT_PROWEEK = '<?php echo $this->createUrl ( 'mytask/addWeekTask' )?>';
    var MT_WEEKTIME = '<?php echo $this->createUrl ( 'mytask/weekTime' )?>';
    var MT_PROWEEKTASK = '<?php echo $this->createUrl ( 'mytask/protWeekTask' )?>';
    var MT_UPWTENDTIME1 = '<?php echo $this->createUrl ( 'mytask/updateWeekTasKendTime1' )?>';
    var MT_UPWTENDTIME2 = '<?php echo $this->createUrl ( 'mytask/updateWeekTaskEndTime2' )?>';
    var MT_STAFFPROVISITE = '<?php echo $this->createUrl ( 'mytask/staffProVisiteTask' )?>';
</script>

<!-- ***************************************** -->

<!-- ***************************************** -->

<div id="task_list" title="已存在任务" style="display: none;">
    <?php
    echo CHtml::label('已存在任务：', 'm_category')?>
    <input type='hidden' value='' id='hide_uid'>
    <table class="table table-hover table-striped table-condensed"
           id='is_task'>
        <tr>
            <th>客服姓名</th>
            <th>发布时间</th>
            <th>标题</th>
            <th>任务状态</th>
            <th>是否已查看</th>
            <th>详细信息</th>
        </tr>
    </table>

    <?php
    echo CHtml::button('关闭', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'ask_for_task()')));
    ?>
</div>


<!-- ***************************************** -->

<!-- ***************************************** -->


<div id="taskmsg" title="任务信息" style="display: none;"><label id="suname"></label>
    <dl class="dl-horizontal">
        <dt>标题（50字）：</dt>
        <dd><?php
            echo CHtml::textField('t_title')?></dd>

        <dt>说明（200字）：</dt>
        <dd><?php
            echo CHtml::textArea('t_content')?></dd>
        <dt>&nbsp;</dt>
        <dd><?php
            echo CHtml::button('确认发布', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'makeSureAskTask()')))?></dd>
    </dl>
</div>


<!-- ***************************************** -->

<!-- ***************************************** -->

<div id="modalcategory" title="修改用户类别" style="display: none;">
    <?php
    echo CHtml::beginForm($this->createUrl('member/Category'), 'post', Bs::cls(Bs::FORM_INLINE)), CHtml::label('用户类别：', 'm_category'), CHtml::dropDownList('m_category', '', MemberCategory::model()->getListToArray()), CHtml::hiddenField('m_uid'), Bs::nbsp, CHtml::submitButton('保存', Bs::cls(Bs::BTN_INFO)), CHtml::endForm();
    ?>
</div>


<!-- ***************************************** -->

<!-- ***************************************** -->


<div id="tasktype" title="任务类别选择" style="display:none;">
   		<span style='margin-top:10px;'>
   		<select id='task_type'>
            <option value='1'>新用户任务</option>
            <!--<option value='2'>降量任务</option>-->
        </select>
   		</span>
   		<span style='margin-top:10px;margin-left:40px;'>
<?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'visi_type'))) ?>
   		</span>

</div>

<!-- ***************************************** -->

<!-- ***************************************** -->


<div id="tasktype2" title="任务类别确认" style="display:none;">
   		<span style='margin-top:10px;'>
            确认申请为有效回访？
   		</span>
   		<span style='margin-top:10px;margin-left:40px;'>
<?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'visi_type2'))) ?>
   		</span>

</div>

<!-- ***************************************** -->

<!-- ***************************************** -->

<input type='hidden' id='time_hide' value=''>

<div id="modaltask" title="我的备注" style="display:none;">
    <?php echo CHtml::beginForm('task', 'post', array('id' => 't_formtask')) ?>
    <label id="suname"></label>
    <dl class="dl-horizontal">

        <dt>用户级别</dt>
        <dd>
            <input width="20px" type='text' name='level' id='level' value='';>
        </dd>
        <dd>
            <span style='color:red'>只能键入数字，越大排名越靠前</span>
        </dd>
        <dt>备注信息</dt>
        <dd>
            <textarea rows="5" cols="10" id='my_remark'></textarea>
        </dd>
        <dt>提醒时间</dt>
        <dd>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value' => '', //设置默认值
                'name' => 'date',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
            ));
            ?>
        </dd>
        <input type='hidden' id='mid' value=''>
        <input type='hidden' id='tw_id' value=''>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'remark()'))) ?></dd>
    </dl>
    <?php echo CHtml::endForm() ?>
</div>

<!-- ***************************************** -->

<!-- ***************************************** -->
<?php
function this_monday($timestamp = 0, $is_return_timestamp = true)
{
    static $cache;
    static $arr = array();
    $id = $timestamp . $is_return_timestamp;
    if (!isset($cache[$id])) {
        if (!$timestamp) $timestamp = time();
        $monday_date = date('Y-m-d', $timestamp - 86400 * date('w', $timestamp) + (date('w', $timestamp) > 0 ? 86400 : - /*6*86400*/518400));
        if ($is_return_timestamp) {
            $cache[$id] = strtotime($monday_date);
        } else {
            $cache[$id] = $monday_date;
        }
    }
    $arr['time'] = $cache[$id];
    $arr['date'] = date('Y-m-d', $cache[$id]);
    return $arr;
}

function getNextMonday()
{
    $arr = array();
    $arr['date'] = date('Y-m-d', strtotime('+1 week last monday'));
    $arr['time'] = strtotime($arr['date']);
    return $arr;
}

$date_start = this_monday();
$date_end = getNextMonday();
?>



<div id="weektaskmsg" title="周任务时间设置" style="display:none;">
    <dl class="dl-horizontal">
        <dt>DATE-START</dt>
        <dd id='d_start'><?php echo date('Y-m-d l', $date_start['time']) ?></dd>
        <dt>开始时间</dt>
        <dd>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value' => $date_start['date'],
                'name' => 'date_start',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
            ));
            ?>
        </dd>
        <dt>DATE-END</dt>
        <dd id='d_end'><?php echo date('Y-m-d l', $date_end['time']) ?></dd>
        <dt>结束时间</dt>
        <dd>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value' => $date_end['date'], //设置默认值
                'name' => 'date_end',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
            ));
            ?>
        </dd>
        <dd><?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'changetime'))) ?></dd>
        <dl class="dl-horizontal">
</div>


<div id="membermsg" title="用户信息" style="display:none;"></div>

<div id='content' title="回复内容" style="display:none;"></div>

<div id='upWeekEndTime' title="修改周任务结束时间" style="display:none;">
    <dl class="dl-horizontal">
        <dt>起始时间</dt>
        <dd id='start' value=''></dd>
        <dt>结束时间</dt>
        <dd id='end' value=''></dd>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'updateendtime'))) ?></dd>
    </dl>
</div>


<div id="DelTaskMsg" title="任务信息" style="display:none;">
    <dt>回复内容</dt>
    <dd>
        <textarea rows="5" cols="10" id='reply_msg'></textarea>
    </dd>

    <?php echo CHtml::Button('提交', array_merge(Bs::cls(Bs::BTN_INFO), array('id' => 'reply_task_msg'))) ?>
</div>


<div id='howtogettaskpay' title="任务收益如何计算查看" style="display:none;">
    <dl class="dl-horizontal">
        <dt>&nbsp</dt>
        <dd >提成等级：</dd>
        <dt>高级客服：</dt>
        <dd >新用户任务：15%；降量任务：7%；周任务：每周3条或3条上周任务合格，876876*&……&%&……￥%……%￥</dd>
        <dt>普通客服：</dt>
        <dd >新用户任务：10%；降量任务：5%；周任务：不计算收益。</dd>
        <dt>见习客服：</dt>
        <dd >只能接收上级管理发布的回访任务，每上报成功一个回访任务并且经上级管理审核通过，可获得1元提成，放入本月工资中</dd>
        <dt>新用户任务：</dt>
        <dd >123</dd>
<!--        <dt>降量任务：</dt>
        <dd >456</dd>
        <dt>周任务：</dt>
        <dd >789</dd>-->
    </dl>
</div>
