<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-23
 * Time: 下午1:55
 */
$this->breadcrumbs = array(
    '员工信息列表' => array('index'),
    '扣款列表' => array('deduct'),
);

$date_list = ManageDeduct::model()->getMounthByThisYear();
$show_id = Yii::app()->session['show_id'];
?>

<div style='margin-top: 20px'>
    <select id = 'thismounth'>
        <?php foreach($date_list AS $key=>$mou){?>
        <option value = '<?php echo $mou ?>'><?php echo $mou ?></option>
        <?php }?>
    </select>
</div>
<div>
    <input type = 'hidden' value = '<?php echo $show_id ?>' id = 'show_id'>
    <?php
    echo CHtml::Button('确认查看', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'thisMounth()')))
    ?>
</div>
<h4 class="text-center"><span style="color: #0099FF"><?php echo $manage_msg->name?>的扣款列表(<?php echo $mounth?>月份)</span></h4>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>类别</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>因由</th>
        <th>回复</th>
        <th>假条状态</th>
<!--        <th>请假时间</th>-->
<!--        <th>扣款</th>-->
    </tr>
    <?php foreach($manage_leave_msg AS $item){?>
    <?php $leave_name =isset($item->leave)?ManageDeduct::model()->getLeaveNameByType($item->leave):''?>
    <tr>
        <td><?php echo $leave_name;?></td>
        <?php $sdate =isset($item->start_time)?date('Y-m-d H:i',$item->start_time):'';?>
        <td><?php echo $sdate ?></td>
        <?php $edate =isset($item->end_time)?date('Y-m-d H:i',$item->end_time):'';?>
        <td><?php echo $edate?></td>
        <?php $reason =isset($item->reason)?$item->reason:'';?>
        <td onclick='showmsg(this)' value = '<?php echo $reason?>'>
            <?php echo mb_substr($reason,0,5,'utf-8').'...'?>
        </td>
        <?php $message =isset($item->message)?$item->message:'';?>
        <td onclick='showmsg(this)' value = '<?php echo $message; ?>'>
            <?php echo mb_substr($message,0,5,'utf-8').'...'?>
        </td>
        <td>
            <?php $status = ManageDeduct::model()->getStatusNameByStatus($item->ischeck);
                echo $status;
            ?>
        </td>
<!--        <td>3小时</td>-->
<!--        <td>50元</td>-->
    </tr>
    <?php }?>
</table>

<div>
    <input type = 'hidden' value = '<?php echo $show_id?>' id = 'show_id'>
    <?php
    echo CHtml::Button('添加扣款', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'addDeduct()')))
    ?>
</div>

<?php
$hours = array();
$minutes = array();
for($h=0;$h<24;$h++){
    $hours[$h] = $h.'时';
}
for($m = 1;$m<=60;$m++){
    $minutes[$m] = $m;
}
?>
<div id = 'add_deduct' title="扣款项" style="display:none;">
    <dl class="dl-horizontal">
        <dt style = 'margin-bottom: 10px;'>扣款类型</dt>
        <dd style = 'margin-bottom: 10px;'>
          <?php echo  CHtml::dropDownList('deduct_list', '', ManageDeduct::model()->getDeductList())?>
        </dd>
        <dt>扣款开始时间</dt>
        <dd>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value'=>'',//设置默认值
                'name'=>'start',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
            ));
            ?>
            <span style = 'color:red'></span>
            <?php echo  CHtml::dropDownList('s_time', $hours, $hours)?>
        </dd>
        <div id = 'none'>
        <dt>扣款结束时间</dt>
        <dd>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'p_title',
                'value'=>'',//设置默认值
                'name'=>'end',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
            ));
            ?>
            <span style = 'color:red'></span>
            <?php echo  CHtml::dropDownList('e_time', $hours, $hours)?>
        </dd>
        </div>
        <dt>信息</dt>
        <dd>
           <textarea id = 'check_message'></textarea>
        </dd>
        <dt>&nbsp;</dt>
        <dd>
            <?php  echo CHtml::Button('添加', array_merge(Bs::cls(Bs::BTN_INFO),
                    array('onclick' => 'makeSureAddDeduct(\''.$show_id.'\')')))?>
        </dd>
    </dl>
</div>

<div id = 'content' title="回复内容" style="display:none;"></div>
<script type="text/javascript">
    var STAT 				    = '<?php echo $_SERVER["QUERY_STRING"];?>';		//路径参数
    var MA_MESSAGE_DEDUCT 	    = '<?php echo $this->createUrl('manageMessage/deduct') ?>';
    var MA_MESSAGE_ADD_DEDUCT 	= '<?php echo $this->createUrl('manageMessage/addDeductByAdmin') ?>';
</script>