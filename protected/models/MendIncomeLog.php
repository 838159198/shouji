<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 14:49
 * 用户收益补入记录表
 */

class MendIncomeLog extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{mend_income_log}}';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'u' => array(self::BELONGS_TO, "Member", "uid"),
            'm' => array(self::BELONGS_TO, 'Manege', 'mid'),
        );
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户',
            'mid' => '操作人',
            'channel' => '渠道',
            'type' => '业务名称',
            'operatime' => '操作时间',
            'stamptime' =>'具体时间',
            'pre_data' => '修改前收益',
            'after_data' => '修改后收益',
            'mend_data' => '补入金额',
            'mend_num' => '修改个数',
            'note' => '备注',
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
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}


