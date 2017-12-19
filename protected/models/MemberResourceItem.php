<?php
class MemberResourceItem extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{member_resource_item}}';
    }
    public function rules()
    {
        return array(
            //array('name', 'required'),
            //array('mid,tel', 'numerical', 'integerOnly' => true),
            //array('name','length','max'=>10),
            //array('remarks','length','max'=>200),
            //array('tel','length','is'=>11),
            //array('tel,name','length','max'=>20),
            //array('address','length','min'=>10,'max'=>200),

        );
    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            //'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
            'xresource'=>array(self::BELONGS_TO,'Resource',array("type"=>"keyword")),
            'memberresource'=>array(self::BELONGS_TO,'MemberResource',""),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'memberId'=>'会员id',
            'name' => '分组名称',
            'remarks' => '备注',
            'create_datetime'=>'创建时间',

        );
    }



}