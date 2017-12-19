<?php

/**
 * This is the model class for table "{{manage_wage}}".
 *
 * The followings are the available columns in table '{{manage_wage}}':
 * @property integer $id
 * @property integer $uid
 * @property string $base_wage
 * @property string $task_payback
 * @property string $bonus
 * @property string $deduct
 * @property string $date
 * @property integer $publish
 * @property string $should_pay
 * @property string $total
 */
class ManageWage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ManageWage the static model class
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
		return '{{manage_wage}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, publish', 'numerical', 'integerOnly'=>true),
			array('base_wage, task_payback, bonus, deduct, should_pay, total', 'length', 'max'=>10),
			array('date', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, base_wage, task_payback, bonus, deduct, date, publish, should_pay, total', 'safe', 'on'=>'search'),
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
			'base_wage' => 'Base Wage',
			'task_payback' => 'Task Payback',
			'bonus' => 'Bonus',
			'deduct' => 'Deduct',
			'date' => 'Date',
			'publish' => 'Publish',
			'should_pay' => 'Should Pay',
			'total' => 'Total',
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
		$criteria->compare('base_wage',$this->base_wage,true);
		$criteria->compare('task_payback',$this->task_payback,true);
		$criteria->compare('bonus',$this->bonus,true);
		$criteria->compare('deduct',$this->deduct,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('publish',$this->publish);
		$criteria->compare('should_pay',$this->should_pay,true);
		$criteria->compare('total',$this->total,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}