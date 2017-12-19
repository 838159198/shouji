<?php

/**
 * This is the model class for table "{{member_category}}".
 *
 * The followings are the available columns in table '{{member_category}}':
 * @property string $id
 * @property string $name
 * @property integer $status
 */
class MemberCategory extends CActiveRecord
{
    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{member_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50),
            // The following rule is used by search().
            array('id, name, status', 'safe', 'on' => 'search'),
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
            'name' => '类型',
            'status' => '是否可用',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('status', self::STATUS_TRUE);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MemberCategory the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取数组形式列表
     * @return array
     */
    public function getListToArray()
    {
        $c = new CDbCriteria();
        $c->compare('status', self::STATUS_TRUE);
        /* @var $list MemberCategory[] */
        $list = $this->findAll($c);

        $data = array();
        $data[0] = '';
        foreach ($list as $item) {
            $data[$item->id] = $item->name;
        }
        return $data;
    }
    public function getByIdName($id)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('id', $id);
        $list = $this->find($criteria);

        return $list["name"];
    }

}
