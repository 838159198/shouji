<?php

class RowSoftdownloadRecord extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{row_softdownload_record}}';
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