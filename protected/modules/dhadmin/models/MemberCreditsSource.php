<?php
class MemberCreditsSource extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{member_credits_source}}';
    }


}