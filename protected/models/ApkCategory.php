<?php

/**
 * This is the model class for table "{{apk_category}}".
 *
 * The followings are the available columns in table '{{apk_category}}':
 * @property string $id
 * @property string $name
 * @property string $pathname
 * @property string $status
 * @property string $fid
 */
class ApkCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{apk_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, pathname', 'required'),
			array('name', 'length', 'max'=>100),
			array('pathname', 'length', 'max'=>60),
			array('status', 'length', 'max'=>1),
			array('fid', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, pathname, status, fid', 'safe', 'on'=>'search'),
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
			'pathname' => 'Pathname',
			'status' => 'Status',
			'fid' => 'Fid',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pathname',$this->pathname,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('fid',$this->fid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApkCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 通过分类id获取当前id和子id的集合
	 * @param int $id
	 * @param array $data
	 * @return string  1,2,3,4,5,6
	 * */
	public function getCategoryById($id,$data=array()){
		if($id==0){
			$sql = "SELECT id FROM {{apk_category}} WHERE status = 1";
			$sql_data = $this->findAllBySql($sql);
			foreach ($sql_data AS $row){
				$data[] = $row['id'];
			}
			//$string = implode(",",$data);
			//echo $string;
		}else{
			$sql = "SELECT id FROM {{apk_category}} WHERE status = 1 AND id={$id}";
			$sql_data = $this->findBySql($sql);
			if($sql_data){
				array_push($data,$sql_data['id']);
				$f_sql = "SELECT id FROM {{apk_category}} WHERE status = 1 AND fid = {$sql_data['id']}";
				$f_sql_data = $this->findAllBySql($f_sql);
				if($f_sql_data){
					foreach ($f_sql_data AS $row){
						$id = $row['id'];
						array_push($data,$id);
						$this->getCategoryById($id,$data);
					}
				}
			}
		}
		$string = implode(",",$data);
		return $string;
		//$data = $this->findByPk($id);
	}
}
