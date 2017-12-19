<?php
Script::registerScriptFile('manage/memberpool.public/Ajaxback.js',CClientScript::POS_HEAD);
$id = Yii::app()->user->manage_id;
$role = Manage::model()->getRoleByUid($id);

//本周周任务 如果不存在，则默认本周一至下周周一时间节点
$this_week = WeekTask::model()->getThisWeekWtTask($id);

$resault = 0;
/*
 * @name: 客服晋升到高级客服SUPPORT_STAFF-->ADVANCED_STAFF（用户池业绩>=20000）
 * */
if ($role == Role::SUPPORT_STAFF) { //如果用户等级是普通客服
    $manage = Manage::model()->findByPk($id);
    $res = $manage->promotion; //赋值晋升状态
    if ($res == 0) { //数据表，不可晋升
        //查看月内是否合格，（用户池业绩>=20000）
        $status=1;
        //日期在7、8、9三天做上个月总收益判定
        $dayt=date('d',time());
        if(in_array($dayt,array(7,8,9))) { $resault = WeekTask::model()->checkConByMonths($status, $id);  }
        else {  $resault=0;  }

        //如果是等待晋升状态
    } elseif ($res == 2) {
        $pro_time = $manage->pro_time;
        //直接升级
        $this_time = time();
        if ($this_time >= $pro_time) {
            Manage::model()->updateByPk($id, array('promotion' => 3, 'role' => Role::ADVANCED_STAFF));
            echo "<script language='javascript' charset='GBK' type='text/javascript'>alert('恭喜已成功晋升为高级客服，现在起任务收益提高！');</script>";
        }
        $resault = 0;
    } else {
        $resault = 0;
    }
}
/*
 * @name: 高级客服晋升到见习客服主管ADVANCED_STAFF-->PRACTICE_VISOR（用户池业绩>=40000）
 * */
elseif($role == Role::ADVANCED_STAFF){
    $manage = Manage::model()->findByPk($id);
    $res = $manage->promotion; //赋值晋升状态
    $pro_time = $manage->pro_time;

    $pro_month = strtotime(date('Y-m', $pro_time));//上次晋升月份
    $this_month = strtotime(date('Y-m', time())); //当前月份
//print_r($this_month-$pro_month);exit;

    //当前月份大于上次晋升两个月
    if(($this_month-$pro_month)/3600/24>58){
        //判断用户当前晋升状态为3的时候才可以继续晋升
        //2为等待晋升
        if($res==3){
            $status=3;
            //日期在7、8、9三天做两个月总收益判定
            $dayt=date('d',time());
            if(in_array($dayt,array(7,8,9,12))) { $resault = WeekTask::model()->checkConByMonths($status, $id);  }
            else {  $resault=0;  }
        }elseif($res==2){

            //获取指定日期下个月的第一天和最后一天
            $next_mounth = WeekTask::model()->GetNextMonth($pro_time);
            $next_mounth = strtotime($next_mounth);
            $this_time = time();
            if ($this_time >= $next_mounth) {
                Manage::model()->updateByPk($id, array('promotion' => 3, 'role' => Role::PRACTICE_VISOR));
                echo "<script language='javascript' charset='GBK' type='text/javascript'>alert('恭喜已成功晋升为见习客服主管，现在起任务收益提高！');</script>";
             }
        }else{
            $resault = 0;
        }
    }else{
        $resault = 0;
    }
}
/*
 * @name: 见习客服主管升级到客服主管PRACTICE_VISOR===>SUPERVISOR（用户池业绩>=80000）
 * */
elseif($role == Role::PRACTICE_VISOR){
    $manage = Manage::model()->findByPk($id);
    $res = $manage->promotion; //赋值晋升状态
    //当前日期大于上次晋升日期+4周strtotime("+4 week"),
    $next_monday = strtotime('next monday' ,$manage->pro_time);
    $week4after = strtotime("+4 week",$next_monday);
    if(time() > $week4after){
        //判断用户当前晋升状态为3的时候才可以继续晋升
        //2为等待晋升
        if($res==3){
            //判断是否连续4周完成任务
            $resault2 = WeekTask::model()->checkConByMonth($this_week['c_time'], $id);
        }elseif($res==2){
            $pro_time = $manage->pro_time;
            //获取指定日期下个月的第一天和最后一天
            $next_mounth = WeekTask::model()->GetNextMonth($pro_time);
            $next_mounth = strtotime($next_mounth);
            //echo $next_mounth;
            $this_time = time();
            if ($this_time >= $next_mounth) {
                Manage::model()->updateByPk($id, array('promotion' => 0, 'role' => Role::SUPERVISOR));
                throw new CHttpException (success, '恭喜已成功晋升为客服主管，现在起任务收益提高.');
            }
        }else{
            $resault = 0;
        }
    }else{
        $resault = 0;
    }
}


//如果是客服主管以上级别，则显示等待审核查看的任务
if ($role <= Role::PRACTICE_VISOR ) {

    $task_status = AskTask::model()->checkStatusByTask();
    $sql = 'SELECT count(id) as id FROM app_member_bill where surplus<0';
    $memberbill = Yii::app()->db->createCommand($sql)->queryAll();

    $sqlshop = 'SELECT count(id) as id FROM app_shop_goods_order where status=0';
    $shopinfo = Yii::app()->db->createCommand($sqlshop)->queryAll();
    ?>

    <input type='hidden' value='1' id='is_check'>
    <input type='hidden' value='<?php echo $task_status ['allow']; ?>' id='is_allow'>
    <input type='hidden' value='<?php echo $task_status ['score']; ?>' id='can_score'>
    <input type='hidden' value='<?php echo $task_status ['del']; ?>' id='can_del'>
    <input type='hidden' value='<?php echo $memberbill[0]['id']; ?>' id='yu_ee'>
    <input type='hidden' value='<?php echo $shopinfo[0]['id']; ?>' id='is_shop'>

<?php } elseif ($role > Role::SUPERVISOR) {

    $task_count = Task::model()->getAllKindTaskCountById($id);
    $not_allow_count = AskTask::model()->getNotAllowTaskByUid($id);?>

    <input type='hidden' value='1' id='wait_allow'>
    <input type='hidden' value='<?php echo $not_allow_count ?>' id='n_a_c'>
    <input type='hidden' value='<?php echo $task_count['w_allow'] ?>' id='w_a_t'>
    <input type='hidden' value='<?php echo $task_count['remind'] ?>' id='r_t_c'>
    <input type='hidden' value='<?php echo $task_count['visite'] ?>' id='v_t'>

<?php } ?>
<input type='hidden' value='<?php echo $role ?>' id='role'>
<input type = 'hidden' value = '<?php echo $resault?>' id = 'promotion'>
<script type="text/javascript">
    $(function () {
        var role = $('#role').val();       //当前登录用户等级
        var yu_ee = $('#yu_ee').val();
        var is_shop = $('#is_shop').val();

        if (is_shop>0) {
            $("#yu_e").html('有'+is_shop+'个积分兑换用户');
            $('#yu_e').attr('href','/dhadmin/shop/goodsOrder');
        }
        if (yu_ee>0) {
            $("#yu_e").html('有'+yu_ee+'个用户余额为负数');
            $('#yu_e').attr('href','/dhadmin/pay/memberbill');
        }
        //客服主管及以上
        if (($("#is_check").length > base_parm.DEFAULT) && ($("#is_check").val() == base_parm.DEFAULT_ONE) && (role <= manage_role.SUPERVISOR)) {
            var can_score = $("#can_score").length > base_parm.DEFAULT ? $("#can_score").val() : base_parm.DEFAULT;
            var can_del = $("#can_del").length > base_parm.DEFAULT ? $("#can_del").val() : base_parm.DEFAULT;

            if ((can_score != base_parm.DEFAULT) || (can_del != base_parm.DEFAULT)) {
                $("#download_now").html('有可操作任务');
                var tr = '';

                tr += '<tr><td class="label">待审核任务:</td><td class ="cont">' + can_score + '条</td><td><a href="#" onclick="checkdone()">查看</a></td></tr>';
                /***临时修改2017-09-21见习主管的任务给了manage3和14**/
                if(<?php echo Yii::app()->user->manage_id;?>==3 || <?php echo Yii::app()->user->manage_id?>==14){
                    tr += '<tr><td class="label">待批准任务:</td><td class ="cont">' + is_allow + '条</td><td><a href="#" onclick="checkshow()">查看</a></td></tr>';
                }
                /***临时修改2017-09-21end***/
                tr += '<tr><td class="label">可清除任务:</td><td class ="cont">' + can_del + '条</td><td><a href="#" onclick="checkfinish()">查看</a></td></tr>';
                $("#msg_list").html(tr);
              //  $("#check_remind").html('任务列表');
            } else if ((can_score == base_parm.DEFAULT) && (can_del == base_parm.DEFAULT)) {

                /***临时操作2017-09-21start***/
                var is_allow = $("#is_allow").length > base_parm.DEFAULT ? $("#is_allow").val() : base_parm.DEFAULT;
                if ((is_allow != base_parm.DEFAULT)) {
                    $("#download_now").html('有可操作任务');
                    var tr = '';

                    tr += '<tr><td class="label">待批准任务:</td><td class ="cont">' + is_allow + '条</td><td><a href="#" onclick="checkshow()">查看</a></td></tr>';
                    $("#msg_list").html(tr);return false;
                    //  $("#check_remind").html('任务列表');
                }
                /****end 2017-09-21****/
                
                $("#download_now").html('无待操作的任务');
                var msg = $("#download_now").html();
                $("#download_now").click(function () {
                    return false;
                })
            }
        }
        //见习主管
        else if (($("#is_check").length > base_parm.DEFAULT) && ($("#is_check").val() == base_parm.DEFAULT_ONE) && (role == manage_role.PRACTICE_VISOR)) {
            var is_allow = $("#is_allow").length > base_parm.DEFAULT ? $("#is_allow").val() : base_parm.DEFAULT;

            if ((is_allow != base_parm.DEFAULT)) {
                $("#download_now").html('有可操作任务');
                var tr = '';

                tr += '<tr><td class="label">待批准任务:</td><td class ="cont">' + is_allow + '条</td><td><a href="#" onclick="checkshow()">查看</a></td></tr>';
                $("#msg_list").html(tr);
                //  $("#check_remind").html('任务列表');
            } else if ((is_allow == base_parm.DEFAULT)) {
                $("#download_now").html('无待操作的任务');
                var msg = $("#download_now").html();
                $("#download_now").click(function () {
                    return false;
                })
            }
        }
        else if (($("#wait_allow").length > base_parm.DEFAULT)) {
            var w_a_t = $("#w_a_t").val();
            var r_t_c = $("#r_t_c").val();
            var visite = $("#v_t").val();
            var n_a_c = $("#n_a_c").val();

            var promotion = $('#promotion').val();  //是否可晋级 1=》是，0=》否

            if ((w_a_t != base_parm.DEFAULT) || (r_t_c != base_parm.DEFAULT) ||  (n_a_c != base_parm.DEFAULT) ||  (promotion == 1)) {
                $("#download_now").html('有新的消息');
                var tr = '';
                //ADVANCED_STAFF=>5,高级。SUPPORT_STAFF=》6普通客服，PRACTICE_STAFF=》7，见习客服
                if ((role == manage_role.SUPPORT_STAFF) && (promotion == 1)) {
                    tr += '<tr><td class="label">可晋升高级客服:</td><td class ="cont"></td><td><a href="#" onclick="promotion(1)">晋升</a></td></tr>';
                }
                if ((role == manage_role.PRACTICE_VISOR) && (promotion == 1)) {
                    tr += '<tr><td class="label">可晋升客服主管:</td><td class ="cont"></td><td><a href="#" onclick="promotion(2)">晋升</a></td></tr>';
                }
                if ((role == manage_role.ADVANCED_STAFF) && (promotion == 1)) {
                    tr += '<tr><td class="label">可晋升见习客服主管:</td><td class ="cont"></td><td><a href="#" onclick="promotion(3)">晋升</a></td></tr>';
                }

                tr += '<tr><td class="label">被拒绝任务:</td><td class ="cont">' + n_a_c + '条</td><td><a href="#" onclick="checkAllow(0)">查看</a></td></tr>';
                tr += '<tr><td class="label">待批准任务:</td><td class ="cont">' + w_a_t + '条</td><td><a href="#" onclick="checkAllow(2)">查看</a></td></tr>';
                tr += '<tr><td class="label">到提醒时间任务:</td><td class ="cont">' + r_t_c + '条</td><td><a href="#" onclick="remindlist()">查看</a></td></tr>';
                /*tr += '<tr><td class="label">回访任务:</td><td class ="cont">' + visite + '条</td><td><a href="#" onclick="tovisit()">查看</a></td></tr>';*/
                $("#msg_list").html(tr);
            } else {
                $("#download_now").html('没有新的消息');
                $("#download_now").click(function () {
                    return false;
                })
            }

        }

    })
    var T_CHECK_URL 		=  '<?php echo $this->createUrl ( 'task/checklist' )?>';
    var MP_REFUSE 			= '<?php echo $this->createUrl('memberPool/refuseTask' )?>';

    function checkAllow(allow) {
        var allow = allow;
        var url = MP_REFUSE;
        location.href = url + '/allow/' + allow;
    }
    function checkdone(){
        var url = T_CHECK_URL;
        var is_done = 1;
        $.post(url,{done:1},function(data){
            if(data=='got'){
                location.href = url+"/is_done/"+is_done;
            }
        });
    }
    function checkfinish(){
        var url = T_CHECK_URL;
        var is_finish = 1;
        $.post(url,{finish:1},function(data){
            if(data=='got'){
                location.href = url+"/is_finish/"+is_finish;
            }
        });
    }
    function checkshow(){
        var url = T_CHECK_URL;
        var is_show = 1;
        $.post(url,{show:1},function(data){
            if(data=='got'){
                location.href = url+"/isshow/"+is_show;
            }
        });
    }
    function remindlist(){
        var remind = 1;
        var url = MP_REFUSE;
        location.href = url+'/remind/'+remind;
    }
</script>