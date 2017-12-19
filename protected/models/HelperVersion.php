<?php

/**
 * This is the model class for table "{{helper_version}}".
 *
 * The followings are the available columns in table '{{helper_version}}':
 * @property string $hv_id
 * @property string $hv_version
 * @property string $hv_content
 * @property string $hv_download_url
 * @property string $hv_constraint
 * @property string $hv_title
 */
class HelperVersion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{helper_version}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hv_version, hv_download_url, hv_title', 'length', 'max'=>255),
			array('hv_constraint', 'length', 'max'=>1),
			array('hv_content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hv_id, hv_version, hv_content, hv_download_url, hv_constraint, hv_title', 'safe', 'on'=>'search'),
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
			'hv_id' => 'Hv',
			'hv_version' => 'Hv Version',
			'hv_content' => 'Hv Content',
			'hv_download_url' => 'Hv Download Url',
			'hv_constraint' => 'Hv Constraint',
			'hv_title' => 'Hv Title',
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

		$criteria->compare('hv_id',$this->hv_id,true);
		$criteria->compare('hv_version',$this->hv_version,true);
		$criteria->compare('hv_content',$this->hv_content,true);
		$criteria->compare('hv_download_url',$this->hv_download_url,true);
		$criteria->compare('hv_constraint',$this->hv_constraint,true);
		$criteria->compare('hv_title',$this->hv_title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HelperVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
