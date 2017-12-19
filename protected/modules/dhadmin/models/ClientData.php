<?php

/**
 * This is the model class for table "{{client_data}}".
 *
 * The followings are the available columns in table '{{client_data}}':
 * @property string $id
 * @property integer $user_id
 * @property integer $app_id
 * @property string $imei
 * @property string $iccid
 * @property string $system_version_code
 * @property string $sim_operator_name
 * @property string $mac
 * @property string $mobi_ip
 * @property integer $createtime
 * @property string $pc_ip
 * @property string $models
 * @property integer $from
 * @property integer $status
 */
class ClientData extends CActiveRecord
{
	public $username;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{client_data}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, app_id, imei, system_version_code, mac, createtime, pc_ip', 'required'),
			array('id,user_id, app_id, createtime, from, status', 'numerical', 'integerOnly'=>true),
			array('imei, iccid', 'length', 'max'=>20),
			array('system_version_code, sim_operator_name, mac', 'length', 'max'=>100),
			array('mobi_ip, pc_ip', 'length', 'max'=>15),
			array('models', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, app_id, imei, iccid, system_version_code, sim_operator_name, mac, mobi_ip, createtime, pc_ip, models, from, status', 'safe', 'on'=>'search'),
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
			'product'=>array(self::BELONGS_TO,'Product','app_id'),
			'member'=>array(self::BELONGS_TO,"Member","user_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'app_id' => 'App',
			'imei' => 'Imei',
			'iccid' => 'Iccid',
			'system_version_code' => '版本号',
			'sim_operator_name' => '运营商',
			'mac' => 'Mac',
			'mobi_ip' => '手机ip',
			'createtime' => '创建时间',
			'pc_ip' => '电脑ip',
			'models' => '手机型号',
			'from' => '来源',
			'status' => 'Status',
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

		$criteria->with = array('member','product');
		$criteria->compare('t.id',$this->id);
		$criteria->compare('member.username',$this->user_id,true);
		//$criteria->compare('user_id',$this->user_id);
		$criteria->compare('product.name',$this->app_id,true);
		//$criteria->compare('app_id',$this->app_id);
		//$criteria->compare('app_id',$this->product->name);
		$criteria->compare('t.imei',$this->imei,true);
		$criteria->compare('t.iccid',$this->iccid,true);
		$criteria->compare('t.system_version_code',$this->system_version_code,true);
		$criteria->compare('t.sim_operator_name',$this->sim_operator_name,true);
		$criteria->compare('t.mac',$this->mac,true);
		$criteria->compare('t.mobi_ip',$this->mobi_ip,true);
		//$criteria->compare('t.createtime',$this->createtime);
		$criteria->compare('t.pc_ip',$this->pc_ip,true);
		$criteria->compare('t.models',$this->models,true);
		$criteria->compare("t.`from`",$this->from);
		$criteria->compare('t.status',$this->status);
		if($this->createtime!=""){
			$stat_time = strtotime($this->createtime);
			$end_time = $stat_time + 3600*24;

			$criteria->addBetweenCondition('t.createtime', $stat_time,$end_time);//between1 and 4
		}



		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' =>30,
			),
			'sort'=>array(
				'defaultOrder'=>'t.id DESC', //设置默认排序
				//       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 来源
	 */
	public function getFromList()
	{
		$data = array(array("key"=>1,"value"=>"电脑"),array("key"=>2,"value"=>"手机"));
		return $data;
	}
	/**
	 * 状态
	 */
	public function getStatusList()
	{
		$data = array(array("key"=>1,"value"=>"1"),array("key"=>0,"value"=>"0"));
		return $data;
	}
}
