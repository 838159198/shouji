<?php

/**
 * @name 任务管理
 */
class TaskController extends DhadminController
{
    /**
     * @param $id
     * @name 任务审核 -高级权限
     */
    public function actionCheckList()
    {
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.controller/task.checklist.js');
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerCssFile('star.css');
        Script::registerCssFile('asyncbox.css');

        //查看当前用户的权限
        $id = Yii::app()->user->manage_id;
        $role = Manage::model()->findByPk($id);
        if ($role->role > Role::PRACTICE_VISOR) {
            throw new CHttpException (404, '你没有权限查看此页面.');
            return false;
        }

        $WHERE = '';
        $show_check = DefaultParm::DEFAULT_ONE;

        if (Yii::app()->request->isAjaxRequest) {
            $show = ( int )Yii::app()->request->getParam('show');
            if (isset ($show) && ($show == DefaultParm::DEFAULT_ONE)) {
                echo 'got';
                exit ();
            }
            if (isset ($show) && ($show == DefaultParm::DEFAULT_TWO)) {
                echo 'got';
                exit ();
            }
            $done = ( int )Yii::app()->request->getParam('done');
            if (isset ($done) && ($done == DefaultParm::DEFAULT_ONE)) {
                echo 'got';
                exit ();
            }
            $finish = ( int )Yii::app()->request->getParam('finish');
            if (isset ($finish) && ($finish == DefaultParm::DEFAULT_ONE)) {
                echo 'got';
                exit ();
            }
        }

        $JOIN = '';
        $ON = '';
        $TW_IS_FAIL = '';
        if (isset ($_GET ['is_done']) && ($_GET ['is_done'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = ' AND at.t_status =  \'' . AskTask::STATUS_APRO . '\' ';
            $JOIN = ' JOIN app_task_when AS tw';
            $ON = ' AND tw.id = at.tw_id';
            $TW_IS_FAIL = ',tw.isfail';
            $show_check = DefaultParm::DEFAULT_THREE;
        }
        if (isset ($_GET ['is_finish']) && ($_GET ['is_finish'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = ' AND at.t_status =  \'' . AskTask::STATUS_ADONE . '\' ';
            $show_check = DefaultParm::DEFAULT_FOUR;
        }
        if (isset ($_GET ['isshow']) && ($_GET ['isshow'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = ' AND at.is_allow =  \'' . AskTask::IS_ALLOW_WAIT . '\' ';
            $show_check = DefaultParm::DEFAULT_TWO;
        }
        //上报任务
        if (isset ($_GET ['fail']) && ($_GET ['fail'] == DefaultParm::DEFAULT_ONE)) {
            $WHERE = ' AND at.t_status =  \'' . AskTask::STATUS_APRO . '\'  AND tw.isfail = \'' . TaskWhen::IS_FAIL_TRUE . '\' ';
            $JOIN = ' JOIN app_task_when AS tw';
            $ON = ' AND tw.id = at.tw_id';
            $show_check = DefaultParm::DEFAULT_THREE;
        }

        if (isset ($_GET ['fail']) && ($_GET ['fail'] == DefaultParm::DEFAULT_ZERO)) {
            $WHERE = ' AND at.t_status =  \'' . AskTask::STATUS_APRO . '\'  AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\' ';
            $JOIN = ' JOIN app_task_when AS tw';
            $ON = ' AND tw.id = at.tw_id';
            $show_check = DefaultParm::DEFAULT_THREE;
        }
        $manage_list = Task::model()->manageCheckList();

        $task_status = AskTask::model()->checkStatusByTask();

        $criteria = new CDbCriteria ();
        $sql = "SELECT at.*,m.name,m.id AS manage_id,mi.username,mi.category,mi.holder,
                mi.id AS member_id $TW_IS_FAIL
			    FROM app_ask_task AS at
				JOIN app_manage AS m
				JOIN app_member AS mi
				$JOIN
				ON at.m_id = mi.id AND m.id = at.f_id
				$ON
    			WHERE at.t_status != " . AskTask::STATUS_DEL . "
    			$WHERE
    			AND m.id != " . DefaultParm::DEFAULT_ZERO . " order by at.a_time desc ";

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($model);
        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $type='';
        foreach ($list as $key => $item) {
            if ($item ['type'] =5) {
                $type=5;
            }
            else{
                $type=0;
            }
        }
        $this->render('checklist', array('num' => $num, 'type' => $type, 'list' => $list, 'pages' => $pages, 'manage_list' => $manage_list,
            'show_check' => $show_check, 'task_status' => $task_status));
    }

    /**
     * @name 任务详情删除任务 —高级权限，
     */
    public function actionDelTaskByMsg()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $m_id = Yii::app()->request->getParam('m_id');
            $at_id = Yii::app()->request->getParam('at_id');
            $t_id = Yii::app()->request->getParam('t_id');
            $tw_id = Yii::app()->request->getParam('tw_id');
            $t = Yii::app()->db->beginTransaction();
            try {
                $task = Task::model()->findByAttributes(array('id' => $t_id));
                $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));
                $asktask = AskTask::model()->getAtByAtId($at_id);

                if (($taskwhen->score > TaskWhen::ZERO_STAR)) {

                    $task->status = Task::STATUS_DEL;
                    $task->update();

                    $asktask->t_status = AskTask::STATUS_DEL;
                    $asktask->update();

                    $member = Member::model()->findByPk($m_id);
                    $member->manage_id = DefaultParm::DEFAULT_ZERO;
                    $member->update();

                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOSCORE));
                    exit;
                }
                $t->commit();
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //收益发布失败
            }
        }
    }

    /**
     * @name 删除已完成的任务 ,释放用户 -高级权限
     */
    public function actionDelTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = Yii::app()->request->getParam('at_id'); //任务申请表id arr
            $del_list = AskTask::model()->getATidByATid($at_id);
            $mid_list = '';
            $tid_list = '';
            $twid_list = '';

            $id = Yii::app()->user->manage_id;
            $role = Manage::model()->getRoleByUid($id);
            if ($role > Role::SUPERVISOR) {
                echo AjaxBack::DATA_ERROR_NOPOWER;
                exit ();
            }

            foreach ($del_list as $key => $item) {
                if ($item ['t_status'] != AskTask::STATUS_ADONE) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NODONE));
                    exit (); //存在未完成的任务，不能删除
                } else {
                    $mid_list [$key] = $item ['m_id'];
                    $tid_list [$key] = $item ['t_id'];
                }
            }
            $tid_list = implode(",", $tid_list);

            $t = Yii::app()->db->beginTransaction();
            try {

                $sql3 = 'UPDATE app_task SET status = \'' . Task::STATUS_DEL . '\' WHERE FIND_IN_SET(id,\'' . $tid_list . '\') ';
                $res3 = Yii::app()->db->createCommand($sql3)->execute();

                $sql4 = 'SELECT status FROM app_task WHERE FIND_IN_SET(id,\'' . $tid_list . '\')';
                $t_list4 = Yii::app()->db->createCommand($sql4)->queryAll();

                foreach ($t_list4 as $key => $item) {
                    if ($item ['status'] != Task::STATUS_DEL) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_OTHER));
                        exit (); //任务修改失败
                    }
                }
                $count = '';
                $count1 = '';
                $count = count($mid_list);
                $count1 = count((explode(",", $at_id)));

                $mid_list = implode(",", $mid_list);

                $sql2 = 'UPDATE app_member SET manage_id = \'' . DefaultParm::DEFAULT_ZERO . '\' WHERE FIND_IN_SET(id,\'' . $mid_list . '\') ';
                $res2 = Yii::app()->db->createCommand($sql2)->execute();

                $res5 = AskTask::model()->updateAll(array('t_status' => AskTask::STATUS_DEL), " id in ( " . $at_id . " )  ");

                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                $t->commit();
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //收益发布失败
            }
        }
    }

    /**
     * 任务的详细信息，task——when表
     * @throws CHttpException
     * @name 审核任务的信息 - 高级权限
     */
    public function actionCheckOut()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/getScoreForTask.js');
        Script::registerScriptFile('manage/memberpool.controller/task.checklist.js');
        Script::registerScriptFile('manage/memberpool.controller/task.checkout.js');
        Script::registerCssFile('star.css');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id;
        $role = Manage::model()->getRoleByUid($id);
        if ($role > Role::SUPERVISOR) {
            throw new CHttpException (404, '你没有权限查看此页面.');
        }

        if (isset ($_GET ['id']) && ($_GET ['id'] != DefaultParm::DEFAULT_ZERO)) {
            $id = $_GET ['id'];
            $task_msg = TaskWhen::model()->getTaskMsgByTid($id);
            $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id);

            $this->render('checkout', array('task_msg' => $task_msg, 'role' => $role));
        } else {
            throw new CHttpException (404, '任务还未创建，请联系管理员.');
        }

    }


    /**
     * 任务评分，task_when
     * @name 任务评分ajax -高级权限
     */
    public function actionGetScore()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $score = ( int )Yii::app()->request->getParam('score'); //得分
            $publish = ( int )Yii::app()->request->getParam('publish'); //评分人
            $tw_id = ( int )Yii::app()->request->getParam('tw_id'); //task_when  id
            $t_id = ( int )Yii::app()->request->getParam('t_id'); //task  id
            $f_id = ( int )Yii::app()->request->getParam('f_id'); //上报此任务的客服id

            $task = Task::model()->findByAttributes(array('id' => $t_id));
            //如果没有被评分
            if (isset ($score) && (($score >= TaskWhen::ZERO_STAR))) {
                //查看当前任务状态
                $status = TaskWhen::model()->checkTaskIsPort($tw_id, $t_id);

                if ($status == DefaultParm::DEFAULT_ONE) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NO_PRO));
                    exit (); //客服还未提交任务

                } else if ($status == DefaultParm::DEFAULT_THREE) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_ADONE));
                    exit (); //任务已完成或已经上报

                } else if ($status == DefaultParm::DEFAULT_TWO) {

                    //如果是见习客服并且是回访任务，并且评分成功不是0分，则把回访任务收益插入客服的收益表中
                    $role = Manage::model()->getRoleByUid($f_id); //上报此任务客服的级别

                    if (($role == Role::PRACTICE_STAFF) && ($task->type == Task::TYPE_VISIT)) {
                        $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));
                        $taskwhen->pay_back = Salary::PRACTICE_STAFF_PAYBACK_VISITE;
                        $taskwhen->update();
                        $arr7 = TaskWhen::model()->updateTaskType($score, $publish, $tw_id, $t_id, $f_id);

                        if ($arr7 != DefaultParm::DEFAULT_ONE) {
                            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                            exit (); //评分失败

                        } else if ($arr7 == DefaultParm::DEFAULT_ONE) {
                            echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                            exit (); //评分失败
                        }
                    }
                    //评分，修改任务状态，并插入任务收益记录表中
                    $arr = TaskWhen::model()->updateTaskType($score, $publish, $tw_id, $t_id, $f_id);

                    if ($arr != DefaultParm::DEFAULT_ONE) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                        exit (); //评分失败

                    } else if ($arr == DefaultParm::DEFAULT_ONE) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                        exit (); //评分失败

                    }
                }

            }
        }

    }

    /**
     * 任务审核
     * @name 准许或拒绝任务 -高级权限
     */
    public function actionUpdeTaskType()
    {
        if (Yii::app()->request->isAjaxRequest) {

            $is_allow = ( int )Yii::app()->request->getParam('is_allow'); //传来的动作
            $at_id = ( int )Yii::app()->request->getParam('at_id'); //任务的id ，ask_task 表
            $mi_id = ( int )Yii::app()->request->getParam('mi_id'); //用户id
            $msg = Yii::app()->request->getParam('msg'); //任务的id ，ask_task 表
            $a_id = Yii::app()->user->manage_id;
            $time = time();

            $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
            $asktask->is_allow = $is_allow;
            $asktask->content = $msg;
            $asktask->allow_time = $time;
            $asktask->a_id = $a_id;
            $asktask->update();

            $role = Manage::model()->getRoleByUid($a_id);
            if ($role >= Manage::MANAGE_POWER) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOPOWER));
                exit ();
            }

            //拒绝任务
            if ($asktask->is_allow == AskTask::IS_ALLOW_FALSE) {

                $t = Yii::app()->db->beginTransaction();
                try {

//                    $sql3 = 'UPDATE app_ask_task SET t_status = \'' . AskTask::STATUS_DEL . '\'  WHERE id = \'' . $at_id . '\' ';
//                    Yii::app()->db->createCommand($sql3)->execute();
                    $del = AskTask::model()->delAsktaskById($at_id);

//                    $sql4 = 'UPDATE app_member SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE id = \'' . $mi_id . '\' ';
//                    Yii::app()->db->createCommand($sql4)->execute();
                    $release = Member::model()->delTaskReleaseMemberByMid($mi_id);

                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));

                    $t->commit();
                } catch (Exception $e) {
                    $t->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit (); //失败
                }

            } else if ($asktask->is_allow == AskTask::IS_ALLOW_TRUE) { //准许


                $tb = Yii::app()->db->beginTransaction();
                try {

                    //添加新任务
                    $model = new Task ();

                    $model->title = date('Y-m-d', time()) . '发布该任务';
                    $model->content = $msg;
                    $model->type = $asktask->type;
                    $model->accept = $asktask->f_id;
                    $model->publish = Yii::app()->user->manage_id;
                    $model->createtime = DateUtil::time();
                    $model->status = Task::STATUS_NORMAL;
                    $model->mid = $mi_id;
                    $model->availability = 1;
                    $model->insert();

                    $taskWhen = new TaskWhen ();
                    $taskWhen->tid = $model->id;
                    $taskWhen->createtime = $model->createtime;
                    $taskWhen->insert();

                    $asktask->t_id = $model->id;
                    $asktask->tw_id = $taskWhen->id;
                    $asktask->t_status = AskTask::STATUS_AASK;
                    $asktask->a_id = $model->publish;
                    $asktask->type = $model->type;
                    $asktask->allow_time = $time;
                    $asktask->update();

                    $tb->commit();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                } catch (Exception $e) {
                    $tb->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit ();
                }

            }
        }
    }
    /**
     * 任务审核
     * @name 准许或拒绝任务 -高级权限--有效回访审核
     */
    public function actionUpdeTaskVType()
    {
        if (Yii::app()->request->isAjaxRequest) {

            $is_allow = ( int )Yii::app()->request->getParam('is_allow'); //传来的动作
            $at_id = ( int )Yii::app()->request->getParam('at_id'); //任务的id ，ask_task 表
            $mi_id = ( int )Yii::app()->request->getParam('mi_id'); //用户id
            $msg = Yii::app()->request->getParam('msg'); //任务的id ，ask_task 表
            $a_id = Yii::app()->user->manage_id;
            $time = time();

            $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
            $asktask->is_allow = $is_allow;
            $asktask->content = $msg;
            $asktask->allow_time = $time;
            $asktask->a_id = $a_id;
            $asktask->update();

            $role = Manage::model()->getRoleByUid($a_id);
            if ($role >= Manage::MANAGE_POWER) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOPOWER));
                exit ();
            }

            //拒绝任务
            if ($asktask->is_allow == AskTask::IS_ALLOW_FALSE) {

                $t = Yii::app()->db->beginTransaction();
                try {

//                    $sql3 = 'UPDATE app_ask_task SET t_status = \'' . AskTask::STATUS_DEL . '\'  WHERE id = \'' . $at_id . '\' ';
//                    Yii::app()->db->createCommand($sql3)->execute();
                  // $upd = Task::model()->updAskById($at_id);

                    //$asktask2 = Task::model()->findByAttributes(array('id' => $at_id));
                    $sql='SELECT t_id from app_ask_task WHERE id = \'' . $at_id . '\' ';
                    $res= Yii::app()->db->createCommand($sql)->queryAll();

                    $t_id=$res[0]["t_id"];

                    $sql3 = 'UPDATE app_task SET availability = 2,status=0  WHERE id = \'' . $t_id . '\' ';
                    Yii::app()->db->createCommand($sql3)->execute();


                    $del = AskTask::model()->updAsktaskById($at_id);


//                    $sql4 = 'UPDATE app_member SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE id = \'' . $mi_id . '\' ';
//                    Yii::app()->db->createCommand($sql4)->execute();


                   //有效回访不需要删除客服id
                   //$release = MemberInfo::model()->delTaskReleaseMemberByMid($mi_id);

                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));

                    $t->commit();
                } catch (Exception $e) {
                    $t->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit (); //失败
                }

            } else if ($asktask->is_allow == AskTask::IS_ALLOW_TRUE) { //准许


                $tb = Yii::app()->db->beginTransaction();
                try {

                    //添加新任务
                    $model = new Task ();

                    $model->title = date('Y-m-d', time()) . '发布该任务';
                    $model->content = $msg;
                    $model->type = $asktask->type;
                    $model->accept = $asktask->f_id;
                    $model->publish = Yii::app()->user->manage_id;
                    $model->createtime = DateUtil::time();
                    $model->status = Task::STATUS_NORMAL;
                    $model->mid = $mi_id;
                    $model->availability = 1;
                    $model->insert();

                    $taskWhen = new TaskWhen ();
                    $taskWhen->tid = $model->id;
                    $taskWhen->createtime = $model->createtime;
                    $taskWhen->insert();

                    $asktask->t_id = $model->id;
                    $asktask->tw_id = $taskWhen->id;
                    $asktask->t_status = AskTask::STATUS_AASK;
                    $asktask->a_id = $model->publish;
                    $asktask->type = $model->type;
                    $asktask->availability = 1;

                    $this14=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))+14*3600;
                    //周一14点前批准的时间改为前一天的时间
                    if($time<$this14)
                    {
                        $allow_time=$time-24*3600;
                    }
                    else
                    {
                        $allow_time=$time;
                    }


                    $asktask->allow_time = $allow_time;
                    $asktask->update();

                    $tb->commit();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                } catch (Exception $e) {
                    $tb->rollback();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit ();
                }

            }
        }
    }
    /**
     * @name 准许或拒绝任务2 -高级权限
     */
    public function actionUpdeTaskTypeAll()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $is_allow = ( int )Yii::app()->request->getParam('allow'); //传来的动作
            $at_id_list = Yii::app()->request->getParam('tid'); //任务的id ，ask_task 表 arr
            $mi_id_list = Yii::app()->request->getParam('mid'); //用户id 		arr
            $msg = Yii::app()->request->getParam('msg'); //驳回任务的回复内容

            $is = AskTask::model()->getATidByATid($at_id_list);

            $array = '';

            foreach ($is as $it) {
                if ($it ['is_allow'] == AskTask::IS_ALLOW_WAIT) {
                    $array [] += $it ['id'];
                }
            }
            if (empty ($array)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOEXISTS));
                exit (); //没有需要批准的任务
            }
            $arr = array();
            $arr1 = '';
            $str = '';
            $str1 = '';
            if ($is_allow == AskTask::IS_ALLOW_TRUE) {
                foreach ($is as $key => $val) {
                    if ($val ['is_allow'] == AskTask::IS_ALLOW_WAIT) { //如果是待审核任务

                        $model = new Task ('insert');
                        $model->title = date('Y-m-d', time()) . '发布该任务';
                        $model->content = $msg;
                        $model->type = $val ['type'];
                        $model->accept = $val ['f_id'];
                        $model->publish = Yii::app()->user->manage_id;
                        $model->createtime = DateUtil::time();
                        $model->status = Task::STATUS_NORMAL;
                        $model->availability = 1;
                        $model->mid = $val ['m_id'];
                        $model->insert();

                        $taskWhen = new TaskWhen ('insert');
                        $taskWhen->tid = $model->id;
                        $taskWhen->createtime = $model->createtime;
                        $taskWhen->insert();

                        $arr [$key] = $val ['id'];
                        $res = AskTask::model()->updateAtMsgById($is_allow, $model->id, $taskWhen->id, $msg, $val ['id']);
                    }
                }

                if ($res == DefaultParm::DEFAULT_ONE) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                    exit (); //准许
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit (); //失败
                }

            } else if ($is_allow == AskTask::IS_ALLOW_FALSE) {

                foreach ($is as $item) {
                    if ($item ['is_allow'] == AskTask::IS_ALLOW_WAIT) { //如果是待审核任务
                        $arr [] += $item ['id'];
                        $arr1 [] += $item ['m_id'];
                    }
                }
                $count = count($arr);

                $str = implode(",", $arr);
                $str1 = implode(",", $arr1);

//                $sql = 'UPDATE app_ask_task SET content = \'' . $msg . '\',allow_time = \'' . time() . '\',
//                        t_status = \''.AskTask::STATUS_DEL.'\',is_allow = \'' . $is_allow . '\' WHERE FIND_IN_SET(id,\'' . $str . '\')';
//                $res = Yii::app()->db->createCommand($sql)->execute();
                $res = AskTask::model()->delAsktaskByIdList($msg, $is_allow, $str);

//                $sql1 = 'UPDATE app_member SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE FIND_IN_SET(id,\'' . $str1 . '\') ';
//                $res1 = Yii::app()->db->createCommand($sql1)->execute();
                $res1 = Member::model()->delTaskReleaseMemberByMidList($str1);

                if (($count == $res) && ($count == $res1)) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                    exit (); //成功
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                    exit (); //失败
                }

            }

        }
    }

    /**
     * @name 批量审核任务 -高级权限
     */
    public function actionCheckAllTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_list = Yii::app()->request->getParam('list'); //aid string
            $mid_list = Yii::app()->request->getParam('mid_list'); //mid string
            $score = Yii::app()->request->getParam('score'); //score array

            //查看发布人权限
            $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id);
            if ($role > 3) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOPOWER));
                exit (); //没有权限

            } else {
                //评分人id
                $scoreuid = Yii::app()->user->manage_id;
            }

            //如果没有评分，则退出
            if (!isset ($score) || (($score == TaskWhen::ZERO_STAR))) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NOSCORE));
                exit (); //没有评分
            }


            $sql = 'SELECT id,t_id,tw_id,f_id,type,t_status FROM app_ask_task WHERE FIND_IN_SET(id,\'' . $at_list . '\')';
            $id_list = Yii::app()->db->createCommand($sql)->queryAll();

            //id参数 数组
            $param = '';
            foreach ($id_list AS $key => $item) {
                $param[$key] = $item['id'] . ',' . $item['t_id'] . ',' . $item['tw_id'] . ',' . $item['type'] . ',' .
                    $item['t_status'] . ',' . $item['f_id'];
            }

            //验证是否可以评分
            $value = '';
            $sql_t_status = '';
            $sql_tw_status = '';

            foreach ($param AS $ke => $val) {
                $value[$ke] = explode(',', $param[$ke]);
                if (!empty ($sql_t_status)) {
                    $sql_t_status .= ' union ALL ';
                }
                if (!empty ($sql_tw_status)) {
                    $sql_tw_status .= ' union ALL ';
                }

                //task status
                $sql_t_status .= 'SELECT status  FROM app_task WHERE id= \'' . $value[$ke][1] . '\' ';

                //task_when status
                $sql_tw_status .= 'SELECT status  FROM app_task_when WHERE id= \'' . $value[$ke][2] . '\' ';

                //判断客服权限，如果是见习客服并且是回访任务，提交任务收益
                $role = Manage::model()->getRoleByUid($value[$ke][5]); //上报此任务客服的级别
                if (($role == Role::PRACTICE_STAFF) && ($value[$ke][3] == Task::TYPE_VISIT)) {
                    $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $value[$ke][2]));
                    $taskwhen->pay_back = Salary::PRACTICE_STAFF_PAYBACK_VISITE;
                    $taskwhen->update();
                }

            }

            $t_sql_t_status = "SELECT status  FROM" . " ($sql_t_status) AS a";
            $t_status = Yii::app()->db->createcommand($t_sql_t_status)->queryAll();

            $tw_sql_tw_status = "SELECT status  FROM" . " ($sql_tw_status) AS a";
            $tw_status = Yii::app()->db->createcommand($tw_sql_tw_status)->queryAll();

            $status['t_status'] = $t_status;
            $status['tw_status'] = $tw_status;

            $is_por = 1;
            foreach ($status AS $ite) {
                foreach ($ite AS $it) {
                    if ($it['status'] != 1) {
                        $is_por = 0;
                    }
                }
            }

            //是已上报的任务，则继续进行修改任务状态
            if ($is_por == 1) {

                $va = '';
                $res = '';

                foreach ($param AS $k => $i) {

                    $va[$k] = explode(',', $param[$k]);
                    $res[$va[$k][0]] = TaskWhen::model()->updateTaskType($score, $scoreuid, $va[$k][2], $va[$k][1], $va[$k][5]);

                    if ($res[$va[$k][0]] != DefaultParm::DEFAULT_ONE) {

                        $sql_error = 'SELECT mi.username FROM app_member AS mi
                                      JOIN app_ask_task AS at
                                      ON mi.id = at.m_id
                                      WHERE at.id =  \'' . $va[$k][0] . '\' ';
                        $error_member = Yii::app()->db->createcommand($sql_error)->queryAll();
                        $error_member = $error_member[0]['m_id'];

                        echo CJSON::encode(array('msg' => $error_member));
                        exit (); //评分失败
                    }
                }

                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                exit (); //评分成功

            } else {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_NO_PRO));
                exit (); //不是以上报的任务
            }

        }
    }

    /**
     * 获取下级用户列表
     * @return array
     */
    private function getRoleDownList()
    {
        $role = Yii::app()->user->getState('role');
        $manageList = Role::model()->getChildManageList($role);
        $downList = array();
        foreach ($manageList as $manage) {
            /** @var $manage Manage */
            if (empty ($manage->r)) {
                continue;
            }
            $_id = $manage->id;
            $downList [$_id] = $manage->name . '(' . $manage->r->name . ')';
        }
        return $downList;
    }

    /**
     * @throws CHttpException
     * @name 任务查询 -高级权限
     */
    public function actionShowTaskList()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.controller/task.checkout.js');
        Script::registerScriptFile('manage/memberinfo.controller/memberinfo.category.js');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id;
        $role = Manage::model()->getRoleByUid($id);
        if ($role > Role::SUPERVISOR) {
            throw new CHttpException (404, '你没有权限查看此页面.');
        }
        /*****************基础列表-开始********************/
        $manage_list = Manage::model()->getManageList();
        $tasks_type = Task::getTypeList2(Task::TYPE_NEW, /*Task::TYPE_DROP, */Task::TYPE_VISIT);
        /*****************基础列表-结束********************/


        /*****************查询参数-开始********************/

        $manage_id       = isset($_GET['manage_id']) && ($_GET['manage_id'] != '') ? $_GET['manage_id'] : '';
        $task_type       = isset($_GET['task_type']) && ($_GET['task_type'] != '') ? $_GET['task_type'] : '';
        $task_status     = isset($_GET['task_status']) && ($_GET['task_status'] != '') ? $_GET['task_status'] : '';
        $member_name     = isset($_GET['member_name']) && ($_GET['member_name'] != '') ? $_GET['member_name'] : '';


        $task_s['start'] = isset($_GET['task_sendtime_start']) && ($_GET['task_sendtime_start'] != '') ?
            $_GET['task_sendtime_start'] : '0';
        $task_s['end']   = isset($_GET['task_sendtime_end']) && ($_GET['task_sendtime_end'] != '')
            ? $_GET['task_sendtime_end'] : '0';


        $task_p['start']    = isset($_GET['task_protime_start']) && ($_GET['task_protime_start'] != '')
            ? $_GET['task_protime_start'] : '0';
        $task_p['end']      = isset($_GET['task_protime_end']) && ($_GET['task_protime_end'] != '')
            ? $_GET['task_protime_end'] : '0';

       // echo $task_s['start'].'++++'.$task_s['end'].'</br>';
        /*****************查询参数-结束********************/


        $OPTION = array();
        $TABLE = array();
        $WHERE = '';

        $TABLE['asktask'] = 'app_ask_task AS at ';
        $TABLE['task'] = 'app_task AS t ';
        $TABLE['taskwhen'] = 'app_task_when AS tw ';
        $TABLE['member'] = 'app_member AS mi ';
        $TABLE['manage'] = 'app_manage AS ma ';
        $AND  = '';

        if (($manage_id != '') || ($task_type != '') || ($task_status != '') ||
            ($member_name != '') || ($task_p['end'] != '0') || ($task_p['start'] != '0') ||
            ($task_s['end'] != '0') || ($task_s['start'] != '0')
        ) {

            $WHERE = ' WHERE at.m_id = mi.id ';
        }


        $OPTION = Task::model()->changeSearchOption($manage_id, $task_type, $task_status, $TABLE, $member_name,
                                                $task_s['start'],$task_s['end'],$task_p['start'], $task_p['end']);

        $criteria = new CDbCriteria ();
        $sql = "SELECT " .$OPTION['FIND'] ."
            FROM " . $TABLE['asktask'] .
            $OPTION['JOIN'] . $OPTION['ON'] ."
            $WHERE  " .
            $AND . $OPTION['manage_id'] . " " .
            $OPTION['task_type'] .
            $OPTION['task_status'] .
            $OPTION['member_name'] .
            $OPTION['task_sta_time'].
            $OPTION['task_pro_time'];

            $model = Yii::app()->db->createCommand($sql)->queryAll();
        $sum = '';
        foreach($model AS $key=>$item){
            $sum += $item['pay_back'];
        }

            $num = count($model);
            $pages = new CPagination (count($model));
            $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
            $pages->applylimit($criteria);
            $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
            $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
            $model->bindValue(':limit', $pages->pageSize);
            $data = $model->queryAll();

        $params = array(
            'sum'=>$sum,
            'num' =>$num,
            'pages' => $pages,
            'data' => $data,
            'manage_list' => $manage_list,
            'tasks_type' => $tasks_type
        );

        $this->render('showtasklist', $params);
    }

    /**
     * @throws CHttpException
     * @name 清除一个客服的所有任务信息 --最高权限
     */
    public function actionManageMsgToDel()
    {

        $manageId = $_GET['manage_id_to_del'];

        $res = Task::model()->delManageAllMsgAndTasks($manageId);
        if ($res == 1) {

            throw new CHttpException (404, 'success.');
        } else {

            throw new CHttpException (404, 'error.');
        }

    }
}