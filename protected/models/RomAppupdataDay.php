<?php

/**
 * This is the model class for table "{{rom_appupdata}}".
 *
 * The followings are the available columns in table '{{rom_appupdata}}':
 * @property string $id
 * @property string $uid
 * @property string $simcode
 * @property string $sys
 * @property string $mac
 * @property string $imeicode
 * @property string $models
 * @property string $type
 * @property string $tjcode
 * @property string $createtime
 * @property string $reportime
 * @property string $reportimestamp
 * @property string $brand
 * @property string $ip
 * @property string $com
 * @property string $count
 */
class RomAppupdataDay extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RomSoftpak the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rom_appupdata_day}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('imeicode,tjcode', 'required'),
            array('uid,type', 'numerical', 'integerOnly' => true),
            array('sys,models', 'length', 'max' => 20),
            array('simcode,brand', 'length', 'max' => 30),
            array('imeicode,ip', 'length', 'max' => 15),
            array('mac', 'length', 'max' => 17),
            array('com', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, simcode, sys, mac, imeicode, tjcode, models, brand, com, type, ip, createtime, reportime, reportimestamp, count', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'member' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'simcode' => 'sim码',
            'sys' => '系统',
            'mac' => 'mac',
            'imeicode' => 'imeicode',
            'tjcode' => '统计app内码',
            'models' => '手机型号',
            'brand' => '品牌',
            'com' => '运营商',
            'type' => '统计上报',
            'ip' => 'IP',
            'createtime' => '创建时间',
            'reportime' =>'上报时间',
            'reportimestamp'=>'上报时间戳',
            'count'  => '上报计数'
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

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->order = 'id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    //判断
    public static function getThreeCount($imeicode){
        $model =RomAppupdataDay::model()->find("imeicode=:imeicode",array(":imeicode"=>$imeicode));
        $count=0;
        if($model){
            $count=$model->count;
        }
        return $count;
    }
   

}