<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/11
 * Time: 9:31
 */

class YearendDraw extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{yearend_draw}}';
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, "ArticleCategory", "cid"),
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}