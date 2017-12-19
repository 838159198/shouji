<?php

/**
 * This is the model class for table "{{error_log_rom}}".
 *
 * The followings are the available columns in table '{{error_log_rom}}':
 * @property string $id
 * @property string $uid
 * @property string $appNo
 * @property string $imei
 * @property string $simcode
 * @property string $model
 * @property string $tjcode
 * @property string $crashstr
 * @property string $createtime
 */
class ErrorLogRom extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{error_log_rom}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid', 'length', 'max'=>10),
			array('model, simcode,imei', 'length', 'max'=>50),
			array('tjcode,appNo', 'length', 'max'=>15),
			array('crashstr', 'length', 'max'=>10000),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid,appNo,imei, simcode, model, tjcode,crashstr,createtime', 'safe', 'on'=>'search'),
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
			'appNo' => 'appNo',
			'imei' => 'imei',
			'simcode' => 'simcode',
			'model' => 'model',
			'tjcode' => 'tjcode','crashstr' => 'crashstr','createtime' => 'createtime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('appNo',$this->appNo,true);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('simcode',$this->simcode,true);
		$criteria->compare('model',$this->model,true);
        $criteria->compare('tjcode',$this->tjcode,true);
        $criteria->compare('crashstr',$this->crashstr,true);
        $criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ErrorLogRom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
