<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/4
 * Time: 14:20
 * 安装量分析
 * This is the model class for table "{{rom_instalcheck}}".
 *
 * The followings are the available columns in table '{{rom_instalcheck}}':
 * @property string $id
 * @property string $uid
 * @property string $model
 * @property string $counts
 * @property string $createstamp
 * @property string $type
 */
class RomInstalcheck extends CActiveRecord
{

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
        return '{{rom_instalcheck}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, type, counts ,createstamp', 'required'),
            array('uid', 'length', 'max' => 6),
            array('type', 'length', 'max' => 1),
            array('model', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, model, counts ,createstamp', 'safe', 'on' => 'search'),
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
            'type' => '数据类型',
            'model' => '手机型号',
            'createstamp' => '创建时间戳',
            'counts' => '计数个数'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    


}
