<?php

/**
 * This is the model class for table "{{manage_deduct}}".
 *
 * The followings are the available columns in table '{{manage_deduct}}':
 * @property integer $id
 * @property integer $uid
 * @property integer $leave
 * @property integer $start_time
 * @property integer $end_time
 * @property string $reason
 */
class ManageDeduct extends CActiveRecord
{
    /** 扣款类型 -事假 */
    const DEDUCT_LEAVE_AFFAIR = DefaultParm::DEFAULT_ONE;
    /** 扣款类型 -病假 */
    const DEDUCT_LEAVE_ILL = DefaultParm::DEFAULT_TWO;
    /** 扣款类型 -迟到早退 */
    const DEDUCT_LEAVE_LATE = DefaultParm::DEFAULT_THREE;
    /** 扣款类型 -保险 */
    const DEDUCT_LEAVE_INSURANCE = DefaultParm::DEFAULT_FOUR;
    /** 扣款类型 -旷工 */
    const DEDUCT_LEAVE_ABSENTEEISM = DefaultParm::DEFAULT_FIVE;
    /** 扣款类型 -娱乐 */
    const DEDUCT_LEAVE_GAME = DefaultParm::DEFAULT_SIX;
    /** 扣款类型 -其他 */
    const DEDUCT_LEAVE_OTHER = DefaultParm::DEFAULT_SEVEN;

    /** 请假状态 -等待查看 */
    const LEAVE_STATUS_WAIT = DefaultParm::DEFAULT_ZERO;
    /** 请假状态 -准许 */
    const LEAVE_STATUS_TRUE = DefaultParm::DEFAULT_ONE;
    /** 请假状态 -拒绝 */
    const LEAVE_STATUS_FALSE = DefaultParm::DEFAULT_TWO;


    /**
     * Returns the static model of the specified AR class.
     * @return ManageDeduct the static model class
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
        return '{{manage_deduct}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, leave, start_time, end_time', 'numerical', 'integerOnly' => true),
            array('reason', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, leave, start_time, end_time, reason', 'safe', 'on' => 'search'),
        );
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'leave' => 'Leave',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'reason' => 'Reason',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('leave', $this->leave);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('reason', $this->reason, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function getLeaveMsg($STATUS)
    {
        $sql = 'SELECT md.*,m.name FROM app_manage_deduct AS md
                JOIN app_manage AS m
                ON m.id = md.uid
                WHERE ischeck = \'' . $STATUS . '\' ';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public function getDeductList()
    {
        $arr = array();
        $arr[self::DEDUCT_LEAVE_AFFAIR] = '事假';
        $arr[self::DEDUCT_LEAVE_ILL] = '病假';
        $arr[self::DEDUCT_LEAVE_LATE] = '迟到';
        $arr[self::DEDUCT_LEAVE_INSURANCE] = '保险';
        $arr[self::DEDUCT_LEAVE_ABSENTEEISM] = '旷工';
        $arr[self::DEDUCT_LEAVE_GAME] = '游戏';
        $arr[self::DEDUCT_LEAVE_OTHER] = '其他';
        return $arr;
    }

    public function getLeaveNameByType($leave_type)
    {
        $leave_name = '';
        switch ($leave_type) {
            case self::DEDUCT_LEAVE_AFFAIR:
                return $leave_name = '事假';
                break;
            case self::DEDUCT_LEAVE_ILL:
                return $leave_name = '病假';
                break;
            case self::DEDUCT_LEAVE_LATE:
                return $leave_name = '迟到/早退';
                break;
            case self::DEDUCT_LEAVE_INSURANCE:
                return $leave_name = '保险';
                break;
            case self::DEDUCT_LEAVE_OTHER:
                return $leave_name = '其他';
                break;
            case DefaultParm::DEFAULT_EMPTY:
                return $status_name = '无';
                break;
            case self::DEDUCT_LEAVE_ABSENTEEISM:
                return $leave_name = '旷工';
                break;
            case self::DEDUCT_LEAVE_GAME:
                return $status_name = '游戏';
                break;
        }
    }

    public function getStatusNameByStatus($status)
    {
        $status_name = '';
        switch ($status) {
            case self::LEAVE_STATUS_WAIT:
                return $status_name = '待批准假条';
                break;
            case self::LEAVE_STATUS_TRUE:
                return $status_name = '已批准';
                break;
            case self::LEAVE_STATUS_FALSE:
                return $status_name = '被拒绝';
                break;
            case DefaultParm::DEFAULT_EMPTY:
                return $status_name = '无';
                break;

        }

    }

    public function getNearestLeaveMsgByUid($id)
    {
        $time = time();
        $arr = array();
        $sql_max = 'SELECT MAX(id) AS id FROM app_manage_deduct
                WHERE end_time < \'' . $time . '\'  AND uid = \'' . $id . '\'
                AND ischeck = \'' . ManageDeduct::LEAVE_STATUS_TRUE . '\' ';

       // $max_did = Yii::app()->db->createCommand($sql_max)->queryAll();

        $max_did = ManageDeduct::model()->findBySql($sql_max);

        $max_msg = ManageDeduct::model()->findByPk($max_did->id);
        $arr['last_stime'] = isset($max_msg->start_time) ? $max_msg->start_time : '' ;
        $arr['last_etime'] = isset($max_msg->end_time) ? $max_msg->end_time : '';
        $arr['last_checkid'] = isset($max_msg->checkid) ? $max_msg->checkid : '';


        $sql_min = 'SELECT MIN(id) AS id FROM app_manage_deduct
                WHERE end_time > \'' . $time . '\'  AND uid = \'' . $id . '\' ';
        $min_did = ManageDeduct::model()->findBySql($sql_min);

        if (isset($min_did->id)) {
            $min_msg = ManageDeduct::model()->findByPk($min_did->id);
            $arr['next_stime'] = $min_msg->start_time;
            $arr['next_etime'] = $min_msg->end_time;
            $arr['next_ischeck'] = isset($min_msg->ischeck) ? $min_msg->ischeck : '';
            $arr['next_checkid'] = isset($min_msg->checkid) ? $min_msg->checkid : '';
        } else {
            $arr['next_stime'] = DefaultParm::DEFAULT_EMPTY;
            $arr['next_etime'] = DefaultParm::DEFAULT_EMPTY;
            $arr['next_ischeck'] = DefaultParm::DEFAULT_EMPTY;
            $arr['next_checkid'] = DefaultParm::DEFAULT_EMPTY;
        }
        return $arr;
    }

    public function getManageLeaveMsgByMounth($mounth, $show_id)
    {
        $firstday = array();
        $firstday['date'] = date('Y-m-01', strtotime($mounth));
        $fd = $firstday['date'];
        $lastday['date'] = date('Y-m-d', strtotime(" $fd +1 month -1 day"));

        $firstday['time'] = strtotime($firstday['date']);
        $lastday['time'] = strtotime($lastday['date']);

        $sql = 'SELECT * FROM app_manage_deduct WHERE uid = \'' . $show_id . '\'
                AND start_time > \'' . $firstday['time'] . '\' AND end_time < \'' . $lastday['time'] . '\' ';
        $res = ManageDeduct::model()->findAllBySql($sql);
        return $res;
    }

    public function getMounthByThisYear()
    {
        $year = date('Y', time());
        $date = array();
        for ($i = 1; $i <= 12; $i++) {

            if(6<$i && $i<=12){
                $year=2016;
            }


                        if($i<10){
                            $i = '0'.$i;
                        }


            $date[$i] = $year . '-' . $i;
        }
        return $date;
    }
    //获取年份列表
    public function getLastYear(){
        $year = date('Y',time());
        $l_year = $year-2;
        $date = array();
        for ($i = $l_year; $i <= $year; $i++) {
            $date[$i] = $i;
        }
        return $date;
    }
    //获取月份列表
    public function getMounth(){
        for ($i = 1; $i <= 12; $i++) {
            if($i<10){
                $i = '0'.$i;
            }
            $date[$i] = $i;
        }
        return $date;
    }
    public function getManageWageByMounth($mounth, $show_id)
    {
        $sql_count = "SELECT COUNT(*) AS count FROM app_manage_wage WHERE uid = $show_id
                    AND date like '%$mounth%' ";
        $count = Yii::app()->db->createCommand($sql_count)->queryAll();
        $count = $count[0]['count'];
        return $count;
    }

    public function getManageWgaeMsgByMounth($mounth, $show_id)
    {
        $sql_wage = "SELECT * FROM app_manage_wage WHERE uid = $show_id
                    AND date like '%$mounth%' ";
        $wage_msg = ManageWage::model()->findBySql($sql_wage);
        return $wage_msg;
    }

    /**
     * 获取按钮样式
     */
    public function button($type, $show, $eve, $fun)
    {
        $fun = '' . $fun . '';
        $show = '' . $show . '';
        $eve = '' . $eve . '';
        if ($type == 1) {
            return CHtml::Button($show, array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE),
                array($eve => $fun . '()')));
        } else if ($type == 2) {
            return CHtml::Button($show,
                array_merge(Bs::cls(Bs::BTN_INFO), array($eve => $fun . '()')));
        } else if ($type == 3) {
            return CHtml::button($show, array_merge(Bs::cls(Bs::BTN_DANGER),
                array($eve => $fun . '()')));
        }
    }

    public function getPayByDate($first,$last,$id){

        $arr = array();

        $role = Manage::model()->getRoleByUid($id);
        $basewage = Role::model()->getBaseWageByRole($role);

        $arr['role'] = $role;
        $arr['basewage'] = $basewage;

        $sql_week = 'SELECT SUM(payback) AS pay FROM app_task_week_earnings WHERE uid = \''.$id.'\'
                    AND endtime >= \''.$first.'\' AND endtime <= \''.$last.'\' ';
        $model_week = Yii::app()->db->createCommand($sql_week)->queryAll();

        $arr['week'] = isset($model_week[0]['pay'])?$model_week[0]['pay']:0;
        //传递年月内的新用户任务收益
        $sql_new = "SELECT sum(te.payback)AS pay FROM app_task_earnings AS te
                    JOIN app_ask_task AS at
                    ON te.atid = at.id
                    WHERE te.type = ".Task::TYPE_NEW."
                    AND te.uid = $id AND te.endtime >=  " . $first . "
                    AND te.endtime<=  " . $last . "  ";

        $model_new = Yii::app()->db->createCommand($sql_new)->queryAll();

        $arr['new'] = isset($model_new[0]['pay'])?$model_new[0]['pay']:0;

        //传递年月内的降量任务收益
        $sql_drop = "SELECT sum(te.payback)AS pay FROM app_task_earnings AS te
    			JOIN app_ask_task AS at
    			ON te.atid = at.id
    			WHERE te.type = ".Task::TYPE_DROP."
    			AND te.uid = $id AND te.endtime>=  " . $first . "
    			AND te.endtime<=  " . $last . "  ";
        $model_drop = Yii::app()->db->createCommand($sql_drop)->queryAll();
        $arr['drop'] = isset($model_drop[0]['pay'])?$model_drop[0]['pay']:0;

        //传递年月内的职务提成收益
        if(($role==Role::PRACTICE_VISOR ) || ($role==Role::SUPERVISOR )){
            $com = ManageDeduct::model()->getCommission($first,$last);
        }else{
            $com = 0;
        }
        $arr['com']=$com;

        //传递年月内的回访任务收益
        $sql_visit = "SELECT sum(te.payback)AS pay FROM app_task_earnings AS te
    			JOIN app_ask_task AS at
    			ON te.atid = at.id
    			WHERE te.type = ".Task::TYPE_VISIT."
    			AND te.uid = $id AND te.endtime>=  " . $first . "
    			AND te.endtime<=  " . $last . "  ";
        $model_visit = Yii::app()->db->createCommand($sql_visit)->queryAll();

        $arr['visit'] = isset($model_visit[0]['pay'])?$model_visit[0]['pay']:0;

        $arr['bonus'] = 0;
        $arr['deduct'] = 0;
        $arr['total'] = $arr['week']+$arr['new']+$arr['drop']+$arr['basewage']+$arr['bonus']+$arr['visit']+$arr['com'];

        return $arr;
    }


    public function getCommission($first,$last){

        $sql = "SELECT SUM(payback)AS payback FROM app_task_earnings WHERE endtime >=".$first." AND endtime <= ".$last." AND uid >= ".Role::PRACTICE_VISOR;
        $sum = Yii::app()->db->createCommand($sql)->queryAll();
        $Commission = $sum[0]['payback']*0.1;
        return $Commission;
    }
}