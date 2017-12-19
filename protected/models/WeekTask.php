<?php

/**
 * 任务
 * This is the model class for table "{{task}}".
 *
 * The followings are the available columns in table '{{task}}':
 * @property string $id
 * @property string $publish
 * @property string $accept
 * @property integer $createtime
 * @property integer $motifytime
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property integer $isshow
 * @property string $mid
 *
 * The followings are the available model relations:
 * @property TaskWhen[] $taskWhens
 * @property Manage $managePublish
 * @property Manage $manageAccept
 * @property Member $member
 */
class WeekTask extends CActiveRecord
{

    /** 周任务领取总量 */
    const TOTAL = DefaultParm::DEFAULT_TWENTY;

    /** 周任务单条任务有效收益最小值 */
    const MIN_PAYBACK = DefaultParm::DEFAULT_FIVE;
    /** 周任务单条任务有效收益增长率最小值 */
    const MIN_INCREASE = DefaultParm::DEFAULT_TWENTY;
    /** 上一周周任务的任务有效率最小值 */
    const MIN_VALID = DefaultParm::DEFAULT_FIFTEEN;

    /** 周任务收益是否已经发布=发布 */
    const WEEK_TASK_ISPRO_TRUE = DefaultParm::DEFAULT_ONE;
    /** 周任务收益是否已经发布=未发布 */
    const WEEK_TASK_ISPRO_FALSE = DefaultParm::DEFAULT_ZERO;

    /** 单条周任务是否合格=合格 */
    const WEEK_TASK_ISCON_TRUE = DefaultParm::DEFAULT_ONE;
    /** 单条周任务是否合格=不合格 */
    const WEEK_TASK_ISCON_FALSE = DefaultParm::DEFAULT_ZERO;

    /** 任务进度 - 进行中 */
    const STATUS_NORMAL = DefaultParm::DEFAULT_ZERO;
    /** 任务进度 - 上报 */
    const STATUS_PRO = DefaultParm::DEFAULT_ONE;
    /** 任务进度 - 无效任务 */
    const STATUS_BACK = DefaultParm::DEFAULT_TWO;
    /** 任务进度 - 有效完成 */
    const STATUS_DONE = DefaultParm::DEFAULT_THREE;
    /** 任务进度 - 删除 */
    const STATUS_DELETE = DefaultParm::DEFAULT_FOUR;

    /** 周任务时间分类 - 上周 */
    const LAST_WEEK = DefaultParm::DEFAULT_ONE;
    /** 周任务时间分类 - 本周 */
    const THIS_WEEK = DefaultParm::DEFAULT_TWO;
    /** 周任务时间分类 - 下周 */
    const NEXT_WEEK = DefaultParm::DEFAULT_THREE;

    /** 周任务领取比例 */
    const SCALE = DefaultParm::DEFAULT_TEN;


    /** 周任务合格任务数量 ，3个有效任务*/
    const CON_WEEK_TASK_MIN = DefaultParm::DEFAULT_THREE;
    /** 周任务合格任务数量 ，4个有效任务*/
    const CON_WEEK_TASK_MIDDLE = DefaultParm::DEFAULT_FOUR;
    /** 周任务合格任务数量 ，大于等于5有效任务*/
    const CON_WEEK_TASK_MAX = DefaultParm::DEFAULT_FIVE;

    /** 周任务收益 ，3个有效任务*/
    const PAYBACK_MIN = DefaultParm::DEFAULT_HUNDRED;
    /** 周任务收益 ，4个有效任务*/
    const PAYBACK_MIDDLE = DefaultParm::DEFAULT_TWO_HUNDRED;
    /** 周任务收益 ，大于等于5有效任务*/
    const PAYBACK_MAX = DefaultParm::DEFAULT_THREE_HUNDRED;

    private $connection = null;
    private $command = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Task the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 任务类型
     * @param args .. 任务类型id
     * @return array
     */
    public static function getWeekTaskType()
    {
        $types = array(self::LAST_WEEK => '上一周', self::THIS_WEEK => '本周', self::NEXT_WEEK => '下一周');

        $args = func_get_args();
        if (empty ($args)) {
            return $types;
        } else {
            $_types = array();
            foreach ($args as $arg) {
                if (!isset ($types [$arg]))
                    continue;
                $_types [$arg] = $types [$arg];
            }
            return $_types;
        }
    }

    /**
     * 任务状态
     * @param args ..
     * @return array
     */
    public static function getWeekTaskStatus()
    {
        $types = array(self::STATUS_NORMAL => '进行中', self::STATUS_PRO => '上报', self::STATUS_BACK => '无效任务', self::STATUS_DONE => '有效完成', self::STATUS_DELETE => '删除 ');

        $args = func_get_args();
        if (empty ($args)) {
            return $types;
        } else {
            $_types = array();
            foreach ($args as $arg) {
                if (!isset ($types [$arg]))
                    continue;
                $_types [$arg] = $types [$arg];
            }
            return $_types;
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getStatusName($id)
    {
        $typeList = self::getWeekTaskStatus();
        return isset ($typeList [$id]) ? $typeList [$id] : '';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{week_task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(array('publish, accept, mid, createtime, motifytime, title, content, status', 'required'), array('status, isshow, type', 'numerical', 'integerOnly' => true), array('publish, accept, mid', 'length', 'max' => 11), array('title', 'length', 'max' => 50), array('content', 'length', 'max' => 255), // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, publish, accept, mid, createtime, motifytime, title, content, type, status, isshow', 'safe', 'on' => 'search'));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria ();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('f_id', $this->f_id, true);
        $criteria->compare('m_id', $this->m_id, true);
        $criteria->compare('payback', $this->payback, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('endtime', $this->endtime, true);
        $criteria->compare('prottime', $this->prottime, true);
        $criteria->compare('at_id', $this->at_id);

        return new CActiveDataProvider ($this, array(
            'criteria' => $criteria,
            'pagination' => array('pagesize' => Common::PAGE_SIZE)
        ));
    }

    /**
     * 获取本周周任务时间格式改变
     */
    public function getThisWeekWtTaskDate()
    {
        //距离当前时间最近的周任务的创建时间
        $time = time();
        $arr = array();

        $sql_max = 'SELECT max(createtime) AS c FROM app_week_task
                    WHERE \'' . $time . '\' > createtime  AND  \'' . $time . '\' < endtime limit 0 ,1 ';
        $count = WeekTask::model()->countBySql($sql_max);

        if (!empty($count)) {

            $max_createtime = Yii::app()->db->createCommand($sql_max)->queryAll();
            $max_createtime = isset($max_createtime) ? $max_createtime[0]['c'] : time();

            //本周周任务
            $sql_this_week = 'SELECT createtime,endtime FROM app_week_task WHERE
                              createtime = \'' . $max_createtime . '\' ';
            $this_week_task = Yii::app()->db->createCommand($sql_this_week)->queryAll();

            //本周周任务，任务创建时的时间格式；
            $this_week_c_date = date('Y-m-d', $this_week_task[0]['createtime']);
            $this_week_c_time = $this_week_task[0]['createtime'];

            $this_week_e_date = date('Y-m-d', $this_week_task[0]['endtime']);
            $this_week_e_time = $this_week_task[0]['endtime'];

            $arr['c_date'] = $this_week_c_date;
            $arr['c_time'] = $this_week_c_time;
            $arr['e_date'] = $this_week_e_date;
            $arr['e_time'] = $this_week_e_time;
        } else {
            $this_monday = self::this_monday();
            $next_monday = self::getNextMonday();
            $arr['c_date'] = $this_monday['date'];
            $arr['c_time'] = $this_monday['time'];;
            $arr['e_date'] = $next_monday['date'];
            $arr['e_time'] = $next_monday['time'];

        }
        return $arr;
    }

    /**
     * 获取本周周任务
     */
    public function getThisWeekWtTask()
    {
        //距离当前时间最近的周任务的创建时间
        $time = time();
        $arr = array();

        //查找最大的任务创建时间
        $sql_max = 'SELECT max(createtime) AS c FROM app_week_task
                    WHERE createtime < \'' . $time . '\'   limit 1 ';
        $count = WeekTask::model()->countBySql($sql_max);

        //如果存最大的createtime
        if (!empty($count)) {
            $max_createtime = Yii::app()->db->createCommand($sql_max)->queryAll();
            $this_monday = self::this_monday();
            $max_createtime = isset($max_createtime) ? $max_createtime[0]['c'] : $this_monday['time'];

            // 查看最大任务创建时间的结束时间
            $sql_max_end = 'SELECT endtime FROM app_week_task
                    WHERE createtime = \'' . $max_createtime . '\'  limit 1 ';
            $count1 = WeekTask::model()->countBySql($sql_max_end);
            $max_endtime = Yii::app()->db->createCommand($sql_max_end)->queryAll();
            //如果结束时间大于当前的时间，任务正常流程格式化时间
            if (!empty($count1) && ($time < $max_endtime[0]['endtime'])) {
                //本周周任务
                $sql_this_week = 'SELECT createtime,endtime FROM app_week_task WHERE
                              createtime = \'' . $max_createtime . '\' AND endtime = \'' . $max_endtime[0]['endtime'] . '\' ';
                $this_week_task = Yii::app()->db->createCommand($sql_this_week)->queryAll();

                //本周周任务，任务创建时的时间格式；
                $this_week_c_date = date('Y-m-d H:i:s l', $this_week_task[0]['createtime']);
                $this_week_c_time = $this_week_task[0]['createtime'];

                $this_week_e_date = date('Y-m-d H:i:s l', $this_week_task[0]['endtime']);
                $this_week_e_time = $this_week_task[0]['endtime'];

                $arr['c_date'] = $this_week_c_date;
                $arr['c_time'] = $this_week_c_time;
                $arr['e_date'] = $this_week_e_date;
                $arr['e_time'] = $this_week_e_time;
                //当前时间大于任务结束时间
            } elseif (!empty($count1) && ($time > $max_endtime[0]['endtime'])) {
                //查看上周周任务结束时间
                $sql_endmax = 'SELECT max(endtime) AS e FROM app_week_task
                    WHERE endtime <= \'' . $time . '\'    limit 1 ';
                $count2 = WeekTask::model()->countBySql($sql_endmax);
                //如果没有上周周任务
                if (empty($count2)) {
                    $this_monday = self::this_monday();
                    $next_monday = self::getNextMonday();
                    $arr['c_time'] = $this_monday['time'];
                    $arr['c_date'] = $this_monday['date'];
                    $arr['e_date'] = $next_monday['date'];
                    $arr['e_time'] = $next_monday['time'];
                    //有上周周任务
                } else {
                    $max_endtime1 = Yii::app()->db->createCommand($sql_endmax)->queryAll();
                    //本周周任务开始时间，上周周任务==结束时间,
                    //本周周任务结束时间，下周周一
                    $next_monday = self::getNextMonday();
                    $arr['c_time'] = $max_endtime1[0]['e'];
                    $arr['c_date'] = date('Y-m-d H:i:s l', $arr['c_time']);
                    $arr['e_date'] = $next_monday['date'];
                    $arr['e_time'] = $next_monday['time'];
                }
                //是空的
            } else {
                $this_monday = self::this_monday();
                $next_monday = self::getNextMonday();
                $arr['c_time'] = $this_monday['time'];
                $arr['c_date'] = $this_monday['date'];
                $arr['e_date'] = $next_monday['date'];
                $arr['e_time'] = $next_monday['time'];
            }


            //如果完全没有任务，则本周周一，下周周一
        } else {
            $this_monday = self::this_monday();
            $next_monday = self::getNextMonday();
            $arr['c_time'] = $this_monday['time'];
            $arr['c_date'] = $this_monday['date'];
            $arr['e_date'] = $next_monday['date'];
            $arr['e_time'] = $next_monday['time'];
        }
        return $arr;
    }

    /**
     * 周任务数量
     */
    public function getCountByEndTime($id, $endtime, $time)
    {
        //全部任务数量
        $sql_total = "SELECT " . "COUNT(id) AS count,createtime,endtime  FROM app_week_task
						WHERE f_id = $id AND $time = $endtime";
        return Yii::app()->db->createcommand($sql_total)->queryAll();

    }

    /**
     * 查看任务数量，时间
     */
    public function getCountByEndTimeANDStatus($id, $time, $status)
    {
        $sql = 'SELECT COUNT(*) AS count,createtime,endtime FROM app_week_task WHERE
                    createtime = \'' . $time . '\' AND f_id = \'' . $id . '\'
                    AND status = \'' . $status . '\' ';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 查看任务数量是否等于存在
     */
    public function getCountByEndTimeIsOne($id, $at_id, $time, $status)
    {
        $sql = 'SELECT COUNT(*) AS count FROM app_week_task WHERE
                    createtime = \'' . $time . '\' AND at_id = \'' . $at_id . '\'
                    AND f_id = \'' . $id . '\'
                    AND status = \'' . $status . '\' ';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * @param $time_type
     * @param $time
     * @return mixed
     */
    public function getWeekTaskByTime($time_type, $time)
    {
        $sql = "SELECT * FROM app_week_task WHERE  $time_type = $time  ";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public function getMinCreatetimeByTime($time)
    {
        $sql = "SELECT MIN(createtime) AS createtime FROM app_week_task WHERE createtime > $time";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 周任务有效任务数量
     */
    public function getConCountByEndTime($id, $endtime)
    {
        $sql_con = 'SELECT COUNT(id) AS count FROM app_week_task
					WHERE f_id = \'' . $id . '\' AND endtime = \'' . $endtime . '\'
					AND isqualified = \'' . WeekTask::WEEK_TASK_ISCON_TRUE . '\' ';

        $con_count = Yii::app()->db->createcommand($sql_con)->queryAll();
        return $con_count[0]['count'];
    }

    /**
     * 周任务有效任务数量
     */
    public function getConCountByEndTime2($id, $endtime, $endtime1)
    {
        $sql_con = "SELECT " . " COUNT(id) AS count FROM app_week_task
					WHERE f_id =  $id  AND  $endtime1  =  $endtime
					AND isqualified = ". WeekTask::WEEK_TASK_ISCON_TRUE  ;

        $con_count = Yii::app()->db->createcommand($sql_con)->queryAll();
        return $con_count[0]['count'];
    }


    /**
     * 周任务有效任务数量
     */
    public function getConCountByParm($id, $ctime, $time)
    {
        $sql_con = "SELECT COUNT(id) AS count FROM app_week_task
					WHERE f_id =  $id AND " . $ctime . " = $time
					AND isqualified = " . WeekTask::WEEK_TASK_ISCON_TRUE;

        $con_count = Yii::app()->db->createcommand($sql_con)->queryAll();
        return $con_count[0]['count'];
    }

    /**
     * 上周周一
     */
    public function last_monday()
    {
        static $last_last_monday = array();
        $this_monday = self::this_monday();
        $last_last_monday['time'] = $this_monday['time'] - 7 * 24 * 3600;
        $last_last_monday['date'] = date('Y-m-d l H:i:s ', $last_last_monday['time']);
        return $last_last_monday;
    }

    /**
     * 获取本周周一
     * Enter description here ...
     * @param unknown_type $timestamp
     * @param unknown_type $is_return_timestamp
     */
    public function this_monday($timestamp = 0, $is_return_timestamp = true)
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
        $arr['date'] = date('Y-m-d l H:i:s ', $cache[$id]);
        return $arr;
    }

    /**
     * 获取下周周一
     * Enter description here ...
     */
    public function getNextMonday()
    {
        $arr = array();
        $arr['date'] = date('Y-m-d l H:i:s', strtotime('+1 week last monday'));
        $arr['time'] = strtotime($arr['date']);
        return $arr;
    }

    /**
     * 获取下下周周一
     * Enter description here ...
     */
    public function getNextNextMonday()
    {
        $arr = array();
        $arr['date'] = date('Y-m-d l H:i:s', strtotime('+2 week last monday'));
        $arr['time'] = strtotime($arr['date']);
        return $arr;
    }

    /**
     * 查询属于本周的周任务数量
     */
    public function getNumInThisWeek($id)
    {
        $arr = array();
        //本周周任务时间
        $arr = WeekTask::model()->getThisWeekWtTask($id);

        //本周周任务数量
        $sql_this = 'SELECT COUNT(id) AS count FROM app_week_task WHERE f_id = \'' . $id . '\'
			             AND createtime = \'' . $arr['c_time'] . '\'  ';

        //下周周任务数量
        $sql_next = 'SELECT COUNT(id) AS count FROM app_week_task WHERE f_id = \'' . $id . '\'
	    		         AND createtime = \'' . $arr['e_time'] . '\' ';

        $this_week_count = WeekTask::model()->countBySql($sql_this);
        $next_week_count = WeekTask::model()->countBySql($sql_next);

        $arr['this'] = !empty($this_week_count) ? $this_week_count : DefaultParm::DEFAULT_ZERO;
        $arr['next'] = !empty($next_week_count) ? $next_week_count : DefaultParm::DEFAULT_ZERO;

        return $arr;

    }

    /**
     * 获取指定时间的下周周一
     */
    function getMonday($d)
    {

        return date('Y-m-d', strtotime('next monday', strtotime($d)));
    }

    /**
     * 获取指定时间的上个周六
     */
    function getlastSatday($d)
    {
        $arr = array();

        $c = strtotime($d);
        $time_msg = WeekTask::model()->getLastWeekTaskTimeByThisWeekEndtime($c);

        //时间节点前移2天，默认周一前移两天等于上周周六
        $sat_date = date('Y-m-d', ($time_msg['e_time'] - 2 * 24 * 3600)); //时间格式
        $sat_time = strtotime($sat_date); //时间戳

        //时间节点前移两天，
        //再前移一周，找到对比周的时间
        //默认周六，前移一周，上周周六
        $last_sat_date = date('Y-m-d', ($time_msg['c_time'] - 2 * 24 * 3600)); //时间格式
        $last_sat_time = strtotime($last_sat_date); //时间戳

        $arr['sat_date'] = $sat_date;
        $arr['sat_time'] = $sat_time;
        $arr['last_sat_date'] = $last_sat_date;
        $arr['last_sat_time'] = $last_sat_time;
        return $arr;
    }


    /**
     * 获取上周周六和上上周周六的收益,昨天和前天的收益
     */
    public function getPayBackByMidInLastSat($sat, $last_sat, $mid)
    {
        $sql0 = "SELECT pathname FROM app_product where status=1 ";
        $_type = Yii::app()->db->createCommand($sql0)->queryAll();
        if (empty ($_type)) {
            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }
        $data = array();
        $sql = '';
        $sql1 = '';
        $sql2 = '';
        $sql3 = '';
        //昨天
        $yesterday = time() - 24 * 3600;
        $yesterday = date('Y-m-d', $yesterday);
        //前天
        $b_yesterday = time() - 2 * 24 * 3600;
        $b_yesterday = date('Y-m-d', $b_yesterday);


        foreach ($_type as $key => $keyword) {
            $TABLE = 'app_income_' . $keyword ['pathname'];

            //查看任务类型表，是否存在发布任务前天的数据

            $res = @TaskWhen::model()->getDataByTime($TABLE, $sat, $mid);
            $res1 = @TaskWhen::model()->getDataByTime($TABLE, $last_sat, $mid);

            $res2 = @TaskWhen::model()->getDataByTime($TABLE, $yesterday, $mid);
            $res3 = @TaskWhen::model()->getDataByTime($TABLE, $b_yesterday, $mid);

            $sat_date = !empty ($res[0] ['data']) ? $res  [0] ['createtime'] : '00-00-00';
            $last_sat_date = !empty ($res1[0] ['data']) ? $res1 [0] ['createtime'] : '00-00-00';
            $yesterday_date = !empty ($res2[0] ['data']) ? $res2 [0] ['createtime'] : '00-00-00';
            $b_yesterday_date = !empty ($res3[0] ['data']) ? $res3 [0] ['createtime'] : '00-00-00';

            if (!empty ($sql)) {
                $sql .= ' union ALL ';
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
            //周任务上周六的收益
            $sql .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime = '$sat_date'";
            //周任务上上周六的收益
            $sql1 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime  = '$last_sat_date' ";
            //昨天的收益总和
            $sql2 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime = '$yesterday_date'";
            //前天的收益总和
            $sql3 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime  = '$b_yesterday_date' ";

        }
        $sql_ = "SELECT sum(data) AS data FROM ($sql) AS a";
        $data_ = @Yii::app()->db->createcommand($sql_)->queryAll();
        $sat_data = ($data_ [0] ['data'] != null) ? $data_ [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        $sql_1 = "SELECT sum(data) AS data FROM ($sql1) AS a";
        $data_1 = @Yii::app()->db->createcommand($sql_1)->queryAll();
        $last_sat_data = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        $sql_2 = "SELECT sum(data) AS data FROM ($sql2) AS a";
        $data_2 = @Yii::app()->db->createcommand($sql_2)->queryAll();
        $yesterday_data = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        $sql_3 = "SELECT sum(data) AS data FROM ($sql3) AS a";
        $data_3 = @Yii::app()->db->createcommand($sql_3)->queryAll();
        $b_yesterday_data = ($data_3 [0] ['data'] != null) ? $data_3 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        /****************************/
        /*---------周六数据---------*/
        /****************************/
        $data['sat'] = $sat_data;
        $data['last_sat'] = $last_sat_data;
        if (($sat_data == DefaultParm::DEFAULT_ZERO) && ($last_sat_data == DefaultParm::DEFAULT_ZERO)) {

            $s = '无数据';
        } else {

            $s = @round(((($sat_data - $last_sat_data) / $last_sat_data) * 100), 2) . '%';
        }
        $data['sal'] = $s;
        if ($data['sat'] > $data['last_sat']) {

            $data ['sat_img'] = '/images/memberpool/2.jpg';
        } else if ($data['sat'] < $data['last_sat']) {

            $data ['sat_img'] = '/images/memberpool/1.jpg';
        } else {

            $data ['sat_img'] = '/images/memberpool/3.jpg';
        }
        /****************************/
        /*-------昨天/前天数据-------*/
        /****************************/
        $data['yesterday'] = $yesterday_data;
        $data['b_yesterday'] = $b_yesterday_data;
        if (($yesterday_data == DefaultParm::DEFAULT_ZERO) && ($b_yesterday_data == DefaultParm::DEFAULT_ZERO)) {

            $y = '无数据';
        } else {

            $y = @round(((($yesterday_data - $b_yesterday_data) / $b_yesterday_data) * 100), 2) . '%';
        }
        $data['y_sal'] = $y;
        if ($data['yesterday'] > $data['b_yesterday']) {

            $data ['y_img'] = '/images/memberpool/2.jpg';
        } else if ($data['yesterday'] < $data['b_yesterday']) {

            $data ['y_img'] = '/images/memberpool/1.jpg';
        } else {

            $data ['y_img'] = '/images/memberpool/3.jpg';
        }
        return $data;
    }

    public function getLastWeekTaskTimeByThisWeekEndtime($endtime)
    {
        $arr = array();
        $res = WeekTask::model()->find('endtime=:endtime', array(':endtime' => $endtime));
        $arr['c_time'] = $res['createtime'];
        $arr['e_time'] = $res['endtime'];
        return $arr;
    }

    /**
     * 获取周任务收益
     */
    public function getPayBcakWhereTaskTypeIsWeek($createtime, $endtime, $mid, $wtid)
    {

        $sql0 = "SELECT pathname FROM app_product where status=1 ";
        $_type = Yii::app()->db->createCommand($sql0)->queryAll();

        if (empty ($_type)) {

            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }

        $data = array();
        $sql = '';
        $sql1 = '';
        $createtime = $createtime; //shan

        $endtime = $endtime;

        $createtime = date('Y-m-d H:i:s', $createtime); //shan
        $endtime = date('Y-m-d', $endtime);

        $get_sat = WeekTask::model()->getlastSatday($endtime);

        //任务开始与结束的日期间隔
        $day = (strtotime($endtime) - strtotime($createtime)) / (24 * 3600); //shan

        //任务开始前的 间隔日期的 对比周的开始时间
        $last_date_b_createtime = date('Y-m-d l', (strtotime($createtime) - $day * 24 * 3600)); //shan

        //任务开始前的一天前的日期
        $last_date_b_endtime = date('Y-m-d l', (strtotime($createtime) - 24 * 3600)); //shan

        $sat_1 = $get_sat['sat_date'];
        $sat_2 = $get_sat['last_sat_date'];

        foreach ($_type as $key => $keyword) {
            $TABLE = 'app_income_' . $keyword ['pathname'];

            if (!empty ($sql)) {
                $sql .= ' union ALL ';
            }
            if (!empty ($sql1)) {
                $sql1 .= ' union ALL ';
            }

            //获取周任务收益总和
            $sql .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND status=1 AND createtime = " . "'" . $sat_1 . "'";

            //获取对比周的收益总和
            $sql1 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND status=1 AND createtime =" . "'" . $sat_2 . "'";
        }
        //获取周任务收益总和
        $sql_ = "SELECT sum(data) AS data FROM ($sql) AS a";
        $data_ = Yii::app()->db->createcommand($sql_)->queryAll();
        $week_data = ($data_ [0] ['data'] != null) ? $data_ [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        //获取对比周的收益总和
        $sql_1 = "SELECT sum(data) AS data FROM ($sql1) AS a";
        $data_1 = Yii::app()->db->createcommand($sql_1)->queryAll();
        $last_week_data = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        //对比周的时间
        $data['old-c'] = $last_date_b_createtime; //shan
        $data['old-e'] = $last_date_b_endtime; //shan

        $data['data'] = $week_data; //周任务收益总和
        $data['b_data'] = $last_week_data; //对比周的收益总和

        $pay_back = $week_data - $last_week_data; //收益

        $data['subtract'] = $pay_back;
        //任务收益存入数据库

        if (($week_data == DefaultParm::DEFAULT_ZERO) && ($last_week_data == DefaultParm::DEFAULT_ZERO)) {

            $y = '无数据';
            $k = 0;
        } else if (($week_data != 0) && ($last_week_data == 0)) {

            $y = 100 . '%';
            $k = 100;
        } else if (($week_data != 0) && ($last_week_data != 0)) {

            $key = round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
            if ($key > 100) {

                $y = 100 . '%';
                $k = 100;
            } else {

                $y = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                    round(((($week_data - $last_week_data) / $last_week_data) * 100), 2) . '%';

                $k = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                    round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
            }

        } else {

            $y = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                round(((($week_data - $last_week_data) / $last_week_data) * 100), 2) . '%';

            $k = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
        }


        $data['y_sal'] = $y;
        $data['k_sal'] = $k;

        if (($k < WeekTask::MIN_INCREASE) || ($data['subtract'] < self::MIN_PAYBACK)) {

            $data['conformity'] = self::WEEK_TASK_ISCON_FALSE; //不合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_BACK,
                'isqualified' => self::WEEK_TASK_ISCON_FALSE,
                'time_slow' => 1,
                'target_payback' => $week_data,
                'contrast_payback' => $last_week_data,
                'payback' => $pay_back,
                'growth' => $y));

        } else if (($k >= self::MIN_INCREASE) && ($data['subtract'] > self::MIN_PAYBACK)) {
            $data['conformity'] = self::WEEK_TASK_ISCON_TRUE; //合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_DONE,
                'isqualified' => self::WEEK_TASK_ISCON_TRUE,
                'time_slow' => 1,
                'target_payback' => $week_data,
                'contrast_payback' => $last_week_data,
                'payback' => $pay_back,
                'growth' => $y));
        }else{
            $data['conformity'] = self::WEEK_TASK_ISCON_TRUE; //合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_DONE,
                'isqualified' => self::WEEK_TASK_ISCON_TRUE,
                'time_slow' => 1,
                'target_payback' => $week_data,
                'contrast_payback' => $last_week_data,
                'payback' => $pay_back,
                'growth' => $y));
        }

        if ($data['data'] > $data['b_data']) {
            $data ['y_img'] = '/images/memberpool/2.jpg';
        } else if ($data['data'] < $data['b_data']) {
            $data ['y_img'] = '/images/memberpool/1.jpg';
        } else {
            $data ['y_img'] = '/images/memberpool/3.jpg';
        }

        return $data;

    }

    /**
     * 获取周任务收益
     */
    public function getPayBcakWhereTaskTypeIsWeek2($createtime, $endtime, $mid, $wtid)
    {

        $sql0 = "SELECT pathname FROM app_product where status=1 ";
        $_type = Yii::app()->db->createCommand($sql0)->queryAll();

        if (empty ($_type)) {

            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
            exit ();
        }

        $data = array();
        $sql = '';
        $sql1 = '';
        $createtime = $createtime; //shan

        $endtime = $endtime;

        $createtime = date('Y-m-d H:i:s', $createtime); //shan
        $endtime = date('Y-m-d', $endtime);

        $get_sat = WeekTask::model()->getlastSatday($endtime);

        //任务开始与结束的日期间隔
        $day = (strtotime($endtime) - strtotime($createtime)) / (24 * 3600); //shan

        //任务开始前的 间隔日期的 对比周的开始时间
        $last_date_b_createtime = date('Y-m-d l', (strtotime($createtime) - $day * 24 * 3600)); //shan

        //任务开始前的一天前的日期
        $last_date_b_endtime = date('Y-m-d l', (strtotime($createtime) - 24 * 3600)); //shan

        $sat_1 = $get_sat['sat_date'];
        $sat_2 = $get_sat['last_sat_date'];

        foreach ($_type as $key => $keyword) {
            $TABLE = 'app_income_' . $keyword ['pathname'];

            if (!empty ($sql)) {
                $sql .= ' union ALL ';
            }
            if (!empty ($sql1)) {
                $sql1 .= ' union ALL ';
            }

            //获取周任务收益总和
            $sql .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime = " . "'" . $sat_1 . "'";

            //获取对比周的收益总和
            $sql1 .= "SELECT data FROM $TABLE
						WHERE uid= $mid AND createtime =" . "'" . $sat_2 . "'";
        }
        //获取周任务收益总和
        $sql_ = "SELECT sum(data) AS data FROM ($sql) AS a";
        $data_ = Yii::app()->db->createcommand($sql_)->queryAll();
        $week_data = ($data_ [0] ['data'] != null) ? $data_ [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        //获取对比周的收益总和
        $sql_1 = "SELECT sum(data) AS data FROM ($sql1) AS a";
        $data_1 = Yii::app()->db->createcommand($sql_1)->queryAll();
        $last_week_data = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : DefaultParm::DEFAULT_ZERO;

        //对比周的时间
        $data['old-c'] = $last_date_b_createtime; //shan
        $data['old-e'] = $last_date_b_endtime; //shan

        $data['data'] = $week_data; //周任务收益总和
        $data['b_data'] = $last_week_data; //对比周的收益总和

        $pay_back = $week_data - $last_week_data; //收益

        $data['subtract'] = $pay_back;
        //任务收益存入数据库

        if (($week_data == DefaultParm::DEFAULT_ZERO) && ($last_week_data == DefaultParm::DEFAULT_ZERO)) {

            $y = '无数据';
            $k = 0;
        } else if (($week_data != 0) && ($last_week_data == 0)) {

            $y = 100 . '%';
            $k = 100;
        } else if (($week_data != 0) && ($last_week_data != 0)) {

            $key = round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
            if ($key > 100) {

                $y = 100 . '%';
                $k = 100;
            } else {

                $y = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                    round(((($week_data - $last_week_data) / $last_week_data) * 100), 2) . '%';

                $k = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                    round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
            }

        } else {

            $y = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                round(((($week_data - $last_week_data) / $last_week_data) * 100), 2) . '%';

            $k = (empty($last_week_data)) ? round($week_data, 2) . '%' :
                round(((($week_data - $last_week_data) / $last_week_data) * 100), 2);
        }

        $data['y_sal'] = $y;
        $data['k_sal'] = $k;
        if (($k < WeekTask::MIN_INCREASE) || ($data['subtract'] < self::MIN_PAYBACK)) {

            $data['conformity'] = self::WEEK_TASK_ISCON_FALSE; //不合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_BACK,
                'isqualified' => self::WEEK_TASK_ISCON_FALSE,
                'time_slow2' => 1,
                //   'target_payback' => $week_data,
                //   'contrast_payback' => $last_week_data,
                'payback' => $pay_back
                //   'growth' => $y
            ));

        } else if (($k >= self::MIN_INCREASE) && ($data['subtract'] > self::MIN_PAYBACK)) {
            $data['conformity'] = self::WEEK_TASK_ISCON_TRUE; //合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_DONE,
                'isqualified' => self::WEEK_TASK_ISCON_TRUE,
                'time_slow2' => 1,
                //  'target_payback' => $week_data,
                //   'contrast_payback' => $last_week_data,
                'payback' => $pay_back
                //  'growth' => $y
            ));
        }else{
            $data['conformity'] = self::WEEK_TASK_ISCON_TRUE; //合格
            WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_DONE,
                'isqualified' => self::WEEK_TASK_ISCON_TRUE,
                'time_slow2' => 1,
                //  'target_payback' => $week_data,
                //   'contrast_payback' => $last_week_data,
                'payback' => $pay_back
                //  'growth' => $y
            ));
        }

        if ($data['data'] > $data['b_data']) {
            $data ['y_img'] = '/images/memberpool/2.jpg';
        } else if ($data['data'] < $data['b_data']) {
            $data ['y_img'] = '/images/memberpool/1.jpg';
        } else {
            $data ['y_img'] = '/images/memberpool/3.jpg';
        }

        return $data;

    }


    /**
     * 修改任务状态
     * Enter description here ...
     * @param unknown_type $con
     * @param unknown_type $wtid
     */
    public function updateWeekTaskStatusByCon($con, $wtid)
    {
        switch ($con) {
            case self::WEEK_TASK_ISCON_FALSE:
                $count = WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_BACK, 'isqualified' => self::WEEK_TASK_ISCON_FALSE));
                break;
            case self::WEEK_TASK_ISCON_TRUE:
                $count = WeekTask::model()->updateByPk($wtid, array('status' => WeekTask::STATUS_DONE, 'isqualified' => self::WEEK_TASK_ISCON_TRUE));
                break;
        }
        return $count;
    }

    /**
     * 上一周任务的操作
     */
    public function lastWeekTaskMsg($this_week_ctime, $time, $id, $endtime, $createtime)
    {
        $WEEKTASKTIME = array();
        $model = WeekTask::model()->findAllByAttributes(array('endtime' => $this_week_ctime, 'f_id' => $id));
        if (!empty($model[0]->endtime)) {
            $WEEKTASKTIME['start'] = $model[0]->createtime;
            $WEEKTASKTIME['end'] = $model[0]->endtime;
        } else {
            throw new CHttpException(404, '没有上一周的周任务.');
        }

//        上一周任务到当前时间的天数
        $sub = ($time - $this_week_ctime) / (24 * 3600);

        $t = Yii::app()->db->beginTransaction();
        try {
            $arr = array();
            $del = array();
            $payback = '';

            foreach ($model AS $key => $item) {
                if ($item->status == WeekTask::STATUS_NORMAL) { //正常状态任务
                    $arr[$key] = $model[$key]->id;
                    $del[$key] = $model[$key]->m_id;
                }
            }

            if (!empty($arr) && !empty($del)) {
                $wtid_list = implode(',', $arr);
                $mid_list = implode(',', $del);

                //修改任务状态
                $ret = WeekTask::model()->updateAll(array('status' => WeekTask::STATUS_PRO), " id in ( " . $wtid_list . " )");
                //释放任务用户
                $res = Member::model()->updateAll(array('wt_id' => DefaultParm::DEFAULT_ZERO), " id in ( " . $mid_list . " )");

            }
            //全部任务数量
            $total_count = WeekTask::model()->getCountByEndTime($id, $this_week_ctime, $endtime);
            $total_count = $total_count[0]['count'];

            //有效任务数量
            $con_count = WeekTask::model()->getConCountByEndTime($id, $this_week_ctime);

            //$total = ($total_count < WeekTask::TOTAL) ? $total_count : WeekTask::TOTAL;

            $total = $total_count;

            //有效率
            $Conformity = round((($con_count / $total) * 100), 3);

            $ar['total'] = WeekTask::TOTAL;
            $ar['now_total'] = $total;
            $ar['con'] = $con_count;
            $ar['conformity'] = $Conformity . '%';
            $detuct = DefaultParm::DEFAULT_HUNDRED;

        if(time() >= $this_week_ctime){

            //任务失效
            if ($Conformity < WeekTask::MIN_VALID) {

                $sql = 'SELECT is_pro,time_slow FROM app_week_task WHERE f_id = \'' . $id . '\' AND  endtime = \'' . $this_week_ctime . '\' ';
                $is_pro = Yii::app()->db->createCommand($sql)->queryAll();

                if ((($is_pro[0]['is_pro'] == null) || ($is_pro[0]['is_pro'] == WeekTask::WEEK_TASK_ISPRO_FALSE))
                    && ($is_pro[0]['time_slow'] == 1)
                ) {

                    //当前客服的等级
                    $role = Manage::model()->findByPk($id);
                    //客服等级，是高级客服，计算扣款金额
                    if ($role->role <= Role::ADVANCED_STAFF) {

                        //任务合格率无效，有2个任务合格
                        if ($ar['con'] == 2) {
                            $detuct = $detuct;
                        } else if ($ar['con'] == 1) {
                            $detuct = 2 * $detuct;
                        } else if ($ar['con'] == 0) {
                            $detuct = 3 * $detuct;
                        } else {
                            $detuct = 0;
                        }

                        //客服等级是普通客服，扣款金额为0
                    } elseif ($role->role > Role::ADVANCED_STAFF) {
                        $detuct = DefaultParm::DEFAULT_ZERO;
                    }

                    $pro = date('Y-m', $this_week_ctime);

                    $sql2 = 'UPDATE app_week_task set is_pro =  \'' . self::WEEK_TASK_ISPRO_TRUE . '\' ,
                            status = \'' . WeekTask::STATUS_DELETE . '\'  WHERE f_id = \'' . $id . '\'
                            AND  endtime = \'' . $this_week_ctime . '\' ';
                    $res = Yii::app()->db->createCommand($sql2)->execute();

                    $detuct = 0 - $detuct;

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
                //任务合格
            } else if ($Conformity >= WeekTask::MIN_VALID) {


                $sql = 'SELECT is_pro,time_slow FROM app_week_task WHERE f_id = \'' . $id . '\' AND  endtime = \'' . $this_week_ctime . '\' ';
                $is_pro = Yii::app()->db->createCommand($sql)->queryAll();
                if ((($is_pro[0]['is_pro'] == null) || ($is_pro[0]['is_pro'] == self::WEEK_TASK_ISPRO_FALSE)) && ($is_pro[0]['time_slow'] == 1)) {
                    // $role = Manage::model()->getRoleByUid($id);
                    $role = Manage::model()->findByPk($id);
                    if ($role->role <= Role::ADVANCED_STAFF) {
                        if ($ar['con'] == 3) {

                            $payback = DefaultParm::DEFAULT_ZERO;
                        } else if ($ar['con'] == 4) {

                            $payback = self::PAYBACK_MIN;
                        } else if ($ar['con'] == 5) {

                            $payback = self::PAYBACK_MIDDLE;
                        } else if ($ar['con'] >= 6) {

                            $payback = self::PAYBACK_MAX;
                        }
                    } elseif ($role->role > Role::ADVANCED_STAFF) {

                        $payback = DefaultParm::DEFAULT_ZERO;
                    }
                    $pro = date('Y-m', $this_week_ctime);

                    $sql2 = 'UPDATE app_week_task set is_pro =  \'' . self::WEEK_TASK_ISPRO_TRUE . '\'
                                WHERE f_id = \'' . $id . '\' AND  endtime = \'' . $this_week_ctime . '\' ';
                    $res = Yii::app()->db->createCommand($sql2)->execute();

                    TaskWeekEarnings::model()->InsertWeekEarnings($id, $model[0]->createtime, $model[0]->endtime, $Conformity, $con_count, $total, $payback);

                    $model2 = TaskWeekEarnings::model()->findByAttributes(array('createtime' => $model[0]->createtime, 'uid' => $id));

                    if (isset($model2->id)) {

                        $model2->percent  = $Conformity;
                        $model2->concount = $con_count;
                        $model2->askcount = $total;
                        $model2->payback  = $payback;
                        $model2->update();
                    } else {

                        TaskWeekEarnings::model()->InsertWeekEarnings($id, $model[0]->createtime, $model[0]->endtime, $Conformity, $con_count, $total, $payback);
                    }
                }
            }
        }
            $t->commit();
        } catch (Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $t->rollBack();
        }

        $WEEKTASKTIME['count'] = $ar;
        return $WEEKTASKTIME;
    }

    /**
     * 本周任务的操作
     */
    public function thisWeekTaskMsg($this_week_ctime, $time, $id, $endtime, $createtime)
    {

        $model = WeekTask::model()->findByAttributes(array('createtime' => $this_week_ctime, 'f_id' => $id));

        if (!empty($model->endtime)) {
            $WEEKTASKTIME['start'] = @$model->createtime;
            $WEEKTASKTIME['end'] = @$model->endtime;
        } else {
            throw new CHttpException(404, '没有本周的周任务.');
        }
        //全部任务数量
        $total_count = @WeekTask::model()->getCountByEndTime($id, $this_week_ctime, $createtime);

        $total_count = @$total_count[0]['count'];
        $ar['total'] = @WeekTask::TOTAL;
        $ar['now_total'] = @$total_count;

        @$WEEKTASKTIME['count'] = $ar;

        return $WEEKTASKTIME;
    }

    /**
     * 下一周任务的操作
     */
    public function nextWeekTaskMsg($this_week_etime, $time, $id, $endtime, $createtime)
    {

        $model = WeekTask::model()->findByAttributes(array('createtime' => $this_week_etime, 'f_id' => $id));

        if (!empty($model->endtime)) {
            $WEEKTASKTIME['start'] = $model->createtime;
            $WEEKTASKTIME['end'] = $model->endtime;
        } else {
            throw new CHttpException(404, '没有下一周的周任务.');
        }
        //全部任务数量
        $total_count = WeekTask::model()->getCountByEndTime($id, $this_week_etime, $createtime);
        $total_count = $total_count[0]['count'];
        $ar['total'] = WeekTask::TOTAL;
        $ar['now_total'] = $total_count;

        $WEEKTASKTIME['count'] = $ar;
        return $WEEKTASKTIME;
    }
    /*
     * 客服晋级--查看月内是否合格---新版（用户池业绩>=X）20160511
     * 合格返回1，不合格0，默认0
     * */
    public function checkConByMonths($status,$id)
    {

        //客服晋升到高级客服,$status==100计算用户总收益
        if($status==1 || $status==100)
        {
            //默认没有成功，0，不能晋级
            $success = DefaultParm::DEFAULT_ZERO;
            //客服所属正在进行中---新用户任务
            $tasking = Task::model()->findAll('accept=:accept and type=1',array(":accept"=>$id));
            if(empty($tasking))
            {
                return $success;
            }
            //mid--用户id  createtime--发布时间
            $firstday=$this->GetLastMonth(time());//上个月第一天
            $firstdaystr=strtotime($firstday);//上个月第一天格式化

            $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstday +1 month -1 day")));//上个月最后一天
            $lastdaystr = date('Y-m-d', $lastday); //上个月最后一天格式化

            $sql0 = 'SELECT pathname ' . 'FROM app_product where status=1 ';
            $_type = Yii::app()->db->createCommand($sql0)->queryAll();
            if (empty ($_type)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit ();
            }

            $sql1 = DefaultParm::DEFAULT_EMPTY;
            foreach ($tasking as $tk => $tv)
            {
                $mid=$tv["mid"];//用户id
                $createtime=$tv["createtime"];//发布时间

                /** @noinspection PhpSillyAssignmentInspection */
                $a_time = $createtime; //任务发布申请时间
                $ask_time = date('Y-m', $a_time); //任务申请/发布的时间格式化

                //月份不符
                if (strtotime($ask_time) > strtotime(date('Y-m', $firstdaystr))) {
                    continue;
                }
                if($tv["status"]==3 || $tv["status"]==2)
                {
                    $motifytime=$tv["motifytime"];//任务上报/放弃时间
                    $m_time = $motifytime; //任务上报/放弃时间
                    $up_time = date('Y-m', $m_time); //任务上报/放弃的时间格式化

                    //月份不符
                    if (strtotime($up_time) != strtotime(date('Y-m', $firstdaystr)) && strtotime($up_time) != strtotime(date('Y-m', time()))) {continue;}
                    $twres=TaskWhen::model()->find('tid=:tid',array('tid'=>$tv["id"]));
                    //放弃任务不计
                    if($twres["isfail"]==1){continue;}

                    //申请月--上报月比较
                    if (strtotime($ask_time) == strtotime($up_time))
                    {
                        $a_time = ($a_time + 24 * 3600); //任务开始时间 = 发布任务的第二天
                        $firstday = date('Y-m-d', $a_time); //任务开始时间格式化
                        $m_time = ($m_time - 24 * 3600); //任务上报时间 = 上报任务的前一天
                        $lastdaystr = date('Y-m-d', $m_time); //任务上报时间格式化
                    }
                    elseif (strtotime($up_time)==strtotime(date('Y-m', time())) && strtotime($ask_time) ==strtotime(date('Y-m', $firstdaystr)))
                    {
                        $a_time = ($a_time + 24 * 3600);
                        $firstday = date('Y-m-d', $a_time);
                    }
                    elseif (strtotime($up_time)==strtotime(date('Y-m', $firstdaystr)) && strtotime($ask_time) < strtotime(date('Y-m', $firstdaystr)))
                    {
                        $m_time = ($m_time - 24 * 3600);
                        $lastdaystr = date('Y-m-d', $m_time);
                    }
                    else
                    {
                        $firstday = $firstday;
                        $lastdaystr = $lastdaystr;
                    }

                }
                //不是整月数据，从发布日期开始到月末
                elseif ($a_time > $firstdaystr)
                {
                    $a_time = ($a_time + 24 * 3600); //任务开始时间 = 发布任务的第二天开始
                    $firstday = date('Y-m-d', $a_time); //任务开始时间格式化
                }

                foreach ($_type as $key => $keyword) {
                    $TABLE = 'app_income_' . $keyword ['pathname'];
                    if (!empty ($sql1)) {
                        $sql1 .= ' union ALL ';
                    }
                    $sql1 .= "SELECT data FROM" . " $TABLE
						WHERE uid= $mid AND status=1 AND createtime between '$firstday' and '$lastdaystr' ";
                }

            }
            if(empty($sql1))
            {
                $data_2[0]['data'] = "";
            }
            else
            {
                $sql3 = "SELECT sum(data) AS data" . " FROM ($sql1) AS a";
                $data_2 = Yii::app()->db->createcommand($sql3)->queryAll();
            }

            $data_new = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : 0;

            //$status==100计算用户总收益
            if($status==100)
            {
                return $data_new;
            }

            //客服晋升到高级客服
            if ($data_new >= 20000)
            {
                   $success = DefaultParm::DEFAULT_ONE;
            }
            else
            {
                $success = $success;
            }
            return $success;
            //
        }

        //高级客服晋升到见习主管
        if($status==3)
        {
            //客服所属正在进行中---新用户任务
            $tasking = Task::model()->findAll('accept=:accept and status<=2 and type=1',array(":accept"=>$id));
            //mid--用户id  createtime--发布时间
            $firstday=$this->GetLastMonth(time());//上个月第一天
            $firstdaystr=strtotime($firstday);//上个月第一天格式化

            $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstday +1 month -1 day")));//上个月最后一天
            $lastdaystr = date('Y-m-d', $lastday); //上个月最后一天格式化

            $sql0 = 'SELECT pathname ' . 'FROM app_product where status=1 ';
            $_type = Yii::app()->db->createCommand($sql0)->queryAll();
            if (empty ($_type)) {
                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR));
                exit ();
            }

            $sql1 = DefaultParm::DEFAULT_EMPTY;
            foreach ($tasking as $tk => $tv)
            {
                $mid=$tv["mid"];//用户id
                $createtime=$tv["createtime"];//发布时间

                /** @noinspection PhpSillyAssignmentInspection */
                $a_time = $createtime; //任务发布申请时间
                $ask_time = date('Y-m', $a_time); //任务申请/发布的时间格式化

                //月份不符
                if (strtotime($ask_time) > strtotime(date('Y-m', $firstdaystr))) {
                    continue;
                }

                //不是整月数据，从发布日期开始到月末
                if ($a_time > $firstdaystr) {
                    $a_time = ($a_time + 24 * 3600); //任务开始时间 = 发布任务的第二天开始
                    $firstday = date('Y-m-d', $a_time); //任务开始时间格式化
                }

                foreach ($_type as $key => $keyword) {
                    $TABLE = 'app_income_' . $keyword ['pathname'];
                    if (!empty ($sql1)) {
                        $sql1 .= ' union ALL ';
                    }
                    $sql1 .= "SELECT data FROM" . " $TABLE
						WHERE uid= $mid AND status=1 AND createtime between '$firstday' and '$lastdaystr' ";
                }

            }

            $sql3 = "SELECT sum(data) AS data" . " FROM ($sql1) AS a";

            $data_2 = Yii::app()->db->createcommand($sql3)->queryAll();
            $data_new = ($data_2 [0] ['data'] != null) ? $data_2 [0] ['data'] : 0;

            //默认没有成功，0，不能晋级
            $success = DefaultParm::DEFAULT_ZERO;

            //客服晋升到高级客服
            if ($data_new >= 20000)
            {
                $success = DefaultParm::DEFAULT_ONE;
            }
            else
            {
                $success = $success;
            }
            return $success;
            //
        }
        //if

    }
    /*
     * 查看两周内是否合格，
     * 合格返回1，不合格0，默认0
     * */
    public function checkConByWeek($this_week, $id, $status = '')
    {
        $l_arr = array();
        $bl_arr = array();
        $this_week = $this_week; //本周任务开始时间
        if ((isset($status)) && ($status == 2)) {

        }
        //查找上周周任务开始结束时间
        $sql_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $this_week . '\'  ';
        $last_week_task_time = Yii::app()->db->createCommand($sql_last)->queryAll();
        $l_arr['l_c_time'] = isset($last_week_task_time[0]['createtime']) ? $last_week_task_time[0]['createtime'] : '0';
        $l_arr['l_e_time'] = $this_week;

        //查找上上周任务开始结束时间
        $sql_b_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $l_arr['l_c_time'] . '\'  ';
        $b_last_week_task_time = Yii::app()->db->createCommand($sql_b_last)->queryAll();
        $bl_arr['l_c_time'] = isset($b_last_week_task_time[0]['createtime']) ? $b_last_week_task_time[0]['createtime'] : '';
        $bl_arr['l_e_time'] = $l_arr['l_c_time'];

        $last_count = WeekTask::model()->getConCount($id, $l_arr['l_c_time']);

        //默认没有成功，0，不能晋级
        $success = DefaultParm::DEFAULT_ZERO;
        if ($last_count >= self::CON_WEEK_TASK_MIN) {

            $b_last_count = WeekTask::model()->getConCount($id, $bl_arr['l_c_time']);

            if ($b_last_count >= self::CON_WEEK_TASK_MIN) {

                $success = DefaultParm::DEFAULT_ONE;
            }
        } else {
            $success = $success;
        }
        return $success;
    }
    /*
     * 查看4周内是否合格，
     * 合格返回1，不合格0，默认0
     * */
    public function checkConByMonth($this_week, $id, $status = '')
    {
        $l_arr = array();
        $bl_arr = array();
        $cl_arr = array();
        $dl_arr = array();
        $this_week = $this_week; //本周任务开始时间
        if ((isset($status)) && ($status == 2)) {

        }
        //查找上周周任务开始结束时间
        $sql_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $this_week . '\'  ';
        $last_week_task_time = Yii::app()->db->createCommand($sql_last)->queryAll();
        $l_arr['l_c_time'] = isset($last_week_task_time[0]['createtime']) ? $last_week_task_time[0]['createtime'] : '0';
        $l_arr['l_e_time'] = $this_week;

        //查找上上周任务开始结束时间
        $sql_b_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $l_arr['l_c_time'] . '\'  ';
        $b_last_week_task_time = Yii::app()->db->createCommand($sql_b_last)->queryAll();
        $bl_arr['l_c_time'] = isset($b_last_week_task_time[0]['createtime']) ? $b_last_week_task_time[0]['createtime'] : '';
        $bl_arr['l_e_time'] = $l_arr['l_c_time'];
        //查找上上上周周任务开始结束时间
        $sql_c_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $bl_arr['l_c_time'] . '\'  ';
        $c_last_week_task_time = Yii::app()->db->createCommand($sql_c_last)->queryAll();
        $cl_arr['l_c_time'] = isset($c_last_week_task_time[0]['createtime']) ? $c_last_week_task_time[0]['createtime'] : '';
        $cl_arr['l_e_time'] = $bl_arr['l_c_time'];
        //查找上上上上周周任务开始结束时间
        $sql_d_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $cl_arr['l_c_time'] . '\'  ';
        $d_last_week_task_time = Yii::app()->db->createCommand($sql_d_last)->queryAll();
        $dl_arr['l_c_time'] = isset($d_last_week_task_time[0]['createtime']) ? $d_last_week_task_time[0]['createtime'] : '';
        $dl_arr['l_e_time'] = $cl_arr['l_c_time'];


        $last_count = WeekTask::model()->getConCount($id, $l_arr['l_c_time']);

        //默认没有成功，0，不能晋级
        $success = DefaultParm::DEFAULT_ZERO;
        if ($last_count >= self::CON_WEEK_TASK_MIN) {
            $b_last_count = WeekTask::model()->getConCount($id, $bl_arr['l_c_time']);
            if ($b_last_count >= self::CON_WEEK_TASK_MIN) {
                $c_last_count = WeekTask::model()->getConCount($id, $cl_arr['l_c_time']);
                if($c_last_count >= self::CON_WEEK_TASK_MIN){
                    $d_last_count = WeekTask::model()->getConCount($id, $dl_arr['l_c_time']);
                    if($d_last_count >= self::CON_WEEK_TASK_MIN){
                        $success = DefaultParm::DEFAULT_ONE;
                    }else{
                        $success = $success;
                    }
                }else{
                    $success = $success;
                }
            }else{
                $success = $success;
            }
        } else {
            $success = $success;
        }
        return $success;
    }
    /*
     * 查看4周内是否合格，
     * 每周任务完成大于3个
     * 合格返回1，不合格0，默认0
     * */
    public function checkConByMontha($this_week, $id, $status = '')
    {
        $l_arr = array();
        $bl_arr = array();
        $cl_arr = array();
        $dl_arr = array();
        $this_week = $this_week; //本周任务开始时间
        if ((isset($status)) && ($status == 2)) {

        }
        //查找上周周任务开始结束时间
        $sql_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $this_week . '\'  ';
        $last_week_task_time = Yii::app()->db->createCommand($sql_last)->queryAll();
        $l_arr['l_c_time'] = isset($last_week_task_time[0]['createtime']) ? $last_week_task_time[0]['createtime'] : '0';
        $l_arr['l_e_time'] = $this_week;

        //查找上上周任务开始结束时间
        $sql_b_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $l_arr['l_c_time'] . '\'  ';
        $b_last_week_task_time = Yii::app()->db->createCommand($sql_b_last)->queryAll();
        $bl_arr['l_c_time'] = isset($b_last_week_task_time[0]['createtime']) ? $b_last_week_task_time[0]['createtime'] : '';
        $bl_arr['l_e_time'] = $l_arr['l_c_time'];
        //查找上上上周周任务开始结束时间
        $sql_c_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $bl_arr['l_c_time'] . '\'  ';
        $c_last_week_task_time = Yii::app()->db->createCommand($sql_c_last)->queryAll();
        $cl_arr['l_c_time'] = isset($c_last_week_task_time[0]['createtime']) ? $c_last_week_task_time[0]['createtime'] : '';
        $cl_arr['l_e_time'] = $bl_arr['l_c_time'];
        //查找上上上上周周任务开始结束时间
        $sql_d_last = 'SELECT createtime FROM app_week_task WHERE endtime = \'' . $cl_arr['l_c_time'] . '\'  ';
        $d_last_week_task_time = Yii::app()->db->createCommand($sql_d_last)->queryAll();
        $dl_arr['l_c_time'] = isset($d_last_week_task_time[0]['createtime']) ? $d_last_week_task_time[0]['createtime'] : '';
        $dl_arr['l_e_time'] = $cl_arr['l_c_time'];


        $last_count = WeekTask::model()->getConCount($id, $l_arr['l_c_time']);

        //默认没有成功，0，不能晋级
        $success = DefaultParm::DEFAULT_ZERO;
        if ($last_count > self::CON_WEEK_TASK_MIN) {
            $b_last_count = WeekTask::model()->getConCount($id, $bl_arr['l_c_time']);
            if ($b_last_count > self::CON_WEEK_TASK_MIN) {
                $c_last_count = WeekTask::model()->getConCount($id, $cl_arr['l_c_time']);
                if($c_last_count > self::CON_WEEK_TASK_MIN){
                    $d_last_count = WeekTask::model()->getConCount($id, $dl_arr['l_c_time']);
                    if($d_last_count > self::CON_WEEK_TASK_MIN){
                        $success = DefaultParm::DEFAULT_ONE;
                    }else{
                        $success = $success;
                    }
                }else{
                    $success = $success;
                }
            }else{
                $success = $success;
            }
        } else {
            $success = $success;
        }
        return $success;
    }

    public function getConCount($id, $createtime)
    {
        $sql = 'SELECT COUNT(id) AS count FROM app_week_task WHERE f_id = \'' . $id . '\' AND
                createtime = \'' . $createtime . '\' AND isqualified = \'' . self::WEEK_TASK_ISCON_TRUE . '\' ';
        $count = Yii::app()->db->createCommand($sql)->queryAll();
        return $count[0]['count'];
    }

    public function mFristAndLast($y = "", $m = "")
    {
        if ($y == "") $y = date("Y");
        if ($m == "") $m = date("m");
        $m = sprintf("%02d", intval($m));
        $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

        $m > 12 || $m < 1 ? $m = 1 : $m = $m;

        $firstday = strtotime($y . $m . "01000000");
        $firstdaystr = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
        return array("firstday" => $firstday, "lastday" => $lastday);

//        $firstday = date('Y-m-01',strtotime($day));
//	    $lastday = date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    }

    public function GetLastMonth($time)
    { //获取指定日期上个月的第一天和最后一天

        $arr = getdate();
        if ($arr['mon'] == 1) {
            $year = $arr['year'] - 1;
            $month = $arr['mon'] + 11;
            $day = $arr['mday'];
            if ($day < 10) {
                $mday = '0' . $day;
            } else {
                $mday = $day;
            }
            $firstday = $year . $month . '-01';
        } else {
            $time = $time;
            $firstday = date('Y-m-01', strtotime(date('Y', $time) . '-' . (date('m', $time) - 1) . '-01'));
        }
        return $firstday;
    }


    public function GetNextMonth($time)
    { //获取指定日期下个月的第一天和最后一天

        $arr = getdate();
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $day = $arr['mday'];
            if ($day < 10) {
                $mday = '0' . $day;
            } else {
                $mday = $day;
            }
            $firstday = $year . '-0' . $month . '-01';
        } else {
            $time = $time;
            $firstday = date('Y-m-01', strtotime(date('Y', $time) . '-' . (date('m', $time) + 1) . '-01'));
        }
        return $firstday;
    }

    /**
     * @param $date
     * @return bool|string
     * 获取3个月前的日期
     */
    function GetNextMonthByTime($date)
    { //获取指定日期下个月的第一天和最后一天
        $time = strtotime($date);
        //年份
        $year_age = date('Y', $time);
        //六个月前
        $mounth_ago1 = date('m', $time);
        //六个月往前移3个月
        $mounth_ago = date('m', $time) + 3;

        $day = date('d', $time);

        //如果大于等于12月份
        if ($mounth_ago > 12) {
            //年份增加
            $year = $year_age + 1;
            $month = $mounth_ago1 - 9;
            $firstday = $year . '-' . $month . '-' . $day;
            $firstday = strtotime($firstday);
            $firstday = date('Y-m-d 23:59:59', $firstday);
            $firstday = strtotime($firstday);
        } else {
            $firstday = date('Y-m-d 23:59:59', strtotime(date('Y', $time) . '-' . (date('m', $time) + 3) . '-' . date('d', $time)));
            $firstday = strtotime($firstday);
        }

        return $firstday;
    }


    public function getCurMonthFirstDay($date)
    {
        return date('Y-m-01', strtotime($date));
    }

    public function getCurMonthLastDay($date)
    {
        return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
    }


    /**
     * 验证上一周任务的操作2
     */
    public function lastWeekTaskMsg2($this_week_ctime, $time, $id, $endtime, $createtime)
    {
        $ar = array();
        $WEEKTASKTIME = array();
        $model = WeekTask::model()->findAllByAttributes(array($endtime => $this_week_ctime, 'f_id' => $id));

        if (!empty($model[0]->endtime)) {
            $WEEKTASKTIME['start'] = $model[0]->createtime;
            $WEEKTASKTIME['end'] = $model[0]->endtime;
        } else {
            throw new CHttpException(404, '没有上一周的周任务.');
        }

        $t = Yii::app()->db->beginTransaction();
        try {

            $payback = '';


            //全部任务数量
            $total_count = WeekTask::model()->getCountByEndTime($id, $this_week_ctime, $endtime);
            $total_count = $total_count[0]['count'];

            //有效任务数量
            $con_count = WeekTask::model()->getConCountByEndTime2($id, $this_week_ctime, $endtime);

            $total = $total_count;

            //有效率
            $Conformity = round((($con_count / 20) * 100), 3);


            $ar['total'] = WeekTask::TOTAL;
            $ar['now_total'] = $total;
            $ar['con'] = $con_count;
            $ar['conformity'] = $Conformity . '%';
            $ar['conformity1'] = $Conformity;
            $detuct = DefaultParm::DEFAULT_HUNDRED;

            if ($Conformity >= WeekTask::MIN_VALID) {

                $sql = "SELECT is_pro,time_slow2 FROM app_week_task WHERE f_id =  $id  AND  $endtime =  $this_week_ctime ";
                $is_pro = Yii::app()->db->createCommand($sql)->queryAll();

                if (($is_pro[0]['time_slow2'] == 1)) {

                    if ($ar['con'] == 3) {

                        $payback = DefaultParm::DEFAULT_ZERO;
                    } else if ($ar['con'] == 4) {

                        $payback = self::PAYBACK_MIN;
                    } else if ($ar['con'] == 5) {

                        $payback = self::PAYBACK_MIDDLE;
                    } else if ($ar['con'] >= 6) {

                        $payback = self::PAYBACK_MAX;
                    }
                }
                    $model2 = TaskWeekEarnings::model()->findByAttributes(array('createtime' => $model[0]->createtime, 'uid' => $id));

                    if (isset($model2->id)) {

                        $model2->percent  = $Conformity;
                        $model2->concount = $con_count;
                        $model2->askcount = $total;
                        $model2->payback  = $payback;
                        $model2->update();
                    } else {

                        TaskWeekEarnings::model()->InsertWeekEarnings($id, $model[0]->createtime, $model[0]->endtime, $Conformity, $con_count, $total, $payback);
                    }

            }
            $t->commit();
        } catch (Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $t->rollBack();
        }

        $WEEKTASKTIME['count'] = $ar;
        return $WEEKTASKTIME;
    }


}