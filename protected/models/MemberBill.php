<?php

/**
 * 会员结款支付--保留
 * This is the model class for table "{{member_bill}}".
 *
 * The followings are the available columns in table '{{member_bill}}':
 * @property string $id
 * @property integer $uid
 * @property double $paid
 * @property double $nopay
 * @property double $surplus
 * @property double $cy
 */
class MemberBill extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberBill the static model class
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
        return '{{member_bill}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('paid', 'required'),
            array('uid', 'numerical', 'integerOnly' => true),
            array('paid, nopay, surplus, cy', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, paid, nopay, surplus, cy', 'safe', 'on' => 'search'),
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
            'uid' => 'Uid',
            'paid' => 'Paid',
            'nopay' => 'Nopay',
            'surplus' => 'Surplus',
            'cy' => 'Cy',
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
        $criteria->compare('paid', $this->paid);
        $criteria->compare('nopay', $this->nopay);
        $criteria->compare('surplus', $this->surplus);
        $criteria->compare('cy', $this->cy);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 根据uid获取用户结款支付记录，如果uid为空则获取当前用户
     * @param int $uid
     * @return MemberBill
     */
    public function getByUid($uid)
    {
        if (empty($uid)) {
            return null;
        }
        $model = $this->find('uid=:uid', array(':uid' => $uid));
        if (is_null($model)) {
            $model = MemberBill::model();
            $model->setIsNewRecord(true);
            $model->unsetAttributes();
            $model->uid = $uid;
            $model->paid = 0;
            $model->nopay = 0;
            $model->surplus = 0;
            $model->cy = 0;
            $model->insert();
        }
        return $model;
    }

    /**
     * 会员余额增加
     * @param $uid
     * @param $pay
     */
    public function addSurplus($uid, $pay)
    {
        $model = $this->getByUid($uid);
        if (is_null($model)) {
            return;
        }
        $update=json_encode($model->attributes);
        $model->surplus += $pay;
        $model->update();

        /*2017-11-15 写入日志表log_income start*/
        $mid=yii::app()->user->getState('uid');
        $content='表[member_bill]中插入数据'.json_encode($model->attributes);
        $ip=$_SERVER['SERVER_ADDR'];
        $title="统计收入数据";
        $before_content=$update;
        Income::addlogincome($mid,$uid,$content,$ip,$before_content,$title);   

        /*2017-11-15 end*/
    }

    /**
     * 修改会员结款信息，1.当月合计加入会员未付款，2.余额减去（用户提款+手续费）
     * @param $uid
     * @param $price 用户提款+手续费
     * @param $pay 用户提款
     */
    public function setPay($uid, $price, $pay)
    {
        $model = $this->getByUid($uid);
        $model->surplus -= $price;
        $model->nopay = $pay;
        $model->update();
    }

    /**
     * @param int[] $uidList
     * @return MemberBill[]
     */
    public function getByUidList($uidList)
    {
        $c = new CDbCriteria();
        $c->addInCondition('uid', $uidList);
        /* @var $modelList MemberBill[] */
        $modelList = $this->findAll($c);
        $data = array();
        foreach ($modelList as $model) {
            $data[$model->uid] = $model;
        }
        return $data;
    }
}