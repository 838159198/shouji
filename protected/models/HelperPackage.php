<?php

/**
 * This is the model class for table "{{helper_package}}".
 *
 * The followings are the available columns in table '{{helper_package}}':
 * @property string $id
 * @property string $apk_id
 * @property string $createtime
 * @property string $updatetime
 * @property integer $create_id
 * @property string $size
 * @property integer $star
 * @property string $name
 * @property integer $num
 */
class HelperPackage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{helper_package}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apk_id, createtime, updatetime, size, star, name, num', 'required'),
			array('create_id, star, num', 'numerical', 'integerOnly'=>true),
			array('apk_id, size, name', 'length', 'max'=>255),
			array('createtime, updatetime', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, apk_id, createtime, updatetime, create_id, size, star, name, num', 'safe', 'on'=>'search'),
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
			'apk_id' => 'Apk',
			'createtime' => 'Createtime',
			'updatetime' => 'Updatetime',
			'create_id' => 'Create',
			'size' => 'Size',
			'star' => 'Star',
			'name' => 'Name',
			'num' => 'Num',
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
		$criteria->compare('apk_id',$this->apk_id,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('updatetime',$this->updatetime,true);
		$criteria->compare('create_id',$this->create_id);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('star',$this->star);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('num',$this->num);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HelperPackage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*
	 * 获取组合套餐列表
	 * */
	public function getListData(){
		$data = $this->findAll("status=1");
		return $data;
	}
	/**
	 * 通过组合套餐列表获取apk数据
	 * @return object
	 * */
	public function getApkData(){
		/*$idArray = explode(",",$this->apk_id);
		$model = new Apk();
		foreach ($idArray AS $id){
			$data[] = $model->getApkDetail($id);
		}*/
		$data = Common::curlData("helperApi/getAppSingle?id={$this->apk_id}");
		$data = json_decode($data,true);
		if($data['status_code']==200){
			return $data['return_data'];
		}else{
			return array();
		}

	}
}
