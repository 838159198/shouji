<?php

/**
 * This is the model class for table "{{bind_sample}}".
 *
 * The followings are the available columns in table '{{bind_sample}}':
 * @property string $id
 * @property string $uid
 * @property string $type
 * @property string $val
 * @property integer $status
 * @property integer $allot
 * @property integer $closed
 * @property integer $utype
 * @property string $klradio
 */
class BindSample extends CActiveRecord
{
    /** 自动分配 */
    const ALLOT_AUTO = 0;
    /** 人工分配 */
    const ALLOT_MANUAL = 1;
    /** 所有 */
    const ALLOT_ALL = -1;

    /** 已封号 */
    const CLOSED_TRUE = 1;
    /** 未封号 */
    const CLOSED_FALSE = 0;

    /** 状态-未分配 */
    const STATUS_TRUE = 1;
    /** 状态-已分配 */
    const STATUS_FALSE = 0;

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
     * 业务ID分配方式
     * @return array
     */
    public static function getAllotList()
    {
        return array(
            '0' => '系统分配',
            '1' => '管理员分配',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BindSample the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * 业务资源状态
     */
    public function getlistDataType()
    {
        $data = array(array("key"=>0,"value"=>"已分配"),array("key"=>1,"value"=>"未分配"));
        return $data;
    }
    /**
     * 业务分配情况
     */
    public function getlistDataType0()
    {
        $data = array(array("key"=>0,"value"=>"自动分配"),array("key"=>1,"value"=>"手动分配"));
        return $data;
    }
    /**
     * 业务封号状态
     */
    public function getlistDataType1()
    {
        $data = array(array("key"=>0,"value"=>"可用"),array("key"=>1,"value"=>"已封号"));
        return $data;
    }
    /**
     * 平台分组,独立用户
     */
    public function getlistDatauType1()
    {
        $data = array(array("key"=>0,"value"=>"独立用户"),array("key"=>1,"value"=>"平台分组"));
        return $data;
    }
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bind_sample}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('type, val,klradio,utype', 'required'),
            array('status, allot, closed', 'numerical', 'integerOnly' => true),
            array('type,uid', 'length', 'max' => 10),
            array('val', 'length', 'max' => 32),
            array('id, uid,type, val, status, allot, closed,username,utype,klradio', 'safe', 'on' => 'search'),
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
            'uid' => 'UID',
            'type' => '资源类型',
            'val' => '资源key',
            'status' => '资源状态',
            'allot' => '分配类型',
            'closed' => '资源封号',
            'username' => '用户名',
            'utype' => '资源类别',
            'klradio' => '扣量基数',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.uid', $this->uid, true);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.val', $this->val, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.allot', $this->allot);
        $criteria->compare('t.closed', $this->closed);
        $criteria->compare('t.utype', $this->utype);
        $criteria->compare('t.klradio', $this->klradio);

        $criteria->with = 'member';
        $criteria->compare('member.username', $this->username,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'member.username ASC',
            ),
        ));
    }

    /**
     * 获得最新一条广告数据
     * @param $type
     * @param int $allot 分配状态0.自动；1.手动；-1.所有
     * @param string $fix 如果有前缀，按前缀来搜索
     * @return BindSample[]
     */
    public function getListGroupByType($type, $allot = self::ALLOT_AUTO, $fix = null)
    {
        $c = new CDbCriteria();
        $c->compare('status', self::STATUS_TRUE); //有效的
        $c->compare('type', $type);
        if ($allot >= 0) {
            $c->compare('allot', $allot);
        }
        if (!empty($fix)) {
            $c->compare('val', $fix, true);
        }
        $c->order = 'id asc';
        $c->limit = 10;

        return $this->findAll($c);
    }

    /**
     * 封号功能
     * @param $val
     */
    public function closeVal($val)
    {
        $this->updateAll(
            array('closed' => Common::STATUS_TRUE),
            'val=:val',
            array(':val' => $val)
        );
    }

    /**
     * @param string $val
     * @param string $type
     * @return BindSample
     */
    public function getByVal($val, $type = '')
    {
        $c = new CDbCriteria();
        if (!empty($type)) {
            $c->compare('`type`', $type);
        }
        $c->compare('`val`', $val, true);
        return $this->find($c);
    }

    /**
     * 根据业务类型和编号获取业务列表
     * @param $valList
     * @param $type
     * @return BindSample[]
     */
    public function getByValList($valList, $type)
    {
        $c = new CDbCriteria();
        $c->compare('`type`', $type);
        $c->addInCondition('val', $valList);
        return $this->findAll($c);
    }
    /*
     * 获取用户名
     * */
/*    public function getUsername()
    {
        $model = new MemberResource();
        $data = $model -> find("`type`=:type and `key`=:key",array(":type"=>$this->type,":key"=>$this->val));
        $uid = $data['uid'];
        $member_model = new Member();
        $member_data = $member_model -> findByPk($uid);
        return $member_data['username'];
    }*/
    /*
     * 业务开启状态
     * */
    public function getOpenStatus()
    {
        $model = new MemberResource();
        $data = $model -> find("`type`=:type and `uid`=:uid",array(":type"=>$this->type,":uid"=>$this->uid));
        if($data['openstatus']==1){
            $a = "<font color=#006600>开启</font>";
        }elseif($data['openstatus']==0){
            $a = "<font color=#ff0000>关闭</font>";
        }else{
            $a = "发生错误";
        }
        return $a;
    }
}