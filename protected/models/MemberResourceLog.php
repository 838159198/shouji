<?php
/**
 * 业务开启关闭LOG
 * This is the model class for table "{{member_resource_log}}".
 *
 * The followings are the available columns in table '{{member_resource_log}}':
 * @property string $id
 * @property string $uid
 * @property string $type
 * @property string $mrid
 * @property integer $open
 * @property integer $createtime
 * @property integer $status
 */
class MemberResourceLog extends CActiveRecord
{
    /** 状态-可用 */
    const STATUS_TRUE = 1;
    /** 状态-不可用 */
    const STATUS_FALSE = 0;
    /** 开启状态-开启 */
    const OPEN_TRUE = 1;
    /** 开启状态-关闭 */
    const OPEN_FALSE = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberResourceLog the static model class
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
        return '{{member_resource_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, mrid, createtime', 'required'),
            array('open, createtime, status', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 6),
            array('uid, mrid', 'length', 'max' => 11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, mrid, open, createtime, status', 'safe', 'on' => 'search'),
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
            'type' => '业务类型',
            'mrid' => '关系ID',
            'open' => '开启状态',
            'createtime' => '添加时间',
            'status' => '是否可用',
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
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('mrid', $this->mrid, true);
        $criteria->compare('open', $this->open);
        $criteria->compare('createtime', $this->createtime);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 最后开启业务log列表
     * @param $type
     * @param $date 最后日期
     * @return MemberResourceLog[]
     */
    public function getLastOpenList($type, $date)
    {
        $times = DateUtil::getTimeByDate($date);
        $sql = 'SELECT * FROM {{member_resource_log}} AS a INNER JOIN
                (SELECT `uid`,MAX(`createtime`) AS `createtime` FROM {{member_resource_log}} WHERE `type`=:type  AND `createtime`<:createtime GROUP BY uid) AS b
                ON a.uid=b.uid AND a.createtime=b.createtime
                WHERE `open`=1';
        $list = $this->findAllBySql($sql, array(':type' => $type, ':createtime' => $times['first']));
//        $data = array();
//        foreach ($list as $item) {
//            /* @var $item MemberResourceLog */
//            $data[$item->uid] = $item;
//        }
        return $list;
    }

    public function getLastOpenList2($type, $date)
    {
        $times = DateUtil::getTimeByDate($date);
        $sql = 'SELECT * FROM {{member_resource_log}} AS a INNER JOIN
                (SELECT `uid`,MAX(`createtime`) AS `createtime` FROM {{member_resource_log}} WHERE `type`=:type  AND `createtime`<:createtime AND uid = :uid GROUP BY uid) AS b
                ON a.uid=b.uid AND a.createtime=b.createtime
                WHERE `open`=1';
        $list = $this->findAllBySql($sql, array(':type' => $type, ':createtime' => $times['first'],'uid'=>2037));
//        $data = array();
//        foreach ($list as $item) {
//            /* @var $item MemberResourceLog */
//            $data[$item->uid] = $item;
//        }
        return $list;
    }

    /**
     * 根据日期获取业务当日开启关闭LOG列表
     * @param $type
     * @param $date
     * @return array
     */
    public function getListByDate($type, $date)
    {
        $times = DateUtil::getTimeByDate($date);
        $c = new CDbCriteria();
        $c->compare('type', $type);
        $c->compare('createtime', '>=' . $times['first']);
        $c->compare('createtime', '<=' . $times['last']);
        $c->order = 'id';
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $item) {
            /* @var $item MemberResourceLog */
            $data[$item->uid][] = $item;
        }
        return $data;
    }

    public function getListByDate2($type, $date)
    {
        $times = DateUtil::getTimeByDate($date);
        $c = new CDbCriteria();
        $c->compare('type', $type);
        $c->compare('uid', 2037);
        $c->compare('createtime', '>=' . $times['first']);
        $c->compare('createtime', '<=' . $times['last']);
        $c->order = 'id';
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $item) {
            /* @var $item MemberResourceLog */
            $data[$item->uid][] = $item;
        }
        return $data;
    }

    /**
     * 添加业务开启关闭LOG，并修改各个业务的开启状态
     * @param MemberResource $memberResource
     * @param int $openStatus
     */
    public function add(MemberResource $memberResource, $openStatus,$sign='')
    {
        $list[] = array(
            'type' => $memberResource->type,
            'mrid' => $memberResource->id,
            'open' => $openStatus
        );
        $sign=empty($sign)? 0:$sign;

        //更新业务开启状态
        $memberResource->openstatus = $openStatus;
        $memberResource->update();

        foreach ($list as $item) {
            $model = MemberResourceLog::model();
            $model->isNewRecord = true;
            $model->unsetAttributes();

            $model->uid = $memberResource->uid;
            $model->type = $item['type'];
            $model->mrid = $item['mrid'];
            $model->open = $item['open'];
            $model->status = self::STATUS_TRUE;
            $model->createtime = DateUtil::time();
            $model->sign =$sign;
            $model->insert();
        }
    }
}