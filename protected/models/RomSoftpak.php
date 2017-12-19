<?php

/**
 * This is the model class for table "{{rom_softpak}}".
 *
 * The followings are the available columns in table '{{rom_softpak}}':
 * @property string $id
 * @property string $uid
 * @property string $type
 * @property string $version
 * @property string $url
 * @property string $serial_number
 * @property integer $status
 * @property integer $allot
 * @property integer $closed
 */
class RomSoftpak extends CActiveRecord
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

    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>未分配</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>已分配</b></font>";
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
        $data = array(array("key"=>1,"value"=>"未分配"),array("key"=>0,"value"=>"已分配"));
        return $data;
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
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rom_softpak}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, serial_number,version', 'required'),
            array('serial_number','unique'),
            array('uid,status, allot, closed,serial_number,version', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 10),
            array('serial_number', 'length','min'=>6,'max' => 6),
            array('url', 'length', 'max' => 80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, serial_number, url, version, status, allot, closed,username,md5,updatetime', 'safe', 'on' => 'search'),
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
            'type' => '软件类型',
            'serial_number' => '软件序列号',
            'version' => '软件版本号',
            'status' => '状态',
            'allot' => '分配类型',
            'closed' => '是否已被封',
            'username' => '用户名',
            'updatetime' => '更新时间',
            'url' => '软件地址'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */

    public function search($x='')
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.uid', $this->uid, true);
        if($x==''){
            $criteria->addCondition("t.uid!=0");
        }

        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.serial_number', $this->serial_number, true);
        $criteria->compare('t.version', $this->version, true);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('t.allot', $this->allot, true);
        $criteria->compare('t.closed', $this->closed, true);
        $criteria->compare('t.url', $this->url, true);
        $criteria->compare('t.md5', $this->md5, true);
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
     * 获得最新一条软件序列号
     * @param $type
     * @param int $allot 分配状态0.自动；1.手动；-1.所有
     * @return RomSoftpak[]
     */
    public function getListGroupByType($type, $allot = self::ALLOT_AUTO)
    {
        $closed=self::CLOSED_FALSE;//未被封号
        $c = new CDbCriteria();
        $c->compare('status', self::STATUS_TRUE); //有效状态
        $c->compare('type', $type);
        $c->compare('closed', $closed);
        if ($allot >= 0) {
            $c->compare('allot', $allot);
        }
        $c->order = 'id asc';
        $c->limit = 1;

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
     * @return RomSoftpak
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
     * @return RomSoftpak[]
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
        $data = $model -> find("`type`=:type and `key`=:key",array(":type"=>$this->type,":key"=>$this->val));
        if($data['openstatus']==1){
            $a = "<font color=#006600>开启</font>";
        }elseif($data['openstatus']==0){
            $a = "<font color=#ff0000>关闭</font>";
        }else{
            $a = "发生错误";
        }
        return $a;
    }
    public static function getlistDataUserGroup(){
        $arrType['tongji']='tongji';
        $arrType['dealer']='dealer';
        $arrType['newdt']='newdt';
        return $arrType;
    }
    public function getlistDataUserType()
    {
        $data = array(array("key"=>"tongji","value"=>"tongji"),
            array("key"=>"dealer","value"=>"dealer"),
            array("key"=>"newdt","value"=>"newdt")
        );
        return $data;
    }
    /**
     * 根据用户分组获取统计软件个数
     * @param $type
     * @param $flag
     * @return $model[] count总数  count1已分配 count2未分配
     */
    public static function getCountByAgent($type, $flag='')
    {
        $sql ="SELECT COUNT(id)count, count(if(status=0,true,null))as count1,count(if(status=1,true,null)) as count2 FROM app_rom_softpak WHERE type='{$type}'";
        $model = Yii::app()->db->createCommand($sql)->queryRow();
        return $model;
    }


}