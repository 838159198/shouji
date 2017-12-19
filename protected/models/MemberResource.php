<?php

/**
 * This is the model class for table "{{member_resource}}".
 *
 * The followings are the available columns in table '{{member_resource}}':
 * @property string $id
 * @property string $uid
 * @property integer $bod
 * @property string $type
 * @property string $key
 * @property integer $status
 * @property integer $openstatus
 * @property integer $createtime
 * @property integer $motifytime
 * @property string $closestart
 * @property string $closeend
 *
 * @property Member $u
 */
class MemberResource extends CActiveRecord
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
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{member_resource}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, type, key, createtime, motifytime, closestart, closeend', 'required'),
            array('bod, status, openstatus, createtime, motifytime', 'numerical', 'integerOnly' => true),
            array('uid', 'length', 'max' => 11),
            array('type', 'length', 'max' => 10),
            array('key', 'length', 'max' => 32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, bod, type, key, status, openstatus, createtime, motifytime, closestart, closeend', 'safe', 'on' => 'search'),
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
            'u' => array(self::BELONGS_TO, 'Member', 'uid'),
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
            'bod' => '网吧序号，0为主用户',
            'type' => '资源类型',
            'key' => 'Key',
            'status' => '状态',
            'openstatus' => '开启状态',
            'createtime' => '创建时间',
            'motifytime' => '修改时间',
            'closestart' => '封号时间开始',
            'closeend' => '封号时间结束',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('`id`', $this->id, true);
        $criteria->compare('`uid`', $this->uid, true);
        $criteria->compare('`bod`', $this->bod);
        $criteria->compare('`type`', $this->type);
        $criteria->compare('`key`', $this->key, true);
        $criteria->compare('`status`', $this->status);
        $criteria->compare('openstatus', $this->openstatus);
        $criteria->compare('`createtime`', $this->createtime);
        $criteria->compare('`motifytime`', $this->motifytime);
        $criteria->compare('`closestart`', $this->closestart, true);
        $criteria->compare('`closeend`', $this->closeend, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    /**
     * 根据key获取对象，无论是否封号
     * 因为有key回收功能，所以一个key可能会分配到多个用户上
     * 因此每个key只获取取一个有效对象，原则：
     * 1、先取未封号数据
     * 2、如果全部封号，取最后一个封号数据
     *
     * @param $key
     * @param $type
     * @return MemberResource
     */
    public function getByKey($key, $type)
    {
        $c = new CDbCriteria();
        $c->compare('t.`type`', $type);
        $c->compare('t.`key`', $key);
        $c->with = 'u';
        $c->order = 'closestart DESC';

        /* @var $list MemberResource[] */
        $list = $this->findAll($c);
        $model = null;
        foreach ($list as $_item) {
            if ($_item->status == MemberResource::STATUS_TRUE) {
                $model = $_item;
                break;
            }
        }

        if ($model == null && count($list) > 0) {
            $model = $list[0];
        }

        return $model;
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
     * 根据type数组，获取对象
     * @param array $type column-搜索字段,value-搜索前缀,id-搜索值
     * @return MemberResource
     */
    public function getByType($type = array())
    {
        $c = new CDbCriteria();
        $c->addCondition('`type`=:type');
        $c->addCondition('`status`=:status');
        $c->addCondition('`key`=:key');
        $c->params = array(
            ':type' => $type['column'],
            ':key' => $type['value'] . $type['id'],
            ':status' => self::STATUS_TRUE
        );
        return $this->find($c);
    }

    /**
     * 根据用户ID和资源类型获取该用户资源对象列表
     * @param $uid
     * @param $typeList
     * @return array
     */
    public function getListByType($uid, $typeList)
    {
        $c = new CDbCriteria();
        $c->compare('status', self::STATUS_TRUE);
        $c->addCondition('uid=:uid');
        $c->params = array(':uid' => $uid);
        $c->addInCondition('type', $typeList);
        return $this->findAll($c);
    }

    /**
     * @param $uidList
     * @param $typeList
     * @param int $status
     * @return array
     */
    public function getListByUidsAndType($uidList, $typeList, $status = self::STATUS_TRUE)
    {
        $c = new CDbCriteria();
        $c->addCondition('status=:status');
        $c->params = array(':status' => $status);
        $c->addInCondition('uid', $uidList);
        $c->addInCondition('type', $typeList);
        return $this->findAll($c);
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
     * 根据广告类型获取绑定的值
     * @param $uid
     * @param $type
     * @param int $status
     * @return MemberResource
     */
    public function getBidValue2($uid, $type, $status = 1)
    {
        $c = new CDbCriteria();
        $c->compare('`t`.`uid`', $uid);
        $c->compare('`t`.`status`', $status);
        $c->compare('`t`.`type`', $type);
        $c->order = '`t`.`status` DESC';
        $c->with = 'u';

        return $this->find($c);
    }
    /**
     * 根据广告类型为用户绑定广告ID
     * @param $uid
     * @param $type
     * @param $allot
     * @param string $fix
     * @param int $bod
     * @throws Exception
     * @return bool
     */
    public function bindMemberByType($uid, $type, $allot = BindSample::ALLOT_ALL, $fix = '', $bod = 0)
    {
        //平台默认：mrid资源id为MemberResource表的id
        if (in_array($type, Ad::getAplus())) {
            $this->createAplusResource($uid, $type);
            return true;
        }


        //以下为BindSample表有分配id情况
        $sampleList = BindSample::model()->getListGroupByType($type, $allot, $fix);

        foreach ($sampleList as $sample) {

            //如果有选择业务前缀的需求，判断查询出的ID是否符合要求
            if (empty($fix) == false && Ad::checkFix($sample->val, $fix) == false) {
                continue;
            }

            if ($type == $sample->type) {
                $model = new MemberResource();
                $model->uid = $uid;
                $model->bod = $bod;
                $model->type = $sample->type;
                $model->key = $sample->val;
                $model->createtime = time();
                $model->motifytime = 0;
                $model->insert();

                $sample->status = 0;
                $sample->update();
                break;
            }
        }
        return true;
    }

    /**
     * 为用户绑定默认绑定的广告ID
     * @param int $uid
     * @return bool
     */
    public function bindMember($uid)
    {
        $aplus = Ad::getAplus();
        foreach ($aplus as $key) {
            $this->createAplusResource($uid, $key);
        }
    }

    /**
     * 根据广告类型判断该类型是否有排除URL地址功能
     * @param $type
     * @return bool
     */
    public function checkHaveExcludeUrlByType($type)
    {
        if (in_array($type, array(Ad::TYPE_T123, Ad::TYPE_SGSS, Ad::TYPE_SGDH))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据广告业务列表查询该业务绑定启用状态 返回array('id'=>'status')
     * @param Member $member
     * @param Resource[] $resourceList
     * @param int $bod
     * @return array
     */
    public function getAdBindStatus(Member $member, $resourceList, $bod = 0)
    {
        $archivesStatus = array();
        foreach ($resourceList as $resource) {
            $memberResource = $this->getBidValue($member->id, $resource->pathname, $bod);

            //账号状态
            $closed = '';
            if ($memberResource != null) {
                //判断ID是否已被回收
                $closed = ($memberResource->status == MemberResource::STATUS_FALSE) ? '业务ID被回收' : '';
                //判断ID是否已被封
                $bindSample = BindSample::model()->getByVal($memberResource->key, $memberResource->type);
                if ($bindSample != null && $bindSample->closed == BindSample::CLOSED_TRUE) {
                    $closed = '此账号已被封';
                }
            }
            //'value' =>为key值;'typekey' =>为key值
            $status = array('status' => false, 'keyword' => $resource->pathname, 'value' => '', 'closed' => $closed, 'typekey' => '11111');
            if(!empty($memberResource))
            {
                if(($memberResource->openstatus==1) && ($memberResource->status==1) && ($memberResource->type==$resource->pathname))
                {
                    $status["status"]= true;
                    $status["value"]= $memberResource->key;
                    $status["typekey"]= $memberResource->key;
                    $status["is_put"]= $memberResource->is_put;
                }
            }
            $archivesStatus[$resource->id] = $status;
        }
        //client客户端ID
        //$archivesStatus['client'] = array('status' => true, 'keyword' => 'client', 'value' => 0, 'closed' => false);
        return $archivesStatus;
    }

    /**
     * 创建与Aplus相关的资源
     * @param $uid
     * @param $type
     * @param int $bod
     */
    private function createAplusResource($uid, $type, $bod = 0)
    {
        $model = new  MemberResource();
        $model->uid = $uid;
        $model->bod = $bod;
        $model->type = $type;
        $model->key = '';
        $model->createtime = time();
        $model->motifytime = 0;
        $model->insert();

        //$model->key = Ad::getPidByKey($type, $model->id);
        $model->key = $model->id;
        $model->update();
    }

    /**
     * 创建与Aplus相关的资源
     * @param $uid
     * @param $type
     * @param int $bod
     */
    private function createAplusResource2($uid, $type, $bod = 0)
    {
        $model = new  MemberResource();
        $model->uid = $uid;
        $model->bod = $bod;
        $model->type = $type;
        $model->key = '';
        $model->createtime = time();
        $model->motifytime = 0;
        $model->insert();

        //$model->key = Ad::getPidByKey($type, $model->id);
        $model->key = $model->id;
        $model->update();
    }



    /**
     * 创建与TT相关的资源
     * @param $uid
     * @param $type
     * @param int $bod
     */
    private function createTtResource($uid, $type, $bod = 0)
    {


        $model = new  MemberResource();
        $model->uid = $uid;
        $model->bod = $bod;
        $model->type = $type;
        $model->key = '';
        $model->createtime = time();
        $model->motifytime = 0;
        $model->insert();

        //$model->key = Ad::getPidByKey($type, $model->id);
        $model->key = $model->id;
        $model->update();
    }
    /**
     * 创建与默认app相关的资源
     * @param $uid
     * @param $type
     * @param int $bod
     */
    public  function createDeResource($uid, $type, $bod = 0)
    {


        $model = new  MemberResource();
        $model->uid = $uid;
        $model->bod = $bod;
        $model->type = $type;
        $model->key = '';
        $model->createtime = time();
        $model->motifytime = 0;
        $model->status=1;
        $model->openstatus=1;
        $model->is_put=1;
        $model->insert();

        //$model->key = Ad::getPidByKey($type, $model->id);
        $model->key = $model->id;
        $model->update();
    }

    /**
     * @param $uid
     * @param $type
     * @param $key
     * @param int $bod
     */
    private function createOtherResource($uid, $type, $key, $bod = 0)
    {
        $model = new  MemberResource();
        $model->uid = $uid;
        $model->bod = $bod;
        $model->type = $type;
        $model->key = $key;
        $model->createtime = time();
        $model->motifytime = 0;
        $model->insert();
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
     * 根据用户ID列表获取列表中用户封号数据
     * @param array $memberIds
     * @return array
     */
    public function getCloseList($memberIds)
    {
        if (empty($memberIds)) return array();

        $tMemberResource = $this->tableName();
        $tBindSample = BindSample::model()->tableName();

        $paramMemberIds = implode(',', $memberIds);

        $sql = 'SELECT * FROM ' . $tMemberResource . ' AS m  WHERE'
            . ' m.`status`=' . MemberResource::STATUS_FALSE;

        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($list as $val) {
            $mid = $val['uid'];
            $data[$mid] = isset($data[$mid]) ? $data[$mid]++ : 1;
        }

        return $data;
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
     * 获取用户所有开启业务的ID
     * @param $uid
     * @param $bod
     * @return array
     */
    public function getBodAdKeyList($uid, $bod = null)
    {
        $bodAdKeyList = array();
        if (!is_null($bod)) {
            $memberResourceList = $this->getByUid($uid, $bod);
            foreach ($memberResourceList as $memberResource) {
                $bodAdKeyList[$memberResource->type][] = $memberResource->id;
            }
        }
        return $bodAdKeyList;
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
     * @param int $uid
     * @return $adList[]
     */
    public static  function getByUidAdlist($uid){
        $adList=array();
        $model = MemberResource::model()->findAll(array(
            'select'=>array('id',"type"),
            'condition' => 'uid=:uid and status=1 and motifytime=0 and closestart=:closestart',
            'params' => array(':uid' => $uid,":closestart"=>'0000-00-00'),
        ));
        if($model){
            foreach($model as $v){
                $adList[]=$v->type;
            }
        }
        return  $adList;
    }
}