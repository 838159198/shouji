<?php
class MemberLocation extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{member_location}}';
    }
    public function rules()
    {
        return array(
            array('mid,address,tel,name', 'required'),
            array('mid,tel', 'numerical', 'integerOnly' => true),
            array('mid','length','max'=>7),
            array('tel','length','is'=>11),
            array('tel,name','length','max'=>20),
            array('address','length','min'=>10,'max'=>200),

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
            'id' => 'ID',
            'mid'=>'会员id',
            'name' => '姓名',
            'tel' => '手机号码',
            'address'=>'收货地址',

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
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>已发货</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>待发货</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    public function getXuser()
    {
        $data = Manage::model()->findByPk($this->opid);
        return $data;
    }
}