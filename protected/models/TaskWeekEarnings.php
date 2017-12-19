<?php

/**
 * This is the model class for table "{{task_week_earnings}}".
 *
 * The followings are the available columns in table '{{task_week_earnings}}':
 * @property integer $id
 * @property integer $uid
 * @property integer $createtime
 * @property integer $endtime
 * @property integer $percent
 * @property integer $concount
 * @property integer $askcount
 * @property string $payback
 * @property integer $role
 */
class TaskWeekEarnings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaskWeekEarnings the static model class
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
		return '{{task_week_earnings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, createtime, endtime, percent, concount, askcount, payback, role', 'required'),
			array('uid, createtime, endtime, percent, concount, askcount, role', 'numerical', 'integerOnly'=>true),
			array('payback', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, createtime, endtime, percent, concount, askcount, payback, role', 'safe', 'on'=>'search'),
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
			'createtime' => 'Createtime',
			'endtime' => 'Endtime',
			'percent' => 'Percent',
			'concount' => 'Concount',
			'askcount' => 'Askcount',
			'payback' => 'Payback',
			'role' => 'Role',
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
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('endtime',$this->endtime);
		$criteria->compare('percent',$this->percent);
		$criteria->compare('concount',$this->concount);
		$criteria->compare('askcount',$this->askcount);
		$criteria->compare('payback',$this->payback,true);
		$criteria->compare('role',$this->role);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

    public function InsertWeekEarnings($id,$createtime,$endtime,$Conformity,$con_count,$total,$detuct){

        $role = Manage::model()->findByPk($id);
        $week_earnings = new TaskWeekEarnings();
        $week_earnings->uid = $id;
        $week_earnings->createtime = $createtime;
        $week_earnings->endtime = $endtime;
        $week_earnings->percent = $Conformity;  //合格率
        $week_earnings->concount = $con_count;
        $week_earnings->askcount = $total;
        $week_earnings->payback = $detuct;
        $week_earnings->role = $role->role;
        $week_earnings->insert ();
    }

    public function getWeekEarningsMsgByTime($mounth,$id){
        $mounthtostr = strtotime($mounth);
        $s_time = WeekTask::model()->getCurMonthFirstDay($mounthtostr);
        $e_time = WeekTask::model()->getCurMonthLastDay($mounthtostr);
        $sql = 'SELECT * FROM app_task_week_earnings
                WHERE createtime >= \''.$s_time.'\' AND endtime <=\''.$e_time.'\'
                AND uid = \''.$id.'\' ';
        $weekEarnings = Yii::app()->db->createCommand($sql)->queryAll();
        return $weekEarnings;
    }
}