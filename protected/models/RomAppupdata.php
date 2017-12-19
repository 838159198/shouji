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
 * @property string $brand
 * @property string $ip
 * @property string $from
 */
class RomAppupdata extends CActiveRecord
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
        return '{{rom_appupdata}}';
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
            array('com', 'length', 'max' => 17),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, simcode, sys, mac, imeicode, model, com, appname,brand, date,runlength,runcount,runtime,createtime,type,username,appmd5,tjcode,finshstatus,finshstatustime,ip,from', 'safe', 'on' => 'search'),
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
            'tjcode' => '内码',
            'finshstatus' => 'finshstatus',
            'finshstatustime' => 'finshstatustime',
            'ip' => 'IP',
            'brand' => '品牌',
            'from' => 'from',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($createtime='')
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->addCondition("t.createtime> :createtime");
        $criteria->params[':createtime']=$createtime;
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.uid', $this->uid, true);
        $criteria->compare('t.simcode', $this->simcode, true);
        $criteria->compare('t.sys', $this->sys, true);
        $criteria->compare('t.mac', $this->mac, true);
        $criteria->compare('t.imeicode', $this->imeicode, true);
        $criteria->compare('t.model', $this->model, true);
        $criteria->compare('t.com', $this->com, true);
        $criteria->compare('t.appname', $this->appname, true);
        $criteria->compare('t.appmd5', $this->appmd5, true);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.runlength', $this->runlength, true);
        $criteria->compare('t.runcount', $this->runcount, true);
        $criteria->compare('t.runtime', $this->runtime, true);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.tjcode', $this->tjcode, true);
        $criteria->compare('t.createtime', $this->createtime, true);
        $criteria->compare('t.finshstatus', $this->finshstatus, true);
        $criteria->compare('t.finshstatustime', $this->finshstatustime, true);
        $criteria->compare('t.ip', $this->ip, true);
        $criteria->compare('t.brand', $this->brand, true);
        $criteria->compare('t.from', $this->from, true);

        $criteria->with = 'member';
        $criteria->compare('member.username', $this->username,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    /**
     * 激活判定数据列表
     * @param $date
     * @param $type
     * @param $username
     * @param $actrule
     * @param $model
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function getDataProviderByDate($date,$type,$username,$actrule,$models)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.id,t.uid,t.simcode,t.sys,t.mac,t.imeicode,t.tjcode,t.model,t.com,t.from,t.appname,t.finshstatus,t.appmd5,sum(t.runlength) as runlength,sum(t.runcount) as runcount,max(t.runtime) as runtime,max(t.createtime) as createtime,max(t.ip) as ip,count(distinct t.date) as date';

        if(!empty($username) && $username!=2)
        {
            $criteria->addCondition("member.username=:username");
            $criteria->params[':username']=$username;
        }
        if(!empty($models))
        {
            $criteria->addCondition("t.model=:model");
            $criteria->params[':model']=$models;
        }
        $criteria->addCondition("t.finshstatus=0");
        $criteria->addCondition("t.appname=:appname");
        $criteria->addCondition("t.date<=:date");
        $criteria->params[':appname']=$type;
        $criteria->params[':date']=$date;

        //$criteria->addCondition("t.mac!=''");20161222
        //$criteria->addCondition("t.date>'2015-12-17'");
        $criteria->addCondition("t.simcode!=''");
        $criteria->addCondition("t.imeicode!=''");
        $criteria->addNotInCondition('t.imeicode',Common::getExceptList());
        $criteria->addCondition("t.runlength>0");
        $criteria->addCondition("t.runcount>0");
        $criteria->addCondition("t.appmd5!=''");
        $criteria->addCondition("t.type!=1");
       // $criteria->addCondition("appresource.type=t.appname");
       // $criteria->addCondition("appresource.imeicode=t.imeicode");
       // $criteria->addCondition("appresource.status=1");
       // $criteria->addCondition("appresource.finishstatus=0");

        $criteria->with = array('member');
        //$criteria->join = 'LEFT JOIN app_rom_appresource appresource ON t.uid=appresource.uid';
        $criteria->group = 'member.username,t.appname,t.imeicode';
        $criteria->order = 'member.username ASC';
        $criteria->having = 'count(distinct t.date)>='.$actrule;

        if($username==2)
        {
            $criteria->addInCondition("t.from",array(5));
            $dats = RomAppupdata::model()->findAll($criteria);
            return $dats;
        }
        else
        {
            $criteria->addInCondition("t.from",array(0,3,4,5,6,7,8,9));
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 500,
                ),
            ));
        }

    }

    /**
     * simcode卸载个数
     * @param $imeicode
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public static function getByUninstall($imeicode){
        $dats = RomAppupdata::model()->count("imeicode=:imeicode and type=1",array(":imeicode"=>$imeicode));
        return $dats;
    }


}