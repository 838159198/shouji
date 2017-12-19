<?php

/**
 * @name 我的任务
 */
class MytaskController extends DhadminController
{
    /**
     *
     * @name 见习客服任务处理 -普通权限
     */
    public function actionStaffProVisiteTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = (int)Yii::app()->request->getParam('id'); //atid int
            $msg = Yii::app()->request->getParam('msg'); //atid int
            $t = Yii::app()->db->beginTransaction();
            try {
                $model = AskTask::model()->findByAttributes(array('id' => $at_id));
                $model->t_status = AskTask::STATUS_APRO;
                $model->content = $msg;
                $model->update();

                $task = Task::model()->findByAttributes(array('id' => $model->t_id));
                $task->status = Task::STATUS_SUBMIT;
                $task->update();

                $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $model->tw_id));
                $taskwhen->porttime = time();
                $taskwhen->content = $msg;
                $taskwhen->status = TaskWhen::STATUS_SUBMAIT;
                $taskwhen->update();

                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //操作成功

            } catch (Exception $e) // 如果有一条查询失败，则会抛出异常
            {
                $t->rollBack();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //操作失败
            }
        }

    }


    /**
     * @param $id
     * @name 周任务列表 -普通权限
     */
    public function actionWeekly()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/mytask.weekly.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');

        $id = isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id
        $uid = Yii::app()->user->manage_id;
        if ($id == $uid) {
            $now = 1;
        } else {
            $now = 0;
        }
        $role = Manage::model()->getRoleByUid($uid);

        if ($role > Role::SUPPORT_STAFF) {
            throw new CHttpException (404, '你没有权限这么做.');
        }

        $title = isset($_GET['week']) ? $_GET['week'] : WeekTask::LAST_WEEK;
        $TIME = '';
        $STATUS = '  AND wt.status = \'' . WeekTask::STATUS_NORMAL . '\' ';
        $ORDER = '  ORDER BY wt.id ASC';
        $time = time();
        $createtime = 'createtime';
        $endtime = 'endtime';
        $WEEKTASKTIME = array();
        $ar = array();

        //本周周任务的开始，结束时间 array
        $this_week = WeekTask::model()->getThisWeekWtTask($id);

        //本周任务开始时间的格式化
        $date_time = date('Y-m-d H:i:s l', $this_week['c_time']);

        //获取本周任务开始时间前的上个周六的日期
        $sat = WeekTask::model()->getlastSatday($date_time);

        switch ($title) {

            case WeekTask::LAST_WEEK:
                $TIME = '  AND wt.endtime = \'' . $this_week['c_time'] . '\'  ';
              //  echo $TIME;exit;
                $STATUS = '';
                $WEEKTASKTIME = WeekTask::model()->lastWeekTaskMsg($this_week['c_time'], $time, $id, $endtime, $createtime);

                break;
            case WeekTask::THIS_WEEK:
                $TIME = '  AND wt.createtime = \'' . $this_week['c_time'] . '\'  ';
                $STATUS = '  AND wt.status = \'' . WeekTask::STATUS_NORMAL . '\' ';

                $WEEKTASKTIME = WeekTask::model()->thisWeekTaskMsg($this_week['c_time'], $time, $id, $endtime, $createtime);

                break;
            case WeekTask::NEXT_WEEK:
                $TIME = '  AND wt.createtime = \'' . $this_week['e_time'] . '\' ';
                $STATUS = '  AND wt.status = \'' . WeekTask::STATUS_NORMAL . '\' ';

                $WEEKTASKTIME = WeekTask::model()->nextWeekTaskMsg($this_week['e_time'], $time, $id, $endtime, $createtime);
                break;
            default:
                $TIME = '  AND wt.endtime = \'' . $this_week['c_time'] . '\'  ';
                $STATUS = '';
                $WEEKTASKTIME = WeekTask::model()->lastWeekTaskMsg($this_week['c_time'], $time, $id, $endtime, $createtime);

        }

        $criteria = new CDbCriteria ();

        $sql = 'SELECT wt.id,wt.status,wt.m_id,wt.createtime,wt.endtime,wt.at_id,mi.username,wt.is_continue
                FROM app_week_task AS wt
				JOIN app_member AS mi
				ON mi.id  = wt.m_id 
				WHERE wt.f_id = \'' . $id . '\' ' . $STATUS . $TIME . $ORDER;



        $model = Yii::app()->db->createCommand($sql)->queryAll();

        //获取上周任务开始时间前的上个周六的日期
        $last_sat = WeekTask::model()->getlastSatday(date('Y-m-d', $model[0]['endtime']));

        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();

        $pram = array(
            'pages' => $pages,
            'list' => $list,
            'title' => $title,
            'sat' => $sat,
            'WEEKTASKTIME' => $WEEKTASKTIME,
            'ar' => $ar,
            'last_sat' => $last_sat,
            'now' => $now
        );

        $this->render('weekly', $pram);
    }
    /**
     * @name 周任务重新计算
     */
    public function actionUpdateWeekTask(){
        $id = Yii::app()->user->manage_id;
        //本周周任务的开始，结束时间 array
        // 也是上周任务的结束时间
        $this_week = WeekTask::model()->getThisWeekWtTask($id);
        $end_time = $this_week['c_time'];
        //周任务有效任务数量
        $con_count = WeekTask::model()->getConCountByEndTime($id, $end_time);

        //全部任务数量
        $total_count = WeekTask::model()->getCountByEndTime($id, $end_time, "endtime");
        $total_count = $total_count[0]['count'];
        $total = $total_count;
        //有效率
        $Conformity = round((($con_count / $total) * 100), 3);
        //测试使用，伪装成真实数据
        /*$con_count =6;
        $Conformity = 16;*/
        //钱
        $detuct = DefaultParm::DEFAULT_HUNDRED;//100
        //当前客服的等级
        $role = Manage::model()->findByPk($id);
        //任务失败
        if ($Conformity < WeekTask::MIN_VALID) {
            //客服等级，是高级客服，计算扣款金额
            if ($role->role <= Role::ADVANCED_STAFF) {

                //任务合格率无效，有2个任务合格
                if ($con_count == 2) {
                    $detuct = $detuct;
                } else if ($con_count == 1) {
                    $detuct = 2 * $detuct;
                } else if ($con_count == 0) {
                    $detuct = 3 * $detuct;
                } else {
                    $detuct = 0;
                }

                //客服等级是普通客服，扣款金额为0
            } elseif ($role->role > Role::ADVANCED_STAFF) {
                $detuct = DefaultParm::DEFAULT_ZERO;
            }
            $sql2 = 'UPDATE app_week_task set is_pro =  \'' . WeekTask::WEEK_TASK_ISPRO_TRUE . '\' ,
                            status = \'' . WeekTask::STATUS_DELETE . '\'  WHERE f_id = \'' . $id . '\'
                            AND  endtime = \'' . $end_time . '\' ';
            $res = Yii::app()->db->createCommand($sql2)->execute();

            $detuct = 0 - $detuct;
            $model = WeekTask::model()->findAllByAttributes(array('endtime' => $end_time, 'f_id' => $id));
            $model2 = TaskWeekEarnings::model()->findByAttributes(array('createtime' => $model[0]->createtime, 'uid' => $id));

            //测试后没有问题再去掉注释部分
            if (isset($model2->id)) {
                $model2->percent  = $Conformity;
                $model2->concount = $con_count;
                $model2->askcount = $total;
                $model2->payback  = $detuct;
                $model2->update();
            } else {
                TaskWeekEarnings::model()->InsertWeekEarnings($id, $model[0]->createtime, $model[0]->endtime, $Conformity, $con_count, $total, $detuct);
            }
            //任务合格
        }elseif($Conformity >= WeekTask::MIN_VALID){
            if ($role->role <= Role::ADVANCED_STAFF) {
                if ($con_count  == 3) {
                    $detuct = DefaultParm::DEFAULT_ZERO;
                } else if ($con_count  == 4) {
                    $detuct = WeekTask::PAYBACK_MIN;
                } else if ($con_count  == 5) {
                    $detuct = WeekTask::PAYBACK_MIDDLE;
                } else if ($con_count  >= 6) {
                    $detuct = WeekTask::PAYBACK_MAX;
                }
            } elseif ($role->role > Role::ADVANCED_STAFF) {
                $detuct = DefaultParm::DEFAULT_ZERO;
            }
            $sql2 = 'UPDATE app_week_task set is_pro =  \'' . WeekTask::WEEK_TASK_ISPRO_TRUE . '\'
                                WHERE f_id = \'' . $id . '\' AND  endtime = \'' . $end_time . '\' ';
            $res = Yii::app()->db->createCommand($sql2)->execute();

            $model = WeekTask::model()->findAllByAttributes(array('endtime' => $end_time, 'f_id' => $id));

            $model2 = TaskWeekEarnings::model()->findByAttributes(array('createtime' => $model[0]->createtime, 'uid' => $id));

            if (isset($model2->id)) {
                $model2->percent  = $Conformity;
                $model2->concount = $con_count;
                $model2->askcount = $total;
                $model2->payback  = $detuct;
                $model2->update();
            } else {
                TaskWeekEarnings::model()->InsertWeekEarnings($id, $model[0]->createtime, $model[0]->endtime, $Conformity, $con_count, $total, $detuct);
            }
        }
/*
        echo "有效数量：{$con_count}";
        echo "<br>";
        echo "申请数量：{$total_count}";
        echo "<br>";
        echo "有效率：{$Conformity}%";
        echo "<br>";
        echo "周任务收益：{$detuct}";
        echo "<br>";
        echo $end_time;*/
        //print_r($model);
    }

    /**
     * @throws CHttpException
     * @name 查询上一周周任务-见习以上权限
     */
    public function actionShowlastWeekTask(){
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.controller/mytask.weekly.js');
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');

        $id = isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->manage_id; //传递过的客服的id或者当前登录的客服id

        $uid = Yii::app()->user->manage_id;
        if ($id == $uid) {
            $now = 1;
        } else {
            $now = 0;
        }

        $role = Manage::model()->getRoleByUid($uid);

        if ($role > Role::SUPPORT_STAFF) {
            throw new CHttpException (404, '你没有权限这么做.');
        }

        if(isset($_GET['lastweek']) && ($_GET['lastweek'] != '')){

            $time_s= $_GET['lastweek'];
            $time = 'endtime';
        }elseif(isset($_GET['nextweek']) && ($_GET['nextweek'] != '')){

            $time_s = $_GET['nextweek'];
            $time = 'createtime';
        }else{

            throw new CHttpException (404, '不存在查找的日期');
        }

        $createtime = 'createtime';
        $WEEKTASKTIME = WeekTask::model()->lastWeekTaskMsg2($time_s, time(), $id, $time, $createtime);

        $sql = "SELECT  "."  wt.*,mi.username FROM app_week_task AS wt
                JOIN app_member AS mi
                ON wt.m_id = mi.id WHERE f_id = $id
                AND $time = $time_s
                AND wt.status !=".WeekTask::STATUS_NORMAL;

        $data_l = Yii::app()->db->createcommand($sql)->queryAll();
        if(isset($data_l[0]['id'])){
            $num = count($data_l);
            $this->render('lastweektask',array('model' => $data_l ,'num' => $num ,'WEEKTASKTIME' =>$WEEKTASKTIME));
        }else{
            throw new CHttpException (404, '不存在查找的日期');
        }
        
    }

    /**
     * @param $id
     * @name 跟进周任务 -普通权限
     */
    public function actionContinue()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $wt_id = Yii::app()->request->getParam('wt_id');
            $t = Yii::app()->db->beginTransaction();
            try {
                $week_task = WeekTask::model()->findByPk($wt_id);
                $member = Member::model()->findByPk($week_task->m_id);
                $week_task->is_continue = DefaultParm::DEFAULT_ONE;
                $week_task->update();

                //查找本周周任务数量，开始/结束时间
                $num = WeekTask::model()->getCountByEndTime($week_task->f_id, 'createtime', $week_task->endtime);
                $count = $num[0]['count'];

                //查找上周周任务是否已经添加到了本周周任务列表
                $num1 = WeekTask::model()->getCountByEndTimeIsOne($week_task->f_id, $week_task->at_id,
                    $num[0]['createtime'], WeekTask::STATUS_NORMAL);

                //查找下周周任务数量
                $num2 = WeekTask::model()->getCountByEndTimeANDStatus($week_task->f_id, $num[0]['endtime'], WeekTask::STATUS_NORMAL);

                //查找上周周任务是否已经添加到了下周周任务列表
                $num3 = WeekTask::model()->getCountByEndTimeIsOne($week_task->f_id, $week_task->at_id, $num2[0]['createtime'], WeekTask::STATUS_NORMAL);

                //如果本周任务数量达到上限，则查看下周周任务的数量
                if (($count >= WeekTask::TOTAL) && ($num1[0]['count'] != DefaultParm::DEFAULT_ONE)) {
                    //如果下周也达到上限，则跟进添加失败
                    if ($num2[0]['count'] >= WeekTask::TOTAL) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_CEILING)); //下周任务数量已达上限
                        exit;
                    } else {
                        //如果下周任务中没有存在这个用户
                        if ($num3[0]['count'] >= DefaultParm::DEFAULT_ONE) {
                            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS)); //任务以存在
                            exit;
                        } else {
                            $next_monday = WeekTask::model()->getNextNextMonday();
                            $week = new WeekTask();
                            $week->f_id = $week_task->f_id;
                            $week->m_id = $week_task->m_id;
                            // $week->createtime = $num2[0]['createtime'];
                            $week->createtime = $num[0]['endtime'];
                            // $week->endtime = $num2[0]['endtime'];
                            $week->endtime = $next_monday['time'];
                            $week->at_id = $week_task->at_id;
                            $week->status = WeekTask::STATUS_NORMAL;
                            $res = $week->insert();
                            $member->wt_id = $week->id;
                            $member->update();
                            $add_week = 'next_week';
                        }
                    }
                } else {
                    if ($num1[0]['count'] == DefaultParm::DEFAULT_ONE) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS)); //任务以存在
                        exit;
                    } else {
                        $week = new WeekTask();
                        $week->f_id = $week_task->f_id;
                        $week->m_id = $week_task->m_id;
                        $week->createtime = $num[0]['createtime'];
                        $week->endtime = $num[0]['endtime'];
                        $week->at_id = $week_task->at_id;
                        $week->status = WeekTask::STATUS_NORMAL;
                        $res = $week->insert();
                        $member->wt_id = $week->id;
                        $member->update();
                        $add_week = 'this_week';
                    }
                }
                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS, 'week' => $add_week)); //跟进成功

            } catch (Exception $e) {

                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //跟进失败
            }

        }
    }

    /**
     * @internal param $id
     * @name 添加周任务 -普通权限
     */
    public function actionAddWeekTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $at_id = Yii::app()->request->getParam('at_id'); //atid int
            $id = Yii::app()->user->manage_id;

            //本周周任务 如果不存在，则默认本周一至下周周一时间节点
            $this_week = WeekTask::model()->getThisWeekWtTask($id);

            //本周周任务开始时间的下午14点
            $week_start_pm = $this_week['c_time'] + 14 * 3600;

            //本周周任务结束时间格式化
            $this_end = date('Y-m-d', $this_week['e_time']);

            //根据本周任务结束时间，获得下周任务开始的时间与下周任务结束时间（最近的一个周一）
            $next_monday['date'] = WeekTask::model()->getMonday($this_end);
            $next_monday['time'] = strtotime($next_monday['date']);

            //当前时间date
            $this_time['date'] = date('Y-m-d H:i:s', time());
            $this_time['time'] = time();

            //周一下午14:00之前标记任务，为本周的任务
            $date_start = '';
            $date_end = '';
            if ($this_time['time'] <= $week_start_pm) {
                $date_start = $this_week['c_time'];
                $date_end = $this_week['e_time'];
            } else {
                $date_start = $this_week['e_time'];
                $date_end = $next_monday['time'];
            }

            $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));

            $memberInfo = Member::model()->findByPk($asktask->m_id);
            //本周周任务已存在的数量
            $sql = 'SELECT COUNT(id) AS count FROM app_week_task WHERE f_id = \'' . $id . '\' AND
                    createtime = \'' . $this_week['c_time'] . '\' ';
            $this_week_task_count = Yii::app()->db->createCommand($sql)->queryAll();
            $this_week_task_count = $this_week_task_count[0]['count'];
            //如果任务已经存在
            if (($memberInfo->wt_id != DefaultParm::DEFAULT_ZERO) && (!empty($memberInfo->wt_id))) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS));
                exit;
            } else {
                //添加周任务
                $t = Yii::app()->db->beginTransaction();
                try {
                    $model = new WeekTask('insert');
                    $model->f_id = $asktask->f_id;
                    $model->m_id = $asktask->m_id;;
                    $model->createtime = $date_start;
                    $model->endtime = $date_end;
                    $model->at_id = $asktask->id;
                    $model->status = WeekTask::STATUS_NORMAL;
                    $model->insert();

                    $memberInfo->wt_id = $model->id;
                    $memberInfo->update();

                    $t->commit();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //周任务添加成功

                } catch (Exception $e) {
                    $t->rollBack();
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //周任务申请失败
                }
            }

        }
    }

    /**
     * @param $id
     * @throws CHttpException
     * @name 修改结束时间1 -高级权限
     */
    public function actionUpdateWeekTaskEndTime1()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $end_date = Yii::app()->request->getParam('end_time'); //atid int
            $start_date = Yii::app()->request->getParam('start_time'); //atid int

            $end_time = strtotime($end_date);
            $start_time = strtotime($start_date);

            $start = date('Y-m-d', $start_time);
            $end = date('Y-m-d', $end_time);

            echo CJSON::encode(array('start' => $start, 'end' => $end)); //操作成功
        }
    }

    /**
     * @param $id
     * @throws CHttpException
     * @name 修改结束时间2 -高级权限
     */
    public function actionUpdateWeekTaskEndTime2()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $end = Yii::app()->request->getParam('end'); //date
            //$start = Yii::app()->request->getParam('start'); //date
            $end_time = strtotime($end);
            $id = Yii::app()->user->manage_id;

            $t = Yii::app()->db->beginTransaction();
            try {
                $this_week = WeekTask::model()->getThisWeekWtTask($id);

                //下周周任务
                $next_week_task = WeekTask::model()->getWeekTaskByTime('createtime', $this_week['e_time']);

                if (empty($next_week_task)) {

                    $next_week_task_createtime = WeekTask::model()->getMinCreatetimeByTime(time());

                    $next_week_task_createtime =
                        !empty($next_week_task_createtime[0]['createtime']) ?
                            $next_week_task_createtime[0]['createtime'] :
                            $this_week['e_time'];

                    $next_week_task = WeekTask::model()->getWeekTaskByTime('createtime', $next_week_task_createtime);
                }
                //上周周任务
                //    $last_week_task = WeekTask::model()->getWeekTaskByTime('endtime', $this_week['c_time']);

                //修改本周任务的结束时间
                $count = WeekTask::model()->updateAll(array('endtime' => $end_time), 'createtime=:start',
                    array(':start' => $this_week['c_time']));
                //存在本周周任务
                if ($count > DefaultParm::DEFAULT_ZERO) {

                    //距离本周任务结束时间的下个周一
                    $next_week_mon = array();
                    $next_week_mon['date'] = WeekTask::model()->getMonday($end);
                    $next_week_mon['time'] = strtotime($next_week_mon['date']);
                    // 本周的结束时间     = 下一周开始时间
                    if (!empty($next_week_task)) {
                        $arr = array();
                        $str = '';
                        foreach ($next_week_task as $key => $item) {
                            $arr[$key] = $item['id'];
                        }
                        $str = implode(',', $arr);
                        $model = WeekTask::model()->updateAll(array('createtime' => $end_time, 'endtime' => $next_week_mon['time']),
                            " id in ( " . $str . " ) ");
                    }
                    // 本周的开始时间     = 上一周结束时间
//                    if (!empty($last_week_task)) {
//                        $arr = array();
//                        $str = '';
//                        foreach ($last_week_task as $ke => $val) {
//                            $arr[$key] = $val['id'];
//
//                        }
//                        $str = implode(',', $arr);
//                        $model1 = WeekTask::model()->updateAll(array('endtime' => $this_week['c_time']), " id in ( " . $str . " ) ");
//                        echo $model1;exit;
//                    }
                } else {
                    $next_week_mon = array();
                    $next_week_mon['date'] = WeekTask::model()->getMonday($end);
                    $next_week_mon['time'] = strtotime($next_week_mon['date']);
                    if (!empty($next_week_task)) {

                        $arr = array();
                        $str = '';
                        foreach ($next_week_task as $key => $item) {
                            $arr[$key] = $item['id'];
                        }
                        $str = implode(',', $arr);
                        $model = WeekTask::model()->updateAll(array('createtime' => $end_time, 'endtime' => $next_week_mon['time']),
                            " id in ( " . $str . " ) ");
                    }

                }
                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            }

        }
    }

}