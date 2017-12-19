<?php

/**
 * @name 用户池memberpool
 */
class MemberPoolController extends DhadminController
{


    public function actions()
    {
        return array('captcha' => array('class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF, 'maxLength' => 4, 'minLength' => 4));
    }

    private function loadModel($id)
    {
        $model = Manage::model()->findByPk($id);
        if ($model === null) {
            if (Yii::app()->request->isAjaxRequest) {
                exit ();
            } else {
                throw new CHttpException (404, 'The requested page does not exist.');
            }
        }
        return $model;
    }

    /**
     * 任务驳回
     * @name 任务驳回 -高级权限
     */
    public function actionBackTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $tw_id  = ( int )Yii::app()->request->getParam('tw_id');
            $t_id   = ( int )Yii::app()->request->getParam('t_id');
            $msg    =        Yii::app()->request->getParam('msg');

            $Rback = TaskWhen::model()->BackTask($tw_id, $t_id, $msg);

            if ($Rback == DefaultParm::DEFAULT_TWO) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //打回修改成功
            } else if ($Rback == DefaultParm::DEFAULT_THREE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_ADONE)); //任务已完成或删除
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //打回失败
            }

        }
    }

    /**
     * @name 降量任务表 -普通权限
     */
    public function actionDropTask()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.nopro.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');

        $id = isset ($_GET ['id']) ? $_GET ['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id

        $criteria = new CDbCriteria ();

        $sql = 'SELECT mi.id AS mid,mi.holder,at.*,
    			tw.porttime,tw.isfail,tw.id AS tw_id,
    			tw.pay_back,tw.motifytime,tw.score,tw.scoreuid,tw.createtime,tw.status AS tw_status
    			FROM app_ask_task AS at 
				JOIN app_task_when AS tw
				JOIN app_member AS mi
				ON at.tw_id = tw.id AND at.m_id = mi.id 
				WHERE at.f_id = \'' . $id . '\' AND at.type =  \'' . AskTask::TYPE_DROP . '\'
				      AND at.t_status != \''.AskTask::STATUS_DEL.'\' ';

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);
        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $pram = array(
            'pages' => $pages,
            'dt_list' => $list,
            'num' => $num

        );
        $this->render('droptask', $pram);
    }

    /**
     * @name 获得备选列表
     */
    public function actionMemberpoolBak()
    {
        $dataProvider = MemberpoolBak::model()->getListAll("");
        $this->render('memberpool_bak', array(
            'dataProvider' => $dataProvider
        ));
    }


    /**
     * @name 任务收益表 -高级权限
     */
    public function actionPayBack()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/memberpool.payback.js');
        Script::registerScriptFile('manage/memberpool.controller/managemessage.payback.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');
        //get到的客服id
        $id = isset ($_GET ['id']) ? $_GET ['id'] : Yii::app()->user->manage_id;

        //当前登录用户id
        $uid = Yii::app()->user->manage_id;

        //当前登录的用户权限
        $role1 = Manage::model()->getRoleByUid($uid);//当前登录用户的权限

        if ($role1 >= Role::PRACTICE_VISOR) {
            throw new CHttpException (404, '没有权限.');
        }

        //查看的权限
        $role = Manage::model()->getRoleByUid($id);  //get到的用户的权限

        //收益提成比例
        $pay = Salary::model()->getPayByRole($role);

        //查看的用户的姓名
        $name = Manage::model()->getNameById($id);

        $status = isset ($_GET ['status']) ? $_GET ['status'] : DefaultParm::DEFAULT_ZERO;

        //查看的月份
        $mounth = DefaultParm::DEFAULT_EMPTY;
        if (isset($_GET['mounth'])) {
            $mounth_b_s = strtotime($_GET['mounth']);
            $mounth = date('Y-m', $mounth_b_s);
        } else {
            $mounth = date("Y-m", time());
        }
        $firstday = strtotime($mounth);
        $first = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d', strtotime("$first +1 month -1 day")));
        $lastday = $lastday+59*60+59+23*60*60;

        //客服列表
        $manage_list = Task::model()->manageCheckList();

        $AND = DefaultParm::DEFAULT_EMPTY;
        $AND = " AND at.f_id = $id ";

        //$ro = Manage::model()->getRoleByUid($id);

        //默认不显示回访任务
        $TYPE = "at.type != " . AskTask::TYPE_VISIT . " AND";

        //可发布的任务收益的总和
        $nad = TaskWhen::model()->getPayBackTotal($TYPE, $status, $AND, $firstday, $lastday);
        $vis = TaskWhen::model()->getVisitPayBackTotal($TYPE, $status, $AND, $firstday, $lastday);

        $total = $nad[0]['pay']+$vis[0]['pay'];

        //今年的每月已发布收益列表
        $salary = TaskWhen::model()->getPayInThisYearByUid($id);

        //今年的当前客服所有的任务收益总和
        $rel_total = TaskWhen::model()->getAllPayByUid($id);

        //如果是见习客服并且是回访任务，并且评分成功不是0分，则把回访任务收益插入客服的收益表中

        $R = "  at.type !=" . AskTask::TYPE_VISIT . "";

        //$ro = Manage::model()->getRoleByUid($id); //上报此任务客服的级别

        if (($role == Role::PRACTICE_STAFF)) {
            $R = "  at.type =" . AskTask::TYPE_VISIT . "";
        }

        $criteria = new CDbCriteria ();
        $sql = "SELECT mi.username,mi.id AS m_id,at.type,at.a_time,tw.porttime,at.a_time,at.type,at.id AS at_id,
                tw.id AS tw_id,tw.pay_back,tw.ispay,tw.paytime,te.id AS te_id
    			FROM app_ask_task AS at
    			JOIN app_member AS mi
    			JOIN app_task_when AS tw
    			JOIN app_task_earnings AS te
    			ON at.m_id = mi.id AND at.tw_id = tw.id AND te.atid = at.id
    			WHERE $R AND tw.isfail !=" . TaskWhen::IS_FAIL_TRUE . "
    			AND tw.status =" . TaskWhen::STATUS_SUBMAIT . "
    			AND tw.porttime >= $firstday AND tw.porttime <= $lastday
    			AND (at.t_status = " . AskTask::STATUS_ADONE . " OR at.t_status = " . AskTask::STATUS_DEL . ")
    			 $AND
    			ORDER BY tw.porttime DESC";

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination (count($model));
        $pages->pageSize = 10;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();

        Yii::app()->session['show_id'] = $id;


        if(($role ==Role::PRACTICE_VISOR ) || ($role == Role::SUPERVISOR )){
            $com = ManageDeduct::model()->getCommission($firstday,$lastday);
        }else{
            $com = 0;
        }



            /*****************************
             ********工资单显示内容*********
            ******************************/

        //查看指定年月的工资单是否存在
        $count = ManageDeduct::model()->getManageWageByMounth($mounth, $id);
        //如果存在
        if ($count == DefaultParm::DEFAULT_ONE) {

            //查看详细信息
            $wage_msg = ManageDeduct::model()->getManageWgaeMsgByMounth($mounth, $id);
        }
        //不存在
        else if ($count == DefaultParm::DEFAULT_ZERO) {

            $wage_msg = DefaultParm::DEFAULT_ZERO;
        }

        $arr = TaskEarnings::model()->getManagePaybackByTime($id,$firstday,$lastday);

        $manage_msg = Manage::model()->findByPk($id);

        $f_time = strtotime($first);

        $sql_week_eng = 'SELECT * FROM app_task_week_earnings
                WHERE endtime >= \''.$f_time.'\' AND endtime <=\''.$lastday.'\'
                AND uid = \''.$id.'\' ';
        $weekEarnings = Yii::app()->db->createCommand($sql_week_eng)->queryAll();


        $pram = array(
            'manage_msg' => $manage_msg,
            'tasknew' => $arr['tasknew'],
            'taskdrop' => $arr['taskdrop'],
            'taskweek' => $arr['taskweek'],
            'taskvisit' =>  $arr['taskvisit'] ,
            'mounth' => $mounth,'wage_msg' => $wage_msg,'count' => $count,'name' => $name,
            'role' => $role,'role1' => $role1,'status' => $status,'pages' => $pages,
            'pay_list' => $list,'id' => $id,'total' => $total,'manage_list' => $manage_list,
            'salary' => $salary,'rel_total' => $rel_total,'pay' => $pay,'weekEarnings'=>$weekEarnings,
            'com'=>$com
        );
        $this->render('payback', $pram);
    }

    /**
     * @name 查询见习客服日期内条数
     */
    public function actionTaskCount()
    {

        if (Yii::app()->request->isAjaxRequest) {
            $kfid1 = ( int )Yii::app()->request->getParam('kfid1');
            $start1 = Yii::app()->request->getParam('start1');
            $end1 = Yii::app()->request->getParam('end1');

            if($start1==$end1)
            {
                $sqlv = 'select * from app_task where accept= \'' . $kfid1 . '\' and STATUS=3 and availability=1
                AND ((FROM_UNIXTIME(motifytime,"%Y-%m-%d") = \'' .$start1 . '\') OR (FROM_UNIXTIME(createtime,"%Y-%m-%d") = \'' .$start1 . '\'))
                ;';

                $sqlu = 'select * from app_task where accept= \'' . $kfid1 . '\' and STATUS=0 and availability=1
                AND ((FROM_UNIXTIME(motifytime,"%Y-%m-%d") = \'' .$start1 . '\') OR (FROM_UNIXTIME(createtime,"%Y-%m-%d") = \'' .$start1 . '\'))  and type in(1,5)
                ;';
            }
            else{
                $sqlv = 'select * from app_task where accept= \'' . $kfid1 . '\' and STATUS=3 and availability=1
                AND ((FROM_UNIXTIME(motifytime,"%Y-%m-%d") >= \'' .$start1 . '\' AND FROM_UNIXTIME(motifytime,"%Y-%m-%d") <= \'' .$end1 . '\') OR (FROM_UNIXTIME(createtime,"%Y-%m-%d") >= \'' .$start1 . '\' AND FROM_UNIXTIME(createtime,"%Y-%m-%d") <= \'' .$end1 . '\'))
                ;';

                $sqlu = 'select * from app_task where accept= \'' . $kfid1 . '\' and STATUS=0 and availability=1
                AND ((FROM_UNIXTIME(motifytime,"%Y-%m-%d") >= \'' .$start1 . '\' AND FROM_UNIXTIME(motifytime,"%Y-%m-%d") <= \'' .$end1 . '\') OR (FROM_UNIXTIME(createtime,"%Y-%m-%d") >= \'' .$start1 . '\' AND FROM_UNIXTIME(createtime,"%Y-%m-%d") <= \'' .$end1 . '\'))  and type in(1,5)
                ;';
            }

            $modelv = Yii::app()->db->createCommand($sqlv)->queryAll();
            $vnum2 = count($modelv);

            $modelu = Yii::app()->db->createCommand($sqlu)->queryAll();
            $vnum3 = count($modelu);

            $vnum=$vnum2+$vnum3;

            if ($vnum!='') {
                echo $vnum;
            }


        }
    }

    /**
     * 当前任务的信息列表
     * Enter description here ...
     * @name 用户池列表1 -普通权限
     */
    public function actionIndexPro()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.pro.js');
        Script::registerCssFile('asyncbox.css');

        //查看ajax传递过来的管理员id与当前登录的id是否相等，判断有没有权限
        if (Yii::app()->request->isAjaxRequest) {
            $a_id = $_POST ['a_id'];
            if ($_POST ['a_id'] != (Yii::app()->user->manage_id)) {
                throw new CHttpException (404, '你没有权限这么做.');
            }
        }

        $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id);
        if ($role == Role::PRACTICE_STAFF) {

            throw new CHttpException (404, '你没有权限这么做,请查看回访任务列表.');
        }
        if (isset ($_GET ['id'])) {

            $role_top = Manage::model()->getRoleByUid(isset ($_GET ['id']));

            if (($role_top == DefaultParm::DEFAULT_ONE) && ($role != DefaultParm::DEFAULT_ONE)) {

                throw new CHttpException (404, '你没有权限这么做.');
            }
        }

        $id = isset ($_GET ['id']) ? $_GET ['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id

        $name = Manage::model()->getNameById($id);

        if ($id == Yii::app()->user->manage_id) {
            $now = DefaultParm::DEFAULT_ONE;
        } else {
            $now = DefaultParm::DEFAULT_ZERO;
        }

        $criteria = new CDbCriteria ();

        $rank = isset ($_GET ['rank']) ? $_GET ['rank'] : DefaultParm::DEFAULT_ZERO;

        $ORDER = 'ORDER BY tw.remind ASC';

        $fail = isset ($_GET ['fail']) ? $_GET ['fail'] : TaskWhen::IS_FAIL_FALSE;

        if ($fail == TaskWhen::IS_FAIL_FALSE) {
            $ISFAIL = ' AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\' ';
        } else if ($fail == TaskWhen::IS_FAIL_TRUE) {
            $ISFAIL = ' AND tw.isfail = \'' . TaskWhen::IS_FAIL_TRUE . '\' ';
        }

        if ($rank == DefaultParm::DEFAULT_ONE) {
            $ORDER = 'ORDER BY tw.important DESC';
        } else if ($rank == DefaultParm::DEFAULT_ZERO) {
            $ORDER = 'ORDER BY tw.remind ASC';
        }

        $WHERE = DefaultParm::DEFAULT_EMPTY;
        if (isset ($_GET ['member'])) {
            $member = trim($_GET ['member']);
            $WHERE = " AND mi.username like '%$member%' ";
        }
        $rem = DefaultParm::DEFAULT_EMPTY;
        $rem1 = DefaultParm::DEFAULT_EMPTY;

        if (isset ($_GET ['rem']) && isset ($_GET ['rem1'])) {
            if (!empty ($_GET ['rem']) && !empty ($_GET ['rem1'])) {
                $rem = strtotime(trim($_GET ['rem']));
                $rem1 = strtotime(trim($_GET ['rem1']));
                $WHERE = " AND tw.remind >= $rem  AND tw.remind <= $rem1 ";
            }
        }
        if (isset ($_GET ['start']) && ($_GET ['start'] == DefaultParm::DEFAULT_ONE)) {
            if (isset ($_GET ['rem']) && !empty ($_GET ['rem'])) {
                $rem = strtotime(trim($_GET ['rem']));
                $WHERE = " AND  tw.remind >= $rem ";
            }
        }


        $sql = "SELECT t.id AS tid,at.id AS at_id,at.t_status AS at_status,at.a_time,t.publish,t.createtime,t.status AS t_status,t.isshow,t.mid,
				mi.id AS mid,mi.username,mi.wt_id,mi.holder,mi.category,ma.name,ma.role,tw.isfail,tw.status AS tw_status,tw.score,
				tw.porttime,tw.id AS tw_id,at.type,tw.pay_back,tw.remind,tw.important
				FROM app_task AS t 
				JOIN app_task_when AS tw
				JOIN app_member AS mi
				JOIN app_manage AS ma
				JOIN app_ask_task AS at
				ON t.mid = mi.id AND t.accept = ma.id AND tw.tid = t.id AND at.t_id = t.id AND at.tw_id = tw.id
				WHERE mi.status != " . DefaultParm::DEFAULT_ZERO . " AND t.accept= $id  AND  tw.status =" . TaskWhen::STATUS_SUBMAIT . "
				AND t.status !=" . Task::STATUS_DEL . "
				AND at.t_status != " . AskTask::STATUS_DEL . "$ISFAIL $WHERE $ORDER ";

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);
        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $pram = array(
            'fail' => $fail,
            'data' => $list,
            'pages' => $pages,
            'role' => $role,
            'name' => $name,
            'id' => $id,
            'now' => $now,
            'num' => $num,
            'rank' => $rank

        );
        $this->render('index_pro', $pram);
    }

    /**
     * @name 用户池列表2 -普通权限
     */
    public function actionIndexNoPro()
    {
        //print_r("fsafsda");exit;
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.nopro.js');
        Script::registerScriptFile('manage/memberinfo.controller/memberinfo.category.js');
        Script::registerCssFile('asyncbox.css');

   //     $keylist = UserFlags::model()->getKeywordList();

        //查看ajax传递过来的管理员id与当前登录的id是否相等，判断有没有权限
        if (Yii::app()->request->isAjaxRequest) {
            $a_id = $_POST ['a_id'];
            if ($_POST ['a_id'] != (Yii::app()->user->manage_id)) {
                throw new CHttpException (404, '你没有权限这么做.');
            }
        }

        //当前登录用户的权限
        $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id); //当前登录客服的级别

        if (isset ($_GET ['id'])) {
            //要查看的客服的权限
            $role_top = Manage::model()->getRoleByUid(isset ($_GET ['id']));

            if (($role_top == Role::SUPERVISOR) && ($role > Role::SUPERVISOR)) {

                throw new CHttpException (404, '你没有权限这么做.');
            }
        }

        $id = isset ($_GET ['id']) ? $_GET ['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id

        //if ($role == Role::PRACTICE_STAFF) {

           // throw new CHttpException (404, '你没有权限这么做,请查看回访任务列表.');
        //}

        $name = Manage::model()->getNameById($id);

        if ($id == Yii::app()->user->manage_id) {
            $now = DefaultParm::DEFAULT_ONE;
        } else {
            $now = DefaultParm::DEFAULT_ZERO;
        }
        $time = time();

        $criteria = new CDbCriteria ();

        $rank = isset ($_GET ['rank']) ? $_GET ['rank'] : DefaultParm::DEFAULT_ZERO;
        $ORDER = 'ORDER BY tw.remind,tw.id ASC';

        if ($rank == DefaultParm::DEFAULT_ONE) {
            $ORDER = ' ORDER BY tw.important DESC';
        } else if ($rank == DefaultParm::DEFAULT_ZERO) {
            $ORDER = ' ORDER BY tw.remind,tw.id ASC';
        }
        $WHERE = DefaultParm::DEFAULT_EMPTY;
        if (isset ($_GET ['member'])) {
            $member = trim($_GET ['member']);
            $WHERE = " AND mi.username like '%$member%' ";
        }
        $rem = DefaultParm::DEFAULT_EMPTY;
        $rem1 = DefaultParm::DEFAULT_EMPTY;

        if (isset ($_GET ['rem']) && isset ($_GET ['rem1'])) {
            if (!empty ($_GET ['rem']) && !empty ($_GET ['rem1'])) {

                $rem = strtotime(trim($_GET ['rem']));
                $rem1 = strtotime(trim($_GET ['rem1']));
                $WHERE = " AND tw.remind >= $rem  AND tw.remind <= $rem1 ";
            }
        }
        if (isset ($_GET ['you']) && isset ($_GET ['you1'])) {
            if (!empty ($_GET ['you']) && !empty ($_GET ['you1'])) {

                $you = trim($_GET ['you']);
                $you1 = trim($_GET ['you1']);
                $WHERE = " AND ((at.type!=2 AND t.availability = 1 AND FROM_UNIXTIME(t.motifytime,'%Y-%m-%d') >= '$you' AND FROM_UNIXTIME(t.motifytime,'%Y-%m-%d') <= '$you1') OR (at.type!=2 AND t.availability = 1 AND FROM_UNIXTIME(t.createtime,'%Y-%m-%d') >= '$you' AND FROM_UNIXTIME(t.createtime,'%Y-%m-%d') <= '$you1')) ";
            }
        }
        if (isset ($_GET ['start']) && ($_GET ['start'] == DefaultParm::DEFAULT_ONE)) {
            if (isset ($_GET ['rem']) && !empty ($_GET ['rem'])) {
                $rem = strtotime(trim($_GET ['rem']));
                $WHERE = " AND  tw.remind >= $rem ";
            }
        }
        if (isset ($_GET ['remind']) && ($_GET ['remind'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = " AND  $time >= tw.remind ";
        }

        if (isset ($_GET ['tstatus'])) {
            $tstatus = $_GET ['tstatus'];
            $WHERE = " AND  t.type = $tstatus ";
        }

        //逾期30天未联系用户打回用户池
        TaskWhen::model()->checkMemberNoContactMoreThenMounth($id);
        $this_time = time();

        //本周周任务 如果不存在，则默认本周一至下周周一时间节点
        $this_week = WeekTask::model()->getThisWeekWtTask($id);

        //本周周任务开始时间的下午14点
        $this_monday_pm = $this_week['c_time'] + 14 * 3600;

        $week_type = WeekTask::NEXT_WEEK;
        if ($this_time <= $this_monday_pm) {
            $week_type = WeekTask::THIS_WEEK;
        }
        $kind_num = array();

        //已标记的本周周任务数量
        $week_num = WeekTask::model()->getNumInThisWeek($id);
        $kind_num ['least'] = WeekTask::TOTAL;
        $kind_num ['this'] = $week_num ['this'];
        $kind_num ['next'] = $week_num ['next'];

        $sql = "SELECT t.id AS tid,t.availability AS availability,at.id AS at_id,at.a_time,t.publish,t.createtime,t.status AS t_status,t.isshow,t.mid,
				mi.id AS mid,mi.username,mi.wt_id,mi.holder,mi.category,mi.cataid,ma.name,ma.role,tw.isfail,tw.status AS tw_status,tw.score,
				tw.porttime,tw.id AS tw_id,at.type,tw.pay_back,tw.remind,tw.important
				FROM app_task AS t
				JOIN app_task_when AS tw
				JOIN app_member AS mi
				JOIN app_manage AS ma
				JOIN app_ask_task AS at
				ON t.mid = mi.id AND t.accept = ma.id AND tw.tid = t.id AND at.t_id = t.id AND at.tw_id = tw.id
				WHERE mi.status != " . DefaultParm::DEFAULT_ZERO . " AND t.accept= $id  AND
				tw.status !=" . TaskWhen::STATUS_SUBMAIT . "  AND
				t.status = " . Task::STATUS_NORMAL . " AND
				tw.spare = 0 AND
				at.t_status != " . AskTask::STATUS_DEL . " AND at.t_status !=" . AskTask::STATUS_APRO .
                " AND at.t_status !=" . AskTask::STATUS_ADONE . "
				$WHERE $ORDER ";

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);

        $sqlv='select * from app_task where accept= \'' . $id . '\' and STATUS=3 and availability=1;';

        if (isset ($_GET ['you']) && isset ($_GET ['you1'])) {
            if (!empty ($_GET ['you']) && !empty ($_GET ['you1'])) {

                $you = trim($_GET ['you']);
                $you1 = trim($_GET ['you1']);
                $WHEREV = " AND ((FROM_UNIXTIME(motifytime,'%Y-%m-%d') >= '$you' AND FROM_UNIXTIME(motifytime,'%Y-%m-%d') <= '$you1') OR (FROM_UNIXTIME(createtime,'%Y-%m-%d') >= '$you'  AND FROM_UNIXTIME(createtime,'%Y-%m-%d') <= '$you1')) ";

                $sqlv = "select * from app_task where accept= " . $id . " and STATUS=3 and availability=1 " .$WHEREV;
            }

        }

        $modelv = Yii::app()->db->createCommand($sqlv)->queryAll();
        $vnum = count($modelv);

        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $pram = array(
            'data' => $list,
            'pages' => $pages,
            'role' => $role,
            'name' => $name,
            'id' => $id,
            'now' => $now,
            'rank' => $rank,
            'week_type' => $week_type,
            'kind_num' => $kind_num,
            'num' => $num,
            'vnum' => $vnum
        );
        $this->render('index_nopro', $pram);
    }

    /**
     * @name 备选用户池 -普通权限
     */
    public function actionIndexSpare()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.nopro.js');
        Script::registerCssFile('asyncbox.css');

        //查看ajax传递过来的管理员id与当前登录的id是否相等，判断有没有权限
        if (Yii::app()->request->isAjaxRequest) {
            $a_id = $_POST ['a_id'];
            if ($_POST ['a_id'] != (Yii::app()->user->manage_id)) {
                throw new CHttpException (404, '你没有权限这么做.');
            }
        }
        //当前登录用户的权限
        $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id); //当前登录客服的级别

        if (isset ($_GET ['id'])) {
            //要查看的客服的权限
            $role_top = Manage::model()->getRoleByUid(isset ($_GET ['id']));

            if (($role_top == Role::SUPERVISOR) && ($role > Role::SUPERVISOR)) {

                throw new CHttpException (404, '你没有权限这么做.');
            }
        }

        $id = isset ($_GET ['id']) ? $_GET ['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id

        if ($role == Role::PRACTICE_STAFF) {

            throw new CHttpException (404, '你没有权限这么做,请查看回访任务列表.');
        }

        $name = Manage::model()->getNameById($id);

        if ($id == Yii::app()->user->manage_id) {
            $now = DefaultParm::DEFAULT_ONE;
        } else {
            $now = DefaultParm::DEFAULT_ZERO;
        }
        $time = time();

        $criteria = new CDbCriteria ();

        $rank = isset ($_GET ['rank']) ? $_GET ['rank'] : DefaultParm::DEFAULT_ZERO;
        $ORDER = 'ORDER BY tw.remind ASC';

        if ($rank == DefaultParm::DEFAULT_ONE) {

            $ORDER = ' ORDER BY tw.important DESC';
        } else if ($rank == DefaultParm::DEFAULT_ZERO) {

            $ORDER = ' ORDER BY tw.remind ASC';
        }
        $WHERE = DefaultParm::DEFAULT_EMPTY;
        if (isset ($_GET ['member'])) {

            $member = trim($_GET ['member']);
            $WHERE = " AND mi.username like '%$member%' ";
        }
        $rem = DefaultParm::DEFAULT_EMPTY;
        $rem1 = DefaultParm::DEFAULT_EMPTY;

        if (isset ($_GET ['rem']) && isset ($_GET ['rem1'])) {
            if (!empty ($_GET ['rem']) && !empty ($_GET ['rem1'])) {

                $rem = strtotime(trim($_GET ['rem']));
                $rem1 = strtotime(trim($_GET ['rem1']));
                $WHERE = " AND tw.remind >= $rem  AND tw.remind <= $rem1 ";
            }
        }
        if (isset ($_GET ['start']) && ($_GET ['start'] == DefaultParm::DEFAULT_ONE)) {
            if (isset ($_GET ['rem']) && !empty ($_GET ['rem'])) {
                $rem = strtotime(trim($_GET ['rem']));
                $WHERE = " AND  tw.remind >= $rem ";
            }
        }
        if (isset ($_GET ['remind']) && ($_GET ['remind'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = " AND  $time >= tw.remind ";
        }


        $this_time = time();

        //本周周任务 如果不存在，则默认本周一至下周周一时间节点
        $this_week = WeekTask::model()->getThisWeekWtTask($id);

        $resault = 0; //默认不可晋升

        if ($role == Role::SUPPORT_STAFF) { //如果用户等级是普通客服
            //可以晋级
            $manage = Manage::model()->findByPk($id);
            $res = $manage->promotion; //赋值晋升状态
            if ($res == 0) { //数据表，不可晋升
                //查看两周内是否都合格，
                $resault = WeekTask::model()->checkConByWeek($this_week['c_time'], $id);
//                //可晋升
//                if($resault == 1){      //如果是1，可以晋升
//                    //修改数据表状态，可晋升
//                    $count = Manage::model()->updateByPk($id,array('promotion'=>1));
//                }
                //如果是等待晋升状态
            } elseif ($res == 2) {
                $pro_time = $manage->pro_time;
                $next_mounth = WeekTask::model()->GetNextMonth($pro_time);
                $next_mounth = strtotime($next_mounth);
                if ($this_time >= $next_mounth) {
                    Manage::model()->updateByPk($id, array('promotion' => 3, 'role' => Role::ADVANCED_STAFF));
                    throw new CHttpException (success, '恭喜已成功晋升为高级客服，现在起任务收益提高，周任务收益正常计算.');
                }
                $resault = 0;
            } else {
                $resault = 0;
            }
        }

        //本周周任务开始时间的下午14点
        $this_monday_pm = $this_week['c_time'] + 14 * 3600;

        $week_type = WeekTask::NEXT_WEEK;
        if ($this_time <= $this_monday_pm) {
            $week_type = WeekTask::THIS_WEEK;
        }
        $kind_num = array();

        //已标记的本周周任务数量
        $week_num = WeekTask::model()->getNumInThisWeek($id);
        $kind_num ['least'] = WeekTask::TOTAL;
        $kind_num ['this'] = $week_num ['this'];
        $kind_num ['next'] = $week_num ['next'];

        $sql = "SELECT t.id AS tid,at.id AS at_id,at.a_time,t.publish,t.createtime,t.status AS t_status,t.isshow,t.mid,
				mi.id AS mid,mi.username,mi.wt_id,mi.holder,mi.category,ma.name,ma.role,tw.isfail,tw.status AS tw_status,tw.score,
				tw.porttime,tw.id AS tw_id,at.type,tw.pay_back,tw.remind,tw.important
				FROM app_task AS t
				JOIN app_task_when AS tw
				JOIN app_member AS mi
				JOIN app_manage AS ma
				JOIN app_ask_task AS at
				ON t.mid = mi.id AND t.accept = ma.id AND tw.tid = t.id AND at.t_id = t.id AND at.tw_id = tw.id
				WHERE mi.status != " . DefaultParm::DEFAULT_ZERO . " AND t.accept= $id  AND
				tw.status !=" . TaskWhen::STATUS_SUBMAIT . "  AND
				t.status = " . Task::STATUS_NORMAL . " AND
				tw.spare = 1 AND
				at.t_status != " . AskTask::STATUS_DEL . " AND at.t_status !=" . AskTask::STATUS_APRO . " AND at.t_status !=" . AskTask::STATUS_ADONE . "
				$WHERE $ORDER ";

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);
        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $pram = array(
            'data' => $list,'pages' => $pages,'role' => $role,'name' => $name,'id' => $id,
            'now' => $now,'rank' => $rank,'week_type' => $week_type, 'kind_num' => $kind_num,
            'num' => $num,'res' => $resault
        );
        $this->render('index_spare', $pram);
    }

    /**
     * @name 被拒绝 /待批准任务 -普通权限
     */
    public function actionRefuseTask()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/memberpool.refusetask.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.nopro.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id;

        if (isset ($_GET ['remind']) && ($_GET ['remind'] == DefaultParm::DEFAULT_ONE)) {
            $title = '提醒到期任务列表';

            $data = AskTask::model()->getRemindTask($id);
            $this->render('refusetask', array('data' => $data, 'title' => $title));
            exit ();
        }

        $allow = ( int )Yii::app()->request->getParam('allow');
        $data = AskTask::model()->getAskTaskStatusNoAllow($id, $allow);


        if ($allow == AskTask::IS_ALLOW_FALSE) {
            $title = '被拒绝任务列表';
        } else if ($allow == AskTask::IS_ALLOW_WAIT) {
            $title = '待批准任务列表';
        } else {
            throw new CHttpException (404, '你没有权限这么做.');
        }
        $pram = array(
            'data' => $data,
            'title' => $title,
            'allow' => $allow
        );
        $this->render('refusetask', $pram);

    }

    /**
     * @name 任务类型跳转 -普通权限
     */
    public function actionTaskType()
    {
        Script::registerScriptFile('manage/memberpool.controller/memberpool.nopro.js');
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerCssFile('asyncbox.css');

        $pram = array();
        $this->render('tasktype', $pram);
    }

    /**
     * @name 清除被拒任务 -普通权限
     */
    public function actionDelNotAllowTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = Yii::app()->request->getParam('at_id'); //atid int

            $model = AskTask::model()->getATidByATid($at_id);

            foreach ($model as $key => $item) {
                if (($item ['is_allow'] != AskTask::IS_ALLOW_FALSE)) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit (); //失败
                }
            }

            $count = count(explode(',', $at_id));

            $model1 = AskTask::model()->deleteAll('id in(\'' . $at_id . '\')');

            if ($count == $model1) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                exit (); //成功
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit (); //失败
            }
        }
    }

    /**
     * @name 删除等待批准的任务 -普通权限
     */
    public function actionDelWaitToAllowTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = Yii::app()->request->getParam('at_id'); //atid int

            $model = AskTask::model()->getATidByATid($at_id);

            $count = count(explode(',', $at_id));
            $arr = array();
            //查询mid
            $sql = 'SELECT m_id FROM app_ask_task WHERE FIND_IN_SET(id,\'' . $at_id . '\')';
            $res = Yii::app()->db->createcommand($sql)->queryAll();

            foreach ($res AS $key => $item) {
                $arr[$key] = $item['m_id'];
            }
            $mid_list = implode(",", $arr);
            //解除任务锁定
            $sql = 'UPDATE app_member SET manage_id = 0 WHERE FIND_IN_SET(id,\'' . $mid_list . '\')';
            $query = Yii::app()->db->createcommand($sql)->execute();

            //删除任务
            $sql = 'DELETE FROM app_ask_task WHERE FIND_IN_SET(id,\'' . $at_id . '\')';
            $model1 = Yii::app()->db->createcommand($sql)->execute();

            if ($count == $model1) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                exit (); //成功
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit (); //失败
            }
        }
    }

    /**
     *放弃回访任务by atid
     * @name 放弃回访任务 -普通权限
     */
    public function actionDelVisiteTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = ( int )Yii::app()->request->getParam('at_id'); //atid int
            $res = AskTask::model()->getAtByAtId($at_id);
            if (!empty ($res) && ($res->type == AskTask::TYPE_VISIT)) {
                $t = Yii::app()->db->beginTransaction();
                try {

                    $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
                    $asktask->t_status = AskTask::STATUS_DEL;
                    $asktask->update();

                    $task = Task::model()->findByAttributes(array('id' => $res->t_id));
                    $task->status = Task::STATUS_DEL;
                    $task->update();

                    $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $res->tw_id));
                    $taskwhen->status = Task::STATUS_SUBMIT;
                    $taskwhen->isfail = TaskWhen::IS_FAIL_TRUE;
                    $taskwhen->update();

                    Member::model()->updateByPk($res->m_id, array('manage_id' => DefaultParm::DEFAULT_ZERO));

                    $t->commit();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //回访任务已删除

                } catch (Exception $e) {

                    $t->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //回访任务失败

                }
            }
        }
    }
    /**
     *放弃回访任务by atid
     * @name 客服释放此页客户 -普通权限
     */
    public function actionDelVisiteTaskall()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id0 = Yii::app()->request->getParam('at_id'); //atid int
            $at_id=explode(",",$at_id0);
            $result=array_pop($at_id);
            foreach ($at_id AS $val) {
               $res = AskTask::model()->getAtByAtId($val);
                if (!empty ($res)) {
                    $t = Yii::app()->db->beginTransaction();
                    try {

                        $asktask = AskTask::model()->findByAttributes(array('id' => $val));
                        if(!empty($asktask))
                        {
                            $asktask->t_status = AskTask::STATUS_DEL;
                            $asktask->update();
                        }


                        $task = Task::model()->findByAttributes(array('id' => $res->t_id));
                        if(!empty($task))
                        {
                            $task->status = Task::STATUS_DEL;
                            $task->update();
                        }


                        $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $res->tw_id));
                        if(!empty($taskwhen))
                        {
                            $taskwhen->status = Task::STATUS_SUBMIT;
                            $taskwhen->isfail = TaskWhen::IS_FAIL_TRUE;
                            $taskwhen->update();
                        }


                        Member::model()->updateByPk($res->m_id, array('manage_id' => DefaultParm::DEFAULT_ZERO));

                        $t->commit();
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //回访任务已删除

                    } catch (Exception $e) {

                        $t->rollback();
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //回访任务失败

                    }
                }


            }

        }
    }

    /**
     *变更回访任务为其他任务类型并申请任务 by atid
     * @name 回访任务变更 -普通权限
     */
    public function actionAskForVisiteTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = ( int )Yii::app()->request->getParam('at_id'); //atid int
            $type = ( int )Yii::app()->request->getParam('type'); //atid int

            $res = AskTask::model()->getAtByAtId($at_id);

            $t = Yii::app()->db->beginTransaction();
            try {
                $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
                $asktask->m_id = $res->m_id;
                $asktask->a_time = time();
                $asktask->is_allow = AskTask::IS_ALLOW_WAIT;
                $asktask->t_status = AskTask::STATUS_AASK;
                $asktask->f_id = $res->f_id;
                $asktask->type = $type;
                $asktask->update();

                $task = Task::model()->findByAttributes(array('id' => $res->t_id));
                $task->status = Task::STATUS_DEL;
                $task->availability = 0;
                $task->update();

                $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $res->tw_id));
                $taskwhen->status = Task::STATUS_SUBMIT;
                $taskwhen->isfail = TaskWhen::IS_FAIL_TRUE;
                $taskwhen->update();

                // $asktask1 = new AskTask ();
                // $asktask1->m_id = $res->m_id;
                // $asktask1->a_time = time();
                // $asktask1->is_allow = AskTask::IS_ALLOW_WAIT;
                // $asktask1->t_status = AskTask::STATUS_AASK;
                // $asktask1->f_id = $res->f_id;
                // $asktask1->type = $type;
                // $asktask1->insert();

                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }

        }
    }
    /**
     * @name 见习客服-查看安装量
     */
    public function actionGetWeeekcount()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $mid = ( int )Yii::app()->request->getParam('mid');
            $weeekcount=RomAppresource::model()->memberPoolSee($mid);
            if(!empty($weeekcount))
            {
                echo CJSON::encode(array('msg' => $weeekcount));
            }
            else
            {
                echo CJSON::encode(array('msg' => 0));
            }
        }
        else
        {
            echo CJSON::encode(array('msg' => 0));
        }
    }
    /**
     * @name 有效回访状态变更 - 见习客服
     */
    public function actionAskForVisiteVTask()
    {

        if (Yii::app()->request->isAjaxRequest) {
            $at_id = ( int )Yii::app()->request->getParam('at_id'); //atid int
            $availability = ( int )Yii::app()->request->getParam('availability'); //atid int

            $res = AskTask::model()->getAtByAtId($at_id);

            $t = Yii::app()->db->beginTransaction();
            try {
                $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
                $asktask->t_status = AskTask::STATUS_AASK;
                $asktask->update();

                $task = Task::model()->findByAttributes(array('id' => $res->t_id));
                $task->status = Task::STATUS_DEL;
                $task->update();

                $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $res->tw_id));
                $taskwhen->status = Task::STATUS_NORMAL;
                $taskwhen->isfail = TaskWhen::IS_FAIL_TRUE;
                $taskwhen->update();

                $asktask1 = new AskTask ();
                $asktask1->m_id = $res->m_id;
                $asktask1->t_id = $res->t_id;
                $asktask1->a_time = time();
                $asktask1->is_allow = AskTask::IS_ALLOW_WAIT;
                $asktask1->t_status = AskTask::STATUS_AASK;
                $asktask1->f_id = $res->f_id;
                $asktask1->type = 5;
                $asktask1->availability = $availability;
                $asktask1->insert();

                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }

        }
        else
        {echo "not valuse";}
    }

    /**
     * @name 回访任务表 -普通权限
     */
    public function actionVisit()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.visite.js');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id
        $name = Manage::model()->getNameById($id);
        $role = Manage::model()->getRoleByUid($id); //当前登录客服的级别

        $STATUS = '';
        $type = 0;
        if(isset($_GET['v_status'])){
            $type = 1;
            switch ($_GET['v_status'])
            {
                case 0://可执行
                    $type = 0;

                    $STATUS = 'AND at.t_status !=\'' . AskTask::STATUS_APRO . '\'
                       AND at.t_status !=\'' . AskTask::STATUS_ADONE . '\'
                       AND at.t_status !=\'' . AskTask::STATUS_DEL . '\'
                       AND t.status =\'' . Task::STATUS_NORMAL . '\'
                       AND ((tw.status  = \'' . TaskWhen::STATUS_NORMAL . '\')
                       OR (tw.status  = \'' . TaskWhen::STATUS_ROLLBACK . '\'))
                       AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\'';
                break;

                case 1://显示全部
                    $STATUS = '';
                break;

                case 2://显示已上报
                    $STATUS = 'AND at.t_status =\'' . AskTask::STATUS_APRO . '\'
                               AND t.status =\'' . Task::STATUS_SUBMIT . '\'
                               AND tw.status = \''.TaskWhen::STATUS_SUBMAIT.'\'
                               AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\'
                               ';
                break;

                case 3://显示已放弃
                    $STATUS = 'AND at.t_status =\'' . AskTask::STATUS_DEL . '\'
                               AND t.status =\'' . Task::STATUS_DEL . '\'
                               AND tw.isfail = \''. TaskWhen::IS_FAIL_TRUE .'\'';
                break;
                case 4://显示已被审核清除的任务
                    $STATUS = '
                               AND tw.isfail = \''. TaskWhen::IS_FAIL_FALSE .'\'
                               AND tw.score !=  \''.TaskWhen::ZERO_STAR.'\'
                               ';
                    break;
            }
        }else{
            $STATUS = 'AND at.t_status !=\'' . AskTask::STATUS_APRO . '\'
                       AND at.t_status !=\'' . AskTask::STATUS_ADONE . '\'
                       AND at.t_status !=\'' . AskTask::STATUS_DEL . '\'
                       AND t.status =\'' . Task::STATUS_NORMAL . '\'
                       AND ((tw.status  = \'' . TaskWhen::STATUS_NORMAL . '\')
                       OR (tw.status  = \'' . TaskWhen::STATUS_ROLLBACK . '\'))
                       AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\'';
        }

        $criteria = new CDbCriteria ();

        //排序

        $ORDER = '';
        if(isset($_GET['order'])){

            if($_GET['order'] == 0){        //提醒时间排序升

                $ORDER = 'ORDER BY tw.remind DESC';
            }elseif($_GET['order'] == 1){   //提醒时间排序降

                $ORDER = 'ORDER BY tw.remind ASC';
            }elseif($_GET['order'] == 2){   //用户等级排序升

                $ORDER = 'ORDER BY tw.important DESC';
            }elseif($_GET['order'] == 3){   //用户等级排序降

                $ORDER = 'ORDER BY tw.important ASC';
            }
        }else{

            $ORDER = 'ORDER BY tw.remind DESC';
        }


        $sql = 'SELECT mi.username,at.a_time,at.id,at.a_id,at.m_id,at.t_id,at.tw_id,tw.remind FROM app_ask_task AS at
       			JOIN app_member AS mi
       			JOIN app_task AS t
       			JOIN app_task_when AS tw
       			ON at.m_id = mi.id AND at.t_id = t.id AND at.tw_id = tw.id
       			WHERE at.type= \'' . AskTask::TYPE_VISIT . '\'
       			AND at.f_id = \'' . $id . '\'
       			'.$STATUS.'' .$ORDER.' ';

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);

        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $pram = array(
            'role'  => $role,
            'data'  => $list,
            'num'   => $num,
            'pages' => $pages,
            'name'  => $name,
            'type'  => $type
        );
        $this->render('visit', $pram);

    }

//    /**
//     * @name 回访任务状态列表
//     */
//    public function  actionVisitTaskStatus(){
//        Script::registerScriptFile(Script::JQUERY_TOOLS);
//        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
//        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
//        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
//        Script::registerScriptFile('manage/memberpool.controller/memberpool.visite.js');
//        Script::registerCssFile('asyncbox.css');
//
//        $id = Yii::app()->user->manage_id;
//        $sql = 'SELECT at.m_id,at.a_time,at.t_status,t.content,t.status AS tstatus,tw.* FROM app_ask_task AS at
//                JOIN app_task AS t
//                JOIN app_task_when AS tw
//                ON at.t_id =t.id AND at.tw_id = tw.id AND t.id = tw.tid
//                WHERE at.f_id = \''.$id.'\' AND  at.type = \''.AskTask::TYPE_VISIT.'\'';
//
//        echo $sql;
//
//        $this->render('visittaskstatus');
//    }




    /**
     * 发布任务
     * @name 任务发布 (new) -高级权限
     */

    public function actionGetTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $arr = $_POST ['val'];
            $f_id = $_POST ['a_id'];
            $memberList = explode(',', $arr);

            $str = implode(",", $memberList);

            $sql = 'SELECT count(*) AS count  FROM app_task WHERE mid IN (\'' . $str . '\')';
            $count = Yii::app()->db->createCommand($sql)->queryAll();
            if ($count [0] ['count'] != DefaultParm::DEFAULT_ZERO) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS));
                exit ();
            }
            $t = Yii::app()->db->beginTransaction();
            try {
                foreach ($memberList as $memberId) {
                    //添加新任务
                    $model = new Task ('insert');
                    $model->title = date('Y-m-d', time()) . '发布新用户任务';
                    $model->content = '新用户任务';
                    $model->type = Task::TYPE_NEW;
                    $model->accept = $f_id;
                    $model->publish = Yii::app()->user->manage_id;
                    $model->createtime = DateUtil::time();
                    $model->status = Task::STATUS_NORMAL;
                    $model->mid = $memberId;
                    $model->insert();

                    $taskWhen = new TaskWhen ('insert');
                    $taskWhen->tid = $model->id;
                    $taskWhen->createtime = $model->createtime;
                    $taskWhen->status = TaskWhen::STATUS_NORMAL;
                    $taskWhen->insert();

                    $askTask = new AskTask ('insert');
                    $askTask->allow_time = $model->createtime; //任务批准/发布时间
                    $askTask->t_status = AskTask::STATUS_AASK; //已申请
                    $askTask->f_id = $f_id; //申请人id/任务接收人id
                    $askTask->a_id = $model->publish; //发布任务的管理员id
                    $askTask->is_allow = AskTask::IS_ALLOW_TRUE; //批准任务
                    $askTask->tw_id = $taskWhen->id;
                    $askTask->t_id = $model->id;
                    $askTask->type = $model->type;
                    $askTask->m_id = $memberId;
                    $askTask->insert();

                }

                $memberinfo = Member::model()->updateManageidByIdList($f_id, $str);
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } catch (Exception $e) {

                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }
            exit;
        }
    }

    /**
     * 当前任务的详细信息
     * @name 任务信息 -普通权限
     */
    public function actionMytask()
    {
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.mytask.js');
        Script::registerScriptFile('manage/memberpool.public/getScoreForTask.js');
        Script::registerCssFile('asyncbox.css');
        Script::registerCssFile('star.css');

        $tid = $_GET ['tid']; //任务id
        $uid = $_GET ['uid']; //客服id
        $id = Yii::app()->user->manage_id;
        $monday1 = TaskWhen::model()->this_monday(); //获取本周周一的时间戳0点，0时，0秒
        $monday2 = $monday1 + (( int )24 * ( int )3600) - 1; //获取本周周一的时间戳23点，59时，59秒
        $time = time(); //当前时间
        //如果今天是周一
        if (($monday1 <= $time) && ($time <= $monday2)) {
            $monday = DefaultParm::DEFAULT_ONE;
        } else {
            $monday = DefaultParm::DEFAULT_ZERO;
        }
        if ($id == $uid) {
            $now = DefaultParm::DEFAULT_ONE;
        } else {
            $now = DefaultParm::DEFAULT_ZERO;
        }


        $task_msg = TaskWhen::model()->getTaskMsgByTid($tid); //任务信息

        $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id); //当前用户权限

        $ro = Manage::model()->getRoleByUid($uid); //当前用户权限

        $data = Task::model()->getMemberMsgByTid($tid); //用户信息

        $pram = array(
            'model' => $data,
            'tid' => $tid, 'uid' => $uid,
            'now' => $now, 'role' => $role,
            'task_msg' => $task_msg,
            'monday' => $monday,
            'ro' => $ro
        );
        $this->render('mytask', $pram);
    }


    /**
     * 客服提交当前任务信息
     * Enter description here ...
     * @name 提交任务 -普通权限
     */
    public function actionReply()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $is_fail = ( int )Yii::app()->request->getParam('is_fail'); //任务是否失败
            $len = ( int )Yii::app()->request->getParam('len'); //回复内容的长度
            $tid = ( int )Yii::app()->request->getParam('tid'); //t  id
            $tw_id = ( int )Yii::app()->request->getParam('tw_id'); //tw id
            $tw_status = ( int )Yii::app()->request->getParam('tw_status'); //任务状态，上报，打回
            $my_reply = Yii::app()->request->getParam('my_reply'); //客服回复
            $type = ( int )Yii::app()->request->getParam('type'); //任务类型
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id

            $id = Yii::app()->user->manage_id;
            if(in_array($id,array(33)))
            {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //部分客服不能提成
                exit;
            }
            //用户权限
            $role = Manage::model()->getRoleByUid($id);
            //当前权限下任务的提成比例
            $limittwenty=0;//新提成规则20170120
            $pay = Salary::model()->getPayByRole($role,$limittwenty);

            $t_status = Task::model()->findByAttributes(array('id' => $tid));

            $sql2 = 'SELECT a_time FROM app_ask_task WHERE m_id = \'' . $mid . '\' AND t_id = \'' . $tid . '\' ';

            $ask_task_time = Yii::app()->db->createCommand($sql2)->queryAll();

            $a_time = $ask_task_time [0] ['a_time']; //任务申请，发布的时间

            $prottime = time(); //任务提交时间，当前的时间

            if (($t_status->status == Task::STATUS_SUBMIT) && ($tw_status == TaskWhen::STATUS_SUBMAIT)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_APRO));
                exit (); //以提交
            }
            //任务上报失败
            if ($is_fail == TaskWhen::IS_FAIL_TRUE) {

                $t = Yii::app()->db->beginTransaction();
                try {
                    //任务收益为0
                    $pay_back = DefaultParm::DEFAULT_ZERO;
                    $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));

                    $ret = TaskWhen::model()->updateAll(array(
                        'isfail' => TaskWhen::IS_FAIL_TRUE, 'content' => $my_reply, 'status' => TaskWhen::STATUS_SUBMAIT,
                        'porttime' => time(), 'pay_back' => $pay_back,
                    ), " id = " . $tw_id);

                    $task = Task::model()->findByAttributes(array('id' => $tid));
                    $task->status = Task::STATUS_SUBMIT;
                    $task->type = $type;
                    $task->update();

                    $asktask = AskTask::model()->findByAttributes(array('t_id' => $tid));
                    $asktask->type = $type;
                    $asktask->t_status = AskTask::STATUS_APRO;
                    $asktask->update();

                    $t->commit();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //评论提交成功
                    exit;
                } catch (Exception $e) {
                    $t->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
                    exit;
                }

            }

            //任务进行中
            if (($t_status->status == Task::STATUS_NORMAL) &&
                (($tw_status == TaskWhen::STATUS_ROLLBACK) || ($tw_status == TaskWhen::STATUS_NORMAL))
            ) {

                //回复内容为空
                if (empty ($my_reply) || ($len == DefaultParm::DEFAULT_ZERO)) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_MSG_EMPTY));
                    exit (); //评论内容不能为空
                }

                //判断任务类型
                if ($type == Task::TYPE_NEW) { //新用户任务正常提交

                    $pay_back = TaskWhen::model()->getPayBackForNewMemberTask($a_time, $prottime, $mid, $pay ['new']);

                    $same = TaskWhen::model()->replyTask($tw_id, $tid, $is_fail, $my_reply, $type, $pay_back,$role);

                    if ($same == DefaultParm::DEFAULT_ONE) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //任务提交成功
                    } else {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));   //任务提交失败
                        exit;
                    }

                } else if ($type == Task::TYPE_DROP) { //降量任务正常提交

                    $pay_back = TaskWhen::model()->getPayBackForDropTask($a_time, $prottime, $mid, $pay ['drop']);

                    $same = TaskWhen::model()->replyTask($tw_id, $tid, $is_fail, $my_reply, $type, $pay_back,$role);

                    if ($same == DefaultParm::DEFAULT_ONE) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //评论提交成功
                    } else {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
                        exit;
                    }
                }
            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
            }
        }
    }

    /**
     * 用户信息操作
     * Enter description here ...
     * @name 信息操作1 -普通权限
     */
    public function actionUserMsg()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id
            $tw_id = ( int )Yii::app()->request->getParam('tw_id'); //任务信息表id
            $remark = Yii::app()->request->getParam('remark'); //用户备注
            $remind_time = Yii::app()->request->getParam('remind_time'); //提醒时间
            $important = Yii::app()->request->getParam('val'); //用户等级

            $remind = strtotime($remind_time);

            $count = TaskWhen::model()->getTaskMsgById($tw_id);

            if ($count [0] ['count'] != DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //备注信息提交失败
            } else {

                $res = TaskWhen::model()->updateTasRemarkById($remark, $remind, $important, $tw_id);

                if ($res == DefaultParm::DEFAULT_ONE) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //备注信息提交成功
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //备注信息提交失败
                }
            }

        }
    }

    /**
     * @name 信息操作2 -普通权限
     */
    public function actionSetMsg()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = ( int )Yii::app()->request->getParam('id'); //twid2

            $count = TaskWhen::model()->getTaskMsgById($id);

            if ($count [0] ['count'] != DefaultParm::DEFAULT_ONE) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit (); //备注信息提交失败
            } else {
                $remind = isset ($count [0] ['remind']) && ($count [0] ['remind'] != 0) ? date('Y-m-d', $count [0] ['remind']) : DefaultParm::DEFAULT_EMPTY;
                $remark = isset ($count [0] ['remark']) ? $count [0] ['remark'] : DefaultParm::DEFAULT_EMPTY;
                $important = isset ($count [0] ['important']) ? $count [0] ['important'] : DefaultParm::DEFAULT_EMPTY;
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS, 'remark' => "$remark", 'remind' => "$remind", 'important' => "$important")); //备注信息提交失败
            }
        }
    }

    /**
     * @name 切换用户池 -普通权限
     */
    public function actionChangePool()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $twid = ( int )Yii::app()->request->getParam('twid'); //twid
            $to = ( int )Yii::app()->request->getParam('to'); //移入移出
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id

            //$member = MemberInfo::model()->findByPk($mid); //用户信息
           // $id = Yii::app()->user->manage_id; //当前登录用户id

            $sql = 'SELECT COUNT(id) AS count,status FROM app_task_when WHERE id = \'' . $twid . '\' ';
            $res = Yii::app()->db->createCommand($sql)->queryAll();

            if ($res[0]['count'] == 1) {
                $sql = 'UPDATE app_task_when SET spare = \'' . $to . '\' WHERE id = \'' . $twid . '\' ';
                $res = Yii::app()->db->createCommand($sql)->execute();
                if ($res == 1) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit;
                }
            } elseif ($res[0]['count'] == 0) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOEXISTS));
                exit;
            }
        }
    }

    /**
     * 用户详细信息(ajax)
     * @param $mid
     * @name 用户信息 (弹窗) -普通权限
     */

    public function actionInfo()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id

            $model = Member::model()->findByPk($mid);

            if ($model === null) {
                if (Yii::app()->request->isAjaxRequest) {
                    exit ();
                } else {
                    throw new CHttpException (404, 'The requested page does not exist.');
                }
            }

            $back = '';
            $sql = 'SELECT tw.remark FROM app_ask_task AS a
                    JOIN app_task AS t
                    JOIN app_task_when AS tw
                    ON a.tw_id = tw.id AND a.t_id = t.id AND tw.tid = t.id
                    WHERE a.f_id = \''.$model->manage_id.'\' AND a.m_id = \''.$mid.'\' AND t.mid = \''.$mid.'\' AND a.t_status = 2 AND t.status = 0';
            $res = Yii::app()->db->createCommand($sql)->queryAll();

            if(empty($res[0]['remark'])){
                $back = '无备注';
            }else{
                $back = $res[0]['remark'];
            }

            $memberCategoryList = MemberCategory::model()->getListToArray();
            $category = isset ($memberCategoryList [$model->category]) ? $memberCategoryList [$model->category] : '';
            $html = '<dl class="dl-horizontal">';
            $html .= '<dt>' . $model->getAttributeLabel('username') . '</dt><dd>' . $model->username . '</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('holder') . '</dt><dd>' . $model->holder . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('tel') . '</dt><dd>' . $model->tel . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('regist_tel') . '</dt><dd>' . $model->regist_tel . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('mail') . '</dt><dd>' . $model->mail . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('qq') . '</dt><dd>' . $model->qq . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('bank') . '</dt><dd>' . $model->bank . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('clients') . '</dt><dd>' . $model->clients . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('jointime') . '</dt><dd>' . date('Y-m-d H:i:s', $model->jointime) . '</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('overtime') . '</dt><dd>' . date('Y-m-d H:i:s', $model->overtime) . '</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('type') . '</dt><dd>' . Member::getNameByType($model->type) . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('alias') . '</dt><dd>' . $model->alias . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('category') . '</dt><dd>' . $category . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('agent') . '</dt><dd>' . (empty ($model->agent) ? '无' : '有') . '&nbsp;</dd>';
            $html .= '<dt>任务备注</dt><dd>' . $back . '</dd>';

            if (!empty ($model->agent)) {
                $agent = Member::model()->getById($model->agent);
                $html .= '<dt>' . $model->getAttributeLabel('agent') . '</dt><dd>' . $agent->username . '&nbsp;</dd>';
            }

            //已开启和已被封的业务key
            $html .= '<dt>&nbsp;</dt><dd><h5>业务</h5></dd>';
            $resourceList = MemberResource::model()->getByUid($mid);

            foreach ($resourceList as $resource) {
                $html .= '<dt>' . Ad::getAdNameById($resource->type) . '</dt><dd>';
                $html .= $resource->key;
                $html .= $resource->openstatus == MemberResource::OPEN_TRUE ? ' [开启] ' : ' ';

                if ($resource->status == MemberResource::STATUS_FALSE) {
                    $bindSample = BindSample::model()->getByVal($resource->key, $resource->type);
                    if ($bindSample->closed == BindSample::CLOSED_TRUE && $bindSample->status == BindSample::STATUS_FALSE) {
                        $html .= '(封号 - ' . DateUtil::dateFormate($resource->motifytime) . ')';
                    } else {
                        $html .= '(回收 - ' . DateUtil::dateFormate($resource->motifytime) . ')';
                    }
                }
                $html .= '</dd>';
            }


            //任务绑定的客服
            $html .= '<dt>&nbsp;</dt><dd><h5>客服</h5></dd>';

            $taskList = Task::model()->getListByMemberId($model->id);
            foreach ($taskList as $task) {
                $html .= '<dt>' . $task->manageAccept->name . '</dt>';
                $html .= '<dd>' . DateUtil::dateFormate($task->createtime) . ' - ' . $task->getStatusName($task->status) . '</dd>';
            }

            $html .= '</dl>';

            echo $html;
            exit ();
        }
    }

    /**
     * @name 批量移动任务
     */
    public function actionChangePoolAll(){
        if (Yii::app()->request->isAjaxRequest) {
            $twid_list  = Yii::app()->request->getParam('twid_list'); //twid
            $to         = (int)Yii::app()->request->getParam('to'); //twid

            $t = Yii::app()->db->beginTransaction();
            try {
                $sql = 'UPDATE app_task_when SET spare = \''.$to.'\' WHERE FIND_IN_SET(id,\''.$twid_list.'\')';
                $res = Yii::app()->db->createCommand($sql)->execute();

                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //评论提交成功
                exit;
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
                exit;
            }
        }
    }
    /**
     * @name 逾期未联系列表
     */
    public function actionLastContactList(){
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.visite.js');
        Script::registerCssFile('asyncbox.css');
        $id = Yii::app()->user->manage_id;
        $last_contact = TaskWhen::model()->checkMemberNoContactMoreThenMounth($id);
        $never_contact = TaskWhen::model()->checkTasksNotInPool($id);
        $parm = array(
            'last_contact'=>$last_contact,
            'never_contact'=>$never_contact
        );
        $this->render('contact', $parm);
    }

    /**
     * @name 客服帮助文档
     */
    public function actionHelp(){
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.visite.js');
        Script::registerCssFile('asyncbox.css');

        $this->render('help');

    }
    /**
     * @name 业务流文档
     */
    public function actionHelp2(){
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/visiteTaskAction.js');
        Script::registerScriptFile('manage/memberpool.controller/memberpool.visite.js');
        Script::registerCssFile('asyncbox.css');

        $this->render('help2');

    }
}