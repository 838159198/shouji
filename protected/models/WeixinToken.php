<?php
class WeixinToken extends CActiveRecord
{
    public function tableName()
    {
        return '{{weixin_token}}';
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(

        );
    }


}