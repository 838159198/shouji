<?php

/**
 * This is the model class for table "{{client_data_seller}}".
 * @property string $id
 * @property string $userId
 * @property string $app_id
 * @property string $imei
 * @property string $iccid
 * @property string $systemVersionCode
 * @property string $simOperatorName
 * @property string $mac
 * @property string $phoneIp
 * @property string $models
 * @property string $brand
 * @property string $cpu
 * @property string $resolution_w
 * @property string $resolution_h
 * @property string $pcIp
 * @property string $installtime
 * @property string $timestamp
 * @property string $installcount
 * @property string $status
 */
class ClientDataSeller extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{client_data_seller}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			array('userId, app_id, imei,systemVersionCode,phoneIp,models,brand,cpu,resolution_w,resolution_h,pcIp', 'required'),
			array('app_id, installtime', 'numerical', 'integerOnly'=>true),
			array('simOperatorName, systemVersionCode', 'length', 'max'=>100),
			array('iccid', 'length', 'max'=>30),//is固定
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
			array('id, userId, app_id, imei, iccid, systemVersionCode, simOperatorName, mac, phoneIp, models, brand,cpu,status,resolution_w,resolution_h,pcIp,timestamp,installtime,installcount', 'safe', 'on'=>'search'),

		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
			'userId' => 'userId',
			'app_id' => 'App',
			'imei' => 'Imei',
			'iccid' => 'Iccid',
			'systemVersionCode' => 'System Version Code',
			'simOperatorName' => 'Sim Operator Name',
			'mac' => 'Mac',
			'phoneIp' => 'phone Ip',
			'models' => 'Model',
			'brand' => 'Brand',
            'cpu' => 'Cpu',
            'resolution_w' => 'Resolution_w',
            'resolution_h' => 'Resolution_h',
            'pcIp' => 'Pc Ip',
            'timestamp' => 'Timestamp',
            'installtime' => 'Install time',
            'installcount' => 'Install count',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('app_id',$this->app_id);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('iccid',$this->iccid,true);
		$criteria->compare('systemVersionCode',$this->systemVersionCode,true);
		$criteria->compare('simOperatorName',$this->simOperatorName,true);
		$criteria->compare('mac',$this->mac,true);
		$criteria->compare('phoneIp',$this->phoneIp,true);
		$criteria->compare('models',$this->models,true);
		$criteria->compare('brand',$this->brand,true);
        $criteria->compare('cpu',$this->cpu,true);
        $criteria->compare('resolution_w',$this->resolution_w,true);
        $criteria->compare('resolution_h',$this->resolution_h,true);
        $criteria->compare('pcIp',$this->pcIp,true);
        $criteria->compare('timestamp',$this->timestamp,true);
        $criteria->compare('installtime',$this->installtime,true);
        $criteria->compare('installcount',$this->installcount,true);
        $criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientDataSeller the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
