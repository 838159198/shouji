<?php

/**
 * This is the model class for table "{{client_data_seller}}".
 * @property string $id
 * @property string $tj_id
 * @property string $imei
 * @property string $iccid
 * @property string $systemVersionCode
 * @property string $simOperatorName
 * @property string $mac
 * @property string $models
 * @property string $brand
 * @property string $ip
 * @property string $timestamp
 * @property string $uid
 * @property string $pack_id
 * @property string $createtime
 * @property string $status
 */
class ClientMdappData extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{client_mdapp_data}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(

            array('tj_id', 'required'),
            array('tj_id', 'numerical', 'integerOnly'=>true),
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
            array('id, tj_id, imei, iccid, systemVersionCode, simOperatorName, models, brand, ip, timestamp, md5,uid, pack_id, createtime, status', 'safe', 'on'=>'search'),

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
            'tj_id' => '统计ID',
            'imei' => 'Imei',
            'iccid' => 'Iccid',
            'systemVersionCode' => 'System Version Code',
            'simOperatorName' => 'Sim Operator Name',
            'mac' => 'Mac',
            'models' => 'Model',
            'brand' => 'Brand',
            'ip' => 'Ip',
            'timestamp' => 'Timestamp',
            'uid' => 'UserId',
            'pack_id' => '套餐ID',
            'createtime'=>'创建时间',
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
        $criteria->compare('tj_id',$this->tj_id);
        $criteria->compare('imei',$this->imei,true);
        $criteria->compare('iccid',$this->iccid,true);
        $criteria->compare('systemVersionCode',$this->systemVersionCode,true);
        $criteria->compare('simOperatorName',$this->simOperatorName,true);
        $criteria->compare('mac',$this->mac,true);
        $criteria->compare('models',$this->models,true);
        $criteria->compare('brand',$this->brand,true);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('timestamp',$this->timestamp,true);
        $criteria->compare('uid',$this->uid,true);
        $criteria->compare('pack_id',$this->pack_id,true);
        $criteria->compare('createtime',$this->createtime,true);
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
