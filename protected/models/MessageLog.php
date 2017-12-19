<?php
class MessageLog extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{message_log}}';
    }
    public function rules()
    {
        return array(
            array('content,theme', 'required'),
//            array('mid,tel', 'numerical', 'integerOnly' => true),
//            array('mid','length','max'=>7),
//            array('tel','length','is'=>11),
//            array('tel,name','length','max'=>20),
//            array('address','length','min'=>10,'max'=>200),

        );
    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
        );
    }
    public function attributeLabels()
    {
        return array(

            'theme' => '主题',
            'content' => '短信内容',

        );
    }

    public static function noteMessageLog($theme,$content,$resource){
        $message = new MessageLog();
        $message->theme =$theme;
        $message->content = $content;
        $message->category = 2;
        $message->resource = $resource;
        $message->createtime = time();
        $message->send_type = 0;
        if($message->save()){
            return true;
        }else{
            return false;
        }
    }

}