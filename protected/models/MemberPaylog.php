<?php

/**
 * 会员提现记录--保留
 * This is the model class for table "{{member_paylog}}".
 *
 * The followings are the available columns in table '{{member_paylog}}':
 * @property string $id
 * @property integer $uid
 * @property double $ask_sum
 * @property double $sums
 * @property string $ask_time
 * @property string $answer_time
 * @property integer $status
 * @property integer $valid
 * @property double $surplus
 *
 * @property Member $member
 */
class MemberPaylog extends CActiveRecord
{
    /** 数据有效 */
    const VALID_TRUE = 1;
    /** 数据无效 */
    const VALID_FALSE = 0;
    /** 已付款 */
    const STATUS_TRUE = 1;
    /** 未付款 */
    const STATUS_FALSE = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberPaylog the static model class
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
        return '{{member_paylog}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'required'),
            array('uid, status, valid', 'numerical', 'integerOnly' => true),
            array('sums, ask_sum,surplus', 'numerical'),
            array('ask_time, answer_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, sums, ask_sum, ask_time, answer_time, status, valid ,surplus', 'safe', 'on' => 'search'),
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
            'uid' => 'Uid',
            'ask_sum' => '申请金额',
            'sums' => '提现金额',
            'ask_time' => '申请时间',
            'answer_time' => '支付时间',
            'status' => '状态',
            'valid' => '是否有效',
            'surplus' => '打回提现负数余额',
            'payee'=>'收款人',
            'bank'=>'开户行',
            'bank_site'=>'开户地址',
            'bank_num'=>'银行账号',
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
        $criteria->compare('uid', $this->uid);
        $criteria->compare('ask_sum', $this->ask_sum);
        $criteria->compare('sums', $this->sums);
        $criteria->compare('ask_time', $this->ask_time, true);
        $criteria->compare('answer_time', $this->answer_time, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('valid', $this->valid);
        $criteria->compare('surplus', $this->surplus);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 根据uid和日期获取提取历史记录
     * @param int $uid
     * @param string $date Y-m
     * @return MemberPaylog
     */
    public function getByUidAndDate($uid, $date)
    {
        return $this->find('uid=:uid AND valid=:valid AND ask_time LIKE :date', array(
            ':uid' => $uid,
            ':valid' => self::VALID_TRUE,
            ':date' => '%' . $date . '%'
        ));
    }

    /**
     * 添加一条提现记录
     * @param $uid
     * @param $sums
     * @param $ask_sum
     */
    public function addOne($uid, $sums, $ask_sum)
    {
        $model = new MemberPaylog();
        $model->uid = $uid;
        $model->ask_sum = $ask_sum;
        $model->sums = $sums;
        $model->ask_time = date('Y-m-d H:i:s');
        $model->status = self::STATUS_FALSE;
        $model->valid = self::VALID_TRUE;
        /*
         * time:2015年5月29日15:01:26
         * 添加收款人信息到数据库
         * */
        $member_model = new Member();
        $member_data = $member_model -> findByPk($uid);
        $model -> payee = $member_data['holder'];
        $model -> bank = $member_data['bank'];
        $model -> bank_num = $member_data['bank_no'];
        $model -> bank_site = $member_data['bank_site'];
        $model->insert();

        //增加积分
        $credits = floor($sums);
        $member_model ->updateCounters(array("credits"=>$credits),"id={$uid}");
        //提现增加积分日志
        $credits_model = new MemberCreditsLog();
        $credits_model->create_datetime = time();
        $credits_model->memberId = $uid;
        $credits_model->credits = $credits;
        $credits_model->remarks = "用户提现获赠{$credits}积分";
        $credits_model->opid = 0;
        $credits_model->source = "pay";
        $credits_model->account_credits = $member_data['credits']+$credits;
        $credits_model->save();


    }

    /**
     * 置为无效
     * @param $id
     */
    public function edit2Invalid($id)
    {
        /* @var $model MemberPaylog */
        $model = $this->findByPk($id);
        if (is_null($model)) return;
        $model->valid = self::VALID_FALSE;
        $model->update();
    }

    /**
     * 获取用户提现、支付记录，uid为空则取当前用户
     * @param int $pagesize
     * @param string $y 年
     * @param int $uid
     * @return CArrayDataProvider
     */
    public function getDataProviderByUid($pagesize = 20, $uid, $y = null)
    {
        $c = new CDbCriteria();
        $c->addCondition('uid=:uid');
        $c->addCondition('valid=:valid');
        $c->params = array(
            ':uid' => $uid,
            ':valid' => self::VALID_TRUE,
        );

        if (!is_null($y)) {
            //$c->addSearchCondition('ask_time', $y);
            //$c->addSearchCondition('ask_time', strtotime('-1' .$y));
        }

        $c->order = 'id DESC';

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $c,
            'pagination' => array('pageSize' => $pagesize),
        ));
    }

    /**
     * 根据年月获取对象列表
     * @param $yearMonth
     * @param int $status
     * @return IDataProvider
     */
    public function getDataProviderByDate($yearMonth, $status = self::STATUS_FALSE)
    {
        $c = new CDbCriteria();
        $c->compare('t.`ask_time`', $yearMonth, true);
        $c->compare('t.`status`', $status);
        $c->compare('t.`valid`', self::VALID_TRUE);

        $c->with = 'member';

        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }

    /**
     * @param $yearMonth
     * @param int $status
     * @return MemberPaylog[]
     */
    public function getListByDate($yearMonth, $status = self::STATUS_FALSE)
    {
        $c = new CDbCriteria();
        $c->compare('t.`ask_time`', $yearMonth, true);
        $c->compare('t.`status`', $status);
        $c->compare('t.`valid`', self::VALID_TRUE);

        $c->with = 'member';
        return $this->findAll($c);
    }

}