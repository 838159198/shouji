<?php

/**
 * This is the model class for table "{{article_tags}}".
 *
 * The followings are the available columns in table '{{article_tags}}':
 * @property integer $id
 * @property string $name
 * @property string $seotitle
 * @property string $keywords
 * @property string $descriptions
 * @property integer $createtime
 * @property integer $hits
 * @property integer $frequency
 */
class ArticleTags extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article_tags}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, seotitle, keywords, descriptions, createtime', 'required'),
			array('createtime, hits, frequency', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>10),
			array('seotitle', 'length', 'max'=>60),
			array('keywords, descriptions', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, seotitle, keywords, descriptions, createtime, hits, frequency', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'seotitle' => 'Seotitle',
			'keywords' => 'Keywords',
			'descriptions' => 'Descriptions',
			'createtime' => 'Createtime',
			'hits' => 'Hits',
			'frequency' => '频率',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('descriptions',$this->descriptions,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('hits',$this->hits);
		$criteria->compare('frequency',$this->frequency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleTags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
