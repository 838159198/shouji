<?php
class Invitationcode extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{invitationcode}}';
    }
    public function rules()
    {
        return array(
            array('id,uid,agent,mid,status', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            'member' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户',
            'mid' => '管理员',
            'agent' => '分组',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        /*$criteria->compare('title', $this->title, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('lasttime', $this->lasttime, true);*/
        //$criteria->compare('status', $this->status);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    /*
     * @param $uid  用户id
     */
    public static function getCode($uid){
        $invitationcode=Invitationcode::model()->find("uid=:uid and mid=:mid and status=1",array(":uid"=>$uid,":mid"=>0));
        if($invitationcode){
            return $invitationcode;
        }

    }

}