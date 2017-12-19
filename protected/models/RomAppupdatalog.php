<?php

/**
 * This is the model class for table "{{rom_appupdatalog}}".
 *
 * The followings are the available columns in table '{{rom_appupdatalog}}':
 * @property string $id
 * @property string $uid
 * @property string $simcode
 * @property string $sys
 * @property string $mac
 * @property string $imeicode
 * @property string $model
 * @property string $appname
 * @property string $runlength
 * @property string $runcount
 * @property string $type
 * @property string $appmd5
 * @property string $tjcode
 * @property string $createtime
 * @property string $status
 * @property string $ip
 * @property string $first
 * @property string $from
 */
class RomAppupdatalog extends CActiveRecord
{
    //关联表--字段username
    private $_username = null;
    public function getUsername()
    {
        if ($this->_username === null && $this->member !== null)
        {
            $this->_username = $this->member->username;
        }
        return $this->_username;
    }
    public function setUsername($value)
    {
        $this->_username = $value;
    }

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
        return '{{rom_appupdatalog}}';
    }
    public function getlistDataType()
    {
        $data = array(array("key"=>1,"value"=>"已卸载"),array("key"=>0,"value"=>"正常"));
        return $data;
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
            array('id, uid, simcode, sys, mac, imeicode, model, appname,first,runlength,runcount,createtime,type,username,appmd5,tjcode,status,ip,from', 'safe', 'on' => 'search'),
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
            'appname' => '业务名',
            'appmd5' => 'appmd5',
            'runlength' => '运行时长',
            'runcount' => '运行次数',
            'type' => '卸载否',
            'createtime' => '上报时间',
            'username' => '用户名',
            'tjcode' => '统计内码',
            'first' => 'first',
            'status' => 'status',
            'ip' => 'IP',
            'from' => 'from',
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

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.uid', $this->uid, true);
        $criteria->compare('t.simcode', $this->simcode, true);
        $criteria->compare('t.sys', $this->sys, true);
        $criteria->compare('t.mac', $this->mac, true);
        $criteria->compare('t.imeicode', $this->imeicode, true);
        $criteria->compare('t.model', $this->model, true);
        $criteria->compare('t.appname', $this->appname, true);
        $criteria->compare('t.appmd5', $this->appmd5, true);
        $criteria->compare('t.runlength', $this->runlength, true);
        $criteria->compare('t.runcount', $this->runcount, true);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.tjcode', $this->tjcode, true);
        $criteria->compare('t.createtime', $this->createtime, true);
        $criteria->compare('t.first', $this->first, true);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('t.ip', $this->ip, true);
        $criteria->compare('t.from', $this->from, true);

        $criteria->with = 'member';
        $criteria->compare('member.username', $this->username,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC',
            ),
        ));
    }



}