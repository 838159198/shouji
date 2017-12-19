<?php

/**
 * This is the model class for table "{{advisory_records}}".
 *
 * The followings are the available columns in table '{{advisory_records}}':
 * @property string $id
 * @property string $uid
 * @property string $mid
 * @property string $content
 * @property int $jointime
 *
 * The followings are the available model relations:
 * @property Member $u
 * @property Manage $m
 */
class AdvisoryRecords extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AdvisoryRecords the static model class
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
        return '{{advisory_records}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
            array('uid, mid', 'length', 'max' => 11),
            array('jointime', 'length', 'max' => 12),
            array('content', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, mid, content, jointime', 'safe', 'on' => 'search'),
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
            'm' => array(self::BELONGS_TO, 'Manage', 'mid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户',
            'mid' => '客服',
            'content' => '内容',
            'jointime' => '添加时间',
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
        $criteria->compare('mid', $this->mid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('jointime', $this->jointime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param $uid
     * @return CActiveDataProvider
     */
    public function getByUid($uid)
    {
        $c = new CDbCriteria();
        $c->addCondition('t.uid=:uid');
        $c->params = array(':uid' => $uid);
        $c->with = array('u', 'm');
        $c->order = 't.jointime DESC';
        return new CActiveDataProvider($this, array('criteria' => $c));
    }

    /**
     * 用户联系记录数量及最后联系时间
     * @param $uid
     * @return array
     */
    public function getCountAndLastTimeByUid($uid)
    {
        $sql = 'SELECT COUNT(`uid`) AS `count`,`jointime` FROM (SELECT * FROM ' . $this->tableName() . ' WHERE uid=:uid ORDER BY jointime DESC) AS s';
        return Yii::app()->db->createCommand($sql)->queryRow(true, array(':uid' => $uid));
    }

    /**
     * 用户联系记录数量及最后联系时间
     * @param $uidList
     * @return array
     */
    public function getCountAndLastTimeByUidList($uidList)
    {
        if (empty($uidList)) return array();
        $sql = 'SELECT uid,COUNT(`uid`) AS `count`,`jointime`
                FROM (SELECT * FROM ' . $this->tableName() .
                ' WHERE uid in (' . implode(',', $uidList) . ')
                ORDER BY jointime DESC) AS s GROUP BY uid';
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($list as $v) {
            $data[$v['uid']] = $v;
        }
        return $data;
    }
}


