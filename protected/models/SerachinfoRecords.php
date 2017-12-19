<?php

/**
 * This is the model class for table "{{serachinfo_records}}".
 *
 * The followings are the available columns in table '{{serachinfo_records}}':
 * @property string $id
 * @property string $sid
 * @property string $mid
 * @property string $content
 * @property int $jointime
 *
 */
class SerachinfoRecords extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SerachinfoRecords the static model class
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
        return '{{serachinfo_records}}';
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
            array('sid, mid', 'length', 'max' => 11),
            array('jointime', 'length', 'max' => 12),
            array('content', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sid, mid, content, jointime', 'safe', 'on' => 'search'),
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
            'u' => array(self::BELONGS_TO, 'SerachInfo', 'sid'),
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
            'sid' => '用户',
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
        $criteria->compare('sid', $this->sid, true);
        $criteria->compare('mid', $this->mid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('jointime', $this->jointime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param sid
     * @return CActiveDataProvider
     */
    public function getBySid($sid)
    {
        $c = new CDbCriteria();
        $c->addCondition('t.sid=:sid');
        $c->params = array(':sid' => $sid);
        $c->with = array('u', 'm');
        $c->order = 't.jointime DESC';
        return new CActiveDataProvider($this, array('criteria' => $c));
    }

    /**
     * 用户联系记录数量及最后联系时间
     * @param $sid
     * @return array
     */
    public function getCountAndLastTimeBySid($sid)
    {
        $sql = 'SELECT COUNT(`sid`) AS `count`,`jointime` FROM (SELECT * FROM ' . $this->tableName() . ' WHERE sid=:sid ORDER BY jointime DESC) AS s';
        return Yii::app()->db->createCommand($sql)->queryRow(true, array(':sid' => $sid));
    }

    /**
     * 用户联系记录数量及最后联系时间
     * @param $sidList
     * @return array
     */
    public function getCountAndLastTimeBySidList($sidList)
    {
        if (empty($sidList)) return array();
        $sql = 'SELECT sid,COUNT(`sid`) AS `count`,`jointime`
                FROM (SELECT * FROM ' . $this->tableName() .
                ' WHERE sid in (' . implode(',', $sidList) . ')
                ORDER BY jointime DESC) AS s GROUP BY sid';
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($list as $v) {
            $data[$v['sid']] = $v;
        }
        return $data;
    }
}


