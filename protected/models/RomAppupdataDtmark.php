<?php

/**
 * This is the model class for table "{{rom_appupdata_dtmark}}".
 *
 * The followings are the available columns in table '{{rom_appupdata_dtmark}}':
 * @property string $id
 * @property string $uid
 * @property string $simcode
 * @property string $sys
 * @property string $mac
 * @property string $imeicode
 * @property string $model
 * @property string $com
 * @property string $appname
 * @property string $date
 * @property string $runlength
 * @property string $runtime
 * @property string $runcount
 * @property string $type
 * @property string $appmd5
 * @property string $tjcode
 * @property string $createtime
 * @property string $finshstatus
 * @property string $finshstatustime
 * @property string $ip
 * @property string $from
 */
class RomAppupdataDtmark extends CActiveRecord
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
        return '{{rom_appupdata_dtmark}}';
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid,imeicode,appname', 'required'),
            array('uid,runlength,runcount,type', 'numerical', 'integerOnly' => true),
            array('sys,model,appname', 'length', 'max' => 10),
            array('simcode', 'length', 'max' => 30),
            array('appmd5', 'length', 'max' => 32),
            array('imeicode,ip', 'length', 'max' => 15),
            array('mac', 'length', 'max' => 17),
            array('com', 'length', 'max' => 17),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, simcode, sys, mac, imeicode, model, com, appname, date,runlength,runcount,runtime,createtime,type,username,appmd5,tjcode,finshstatus,finshstatustime,ip,from', 'safe', 'on' => 'search'),
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
            'sys' => 'andriod系统',
            'mac' => '手机mac',
            'imeicode' => '手机imeicode',
            'model' => '手机型号',
            'com' => '运营商',
            'appname' => '业务名',
            'appmd5' => 'appmd5',
            'date' => '数据时间',
            'runlength' => '运行时长',
            'runcount' => '运行次数',
            'runtime' => '运行时间点',
            'type' => '卸载否',
            'createtime' => '上报时间',
            'username' => '用户名',
            'tjcode' => '统计内码',
            'finshstatus' => 'finshstatus',
            'finshstatustime' => 'finshstatustime',
            'ip' => 'IP',
            'from' => 'from',
        );
    }


}