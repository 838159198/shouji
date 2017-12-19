<?php

/**
 * 任务进度管理
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-28 上午11:15
 * @name 任务进度管理
 */
class TaskWhen extends CActiveRecord
{

    /** 正常 */
    const STATUS_NORMAL = DefaultParm::DEFAULT_ZERO;
    /** 已上报 */
    const STATUS_SUBMAIT = DefaultParm::DEFAULT_ONE;
    /** 打回修改 */
    const STATUS_ROLLBACK = DefaultParm::DEFAULT_TWO;

    /** 任务失败 */
    const IS_FAIL_TRUE = DefaultParm::DEFAULT_ONE;
    /** 任务正常 */
    const IS_FAIL_FALSE = DefaultParm::DEFAULT_ZERO;

    /** 0星 ***/
    const ZERO_STAR = DefaultParm::DEFAULT_ZERO;
    /** 1星 ***/
    const ONE_STAR = DefaultParm::DEFAULT_ONE;
    /** 2星 ***/
    const TWO_STAR = DefaultParm::DEFAULT_TWO;
    /** 3星 ***/
    const THREE_STAR = DefaultParm::DEFAULT_THREE;
    /** 4星 ***/
    const FOUR_STAR = DefaultParm::DEFAULT_FOUR;
    /** 5星 ***/
    const FIVE_STAR = DefaultParm::DEFAULT_FIVE;

    /** 任务收益发布 */
    const IS_PAY_TRUE = DefaultParm::DEFAULT_ONE;
    /** 任务收益未发布*/
    const IS_PAY_FALSE = DefaultParm::DEFAULT_ZERO;

    /**
     * 任务状态
     * @return array
     */
    public static function getStatusList()
    {
        return array(0 => '正常', 1 => '已上报', 2 => '打回修改');
    }

    /**
     * 任务状态名称
     * @param $status
     * @return string
     */
    public static function getStatusName($status)
    {
        $_statusList = self::getStatusList();
        return isset ($_statusList [$status]) ? $_statusList [$status] : DefaultParm::DEFAULT_EMPTY;
    }

    /**
     * 任务是否已失败，不能继续进行
     * @return array
     */
    public static function getIsFailList()
    {
        return array(0 => '否', 1 => '是');
    }

    /**
     * 任务失败名称
     * @param $isFail
     * @return string
     */
    public static function getIsFailName($isFail)
    {
        $_isFailList = self::getIsFailList();
        return isset ($_isFailList [$isFail]) ? $_isFailList [$isFail] : DefaultParm::DEFAULT_EMPTY;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TaskWhen the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{task_when}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(array('tid, createtime, motifytime, content', 'required'), array('status, isfail, score', 'numerical', 'integerOnly' => true), array('tid, scoreuid', 'length', 'max' => 11), array('content', 'length', 'max' => 255), // The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, tid, createtime, motifytime, content, status, isfail, score, scoreuid', 'safe', 'on' => 'search'));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array('t' => array(self::BELONGS_TO, 'Task', 'tid'), 'm' => array(self::BELONGS_TO, 'Manage', 'scoreuid'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array('id' => 'ID', 'tid' => '任务ID', 'createtime' => '创建时间', 'motifytime' => '更改时间', 'content' => '内容', 'status' => '状态', 'isfail' => '是否失败', 'score' => '评分', 'scoreuid' => '评分用户');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $c = new CDbCriteria ();

        $c->compare('id', $this->id, true);
        $c->compare('tid', $this->tid, true);
        $c->compare('createtime', $this->createtime, true);
        $c->compare('motifytime', $this->motifytime, true);
        $c->compare('content', $this->content, true);
        $c->compare('status', $this->status);
        $c->compare('isfail', $this->isfail);
        $c->compare('score', $this->score);
        $c->compare('scoreuid', $this->scoreuid, true);

        return new CActiveDataProvider ($this, array('criteria' => $c));
    }

    /**
     * @param $taskId
     * @return TaskWhen
     */
    public function getNormalByTask($taskId)
    {
        $c = new CDbCriteria ();
        $c->compare('`tid`', $taskId);
        return $this->find($c);
    }

    /**
     * 获取任务的详细信息
     * return array
     * @name 获取任务的详细信息
     */
    public function getTaskMsgByTid($taskId)
    {
        $sql1 = 'SELECT t.publish,t.createtime,t.title,m.name FROM app_task AS t
    			JOIN app_manage AS m
    			WHERE m.id = t.publish AND t.id = \'' . $taskId . '\' ';
        $publish = Yii::app()->db->createcommand($sql1)->queryAll();

        $sql2 = 'SELECT t.accept,m.name FROM app_task AS t
    			JOIN app_manage AS m
    			WHERE m.id = t.accept AND t.id = \'' . $taskId . '\' ';
        $accept = Yii::app()->db->createcommand($sql2)->queryAll();

        $sql3 = 'SELECT tw.* FROM app_task_when AS tw
    			WHERE tw.tid = \'' . $taskId . '\' ';
        $tw_msg = Yii::app()->db->createcommand($sql3)->queryAll();

        if (isset ($tw_msg [0] ['scoreuid'])) {
            $sql4 = 'SELECT name FROM app_manage WHERE id = \'' . $tw_msg [0] ['scoreuid'] . '\' ';
            $score_uid = Yii::app()->db->createcommand($sql4)->queryAll();
        }

        $sql5 = 'SELECT at.a_time,at.m_id,mi.username,at.id,at.type,at.f_id FROM app_ask_task AS at
		        JOIN app_member AS mi
		        ON at.m_id = mi.id
		        WHERE at.t_id = \'' . $taskId . '\' ';
        $atime = Yii::app()->db->createcommand($sql5)->queryAll();

        $can_pro = DefaultParm::DEFAULT_EMPTY;
        $a_time = $atime [0] ['a_time'];
        $time = time();
        $is_pro = $a_time + 30 * 24 * 3600;

        if ($time > $is_pro) {
            $can_pro = DefaultParm::DEFAULT_ONE;
        } else {
            $can_pro = DefaultParm::DEFAULT_ZERO;
        }
        $tw_isset = DefaultParm::DEFAULT_EMPTY;
        if (!empty ($tw_msg)) {
            $tw_isset = DefaultParm::DEFAULT_ONE;;
        } else {
            $tw_isset = DefaultParm::DEFAULT_ZERO;
        }
        $arr = DefaultParm::DEFAULT_EMPTY;
        $arr = array('publish' => $publish [0] ['name'], 'accept' => $accept [0] ['name'],
            'publish_id' => $publish [0] ['publish'], 'accept_id' => $accept [0] ['accept'],
            'pro' => $can_pro,
            'a_time' => isset ($a_time) ? date('Y-m-d H:i', $a_time) : "",
            't_sendime' => isset ($publish [0] ['createtime']) ? $publish [0] ['createtime'] : "",
            'tw_id' => isset ($tw_msg [0] ['id']) ? $tw_msg [0] ['id'] : "",
            't_id' => $taskId, 'm_id' => isset ($atime [0] ['m_id']) ? $atime [0] ['m_id'] : "",
            'at_id' => isset ($atime [0] ['id']) ? $atime [0] ['id'] : "",
            'type' => isset ($atime [0] ['type']) ? $atime [0] ['type'] : "",
            'f_id' => isset ($atime [0] ['f_id']) ? $atime [0] ['f_id'] : "",
            'username' => isset ($atime [0] ['username']) ? $atime [0] ['username'] : "",
            'tw_createtime' => isset ($tw_msg [0] ['createtime']) ? date('Y-m-d H:i', $tw_msg [0] ['createtime']) : "未填写",
            'tw_content' => isset ($tw_msg [0] ['content']) ? $tw_msg [0] ['content'] : "未填写",
            'tw_status' => isset ($tw_msg [0] ['status']) ? $tw_msg [0] ['status'] : "未填写",
            'tw_isfail' => isset ($tw_msg [0] ['isfail']) ? $tw_msg [0] ['isfail'] : "未填写",
            'tw_score' => isset ($tw_msg [0] ['score']) ? $tw_msg [0] ['score'] : "未填写",
            'tw_scoreuid' => isset ($tw_msg [0] ['scoreuid']) ? $tw_msg [0] ['scoreuid'] : "未填写",
            'score_uid' => isset ($score_uid [0] ['name']) ? $score_uid [0] ['name'] : "未填写",
            'tw_porttime' => isset ($tw_msg [0] ['porttime']) ? date('Y-m-d H:i', $tw_msg [0] ['porttime']) : "未填写", 'tw_isset' => $tw_isset);
        return $arr;
    }

    /**
     * 提交任务回复
     * @name 提交任务回复
     */
    public function replyTask($tw_id, $tid, $is_fail, $my_reply, $type, $pay_back, $role)
    {
        $same = DefaultParm::DEFAULT_EMPTY;

        $t = Yii::app()->db->beginTransaction();
        try {

            $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));

            $ret = TaskWhen::model()->updateAll(array(
                'isfail' => $is_fail, 'content' => $my_reply, 'status' => TaskWhen::STATUS_SUBMAIT,
                'porttime' => time(), 'pay_back' => $pay_back['pay_back'],
                'a_pay' => $pay_back['a_pay'], 'b_pay' => $pay_back['b_pay'],
            ), " id = " . $tw_id);



            $task = Task::model()->findByAttributes(array('id' => $tid));
            $task->status = Task::STATUS_SUBMIT;
            $task->type = $type;
            $task->update();

            $asktask = AskTask::model()->findByAttributes(array('t_id' => $tid));
            $asktask->type = $type;
            $asktask->t_status = AskTask::STATUS_APRO;
            $asktask->update();

            //任务上报成功完成，插入任务收益表中
            if ($is_fail == TaskWhen::IS_FAIL_FALSE) {
                TaskEarnings::model()->insretTaskEar($asktask->f_id, $asktask->m_id, $asktask->a_time,
                    time(), $pay_back['pay_back'], $role, $type, $asktask->id, $tw_id);
            }

            $t->commit();
            $same = DefaultParm::DEFAULT_ONE;
        } catch (Exception $e) {
            $t->rollback();
            $same = DefaultParm::DEFAULT_ZERO;
        }

        return $same;

    }

    /**
     * 查看一条任务信息
     */
    public function getTaskMsgById($tw_id)
    {
        $sql = 'SELECT count(id)AS count,remind,remark,important FROM app_task_when WHERE id = \'' . $tw_id . '\'';
        return Yii::app()->db->createcommand($sql)->queryAll();
    }

    /*
     * 修改任务备注信息
     */
    public function updateTasRemarkById($remark, $remind, $important, $tw_id)
    {
        $sql = 'UPDATE app_task_when SET remark = \'' . $remark . '\',remind = \'' . $remind . '\',important = \'' . $important . '\' WHERE id = \'' . $tw_id . '\' ';
        return Yii::app()->db->createcommand($sql)->execute();
    }

    /**
     * 类型名，时间，用户
     * 根据类型，获取当天的信息 income 表
     */
    public function getDataByTime($table, $time, $mid)
    {
        $sql = "SELECT * FROM $table WHERE uid = $mid AND createtime = '$time' ";
        return Yii::app()->db->createcommand($sql)->queryAll();
    }

    /**
     * 获取离指定日期最近的时间
     * 获取离指定日期最近的时间 income 表
     */
    public function getTimeByTime($table, $time, $mid)
    {
        $sql = "select * from $table where createtime > '$time' AND uid = $mid
	    			order by createtime asc limit 1 ";
        return Yii::app()->db->createcommand($sql)->queryAll();

    }

    /**
     * 获取指定日期的第一天和最后一天
     */
    public function getCurMonthFirstDay($date)
    {
        return strtotime(date('Y-m-01', $date));
    }

    public function getCurMonthLastDay($date)
    {
        $time = strtotime(date('Y-m-d', strtotime(date('Y-m-01', $date) . ' +1 month ')));
        return $time - 1;
    }

    /**
     * 获取今年的客服的每个月的任务收益
     */
    public function getPayInThisYearByUid($uid)
    {
        $time = time();
        $this_year = date('Y', $time);
        $start_month = $this_year . '-01';
        $end_month = $this_year . '-12';

        $sql = "SELECT * FROM app_salary WHERE t_prottime BETWEEN '$start_month' AND '$end_month' AND uid = $uid";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 今年有效收益总计
     */
    public function getAllPayByUid($uid)
    {
        $time = time();
        $this_year = date('Y', $time);
        $start_month = $this_year . '-01';
        $end_month = $this_year . '-12';

        $sql = "SELECT SUM(task_payback) AS task_pay,SUM(week_payback) AS week_pay  FROM app_salary WHERE t_prottime BETWEEN '$start_month' AND '$end_month' AND uid = $uid";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 降量任务收益
     * Enter description here ...
     * @name 降量任务收益
     */
    public function getPayBackForDropTask($a_time, $prottime, $mid, $pay)
    {
        $data = DefaultParm::DEFAULT_EMPTY;
        $sql = DefaultParm::DEFAULT_EMPTY;
        $sql1 = DefaultParm::DEFAULT_EMPTY;
        $sql0 = "SELECT pathname FROM app_product where status=1 ";
        $_type = Yii::app()->db->createCommand($sql0)->queryAll();

        if (empty ($_type)) {
            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }

        $back = DefaultParm::DEFAULT_EMPTY;
        foreach ($_type as $keyword) {
            $TABLE = 'app_income_' . $keyword ['pathname'];

            $a_time = $a_time; //任务申请/发布时间
            $ask_time = date('Y-m-d', $a_time); //任务申请/发布的时间格式化


            $porttime = $prottime - 24 * 3600; //当前时间
            $up_time = date('Y-m-d', $porttime); //当前时间时间格式化


            $end_time = ($porttime - (30 * 24 * 3600)); //当前时间前30天的时间
            $t_end_time = date('Y-m-d', $end_time); //当前时间时间格式化


            //查看任务类型表，是否存在发布任务当天的数据
            $res = TaskWhen::model()->getDataByTime($TABLE, $ask_time, $mid);
            $date = DefaultParm::DEFAULT_EMPTY;
            if (!empty ($res)) {
                $ask_for_time = $res [0] ['createtime']; //开始时间
            } else {
                //如果发布任务当天没有收益，或未空的
                $ask_for_time = '00-00-00';
            }

            //如果发布时间到上报时间未满30天
            if ($end_time < $a_time) {
                $end_time = ($a_time + 24 * 3600); //任务结束时间 = 发布任务的第二天开始
                $t_end_time = date('Y-m-d', $end_time); //任务结束时间格式化
            }

            if (!empty ($sql)) {
                $sql .= ' union ALL ';
            }
            //任务发布或申请时当日的收益	*30
            $sql .= "SELECT data*30 AS data FROM " . "$TABLE WHERE uid= $mid
                    AND createtime = '$ask_for_time' AND status = 1";

            if (!empty ($sql1)) {
                $sql1 .= ' union ALL ';
            }
            //至結束前时间30天的所有收益的总和
            $sql1 .= "SELECT " . "data FROM $TABLE
						WHERE uid= $mid AND createtime between '$t_end_time' and '$up_time' AND status = 1";

        }
        //任务发布或申请时当日的收益	*30
        $sql4 = "SELECT " . "sum(data) AS data FROM ($sql) AS a";
        $data_1 = Yii::app()->db->createcommand($sql4)->queryAll();
        $data_old = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : 0;

        //至前时间30天的所有收益的总和
        $sql3 = "SELECT " . " sum(data) AS data FROM ($sql1) AS a";
        $data_2 = Yii::app()->db->createcommand($sql3)->queryAll();
        $data_new = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : 0;


        $data['pay_back'] = ($data_new - $data_old) * $pay;
        $data['b_pay'] = $data_old;
        $data['a_pay'] = $data_new;

        return $data;

    }

    /**
     * 获取新用户任务收益
     */
    public function getPayBackForNewMemberTask($a_time, $prottime, $mid, $pay)
    {
        $sql0 = 'SELECT pathname ' . 'FROM app_product where status=1 ';
        $_type = Yii::app()->db->createCommand($sql0)->queryAll();
        if (empty ($_type)) {
            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }
        $data = DefaultParm::DEFAULT_EMPTY;
        $sql = DefaultParm::DEFAULT_EMPTY;
        $sql1 = DefaultParm::DEFAULT_EMPTY;
        foreach ($_type as $key => $keyword) {
            $TABLE = 'app_income_' . $keyword ['pathname'];

            /** @noinspection PhpSillyAssignmentInspection */
            $a_time = $a_time; //任务发布申请时间
            $ask_time = date('Y-m-d', $a_time); //任务申请/发布的时间格式化

            /*$b_time = ($a_time - (30 * 24 * 3600)); //任务申请发布前30天的时间
            $b_ask_time = date('Y-m-d', $b_time); //任务申请发布前30天的时间时间格式化*/

            $porttime = $prottime - 24 * 3600 * 9; //任务上报时间-9d
            $up_time = date('Y-m-d', $porttime); //任务上报时间格式化

            $end_time = ($prottime - (39 * 24 * 3600)); //任务上报前39天的时间
            $t_end_time = date('Y-m-d', $end_time); //任务结束时间格式化

            //如果发布时间到上报时间未满39天
            if ($end_time < $a_time) {
                $end_time = ($a_time + 24 * 3600); //任务结束时间 = 发布任务的第二天开始
                $t_end_time = date('Y-m-d', $end_time); //任务结束时间格式化
            }

            if (!empty ($sql)) {
                $sql .= ' union ALL ';
            }
            if (!empty ($sql1)) {
                $sql1 .= ' union ALL ';
            }
            //任务发布申请前，30天内的收益的总合
            /*$sql .= "SELECT data FROM " . "$TABLE
						WHERE uid= $mid AND status=1 AND createtime between '$b_ask_time' and '$ask_time'";*/
            //任务上报提交前30天的所有收益的总和
            $sql1 .= "SELECT data FROM" . " $TABLE
						WHERE uid= $mid AND status=1 AND createtime between '$t_end_time' and '$up_time' ";
        }
        /*$sql2 = "SELECT sum(data) AS data " . "FROM ($sql) AS a";
        $data_1 = Yii::app()->db->createcommand($sql2)->queryAll();
        $data_old = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : 0;*/

        $sql3 = "SELECT sum(data) AS data" . " FROM ($sql1) AS a";
        $data_2 = Yii::app()->db->createcommand($sql3)->queryAll();
        $data_new = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : 0;
        $data['pay_back'] = ($data_new) * $pay;
        $data['b_pay'] = 0;
        $data['a_pay'] = $data_new;
        return $data;
    }
    /**
     *获取所在月份的周信息(当月前三周)
     */
    public static function getNextMonthweeks($date){
        $ret=array();
        $stimestamp=strtotime($date);
        $mdays=date('t',$stimestamp);
        $msdate=date('Y-m-d',$stimestamp);
        $medate=date('Y-m-'.$mdays,$stimestamp);
        $etimestamp = strtotime($medate);
        //獲取第一周
        //$zcsy=6-date('w',$stimestamp);//第一周去掉第一天還有幾天
        $zcs1=$msdate;
        //$zce1=date('Y-m-d',strtotime("+$zcsy day",$stimestamp));
        $ret[1]=$zcs1;
        //獲取中間周次
        $jzc=0;
        //獲得當前月份是6周次還是5周次
        $jzc0="";
        $jzc6="";
        for($i=$stimestamp; $i<=$etimestamp; $i+=86400){
            if(date('w', $i) == 0){$jzc0++;}
            if(date('w', $i) == 6){$jzc6++;}
        }
        if($jzc0==5 && $jzc6==5)
        {
            $jzc=5;
        }else{
            $jzc=4;
        }
        date_default_timezone_set('PRC');
        $t = strtotime('+1 monday '.$msdate);
        $n = 1;
        for($n=1; $n<$jzc; $n++) {
            $b = strtotime("+$n week -1 week", $t);
            $dsdate=date("Y-m-d", $b);
            //$dedate=date("Y-m-d", strtotime("5 day", $b));
            $jzcz=$n+1;
            $ret[$jzcz]=$dsdate;
        }
        //獲取最後一周
        /*$zcsy=date('w',$etimestamp);//最後一周是周幾日~六 0~6
        $zcs1=date('Y-m-d',strtotime("-$zcsy day",$etimestamp));
        //$zce1=$medate;
        $jzcz=$jzc+1;
        $ret[$jzcz]=$zcs1;*/
        return $ret;
    }

    /**
     * 任务未上报提交前，可获得的收益与
     * 任务申请/发布时，和当前时间用户的收益的近几天的对比与
     * 数据的上升下降比例
     */
    public static function getDataByNow($mid, $type, $a_time, $role,$at_id)
    {

        $pay = Salary::model()->getPayByRole($role);

        //for($i=0; $i<=6; $i++) {echo $ret[$i]."<br />";}
        if ($type == Task::TYPE_NEW) { //新用户任务
            $sql = "SELECT pathname FROM app_product where status=1 ";
            $_type = Yii::app()->db->createCommand($sql)->queryAll();
            if (empty ($_type)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit ();
            }

            $data = DefaultParm::DEFAULT_EMPTY;
            $sql0 = DefaultParm::DEFAULT_EMPTY;
            $sql1 = DefaultParm::DEFAULT_EMPTY;
            $sql2 = DefaultParm::DEFAULT_EMPTY;
            $sql3 = DefaultParm::DEFAULT_EMPTY;
            $sql4 = DefaultParm::DEFAULT_EMPTY;
            $sql5 = DefaultParm::DEFAULT_EMPTY;
            $sql6 = DefaultParm::DEFAULT_EMPTY;
            $sql7 = DefaultParm::DEFAULT_EMPTY;
            $w_a_time=DefaultParm::DEFAULT_EMPTY;
            $w_a_time=DefaultParm::DEFAULT_EMPTY;
            $w_a_time=DefaultParm::DEFAULT_EMPTY;
            $arr = array();
            //周比
            $mond= date('Y-m', $a_time);//申请月份
            $thism=date('Y-m', time());//当前月份
            foreach ($_type as $key => $keyword) {
                $TABLE = 'app_income_' . $keyword ['pathname'];

                $a_time = $a_time; //任务发布申请时间
                $ask_time = date('Y-m-d', $a_time); //任务申请/发布的时间格式化

                //周比
                if($mond!=$thism)
                {
                    $ret=TaskWhen::model()->getNextMonthweeks($thism);
                    foreach ($ret as $keyw => $valw)
                    {
                        if($keyw==2){$w_a_time=$valw;}//任务申请的下个月第一周周一
                        if($keyw==3){$ww_a_time=$valw;}//任务申请的下个月第二周周一
                        if($keyw==4){$www_a_time=$valw;}//任务申请的下个月第三周周一
                    }
                    //print_r($mond.$thism);exit;
                }

/*                $b_a_time = $a_time - 24 * 3600; //任务申请/发布前一天
                $y_ask_time = date('Y-m-d', $b_a_time); //任务申请/发布前一天的时间格式化


                $b_time = ($a_time - (30 * 24 * 3600)); //任务申请发布前30天的时间
                $b_ask_time = date('Y-m-d', $b_time); //任务申请发布前30天的时间时间格式化*/


                $time = time(); //当前时间的时间戳


                $porttime = $time - 24 * 3600; //当前时间
                $up_time = date('Y-m-d', $porttime); //当前时间格式化

                $porttime9 = $time - 24 * 3600 * 9; //当前时间-9
                $up_time9 = date('Y-m-d', $porttime9); //当前时间-9格式化


                $b_porttime = $porttime - 24 * 3600; //前天时间
                $b_up_time = date('Y-m-d', $b_porttime); //前天时间格式化


                $end_time = ($time - (39 * 24 * 3600)); //当前时间 前39天的时间
                $t_end_time = date('Y-m-d', $end_time); //当前时间 时间格式化


                //如果发布时间到上报时间未满39天
                if ($end_time < $a_time) {
                    $end_time = ($a_time + 24 * 3600); //任务结束时间 = 发布任务的第二天开始
                    $t_end_time = date('Y-m-d', $end_time); //任务结束时间格式化
                }
                if (!empty ($sql0)) {
                    $sql0 .= ' union ALL ';
                }
                if (!empty ($sql1)) {
                    $sql1 .= ' union ALL ';
                }
                if (!empty ($sql2)) {
                    $sql2 .= ' union ALL ';
                }
                if (!empty ($sql3)) {
                    $sql3 .= ' union ALL ';
                }
                if (!empty ($sql4)) {
                    $sql4 .= ' union ALL ';
                }
                if (!empty ($sql5)) {
                    $sql5 .= ' union ALL ';
                }
                if (!empty ($sql6)) {
                    $sql6 .= ' union ALL ';
                }
                if (!empty ($sql7)) {
                    $sql7 .= ' union ALL ';
                }
                //任务发布申请前，30天内的收益的总合
                /*$sql0 .= "SELECT data FROM $TABLE" . "
						WHERE uid= $mid AND status=1 AND createtime between '$b_ask_time' and '$ask_time'";*/

                //任务上报提交前9天前30天的所有收益的总和
                $sql1 .= "SELECT data FROM $TABLE" . "
						WHERE uid= $mid AND status=1 AND createtime between '$t_end_time' and '$up_time9' ";

                //任务发布当天的所有收益
                $sql2 .= "SELECT data FROM $TABLE" . " WHERE uid= $mid AND status=1  AND createtime = '$ask_time' ";

                //前第9天的所有收益
                $sql3 .= "SELECT data FROM $TABLE" . " WHERE uid= $mid AND status=1 AND createtime = '$up_time9' ";

                //任务发布前天的所有收益
                /*$sql4 .= "SELECT data FROM $TABLE" . " WHERE uid= $mid AND status=1 AND createtime = '$y_ask_time' ";*/

                //前天的所有收益
                $sql5 .= "SELECT data FROM $TABLE " . "WHERE uid= $mid AND status=1 AND createtime = '$b_up_time' ";

                if(!empty($w_a_time))
                {
                    //周比--每月第一周
                    $sql6 .= "SELECT data FROM $TABLE" . "
						WHERE uid= $mid AND status=1 AND createtime >= '$w_a_time' and createtime < '$ww_a_time' ";
                    //周比--每月第二周
                    $sql7 .= "SELECT data FROM $TABLE" . "
						WHERE uid= $mid AND status=1 AND createtime >= '$ww_a_time' and createtime < '$www_a_time' ";
                }


            }
            //任务发布申请前，30天内的收益的总合
            /*$sql_0 = "SELECT sum(data) AS data FROM " . "($sql0) AS a";
            $data_0 = Yii::app()->db->createcommand($sql_0)->queryAll();
            $data_old = ($data_0 [0] ['data'] != null) ? $data_0 [0] ['data'] : DefaultParm::DEFAULT_ZERO;*/

            //任务上报提交前9天的30天前所有收益的总和
            $sql_1 = "SELECT sum(data) AS data FROM " . "($sql1) AS a";
            $data_1 = Yii::app()->db->createcommand($sql_1)->queryAll();
            $data_new = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //任务发布当天的所有收益
            $sql_2 = "SELECT sum(data) AS data FROM " . "($sql2) AS a";
            $data_2 = Yii::app()->db->createcommand($sql_2)->queryAll();
            $data_the_day = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //前第9天的所有收益
            $sql_3 = "SELECT sum(data) AS data FROM " . "($sql3) AS a";
            $data_3 = Yii::app()->db->createcommand($sql_3)->queryAll();
            $data_yesterday = ($data_3 [0] ['data'] != null) ? $data_3 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //任务发布前天的所有收益
            /*$sql_4 = "SELECT sum(data) AS data FROM" . " ($sql4) AS a";
            $data_4 = Yii::app()->db->createcommand($sql_4)->queryAll();
            $b_data_the_day = ($data_4 [0] ['data'] != null) ? $data_4 [0] ['data'] : DefaultParm::DEFAULT_ZERO;*/

            //前天的所有收益
            $sql_5 = "SELECT sum(data) AS data FROM " . "($sql5) AS a";
            $data_5 = Yii::app()->db->createcommand($sql_5)->queryAll();
            $b_data_yesterday = ($data_5 [0] ['data'] != null) ? $data_5 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            if(!empty($sql6) && !empty($sql7))
            {
                //周比--每月第一周
                $sql_6 = "SELECT sum(data) AS data FROM " . "($sql6) AS a";
                $data_6 = Yii::app()->db->createcommand($sql_6)->queryAll();
                $week_ratio6 = ($data_6 [0] ['data'] != null) ? $data_6 [0] ['data'] : DefaultParm::DEFAULT_ZERO;
                //周比--每月第二周
                $sql_7 = "SELECT sum(data) AS data FROM " . "($sql7) AS a";
                $data_7 = Yii::app()->db->createcommand($sql_7)->queryAll();
                $week_ratio7 = ($data_7 [0] ['data'] != null) ? $data_7 [0] ['data'] : DefaultParm::DEFAULT_ZERO;
            }



            /*$data = ($data_new - $data_old) * $pay['new'];*/
            $data = ($data_new) * $pay['new'];

            //周比
            if(empty($week_ratio6) || empty($week_ratio7) || $week_ratio6<=0 || $week_ratio7<=0)
            {
                $arr ['week_ratio']=0;
            }
            else
            {
                $arr ['week_ratio'] = ($week_ratio7/$week_ratio6)*100;
            }

            //打回大用户池（非本月）--week_ratio=0不打回，
            if($mond!=$thism)
            {
                if(!empty($www_a_time))
                {
                    $thisday=date('Y-m-d', time());//当前日期
                    $www_friday=$thism."-28";//第三周周五-->改为每月28号

                    if($thisday>=$www_friday)
                    {
                        if($arr ['week_ratio']==0)
                        {

                        }
                        elseif($arr ['week_ratio']<=50)
                        {
                            //打回大用户池
                            $res = AskTask::model()->getAtByAtId($at_id);
                            if (!empty ($res)) {
                                $t = Yii::app()->db->beginTransaction();
                                try {
                                    //打回备选用户池
                                    $thistime=date("Y-m-d H:i:s",time());
                                    $sqlb = 'INSERT INTO `app_memberpool_bak` (`id`, `mid`, `uid`, `createtime`, `status`) VALUES
                                            ("",\'' . $res->f_id . '\',	\'' . $res->m_id . '\',	\'' . $thistime . '\',	0)';
                                    Yii::app()->db->createcommand($sqlb)->execute();

                                    $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
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

                            //
                        }
                    }
                }


            }




            $arr ['the_day'] = $data_the_day; //发布当天收入
            $arr ['data'] = $data;
            $arr ['yesterday'] = $data_yesterday; //昨天收入
            /*$arr ['b_the_day'] = $b_data_the_day; //发布前天收入*/
            $arr ['b_yesterday'] = $b_data_yesterday; //前天收入


            if ($arr ['the_day'] > $arr ['yesterday']) {
                $arr ['img'] = '/images/memberpool/1.jpg';
            } else if ($arr ['the_day'] < $arr ['yesterday']) {
                $arr ['img'] = '/images/memberpool/2.jpg';
            } else {
                $arr ['img'] = '/images/memberpool/3.jpg';
            }

            /*if (($arr ['b_the_day'] > $arr ['b_yesterday'])) {
                $arr ['b_img'] = '/images/memberpool/1.jpg';
            } else if ($arr ['b_the_day'] < $arr ['b_yesterday']) {
                $arr ['b_img'] = '/images/memberpool/2.jpg';
            } else {
                $arr ['b_img'] = '/images/memberpool/3.jpg';
            }*/
            return $arr;

        }
        //平台无降量任务
        else if ($type == Task::TYPE_DROP) {
            $data = DefaultParm::DEFAULT_EMPTY;
            $sql0 = DefaultParm::DEFAULT_EMPTY;
            $sql1 = DefaultParm::DEFAULT_EMPTY;
            $sql2 = DefaultParm::DEFAULT_EMPTY;
            $sql3 = DefaultParm::DEFAULT_EMPTY;
            $sql4 = DefaultParm::DEFAULT_EMPTY;
            $sql = "SELECT pathname FROM " . " app_product where status=1 ";
            $_type = Yii::app()->db->createCommand($sql)->queryAll();

            if (empty ($_type)) {
                echo CJSON::encode(array('msg' => '0'));
                exit ();
            }

            $back = DefaultParm::DEFAULT_EMPTY;
            foreach ($_type as $keyword) {
                $TABLE = 'app_income_' . $keyword ['pathname'];

                $a_time = $a_time; //任务申请/发布时间
                $ask_time = date('Y-m-d', $a_time); //任务申请/发布的时间格式化


                $b_a_time = $a_time - 24 * 3600; //任务申请/发布前一天
                $b_ask_time = date('Y-m-d', $b_a_time); //任务申请/发布前一天的时间格式化


                $time = time(); //当前时间时间戳


                $porttime = $time - 24 * 3600; //当前时间
                $up_time = date('Y-m-d', $porttime); //当前时间时间格式化


                $b_porttime = $porttime - 24 * 3600; //前天时间
                $b_up_time = date('Y-m-d', $b_porttime); //前天时间格式化


                $end_time = ($porttime - (30 * 24 * 3600)); //当前时间前30天的时间
                $t_end_time = date('Y-m-d', $end_time); //当前时间时间格式化


                //查看任务类型表，是否存在发布任务当天的数据
                $res = TaskWhen::model()->getDataByTime($TABLE, $ask_time, $mid);

                //查看任务类型表，是否存在发布任务前天的数据
                $res1 = TaskWhen::model()->getDataByTime($TABLE, $ask_time, $mid);

                if (!empty ($res)) {
                    $ask_for_time = $res [0] ['createtime']; //开始时间
                } else {
                    $ask_for_time = '00-00-00';
                }
                if (!empty ($res1)) {
                    $b_ask_for_time = $res1 [0] ['createtime']; //开始时间
                } else {
                    $b_ask_for_time = '00-00-00';
                }
                //如果发布时间到上报时间未满30天
                if ($end_time < $a_time) {
                    $end_time = ($a_time + 24 * 3600); //任务结束时间 = 发布任务的第二天开始
                    $t_end_time = date('Y-m-d', $end_time); //任务结束时间格式化
                }
                if (!empty ($sql0)) {
                    $sql0 .= ' union ALL ';
                }
                if (!empty ($sql1)) {
                    $sql1 .= ' union ALL ';
                }
                if (!empty ($sql2)) {
                    $sql2 .= ' union ALL ';
                }
                if (!empty ($sql3)) {
                    $sql3 .= ' union ALL ';
                }
                if (!empty ($sql4)) {
                    $sql4 .= ' union ALL ';
                }
                //任务发布或申请时前天的收益
                $sql0 .= "SELECT data AS " . "data FROM $TABLE WHERE uid= $mid AND status=1 AND createtime = '$b_ask_for_time'";

                //前天的收益
                $sql4 .= "SELECT data AS " . "data FROM $TABLE WHERE uid= $mid AND status=1 AND createtime = '$b_up_time'";

                //任务发布或申请时当日的收益
                $sql1 .= "SELECT data AS data FROM " . "$TABLE WHERE uid= $mid AND status=1 AND createtime = '$ask_for_time'";

                //昨天的收益
                $sql2 .= "SELECT data AS data FROM " . "$TABLE WHERE uid= $mid AND status=1 AND createtime = '$up_time'";
                //至前时间30天的所有收益的总和
                $sql3 .= "SELECT data FROM $TABLE" . "
						WHERE uid= $mid AND status=1 AND createtime between '$t_end_time' and '$up_time' ";
            }
            //任务发布或申请时当日的前一天的收益
            $sql_0 = "SELECT sum(data) AS data FROM" . " ($sql0) AS a";
            $data_0 = Yii::app()->db->createcommand($sql_0)->queryAll();
            $b_data_the_day = ($data_0 [0] ['data'] != null) ? $data_0 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //前天的收益
            $sql_4 = "SELECT sum(data) AS data FROM" . " ($sql4) AS a";
            $data_4 = Yii::app()->db->createcommand($sql_4)->queryAll();
            $b_data_yesterday = ($data_4 [0] ['data'] != null) ? $data_4 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //任务发布或申请时当日的收益
            $sql_1 = "SELECT sum(data) AS data FROM " . "($sql1) AS a";
            $data_1 = Yii::app()->db->createcommand($sql_1)->queryAll();
            $data_the_day = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            $data_old = $data_the_day * 30;

            //昨天的收益
            $sql_2 = "SELECT sum(data) AS data FROM " . "($sql2) AS a";
            $data_2 = Yii::app()->db->createcommand($sql_2)->queryAll();
            $data_yesterday = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            //至前时间30天的所有收益的总和
            $sql_3 = "SELECT sum(data) AS data FROM " . "($sql3) AS a";
            $data_3 = Yii::app()->db->createcommand($sql_3)->queryAll();
            $data_new = ($data_3 [0] ['data'] != null) ? $data_3 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

            $data = ($data_new - $data_old) * $pay['drop'];

            $arr ['the_day'] = $data_the_day; //发布当天收入
            $arr ['b_the_day'] = $b_data_the_day; //发布前天收入
            $arr ['yesterday'] = $data_yesterday; //昨天收入
            $arr ['b_yesterday'] = $b_data_yesterday; //昨天收入
            $arr ['data'] = $data;

            if (($arr ['the_day'] > $arr ['yesterday'])) {
                $arr ['img'] = '/images/memberpool/1.jpg';
            } else if ($arr ['the_day'] < $arr ['yesterday']) {
                $arr ['img'] = '/images/memberpool/2.jpg';
            } else {
                $arr ['img'] = '/images/memberpool/3.jpg';
            }
            if (($arr ['b_the_day'] > $arr ['b_yesterday'])) {
                $arr ['b_img'] = '/images/memberpool/1.jpg';
            } else if ($arr ['b_the_day'] < $arr ['b_yesterday']) {
                $arr ['b_img'] = '/images/memberpool/2.jpg';
            } else {
                $arr ['b_img'] = '/images/memberpool/3.jpg';
            }
            return $arr;
        }
    }

    /**
     * 任务打回
     * @name 任务打回
     */
    public function BackTask($tw_id, $t_id, $msg)
    {
        $res = DefaultParm::DEFAULT_EMPTY;
        $por = self::checkTaskIsPort($tw_id, $t_id);

        if ($por == DefaultParm::DEFAULT_ONE) {

            $res = DefaultParm::DEFAULT_ONE; //任务未上报

        }
        else if ($por == DefaultParm::DEFAULT_THREE) {

            $res = DefaultParm::DEFAULT_THREE; //任务已完成或删除
        }
        else if ($por == DefaultParm::DEFAULT_TWO) {

            //任务上报状态，则驳回任务
            $t = Yii::app()->db->beginTransaction();
            try {
                $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));
                $taskwhen->status = TaskWhen::STATUS_ROLLBACK;
                $taskwhen->motifytime = time();
                $taskwhen->pay_back = 0;
                $taskwhen->update();

                $task = Task::model()->findByAttributes(array('id' => $t_id));
                $task->status = Task::STATUS_NORMAL;
                $task->motifytime = time();
                $task->content = $msg;
                $task->update();

                $asktask = AskTask::model()->findByAttributes(array('t_id' => $t_id));
                $asktask->t_status = AskTask::STATUS_AASK;
                $asktask->content = $msg;
                $asktask->update();

                if($asktask->type != AskTask::TYPE_VISIT){

                $sql = 'SELECT id FROM app_task_earnings WHERE twid = \'' . $tw_id . '\' ';
                $res = Yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($res))
                    {
                        $res = $res[0]['id'];

                        //删除该条任务的收益
                        $taskear = TaskEarnings::model()->deleteByPk($res);
                    }

                }
                $t->commit();
                $res = DefaultParm::DEFAULT_TWO; //驳回成功

            } catch (Exception $e) {
                $t->rollback();
                $res = DefaultParm::DEFAULT_ZERO; //驳回失败
            }
        }
        return $res;
    }

    /**
     * 任务评分
     * @name 任务评分
     */
    public function setScoreByUid($id, $score, $tid)
    {

        $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $id));
        $taskwhen->score = $score;
        $taskwhen->scoreuid = $id;
        return $taskwhen->update();

    }

    /**
     * 查看任务是否被提交
     * @name 查看任务是否被提交
     */
    public function checkTaskIsPort($tw_id, $t_id)
    {

        $task = Task::model()->findByAttributes(array('id' => $t_id));

        $task_when = TaskWhen::model()->findByAttributes(array('id' => $tw_id));

        $is_por = DefaultParm::DEFAULT_EMPTY;

        if (($task->status == Task::STATUS_NORMAL) && (($task_when->status == TaskWhen::STATUS_NORMAL) || ($task_when->status == TaskWhen::STATUS_ROLLBACK))) {
            $is_por = DefaultParm::DEFAULT_ONE; //未上报

        } else if ((($task->status == Task::STATUS_SUBMIT)) && (($task_when->status == TaskWhen::STATUS_SUBMAIT))) {
            $is_por = DefaultParm::DEFAULT_TWO; //已上报

        } else if ((($task->status == Task::STATUS_DONE) || ($task->status == Task::STATUS_DEL))) {
            $is_por = DefaultParm::DEFAULT_THREE; //已完成或删除

        }

        return $is_por;
    }

    /**
     * 修改任务信息，任务流程结束
     * @param $score
     * @param $publish
     * @param $tw_id
     * @param $t_id
     * @return array
     */
    public function updateTaskType($score, $publish, $tw_id, $t_id, $f_id)
    {
        $arr = DefaultParm::DEFAULT_EMPTY;
        $role = Manage::model()->getRoleByUid($f_id);
        $t = Yii::app()->db->beginTransaction();
        try {

            $asktask = AskTask::model()->findByAttributes(array('t_id' => $t_id));
            $asktask->t_status = AskTask::STATUS_ADONE;
            $asktask->update();

            $task = Task::model()->findByAttributes(array('id' => $t_id));
            $task->status = Task::STATUS_DONE;
            $task->isshow = Task::TYPE_IS_SHOW_TRUE;
            $task->motifytime = time();
            $task->update();

            $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $tw_id));
            $taskwhen->score = $score;
            $taskwhen->scoreuid = $publish;
            $taskwhen->motifytime = time();
            $taskwhen->update();

            //如果是回访任务，并且克服等级是见习客服，则插入任务收益表中
            if (($taskwhen->isfail == TaskWhen::IS_FAIL_FALSE) && ($role == Role::PRACTICE_STAFF) &&
                ($asktask->type == AskTask::TYPE_VISIT)
            ) {
                TaskEarnings::model()->insretTaskEar($f_id, $asktask->m_id, $asktask->a_time, $taskwhen->porttime, $taskwhen->pay_back, $role, $asktask->type, $asktask->id, $taskwhen->id);
            }

            $t->commit();
            $arr = 1;
        } catch (Exception $e) {
            $t->rollback();
            $arr = 0;
        }

        return $arr;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $timestamp
     * @name 找出当前周的周一
     */
    public function this_monday($timestamp = 0, $is_return_timestamp = true)
    {
        static $cache;
        $id = $timestamp . $is_return_timestamp;
        if (!isset ($cache [$id])) {
            if (!$timestamp)
                $timestamp = time();
            $monday_date = date('Y-m-d', $timestamp - 86400 * date('w', $timestamp) + (date('w', $timestamp) > 0 ? 86400 : - /*6*86400*/518400));
            if ($is_return_timestamp) {
                $cache [$id] = strtotime($monday_date);
            } else {
                $cache [$id] = $monday_date;
            }
        }
        return $cache [$id];

    }

    /**
     * 查询任务收益修改于发布时间
     */
    public function CheckTaskPayBackStatus($arr_tw)
    {
        $sql = 'SELECT porttime,pay_back,id,ispay FROM app_task_when WHERE FIND_IN_SET(id,\'' . $arr_tw . '\')';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public function sumPayBack($valf, $vall, $f_id, $id_list)
    {
        $sql = 'SELECT sum(tw.pay_back) AS payback FROM app_task_when AS tw
		            		JOIN app_ask_task AS at
							ON at.tw_id = tw.id
		            		WHERE tw.porttime BETWEEN \'' . $valf . '\' AND \'' . $vall . '\'  
		            		AND at.f_id = \'' . $f_id . '\' AND tw.ispay= \'' . TaskWhen::IS_PAY_TRUE . '\'
		            		AND id IN \'' . $id_list . '\' ';
        return Yii::app()->db->createcommand($sql)->queryAll();
    }

    //月份中发布的任务的id
    public function twidList($valf, $vall, $f_id)
    {
        $sql = 'SELECT tw.id FROM app_task_when AS tw
		            		JOIN app_ask_task AS at
							ON at.tw_id = tw.id
		            		WHERE tw.porttime BETWEEN \'' . $valf . '\' AND \'' . $vall . '\'  AND at.f_id = \'' . $f_id . '\' AND tw.ispay= \'' . TaskWhen::IS_PAY_TRUE . '\' ';
        return Yii::app()->db->createcommand($sql)->queryAll();
    }


    public function countSalaryIdByDate($date, $f_id)
    {
        $sql = 'SELECT count(id) AS count,id,task_payback  FROM app_salary WHERE uid = \'' . $f_id . '\' AND t_prottime = \'' . $date . '\' ';
        return Yii::app()->db->createcommand($sql)->queryAll();
    }

    /**
     * 可发布任务收益的收益总和
     */
    public function getPayBackTotal($TYPE, $status, $AND, $firstday, $lastday)
    {
        $sql = "SELECT sum(tw.pay_back)AS pay FROM app_task_when AS tw
    			JOIN app_ask_task AS at
    			JOIN app_task_earnings AS te
    			ON at.tw_id = tw.id AND te.twid = tw.id
    			WHERE $TYPE  tw.isfail =" . TaskWhen::IS_FAIL_FALSE .
                " AND tw.status =" . TaskWhen::STATUS_SUBMAIT .
                 " AND (at.t_status =" . AskTask::STATUS_ADONE ." OR at.t_status =" . AskTask::STATUS_DEL ." )"."
    			$AND  AND tw.porttime >= $firstday  AND tw.porttime <= $lastday ";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * @param $TYPE
     * @param 可发布回访任务收益的收益总和
     * @param $AND
     * @param $firstday
     * @param $lastday
     * @return mixed
     */
    public function getVisitPayBackTotal($TYPE, $status, $AND, $firstday, $lastday){
        $sql = "SELECT SUM(tw.pay_back) AS pay  "." FROM app_task_when AS tw
                JOIN app_ask_task AS at
                ON at.tw_id = tw.id
                WHERE   tw.porttime >= $firstday  AND tw.porttime <= $lastday $AND
                AND at.type = ".AskTask::TYPE_VISIT;

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 查看客服任务列表超过一个月没联系的任务
     * 更改---打回大用户池
     */
    public function checkMemberNoContactMoreThenMounth($id){
        $arr = Array();
        $str = '';
        $sql_m = 'SELECT mid FROM app_task WHERE accept = \''.$id.'\' AND status = \''.Task::STATUS_NORMAL.'\' ';
        $mid_list =  Yii::app()->db->createCommand($sql_m)->queryAll();

        foreach($mid_list AS $key => $val){
            $arr[$key] = $val['mid'];
        }
        $str = implode(",",$arr);

        if(empty($str)){
            $str = 0;
        }
        //超过一个月没有联系的用户id与最后联系时间
        $uid_arr = array();
        $num = '';
        $mounth_ago = strtotime("-1 month");

        $sql = "SELECT  b.*,at.id as at_id,tw.spare FROM
                        (SELECT MAX(ar.jointime) AS mtime ,ar.uid,mi.username,ar.mid
                        FROM app_advisory_records AS ar
                        JOIN app_member AS mi
                        ON mi.id = ar.uid
                        WHERE ar.mid = $id
                        GROUP BY ar.uid ORDER BY mtime DESC) AS b
               JOIN app_task AS t
               JOIN app_task_when AS tw
               JOIN app_ask_task AS at
               ON t.mid = b.uid AND t.id = tw.tid AND t.id = at.t_id
               WHERE b.uid IN ($str)  AND b.mtime < $mounth_ago AND (tw.status = ".TaskWhen::STATUS_NORMAL." OR tw.status = ".TaskWhen::STATUS_ROLLBACK.")
               AND t.accept = $id GROUP BY t.id";

        $uid_list =  Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($uid_list);

        if(!empty($uid_list)){
/*            foreach($uid_list AS $ke=>$item){
                $uid_arr[$ke]['mid'] = $item['uid'];
                $uid_arr[$ke]['time'] = $item['mtime'];
                $uid_arr[$ke]['name'] = $item['username'];
                $uid_arr[$ke]['spare'] = $item['spare'];
            }
            $uid_arr['num'] = $num;
            return $uid_arr;*/
            //打回大用户池
            foreach($uid_list AS $ke=>$item){
                //打回大用户池
                $at_id=$item["at_id"];
                $resd = AskTask::model()->getAtByAtId($at_id);
                if (!empty ($resd)) {
                    $t = Yii::app()->db->beginTransaction();
                    try {
                        //打回备选用户池
                        $thistime=date("Y-m-d H:i:s",time());
                        $sqlb = 'INSERT INTO `app_memberpool_bak` (`id`, `mid`, `uid`, `createtime`, `status`) VALUES
                                            ("",\'' . $resd->f_id . '\',	\'' . $resd->m_id . '\',	\'' . $thistime . '\',	0)';
                        Yii::app()->db->createcommand($sqlb)->execute();

                        $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));
                        if(!empty($asktask))
                        {
                            $asktask->t_status = AskTask::STATUS_DEL;
                            $asktask->update();
                        }
                        $task = Task::model()->findByAttributes(array('id' => $resd->t_id));
                        if(!empty($task))
                        {
                            $task->status = Task::STATUS_DEL;
                            $task->update();
                        }
                        $taskwhen = TaskWhen::model()->findByAttributes(array('id' => $resd->tw_id));
                        if(!empty($taskwhen))
                        {
                            $taskwhen->status = Task::STATUS_SUBMIT;
                            $taskwhen->isfail = TaskWhen::IS_FAIL_TRUE;
                            $taskwhen->update();
                        }
                        Member::model()->updateByPk($resd->m_id, array('manage_id' => DefaultParm::DEFAULT_ZERO));

                        $t->commit();
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //回访任务已删除

                    } catch (Exception $e) {

                        $t->rollback();
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //回访任务失败

                    }
                }
                //
            }
        }else{
/*            $uid_arr['num'] = $num;
            return $uid_arr;*/
        }

    }
    /**
     * 查看不在任务记录表中的客服的任务
     */
    public function checkTasksNotInPool($id){
        $arr = Array();
        $str = '';
        $sql_m = 'SELECT '.' uid FROM app_advisory_records GROUP BY uid';
        $mid_list =  Yii::app()->db->createCommand($sql_m)->queryAll();

        foreach($mid_list AS $key => $val){
            $arr[$key] = $val['uid'];
        }
        $str = implode(",",$arr);

        $sql = "SELECT t.*,mi.username,tw.spare "." FROM app_task AS t
                JOIN app_member AS mi
                JOIN app_task_when AS tw
                ON mi.id = t.mid AND tw.tid = t.id
                WHERE accept = $id AND mid NOT IN($str)
                AND (tw.status = ".TaskWhen::STATUS_NORMAL." OR tw.status = ".TaskWhen::STATUS_ROLLBACK.")
                AND t.status = ".Task::STATUS_NORMAL;
        $msg_list =  Yii::app()->db->createCommand($sql)->queryAll();
        $num = count($msg_list);
        $msg_list['num']=$num;
        return $msg_list;
    }
}