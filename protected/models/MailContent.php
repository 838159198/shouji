<?php

/**
 * This is the model class for table "{{mail_content}}".
 *
 * The followings are the available columns in table '{{mail_content}}':
 * @property integer $id
 * @property string $title
 * @property string $content
 *
 * The followings are the available model relations:
 * @property Mail[] $mails
 */
class MailContent extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MailContent the static model class
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
        return '{{mail_content}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, content', 'required', 'on' => 'insert'),
            array('title', 'length', 'max' => 255),
            array('content', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, content', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Mail' => array(self::HAS_MANY, 'Mail', 'content'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 站内信分页使用
     * ZLB 2017-09-08
     */
    public function research(){
        $criteria = new CDbCriteria;
        $criteria->select='t.id,title,t.content,send';
        $criteria->join=' left JOIN `app_mail` ON `app_mail`.content=t.id';
        $criteria->compare('t.id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('t.content', $this->content, true);
        $criteria->group='t.id';
        $criteria->order='t.id desc';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }
    /**
     * 发件人真是姓名
     * 
     */
    public static function manage($id){
        $sql="SELECT name FROM `app_mail_content` as mc LEFT JOIN `app_mail` as m ON mc.id=m.content LEFT JOIN  `app_manage` as mg ON m.send=mg.id  WHERE mc.id=".$id." limit 1";
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['name'];
        }else{
            return '';
        }
    }
    /**
     * 收件人姓名
     * 
     */
    public static function member($id){
        $sql="select username from `app_member` where id=".$id;
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['username'];
        }else{
            return '';
        }
    }
    
    /**
     * 发件总人数
     * 
     */
    public static function total($id){
        $sql="SELECT count(recipient) as total from `app_mail_content` as mc left join `app_mail` as m on mc.id=m.content where mc.id='{$id}' group by mc.id";
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['total'];
        }else{
            return '';
        }
    }
    /**
     * 发送时间
     * 
     */
    public static function sendtime($id){
        $sql="SELECT jointime  from `app_mail_content` as mc left join `app_mail` as m on mc.id=m.content where mc.id='{$id}' limit 1";
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['jointime'];
        }else{
            return '';
        }
    }
    /**
     * 用户状态
     * 
     * @return [type] [description]
     */
    public static function status($status){
        if(isset($status)){
            switch ($status) {
                case '0':
                    return '<font color=#66CD00>未读</font>';
                    break;
                case '1':
                    return '<font color=#C4C4C4>已读</font>';
                    break;
                case '2':
                    return '<font color=#8B0A50>客户删除</font>';
                    break;
                case '3':
                    return '<font color=#EE0000>管理员撤回</font>';
                    break;
                default:
                    return '';
                    break;
            }
        }else{
            return ' ';
        }
    }
    /**
     * 分页中的复选框
     * 
     */
    public static function input($id,$num){
        if(isset($id) && !empty($id)){
            return '<input type="checkbox" key='.$id.' num='.$num.' name="checkbox" >';
        }else{
            return '';
        }
    }
    /**
     * 撤回或已全部撤回
     * 
     */
    public static function chehui($id,$type){
        if(isset($type) && $type==1){
            $sql="select status from `app_mail` as m left join `app_mail_content` as mc on m.content=mc.id where mc.id=".$id;
            $arr=array();
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                foreach ($result as $key => $value) {
                    $arr[]=$value['status'];
                }
                if(in_array(0,$arr) || in_array(1,$arr) || in_array(2,$arr)){
                    return '<a  class="label label-primary"  href=\'/dhadmin/mail/del?id='.$id.'\'>撤回</a>';
                }else{
                    return '<a class="label label-primary" style=\'background-color:#C4C4C4\'  href=\'#\'>已全部撤回</a>';

                }
            }else{
                return '<a class=\'delete\'  href=\'/dhadmin/mail/del?id='.$id.'\'>撤回</a>';
            }
        }
        else if(isset($type) && $type==2){
            $sql="select status from `app_mail` where id=".$id;
            $arr=array();
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                foreach ($result as $key => $value) {
                    $arr[]=$value['status'];
                }
                if(in_array(0,$arr) || in_array(1,$arr) || in_array(2,$arr)){
                    return '<a class="label label-primary"  href=\'/dhadmin/mail/updel?id='.$id.'\'>撤回</a>';
                }else{
                    return '<a class="label label-primary" style=\'background-color:#C4C4C4\'  href=\'#\'>已撤回</a>';

                }
            }else{
                return '<a class="label label-primary"  href=\'/dhadmin/mail/updel?id='.$id.'\'>撤回</a>';
            }
        }
    }
}