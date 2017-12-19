<?php

/**
 * 清理Income表数据前，保留旧数据
 * This is the model class for table "{{income_clear_log}}".
 *
 * The followings are the available columns in table '{{income_clear_log}}':
 * @property string $uid
 * @property string $mrid
 * @property string $data
 * @property string $createtime
 * @property integer $status
 * @property string $type
 * @property string $cleartime
 */
class IncomeClearLog extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{income_clear_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, mrid, createtime, type, cleartime', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid, mrid', 'length', 'max' => 11),
            array('data', 'length', 'max' => 7),
            array('type', 'length', 'max' => 5),
            // The following rule is used by search().
            array('uid, mrid, data, createtime, status, type, cleartime', 'safe', 'on' => 'search'),
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
            'uid' => '用户ID',
            'mrid' => '资源关系ID',
            'data' => '收益值',
            'createtime' => '创建时间',
            'status' => '1可用，0已封号',
            'type' => '业务类型',
            'cleartime' => '清除时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('mrid', $this->mrid, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('cleartime', $this->cleartime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return IncomeClearLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
