<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/21
 * Time: 13:27
 */
class LinkCategory extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{link_category}}';
    }

}