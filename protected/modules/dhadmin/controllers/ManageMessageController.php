<?php

/**
 * Created by AWangBa.com
 * User: zyx849817151@163.com
 * Date: 14-3-13 下午3:01
 * @name 员工个人信息查看与操作
 */
class ManageMessageController extends DhadminController
{
    /**
     * @name 首页列表项 -高级权限
     */
    public function actionIndex()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/managemessage.record.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerCssFile('asyncbox.css');
        $manage_list = Manage::model()->getManageList();
        $id = Yii::app()->user->manage_id;
        $STATUS = isset($_GET['status']) ? $_GET['status'] : ManageDeduct::LEAVE_STATUS_WAIT;
        $leave_msg = ManageDeduct::model()->getLeaveMsg($STATUS);

       
        $pram = array(
            'manage_list' => $manage_list,
            'leave_msg' => $leave_msg
            
        );
        $this->render('index', $pram);
    }

    /**
     * @throws CHttpException
     * @name 员工信息录入 -高级权限
     */
    public function actionRecord()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //判断提交方式是否为POST
            $mid = $_POST['manage_id'];
            $sex = $_POST['manage_sex'];
            $marry = $_POST['manage_ismarry'];
            $phone = $_POST['phone'];
            $idcard = $_POST['idcard'];
            $birthday = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : DefaultParm::DEFAULT_EMPTY;
            $remark = isset($_POST['remark']) ? $_POST['remark'] : DefaultParm::DEFAULT_EMPTY;
            $manage = Manage::model()->findByPk($mid);
            $file = isset($_FILES['picture']) ? $_FILES['picture'] : DefaultParm::DEFAULT_EMPTY;
            if ($file != DefaultParm::DEFAULT_EMPTY) {
                $res = Manage::model()->picUpload($file['tmp_name'], $file['size'], $file['type'], $file['name']);
                if ($res['success'] == AjaxBack::DATA_SUCCESS) {
                    if (!empty($manage->picture)) {
                        unlink(Yii::app()->basePath . '/..' . $manage->picture);
                    }
                    $manage->picture = $res['picname'];
                }
            }
            $manage->sex = $sex;
            $manage->ismarry = $marry;
            $manage->phone = $phone;
            $manage->birthday = $birthday;
            $manage->remark = $remark;
            $manage->idcard = $idcard;
            $count = $manage->update();
            if ($count == DefaultParm::DEFAULT_ONE) {
                $this->redirect(array('index'));
            } else {
                throw new CHttpException (404, '提交失败.');
            }
        }
    }

    /**
     * @throws CHttpException
     * @name 员工信息查看 -高级权限
     */
    public function actionMessgae()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = ( int )Yii::app()->request->getParam('mid');
        }
        $manage = Manage::model()->findByPk($mid);
        $arr = array();
        $arr['sex'] = $manage->sex;
        $arr['ismarry'] = $manage->ismarry;
        $arr['phone'] = isset($manage->phone) ? $manage->phone : DefaultParm::DEFAULT_EMPTY;
        $arr['picture'] = isset($manage->picture) ? $manage->picture : DefaultParm::DEFAULT_EMPTY;
        $arr['birthday'] = isset($manage->birthday) ? date('Y-m-d', $manage->birthday) : DefaultParm::DEFAULT_EMPTY;
        $arr['remark'] = isset($manage->remark) ? $manage->remark : DefaultParm::DEFAULT_EMPTY;
        $arr['idcard'] = isset($manage->idcard) ? $manage->idcard : DefaultParm::DEFAULT_EMPTY;
        echo CJSON::encode($arr);
    }

    /**
     * @throws CHttpException
     * @name 个人信息查看 -普通权限
     */
    public function actionMyMessage()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/managemessage.mymessage.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerCssFile('asyncbox.css');
        $id = Yii::app()->user->manage_id;
        $manage = Manage::model()->findByPk($id);

        //$manage =  isset($manage) ? $manage : '';
        $msg = ManageDeduct::model()->getNearestLeaveMsgByUid($id);

      
        $pram = array(
            'manage' => $manage,
            'msg' => $msg
          
        );
        $this->render('mymessage', $pram);
    }

    /**
     * @throws CHttpException
     * @name 扣款列表项 -高级权限
     */
    public function actionDeduct()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/managemessage.deduct.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerCssFile('asyncbox.css');

        $show_id = isset($_GET['id']) ? $_GET['id'] : '';
        Yii::app()->session['show_id'] = $show_id;

        $id = Yii::app()->user->manage_id;
        $role = Manage::model()->getRoleByUid($id);
        if ($role > Role::PRACTICE_VISOR) {
            throw new CHttpException (404, '没有权限.');
        }
        $mounth = DefaultParm::DEFAULT_EMPTY;
        if (isset($_GET['mounth'])) {
            $mounth_b_s = strtotime($_GET['mounth']);
            $mounth = date('Y-m', $mounth_b_s);
        } else {
            $mounth = date("Y-m", time());
        }
        $manage_leave_msg = ManageDeduct::model()->getManageLeaveMsgByMounth($mounth, $show_id);

        $manage_msg = Manage::model()->findByPk($show_id);
        $pram = array('manage_msg' => $manage_msg,
            'manage_leave_msg' => $manage_leave_msg,
            'mounth' => $mounth
           
        );
        $this->render('deduct', $pram);
    }

    /**
     * @throws CHttpException
     * @name 工资查看与发布 -高级权限
     */
    public function actionPayBack()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/managemessage.payback.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerCssFile('asyncbox.css');
        $show_id = isset($_GET['id']) ? $_GET['id'] : '';
        Yii::app()->session['show_id'] = $show_id;
        $id = Yii::app()->user->manage_id;
        $role = Manage::model()->getRoleByUid($id);
        if ($role > Role::PRACTICE_VISOR) {
            throw new CHttpException (404, '没有权限.');
        }
        $mounth = DefaultParm::DEFAULT_EMPTY;
        if (isset($_GET['mounth'])) {
            $mounth_b_s = strtotime($_GET['mounth']);
            $mounth = date('Y-m', $mounth_b_s);
        } else {
            $mounth = date("Y-m", time());
        }
        $firstday = strtotime($mounth);
        $first=date("Y-m-01",$firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$first +1 month -1 day")));

        //查看指定年月的工资单是否存在
        $count = ManageDeduct::model()->getManageWageByMounth($mounth, $show_id);
        //如果存在
        if ($count == DefaultParm::DEFAULT_ONE) {
            //查看详细信息
            $wage_msg = ManageDeduct::model()->getManageWgaeMsgByMounth($mounth, $show_id);
       //不存在
        } else if ($count == DefaultParm::DEFAULT_ZERO) {
            $wage_msg = DefaultParm::DEFAULT_ZERO;
        }

       // $salary = Salary::model()->findByAttributes(array('uid' => $show_id, 't_prottime' => $mounth));
        //普通任务收益
        $sql_task = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \''.$show_id.'\'
                AND (`endtime` between \''.$firstday.'\' and \''.$lastday.'\')';
        $tasknew = Yii::app()->db->createCommand($sql_task)->queryAll();

        //周任务收益
        $sql_week = 'SELECT SUM(payback) AS pay FROM app_task_week_earnings WHERE uid = \''.$show_id.'\'
                AND (`endtime` between \''.$firstday.'\' and \''.$lastday.'\')';
        $taskweek = Yii::app()->db->createCommand($sql_week)->queryAll();

        $manage_msg = Manage::model()->findByPk($show_id);
        $pram = array('manage_msg' => $manage_msg,
            'tasknew' => $tasknew[0]['pay'],
            'taskweek' => $taskweek[0]['pay'],
            'mounth' => $mounth,
            'wage_msg' => $wage_msg,
            'count' => $count
        );

        $this->render('payback', $pram);
    }

    /**
     * @throws CHttpException
     * @name 工资条发布 -高级权限
     */
    public function actionWageCount()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $publish            = Yii::app()->user->manage_id;
            $date               = date('Y-m', time());
            $date_now           = date('Y-m-d H:i:s', time());
            $check_mounth       = Yii::app()->request->getParam('check_mounth');
            $uid                = ( int )Yii::app()->request->getParam('uid');
            $base_wage          = ( int )Yii::app()->request->getParam('base_wage');
            $task_payback_new   = Yii::app()->request->getParam('task_payback_new');
            $task_payback_drop  = Yii::app()->request->getParam('task_payback_drop');
            $week_payback       = Yii::app()->request->getParam('week_payback');
            $visit_payback      = Yii::app()->request->getParam('visit_payback');
            $bonus              = Yii::app()->request->getParam('bonus');
            $deduct             = Yii::app()->request->getParam('deduct');
            $total_pay          = Yii::app()->request->getParam('total_pay');
            $scale              = Yii::app()->request->getParam('scale');
            $com                = Yii::app()->request->getParam('com');

           // 只能发布上个月的工资，不能发布当月的
            if ($date <= $check_mounth) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NO_PRO));
                exit;
            }
            //如果已存在指定月份工资条，不能发布
            $isset = ManageWage::model()->findByAttributes(array('date' => $check_mounth, 'uid' => $uid));
            if (isset($isset->id)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS));
                exit;
            }
            //如果没有输入工资比例。
            if(($scale==0)||($scale=='')){
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_MSG_EMPTY));
                exit;
            }
            $wage = new ManageWage();
            $wage->uid  = $uid;
            $wage->base_wage    = $base_wage;
            $wage->scale    = $scale;
            $wage->task_payback     = isset($task_payback_new)?$task_payback_new:0;
            $wage->tdr_payback      = isset($task_payback_drop)?$task_payback_drop:0;
            $wage->week_payback     = isset($week_payback)?$week_payback:0;
            $wage->visit_payback    = isset($visit_payback)?$visit_payback:0;
            $wage->bonus            = isset($bonus)?$bonus:0;
            $wage->deduct = $deduct;
            $wage->date = $check_mounth;
            $wage->publish = $publish;
            $wage->publish_time = $date_now;
            $wage->should_pay = $base_wage + $task_payback_new+ $task_payback_drop+ $week_payback +$visit_payback+ $bonus;
            $wage->total_pay = $total_pay;
            $wage->com = $com;
            $num = $wage->insert();

            if ($num == DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }
        }
    }

    /**
     * @throws CHttpException
     * @name 员工请假申请 -普通权限
     */
    public function actionManageLeave()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->user->manage_id;
            $startime = array();
            $endtime = array();
            $leave_type = ( int )Yii::app()->request->getParam('leave_type');
            $s_y_m_d = Yii::app()->request->getParam('s_y_m_d');
            $s_h = Yii::app()->request->getParam('s_h');
            $s_m = Yii::app()->request->getParam('s_m');
            $e_y_m_d = Yii::app()->request->getParam('endtime');
            $e_h = Yii::app()->request->getParam('e_h');
            $e_m = Yii::app()->request->getParam('e_m');
            $reason = Yii::app()->request->getParam('reason');

            $startime['str'] = $s_y_m_d . ' ' . $s_h . ':' . $s_m;
            $startime['time'] = strtotime($startime['str']);
            $startime['date'] = date('Y-m-d H:i', $startime['time']);

            $endtime['str'] = $e_y_m_d . ' ' . $e_h . ':' . $e_m;
            $endtime['time'] = strtotime($endtime['str']);
            $endtime['date'] = date('Y-m-d H:i', $endtime['time']);

            $managededuct = new ManageDeduct();
            $managededuct->uid = $id;
            $managededuct->leave = $leave_type;
            $managededuct->start_time = $startime['time'];
            $managededuct->end_time = $endtime['time'];
            $managededuct->reason = $reason;
            $managededuct->ischeck = ManageDeduct::LEAVE_STATUS_WAIT;
            $num = $managededuct->insert();
            if ($num == DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }
        }

    }

    /**
     * @throws CHttpException
     * @name 批准 /拒绝请假 -高级权限
     */
    public function actionCheckManageLeave()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $did = ( int )Yii::app()->request->getParam('did');
            $check = ( int )Yii::app()->request->getParam('check');
            $message = Yii::app()->request->getParam('message');
            $checkid = Yii::app()->user->manage_id;
            $role = Manage::model()->getRoleByUid($checkid);
            if ($role > Role::SUPERVISOR) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOPOWER));
                exit;
            }
            $managededuct = ManageDeduct::model()->findByPk($did);
            $managededuct->ischeck = $check;
            $managededuct->check_time = time();
            $managededuct->checkid = $checkid;
            $managededuct->message = $message;
            $num = $managededuct->update();

            if ($num == DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit;
            }
        }
    }

    /**
     * @throws CHttpException
     * @name 添加扣款项 -高级权限
     */
    public function actionAddDeductByAdmin()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $start = Yii::app()->request->getParam('start');
            $end = Yii::app()->request->getParam('end');
            $check_message = Yii::app()->request->getParam('check_message');
            $deduct_list = ( int )Yii::app()->request->getParam('deduct_list');
            $s_time = ( int )Yii::app()->request->getParam('s_time');
            $e_time = ( int )Yii::app()->request->getParam('e_time');
            $uid = ( int )Yii::app()->request->getParam('id');
            $checkid = Yii::app()->user->manage_id;

            $start = strtotime($start . ' ' . $s_time . ':01');
            $end = strtotime($end . ' ' . $e_time . ':01');

            $managededuct = new ManageDeduct();
            $managededuct->uid = $uid;
            $managededuct->leave = $deduct_list;
            $managededuct->start_time = $start;
            $managededuct->end_time = $end;
            $managededuct->check_time = time();
            $managededuct->ischeck = ManageDeduct::LEAVE_STATUS_TRUE;
            $managededuct->checkid = $checkid;
            $managededuct->message = $check_message;
            $num = $managededuct->insert();

            if ($num == DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit;
            }

        }

    }

    public function actionWagemessage()
    {
        Script::registerScriptFile(Script::HIGHSTOCK);
        Script::registerScriptFile('manage/memberpool.js');

        $sql = "SELECT keyword FROM app_resource ";
        $_type = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty ($_type)) {
            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }

        $data = DefaultParm::DEFAULT_EMPTY;
        $sql0 = DefaultParm::DEFAULT_EMPTY;
        $mid = 8812;
        $first_time = '2010-01-01';
        $end_time = '2014-04-30';

        $arr = array();
        foreach ($_type as $key => $keyword) {
            $TABLE = 'app_income_' . $keyword ['keyword'];
            if (!empty ($sql0)) {
                $sql0 .= ' union ALL ';
            }

            //任务发布申请前，30天内的收益的总合
            $sql0 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime between '$first_time' and '$end_time'";
        }
        //任务发布申请前，30天内的收益的总合
        $sql_0 = "SELECT  data FROM ($sql0) AS a";
        $data_0 = Yii::app()->db->createcommand($sql_0)->queryAll();

        // $data_old = ($data_0 [0] ['data'] != null) ? $data_0 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        $this->render('wagemessage', array('json' => json_encode($data_0)));
    }

    /**
     * 查看工资单-普通权限
     * @name 查看工资单 -普通权限
     */
    public function actionMyWageList()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/managemessage.mymessage.js');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id;

        $mounth = DefaultParm::DEFAULT_EMPTY;
        if (isset($_GET['mounth'])) {
            $mounth_b_s = strtotime($_GET['mounth']);
            $mounth = date('Y-m', $mounth_b_s);
        } else {
           // $mounth = date("Y-m-01", time());
           // $mounth = date("Y-m", strtotime("$mounth -1 day"));
            $mounth = date("Y-m", time());
        }
        $firstday = strtotime($mounth);
        $first=date("Y-m-01",$firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$first +1 month -1 day")));
        //查找工资单是否存在
        $sql = 'SELECT * FROM app_manage_wage WHERE uid = \'' . $id . '\' AND  date = \'' . $mounth . '\' ';
        $my_wage_msg = Yii::app()->db->createCommand($sql)->queryAll();

        //用户信息
        $manage_msg = Manage::model()->findByPk($id);

        //存在则赋值
        if(!empty($my_wage_msg)){
            $my_wage_msg = $my_wage_msg;
        }else{
            $my_wage_msg = '';
        }
            //不存在
            $sql_task_new = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \''.$id.'\'
                AND (`endtime` between \''.$firstday.'\' and \''.$lastday.'\')
                AND type = \''.Task::TYPE_NEW.'\' ';
            $tasknew = Yii::app()->db->createCommand($sql_task_new)->queryAll();

            //降量任务收益
            $sql_task_drop = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \''.$id.'\'
                AND (`endtime` between \''.$firstday.'\' and \''.$lastday.'\')
                AND type = \''.Task::TYPE_DROP.'\' ';
            $taskdrop = Yii::app()->db->createCommand($sql_task_drop)->queryAll();

            //传递年月内的回访任务收益
            $sql_visit = "SELECT sum(te.payback)AS pay FROM app_task_earnings AS te
                    JOIN app_ask_task AS at
                    ON te.atid = at.id
                    WHERE te.type = ".Task::TYPE_VISIT."
                    AND te.uid = $id AND te.endtime>=  " . $firstday . "
                    AND te.endtime<=  " . $lastday . "  ";
            $taskvisit = Yii::app()->db->createCommand($sql_visit)->queryAll();

            //周任务收益
            $sql_week = 'SELECT SUM(payback) AS pay FROM app_task_week_earnings WHERE uid = \''.$id.'\'
                AND (`endtime` between \''.$firstday.'\' and \''.$lastday.'\')';
            $taskweek = Yii::app()->db->createCommand($sql_week)->queryAll();


        if(($manage_msg->role ==Role::PRACTICE_VISOR ) || ($manage_msg->role == Role::SUPERVISOR )){
            $com = ManageDeduct::model()->getCommission($firstday,$lastday);
        }else{
            $com = 0;
        }

            $wage_msg['visit']  = isset($taskvisit[0]['pay'])?$taskvisit[0]['pay']:0;
            $wage_msg['new']    = isset($tasknew[0]['pay'])?$tasknew[0]['pay']:0;
            $wage_msg['drop']   = isset($taskdrop[0]['pay'])?$taskdrop[0]['pay']:0;
            $wage_msg['week']   = isset($taskweek[0]['pay'])?$taskweek[0]['pay']:0;
            $wage_msg['com']    = $com;

        $parm = array(
            'manage_msg' => $manage_msg,
            'my_wage_msg' => $my_wage_msg,
            'wage_msg'=>$wage_msg,
            'mounth' => $mounth,
            'com'=>$com
        );

        $this->render('mywage', $parm);
    }

    /**
     * @name 工资查看导航栏 -普通权限
     */
    public function actionWageListPower()
        {
            Script::registerScriptFile(Script::JQUERY_TOOLS);
            Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
            Script::registerScriptFile('manage/memberpool.public/memberpool.js');
            Script::registerScriptFile('manage/memberpool.controller/managemessage.power.js');
            Script::registerCssFile('asyncbox.css');

            if (Yii::app()->request->isAjaxRequest) {
                $year   = $_POST['year'];
                $mounth   = $_POST['mounth'];
            }
            $year   = isset($year)?$year:date('Y',time());
            $mounth   = isset($mounth)?$mounth:date('m',time());
            $date = WeekTask::model()->mFristAndLast($year,$mounth);
            $time = $year.'-'.$mounth;
            $arr = array();
            $id = Yii::app()->user->manage_id;
            $role = Manage::model()->getRoleByUid($id);
            $sql = 'SELECT * FROM app_manage_wage WHERE uid = \''.$id.'\' AND date = \''.$time.'\' ';
            $wage_msg = Yii::app()->db->createCommand($sql)->queryAll();

            if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){
                $com = ManageDeduct::model()->getCommission($date['firstday'],$date['lastday']);
            }else{
                $com = 0;
            }

            if(!empty($wage_msg)){
                $arr['role']    =   $role;
                $arr['total']   =   $wage_msg[0]['total_pay'];
                $arr['week']    =   $wage_msg[0]['week_payback'];
                $arr['new']     =   $wage_msg[0]['task_payback'];
                $arr['drop']    =   $wage_msg[0]['tdr_payback'];
                $arr['visit']   =   $wage_msg[0]['visit_payback'];
                $arr['basewage'] =  $wage_msg[0]['base_wage'];
                $arr['bonus']   =   $wage_msg[0]['bonus'];
                $arr['deduct']  =   $wage_msg[0]['deduct'];
                $arr['total']   =   $wage_msg[0]['total_pay'];
            }else{
                $arr = ManageDeduct::model()->getPayByDate($date['firstday'],$date['lastday'],$id);
                $com=$arr['com'];
            }

            $arr['total'] = $arr['total']+$com;

            $parm = array(
                'arr'=>$arr,
                'role'=>$role,
                'com'=>$com
            );
            $this->render('wagemessage',$parm);
        }

    /**
     * @name 周任务导航 -普通权限
     */
    public function actionShowWeekTaskMsgListByDate(){
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/managemessage.power.js');
        Script::registerCssFile('asyncbox.css');

        if(isset($_GET['createtime'])){
            $gettime = $_GET['createtime'];
            $checktime = 'createtime';
        }elseif(isset($_GET['endtime'])){
            $gettime = $_GET['endtime'];
            $checktime = 'endtime';
        }

        $id = Yii::app()->user->manage_id;
        $sql = "SELECT wt.*,mi.username FROM app_week_task AS wt
                JOIN app_member AS mi
                ON mi.id = wt.m_id
                WHERE  ".$checktime." = $gettime  AND f_id = $id ";
        $Weeklist = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($Weeklist);//标记数量

        if($num ==0||($num =='')){
            throw new CHttpException (404, '没有查看的周任务.');
        }
        foreach($Weeklist AS $key=>$item){
            $Weeklist[$key]['target_payback'] = !empty($item['target_payback'])&&($item['target_payback']!=0)?$item['target_payback']:0;
            $Weeklist[$key]['contrast_payback'] = !empty($item['contrast_payback'])&&($item['contrast_payback']!=0)?$item['contrast_payback']:0;
            $Weeklist[$key]['target_payback'] = !empty($item['payback'])&&($item['payback']!=0)?$item['payback']:0;
            $Weeklist[$key]['growth'] = !empty($item['growth'])&&($item['growth']!=0)?$item['growth']:'0%';
            $Weeklist[$key]['payback'] = !empty($item['payback'])&&($item['payback']!=0)?$item['payback']:0;

        }
        $con_count = WeekTask::model()->getConCountByParm($id, $checktime,$gettime);
        $WEEKTASKTIME['start'] = $Weeklist[0]['createtime'];
        $WEEKTASKTIME['end'] = $Weeklist[0]['endtime'];
        $WEEKTASKTIME['con_count'] = $con_count;
        $WEEKTASKTIME['num'] = $num;
        //有效任务数量
       // print_R($Weeklist);
        $parm = array(
            'Weeklist'=>$Weeklist,
            'WEEKTASKTIME'=>$WEEKTASKTIME

        );
        $this->render('weektaskmsg',$parm);

    }


    /**
     * @name 周任务收益列表 -普通权限
     */
    public function actionShowWeekTaskEarningsByDate(){
        if (Yii::app()->request->isAjaxRequest) {
            $year = Yii::app()->request->getParam('year');
            $mounth = Yii::app()->request->getParam('mounth');

            $id = Yii::app()->user->manage_id;

         $date = WeekTask::model()->mFristAndLast($year,$mounth);
         //获取周任务
         $sql = 'SELECT createtime,endtime,percent,concount,askcount,payback,role FROM app_task_week_earnings WHERE endtime >= \''.$date['firstday'].'\'
                AND endtime <= \''.$date['lastday'].'\' AND uid = \''.$id.'\' ORDER BY endtime ASC';
         $resault = Yii::app()->db->createCommand($sql)->queryAll();

            if(empty($resault)){
                echo CJSON::encode(0);exit;
            }else{

                foreach($resault AS $key=>$item){
                    $resault[$key]['ctime'] = date('m-d',$item['createtime']);
                    $resault[$key]['etime'] = date('m-d',$item['endtime']);
                }

                echo CJSON::encode($resault);exit;
            }

        }
     }

    /**
     * @name 非周任务导航变更信息 -普通权限
     */
    public function actionShowWageByDate(){
        if (Yii::app()->request->isAjaxRequest) {
            $mounth = Yii::app()->request->getParam('mounth');
            $year = Yii::app()->request->getParam('year');
            $year   = isset($year)?$year:date('Y',time());
            $mounth   = isset($mounth)?$mounth:date('m',time());
            $date = WeekTask::model()->mFristAndLast($year,$mounth);

            $arr = array();
            $id = Yii::app()->user->manage_id;
            $time = strtotime($year.'-'.$mounth);
            $time = date("Y-m",$time);
            $role = Manage::model()->getRoleByUid($id);

            $sql = 'SELECT * FROM app_manage_wage WHERE uid = \''.$id.'\' AND date = \''.$time.'\' ';
            $wage_msg = Yii::app()->db->createCommand($sql)->queryAll();

            if(!empty($wage_msg)){
                $arr['role']    =   $role;
                $arr['total']   =   $wage_msg[0]['total_pay'];
                $arr['week']    =   $wage_msg[0]['week_payback'];
                $arr['new']     =   $wage_msg[0]['task_payback'];
                $arr['drop']    =   $wage_msg[0]['tdr_payback'];
                $arr['visit']   =   $wage_msg[0]['visit_payback'];
                $arr['basewage'] =  $wage_msg[0]['base_wage'];
                $arr['bonus']   =   $wage_msg[0]['bonus'];
                $arr['deduct']  =   $wage_msg[0]['deduct'];
            }else{
                $arr = ManageDeduct::model()->getPayByDate($date['firstday'],$date['lastday'],$id);
            }
            echo CJSON::encode($arr);exit;

        }
    }

    /**
     * @name 获取任务数据 -普通权限
     */
    public function actionGetTaskNewMsgByDate(){
        if (Yii::app()->request->isAjaxRequest) {
            $type = Yii::app()->request->getParam('type');
            $mounth = Yii::app()->request->getParam('mounth');
            $year = Yii::app()->request->getParam('year');
            $year   = isset($year)?$year:date('Y',time());
            $mounth   = isset($mounth)?$mounth:date('m',time());
            $date = WeekTask::model()->mFristAndLast($year,$mounth);

            $arr = array();
            $id = Yii::app()->user->manage_id;

            $sql = 'SELECT te.*,mi.username FROM app_task_earnings AS te
                    JOIN app_member AS mi
                    ON te.mid = mi.id
                    WHERE te.uid = '.$id.
                    ' AND te.endtime >='. $date['firstday'] .
                    ' AND te.endtime <=' . $date['lastday'].
                    ' AND te.type = \''.$type.'\' ';
            $resault = Yii::app()->db->createCommand($sql)->queryAll();

            if(empty($resault)){
                echo CJSON::encode(0);exit;
            }else{

                foreach($resault AS $key=>$item){
                    $resault[$key]['ctime'] = date('Y-m-d l',$item['createtime']);
                    $resault[$key]['etime'] = date('Y-m-d l',$item['endtime']);
                    $resault[$key]['power'] = Role::model()->getRoleNameByRole($item['role']);
                }

                echo CJSON::encode($resault);exit;
            }


        }
    }
}