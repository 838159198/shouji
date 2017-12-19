<?php

/**
 * This is the model class for table "{{rom_appresource}}".
 *
 * The followings are the available columns in table '{{rom_appresource}}':
 * @property string $id
 * @property string $uid
 * @property string $type
 * @property string $imeicode
 * @property string $simcode
 * @property integer $status
 * @property integer $model
 * @property integer $finishstatus
 * @property string $createtime
 * @property string $closeend
 * @property string $finishtime
 * @property string $finishdate
 * @property string $tjcode
 * @property string $installtime
 * @property string $installcount
 * @property string $ip
 * @property string $brand
 * @property string $sys
 * @property string $from
 * @property string $tcfirsttime
 * @property string $tc
 * @property string $tcid
 * @property Member $noincome
 * @property string $createstamp
 */
class RomAppresource extends CActiveRecord
{
    /** 状态-可用 */
    const STATUS_TRUE = 1;
    /** 状态-不可用 */
    const STATUS_FALSE = 0;
    /** 状态-所有 */
    const STATUS_ALL = -1;

    /** 开启状态-开启 */
    const OPEN_TRUE = 1;
    /** 开启状态-关闭 */
    const OPEN_FALSE = 0;

    private $_username = null;
    public $count;
    public $count1;
    public $count2;
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
     * @return MemberResource the static model class
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
        $data = array(array("key"=>1,"value"=>"监视"),array("key"=>0,"value"=>"不监视"));
        return $data;
    }
    /**
     * 业务分配情况
     */
    public function getlistDataType0()
    {
        $data = array(array("key"=>0,"value"=>"未完成"),array("key"=>1,"value"=>"完成激活"));
        return $data;
    }
    /**
     * 业务来源
     */
    public function getlistDataType1()
    {
        $data = array(array("key"=>0,"value"=>"rom"),array("key"=>1,"value"=>"pc助手"),array("key"=>2,"value"=>"速传"));
        return $data;
    }
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rom_appresource}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, type, createtime', 'required'),
            array('uid,status, finishstatus', 'numerical', 'integerOnly' => true),
            array('uid', 'length', 'max' => 6),
            array('type', 'length', 'max' => 20),
            array('imeicode,ip', 'length', 'max' => 15),
            array('simcode,model', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, status, finishstatus, createtime, imeicode,simcode, closeend,finishtime,finishdate,model,username,brand,sys,tjcode,installtime,installcount,ip,from,tcfirsttime,tcid,tc,noincome,createstamp,count', 'safe', 'on' => 'search'),
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
            'type' => '资源类型',
            'imeicode' => '手机imei码',
            'simcode' => 'sim码',
            'status' => '监视状态',
            'model' => '手机型号',
            'finishstatus' => '完成状态',
            'createtime' => '创建时间',
            'closeend' => '封号时间',
            'finishtime' => '完成时间',
            'finishdate' => '数据激活日期',
            'username' => '用户名',
            'tjcode' => '统计app内码',
            'installtime' => '安装时间',
            'installcount' => '安装次数',
            'ip' => 'IP',
            'brand' => '品牌',
            'sys' => '系统',
            'from' => 'from',
            'tc' => 'tc',
            'tcid' => 'tcid',
            'tcfirsttime' => 'tcfirsttime',
            'noincome' => 'noincome',
            'createstamp' => 'createstamp',
            'count' => '安装软件数量',
            'count1' => '激活数量',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($createstamp='')
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition("t.createstamp> :createstamp");
        $criteria->params[':createstamp']=$createstamp;
        $criteria->compare('t.`id`', $this->id, true);
        $criteria->compare('t.`uid`', $this->uid, true);
        $criteria->compare('t.`type`', $this->type, true);
        $criteria->compare('t.`imeicode`', $this->imeicode, true);
        $criteria->compare('t.`simcode`', $this->simcode, true);
        $criteria->compare('t.`tjcode`', $this->tjcode, true);
        $criteria->compare('t.`status`', $this->status, true);
        $criteria->compare('t.`model`', $this->model, true);
        $criteria->compare('t.finishstatus', $this->finishstatus, true);
        $criteria->compare('t.`createtime`', $this->createtime, true);
        $criteria->compare('t.`closeend`', $this->closeend, true);
        $criteria->compare('t.`finishtime`', $this->finishtime, true);
        $criteria->compare('t.`finishdate`', $this->finishdate, true);
        $criteria->compare('t.`installtime`', $this->installtime, true);
        $criteria->compare('t.`installcount`', $this->installcount, true);
        $criteria->compare('t.`brand`', $this->brand, true);
        $criteria->compare('t.`sys`', $this->sys, true);
        $criteria->compare('t.`ip`', $this->ip, true);
        $criteria->compare('t.`from`', $this->from, true);
        $criteria->compare('t.`tc`', $this->tc, true);
        $criteria->compare('t.`tcid`', $this->tcid, true);
        $criteria->compare('t.`createstamp`', $this->createstamp, true);
        $criteria->compare('t.`tcfirsttime`', $this->tcfirsttime, true);
        $criteria->compare('t.`noincome`', $this->noincome, true);

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
     * 根据key获取对象列表（资源ID有可能会相同）
     * @param string $key
     * @param int $status
     * @return MemberResource[]
     */
    public function getListByKey($key, $status = self::STATUS_TRUE)
    {
        $c = new CDbCriteria();
        $c->compare('t.`key`', $key);
        if ($status >= 0) {
            $c->compare('t.`status`', $status);
        }
        $c->with = 'u';
        return $this->findAll($c);
    }

    /**
     * 根据key列表获取对象列表
     * @param array $keys
     * @param string $type
     * @return MemberResource[]
     */
    public function getByKeys($keys, $type)
    {
        $c = new CDbCriteria();
        $c->with = 'u';
        $c->compare('`t`.`type`', $type);
        $c->compare('`t`.`status`', self::STATUS_TRUE);
        $c->addInCondition('`t`.`key`', $keys);
        /* @var $modelList MemberResource[] */
        $modelList = $this->findAll($c);
        $arr = array();
        foreach ($modelList as $model) {
            $arr[$model->id] = $model;
        }
        return $arr;
    }

    /**
     * 根据用户id获取绑定数据
     * @param $uid
     * @param int $bod
     * @return MemberResource[]
     */
    public function getByUid($uid, $bod = 0)
    {
        $c = new CDbCriteria();
        $c->compare('uid', $uid);
        $c->compare('bod', $bod);
        return $this->findAll($c);
    }

    /**
     * 根据广告类型获取绑定的值
     * @param $uid
     * @param $type
     * @param int $bod
     * @return MemberResource
     */
    public function getBidValue($uid, $type, $bod = 0)
    {
        $c = new CDbCriteria();
        $c->compare('`t`.`uid`', $uid);
        $c->compare('`t`.`bod`', $bod);
        $c->compare('`t`.`type`', $type);
        $c->order = '`t`.`status` DESC';
        $c->with = 'u';

        return $this->find($c);
    }

    /**
     * 封号
     * @param $key
     * @param $date
     */
    public function closeResource($key, $date)
    {
        $this->updateAll(
            array(
                'status' => Common::STATUS_FALSE,
                'motifytime' => time(),
                'closestart' => date('Y - m - d', strtotime($date))
            ),
            '`key` =:key',
            array(':key' => $key)
        );
    }

    /**
     * 根据有收入用户ID列表查询无收入用户ID列表
     * @param $type
     * @param $haveIncomeUidList
     * @return MemberResource[]
     */
    public function getNotHaveIncomUidList($type, $haveIncomeUidList)
    {
        $c = new CDbCriteria();
        $c->compare('type', $type);
        $c->addNotInCondition('uid', $haveIncomeUidList);
        return $this->findAll($c);
    }

    /**
     * 根据业务类型获取开启业务的关系对象列表
     * @param $type
     * @return MemberResource[]
     */
    public function getOpenList($type)
    {
        if (empty($type)) return array();

        $c = new CDbCriteria();
        $c->compare('type', $type);
        $c->compare('status', self::STATUS_TRUE);
        $c->compare('openstatus', self::OPEN_TRUE);
        return $this->findAll($c);
    }

    /**
     * @param array $uids
     * @param array $types
     * @return MemberResource[]
     */
    public function getByUids($uids, $types)
    {
        $c = new CDbCriteria();
        $c->addInCondition('uid', $uids);
        $c->addInCondition('type', $types);
        $c->compare('status', self::STATUS_TRUE);
        return $this->findAll($c);
    }
    /**
     * 安装量---用户池使用客户使用
     */
    public function memberPoolSee($mid)
    {
        //上周起始时间
        $lastweek_s=date("Y-m-d 00:00:00",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));
        $lastweek_e=date("Y-m-d 23:59:59",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
        //本周起始时间
        $thisweek_s=date("Y-m-d 00:00:00",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
        $thisweek_e=date("Y-m-d 23:59:59",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
        $uid = empty($mid) ? 2 : $mid;

        //上周数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createtime >= '{$lastweek_s}' and createtime <= '{$lastweek_e}'";
        $lastweekcount = Yii::app()->db->createCommand($sql_month)->queryAll();
        //上周数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createtime >= '{$thisweek_s}' and createtime <= '{$thisweek_e}'";
        $thisweekcount = Yii::app()->db->createCommand($sql_month)->queryAll();

        return array($lastweekcount,$thisweekcount);

    }

    /**
     * 用户池--有效回访周个数总计
     */
    public function memberPoolSeeCount($mids)
    {
        //上周起始时间
        $lastweek_s=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
        $lastweek_e=mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
        //本周起始时间
        $thisweek_s=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
        $thisweek_e=mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));

        $uid = $mids;

        //上周数据统计
        $sql_month ="select count(*) as counts from app_ask_task where f_id={$uid} and is_allow=1 and availability=1 and allow_time >= {$lastweek_s} and allow_time <= {$lastweek_e}";
        $lastweekcount = Yii::app()->db->createCommand($sql_month)->queryAll();

        //本周起始时间
        $sql_month ="select count(*) as counts from app_ask_task where f_id={$uid} and is_allow=1 and availability=1 and allow_time >= {$thisweek_s} and allow_time <= {$thisweek_e}";
        $thisweekcount = Yii::app()->db->createCommand($sql_month)->queryAll();

        return array($lastweekcount,$thisweekcount);

    }

    /**
     * 地推数据
     * */
    public function getDtDataProviderByDate($date,$type,$username)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.id,t.uid,t.type,t.imeicode,t.simcode,t.tjcode,t.status,t.model,t.finishstatus,t.createtime,t.closeend,t.finishdate,t.finishtime,t.installtime,t.installcount,t.ip,t.from';

        if(!empty($username))
        {
            $criteria->addCondition("member.username=:username");
            $criteria->params[':username']=$username;
        }
        $criteria->addCondition("t.type=:type");
        $criteria->addCondition("t.installtime<=:installtime");
        $criteria->params[':type']=$type;
        $criteria->params[':installtime']=$date;

        $criteria->addCondition("t.finishstatus=0");
        $criteria->addCondition("t.status=1");
        $criteria->addCondition("t.imeicode!=''");
        $criteria->addNotInCondition('t.imeicode',Common::getExceptList());
        $criteria->addCondition("t.model!=''");
        $criteria->addCondition("t.id!=760");//代理商

        $criteria->addInCondition('t.from', array(1,2));
        $criteria->with = array('member');

        $criteria->group = 'member.username,t.type,t.imeicode';
        $criteria->order = 'member.username ASC';


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }


    /**
     * 新地推数据（代理商）
     * */
    public static  function getNewdtDataProviderByDate($date,$username)
    {
        if(!empty($username))
        {
            $sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count,count(if(a.finishstatus=1,true,null)) as count1 FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.username='{$username}' and  a.createstamp={$date} GROUP BY a.imeicode,a.uid";
        }else{
            $sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count,count(if(a.finishstatus=1,true,null)) as count1 FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.subagent=707 and  a.createstamp={$date} GROUP BY a.imeicode,a.uid";
        }
        $lastweekcount = Yii::app()->db->createCommand($sql)->queryAll();


        return $lastweekcount;
    }
    public static function getInstall($imeicode,$time){
        $dats = RomAppresource::model()->count("imeicode=:imeicode and createstamp =:createstamp",array(":imeicode"=>$imeicode,":createstamp"=>$time));
        return $dats;
    }

    public static function getDayInstall($uid,$time){
        $sql ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp={$time}";
        $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
        if($curmodel){
            return $curmodel[0]['counts'];
        }

    }

    /**
     * 
     *激活数量
     * 
     */
    public static function selectnum($uid,$begin,$end,$type){
        $sql="SELECT count(distinct imeicode) as total FROM `app_rom_appresource` WHERE uid={$uid} and createstamp>={$begin} and createstamp<={$end} and type='{$type}' and status=0 and finishstatus=1 and finishdate!='0000-00-00'";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            return $result[0]['total'];
        }else{
            return 0;
        }
    }
    /**
     * 
     * 业务名称
     */
    public static function proname($type){
        $sql="select name from `app_product` where pathname='{$type}' limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            return $result[0]['name'];
        }else{
            return '';
        }
    } 

}