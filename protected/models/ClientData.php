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
 * @property string $models
 * @property string $status
 * @property integer $createtime
 * @property string $pc_ip
 */
class ClientData extends CActiveRecord
{
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

			array('user_id, app_id, imei, system_version_code, mac, mobi_ip, createtime, pc_ip', 'required'),//sim_operator_name,iccid
			array('user_id, app_id, createtime', 'numerical', 'integerOnly'=>true),
			array('system_version_code, sim_operator_name', 'length', 'max'=>100),
			//iccid
			array('iccid', 'length', 'max'=>20),//is固定
			//imei长度15位
			/*array('imei', 'length', 'is'=>15),*/
			//imei
			/*array('imei','match','pattern'=>'/^[0-9]*$/'),*/
			//mac
			/*array('mac','match','pattern'=>'/([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}/'),*/
			//验证ip
			//array('mobi_ip, pc_ip','match','pattern'=>'/(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)/'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, app_id, imei, iccid, system_version_code, sim_operator_name, mac, mobi_ip, createtime, pc_ip,models,status', 'safe', 'on'=>'search'),

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
			'user_id' => 'User',
			'app_id' => 'App',
			'imei' => 'Imei',
			'iccid' => 'Iccid',
			'system_version_code' => 'System Version Code',
			'sim_operator_name' => 'Sim Operator Name',
			'mac' => 'Mac',
			'mobi_ip' => 'Mobi Ip',
			'createtime' => 'Createtime',
			'pc_ip' => 'Pc Ip',
            'status' => 'status',
            'models' => 'models',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('app_id',$this->app_id);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('iccid',$this->iccid,true);
		$criteria->compare('system_version_code',$this->system_version_code,true);
		$criteria->compare('sim_operator_name',$this->sim_operator_name,true);
		$criteria->compare('mac',$this->mac,true);
		$criteria->compare('mobi_ip',$this->mobi_ip,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('pc_ip',$this->pc_ip,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('models',$this->models,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
}
