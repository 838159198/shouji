<?php

/**
 * This is the model class for table "{{task_earnings}}".
 *
 * The followings are the available columns in table '{{task_earnings}}':
 * @property integer $id
 * @property integer $uid
 * @property integer $mid
 * @property integer $createtime
 * @property integer $endtime
 * @property string $payback
 * @property integer $role
 * @property integer $type
 */
class TaskEarnings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaskEarnings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{task_earnings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, mid, createtime, endtime, role, type', 'numerical', 'integerOnly'=>true),
			array('payback', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, mid, createtime, endtime, payback, role, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
			'mid' => 'Mid',
			'createtime' => 'Createtime',
			'endtime' => 'Endtime',
			'payback' => 'Payback',
			'role' => 'Role',
			'type' => 'Type',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('mid',$this->mid);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('endtime',$this->endtime);
		$criteria->compare('payback',$this->payback,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    public function insretTaskEar($id,$mid,$a_time,$prottime,$pay_back,$role,$type,$atid,$twid){

        $taskear = new TaskEarnings();
        $taskear->uid = $id;
        $taskear->mid = $mid;
        $taskear->createtime = $a_time;
        $taskear->endtime = $prottime;
        $taskear->payback = $pay_back;
        $taskear->role = $role;
        $taskear->type = $type;
        $taskear->atid = $atid;
        $taskear->twid = $twid;
        $taskear->insert();
    }
    public function updateTaskPaybackByCheck($check,$tw_id,$te_id){
        $sql = 'UPDATE app_task_when SET pay_back = \''.$check.'\' WHERE id = \''.$tw_id.'\' ';
        $re0  =  Yii::app()->db->createcommand($sql)->execute();
        $sql2 = 'UPDATE app_task_earnings SET payback = \''.$check.'\',motifytime = \''.time().'\'
                         WHERE id = \''.$te_id.'\' ';
        $re2  =  Yii::app()->db->createcommand($sql2)->execute();
        return true;
    }

    /**
     * 获取指定用户指定月份内的收益总和
     */
    public function getManagePaybackByTime($id,$firstday,$lastday){
        $arr = array();
        //新用户任务收益
        $sql_task_new = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \'' . $id . '\'
                AND (`endtime` between \'' . $firstday . '\' and \'' . $lastday . '\')
                AND type = \'' . Task::TYPE_NEW . '\' ';
        $tasknew = Yii::app()->db->createCommand($sql_task_new)->queryAll();
        //降量任务收益
        $sql_task_drop = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \'' . $id . '\'
                AND (`endtime` between \'' . $firstday . '\' and \'' . $lastday . '\')
                AND type = \'' . Task::TYPE_DROP . '\' ';
        $taskdrop = Yii::app()->db->createCommand($sql_task_drop)->queryAll();

        //周任务收益
        $sql_week = 'SELECT SUM(payback) AS pay FROM app_task_week_earnings WHERE uid = \'' . $id . '\'
                    AND endtime>=  \'' . $firstday . '\' and endtime <=\'' . $lastday . '\'';
        $taskweek = Yii::app()->db->createCommand($sql_week)->queryAll();

        //回访任务收益
        $sql_visit = 'SELECT SUM(payback) AS pay FROM app_task_earnings WHERE uid = \'' . $id . '\'
                AND (`endtime` between \'' . $firstday . '\' and \'' . $lastday . '\')
                AND type = \'' . Task::TYPE_VISIT . '\' ';
        $taskvisit = Yii::app()->db->createCommand($sql_visit)->queryAll();

        $arr['tasknew'] =  !empty($tasknew[0]['pay']) ? $tasknew[0]['pay'] : 0;
        $arr['taskdrop'] =  !empty($taskdrop[0]['pay']) ? $taskdrop[0]['pay'] : 0;
        $arr['taskweek'] =  !empty($taskweek[0]['pay']) ? $taskweek[0]['pay'] : 0;
        $arr['taskvisit'] =  !empty($taskvisit[0]['pay']) ? $taskvisit[0]['pay'] : 0;
        return $arr;
    }
}