<?php

class RecycleSoftpakLog extends CActiveRecord
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
        return '{{recycle_softpak_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('imeicode,tjcode', 'required'),
//            array('uid,type', 'numerical', 'integerOnly' => true),
//            array('sys,models', 'length', 'max' => 20),
//            array('simcode,brand', 'length', 'max' => 30),
//            array('imeicode,ip', 'length', 'max' => 15),
//            array('mac', 'length', 'max' => 17),
//            array('com', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,createtime, reply_date, recycle_mid, reply_mid, status,type,serial_number', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户名',
            'createtime' => '回收时间',
            'serial_number' => '统计ID',
            'reply_date' => '恢复时间',
            'type' => '类别',
            'recycle_mid' => '回收人',
            'reply_mid' => '恢复人',
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
        $criteria->with = 'user';
        $criteria->compare('user.username', $this->uid,true);
        $criteria->compare('t.serial_number', $this->serial_number, true);
        $criteria->compare('t.createtime', $this->createtime, true);
        $criteria->compare('t.reply_date', $this->reply_date, true);
        $criteria->compare('t.type', $this->type, true);
        $criteria->order = 't.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>已恢复</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>恢复</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }

    /*
* 状态列表下拉
* */
    public function getlistDataStatus()
    {
        $data = array(array("key"=>1,"value"=>"已恢复"),array("key"=>0,"value"=>"恢复"));
        return $data;
    }
    public function getXtype()
    {
        $data = "";
        if($this->type==1){
            $data = "<font color=#ff0000><b>门店</b></font>";
        }elseif($this->type==0){
            $data = "<font color=#006600><b>ROM</b></font>";
        }elseif($this->type==3){
            $data = "<font color=#ff0000><b>门店桌面</b></font>";
        }else{
            $data = "<font color=#000000><b>其他</b></font>";
        }
        return $data;
    }

    /*
* 状态列表下拉
* */
    public function getlistDataType()
    {
        $data = array(array("key"=>1,"value"=>"门店"),array("key"=>0,"value"=>"ROM"),array("key"=>3,"value"=>"门店桌面"));
        return $data;
    }
}