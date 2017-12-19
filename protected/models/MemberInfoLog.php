<?php

/**
 * This is the model class for table "{{member_info_log}}".
 *
 * The followings are the available columns in table '{{member_info_log}}':
 * @property string $id
 * @property string $mid
 * @property string $utype
 * @property string $username
 * @property string $detail
 * @property integer $createtime
 */
class MemberInfoLog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberInfoLog the static model class
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
        return '{{member_info_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('utype, username, detail, createtime', 'required'),
            array('createtime', 'numerical', 'integerOnly' => true),
            array('mid', 'length', 'max' => 11),
            array('utype', 'length', 'max' => 6),
            array('username', 'length', 'max' => 20),
            array('detail', 'length', 'max' => 1000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, mid, utype, username, detail, createtime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mid' => '用户ID',
            'utype' => '修改人类别',
            'username' => '修改人用户名',
            'detail' => '修改前内容',
            'createtime' => '修改时间',
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
        $criteria->compare('mid', $this->mid, true);
        $criteria->compare('utype', $this->utype, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('createtime', $this->createtime);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param int $mid
     * @return MemberInfoLog[]
     */
    public function getListByMid($mid)
    {
        $c = new CDbCriteria();
        $c->compare('mid', $mid);
        $c->order = 'createtime DESC';
        return $this->findAll($c);
    }

    /**
     *name 用户资料修改日志
     */
    public static function addLog($user,$info,$username=''){
        $log = MemberInfoLog::model();
        $log->setIsNewRecord(true);
        $log->unsetAttributes();

        if($user->type=="Manage"){
            $log->mid = $user->uid;
            $log->utype = Common::USER_TYPE_MANAGE;
        }
        else{
            $log->mid = $user->member_uid;
            $log->utype = Common::USER_TYPE_MEMBER;
        }
        $log->username = $username;
        $log->detail = $info;
        $log->createtime = time();
        $log->insert();
    }
}