<?php

/**
 * 历史记录 
 * This is the model class for table "{{system_log}}".
 *
 * The followings are the available columns in table '{{system_log}}':
 * @property string $id
 * @property string $type
 * @property string $content
 * @property string $target
 * @property string $operate
 * @property integer $status
 * @property string $date
 */
class SystemLog extends CActiveRecord
{
    const TYPE_UPLOAD = 'UPLOAD';
    const TYPE_COUNT = 'COUNT';

    const TARGET_MEMBER = 'MEMBER';

    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SystemLog the static model class
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
        return '{{system_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 20),
            array('content', 'length', 'max' => 200),
            array('target, operate', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, content, target, operate, status, date', 'safe', 'on' => 'search'),
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
            'type' => 'Type',
            'content' => 'Content',
            'target' => 'Target',
            'operate' => 'Operate',
            'status' => 'Status',
            'date' => 'Date',
        );
    }

    /**
     * 根据要导入数据时间获取该数据相关log时间
     * @param $date
     * @return bool|string
     */
    public static function getLogDate($date)
    {
        return date('Y-m-d', strtotime('+1 day', strtotime($date)));
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('target', $this->target, true);
        $criteria->compare('operate', $this->operate, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('date', $this->date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 根据对象获取数据
     * @param SystemLog $obj
     * @return array
     */
    public function getByObj(SystemLog $obj)
    {
        $c = new CDbCriteria();
        $c->addCondition('type=:type');
        $c->addCondition('target=:target');
        $c->addCondition('status=:status');
        $c->addCondition('date LIKE :date');
        $c->params = array(
            'type' => $obj->type,
            'target' => $obj->target,
            'status' => $obj->status,
            'date' => $obj->date . '%',
        );
        return $this->findAll($c);
    }

    /**
     * 根据业务和日期，清理log
     * @param $type
     * @param $date
     */
    public function clear($type, $date)
    {
        $c = new CDbCriteria();
        $c->addCondition('type=:type');
        $c->addCondition('target=:target');
        $c->addCondition('status=:status');
        $c->addCondition('date LIKE :date');

        //删除记录的日期比实际日期多1天
        $date = DateUtil::getDate(strtotime('+1 day', strtotime($date)));

        $c->params = array(
            'type' => self::TYPE_UPLOAD,
            'target' => strtoupper($type),
            'status' => self::STATUS_TRUE,
            'date' => $date . '%',
        );

        /** @var $model SystemLog */
        $model = $this->find($c);
        if (!is_null($model)) {
            $model->status = self::STATUS_FALSE;
            $model->update();
        }
    }
}