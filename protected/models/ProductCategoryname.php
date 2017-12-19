<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2016/12/19
 * Time: 11:16
 */
class ProductCategoryname extends CActiveRecord
{
    public $categroy_name;
    public function tableName()
    {
        return '{{product_categoryname}}';
    }
    
    public function rules()
    {
        return array(
        array('category_name','required'),
            );
    }

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_name' => '类别名称',
        );
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}