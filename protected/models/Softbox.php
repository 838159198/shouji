<?php
class Softbox extends CActiveRecord
{
    public $cat;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{rom_softbox}}';
    }
    public function rules()
    {
        return array(
            array('box_number,uid', 'required'),
            array('uid', 'authenticate'),
            array('box_number','unique'),
            array('box_number', 'length', 'max' => 6,'message'=>'长度最大为6位'),
            array('box_number', 'length', 'is' => 6,'message'=>'长度必须为6位'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,box_number,status,createtime,updatetime,mid,type,cat', 'safe', 'on' => 'search'),
        );
    }
    public function authenticate()
    {
        $uidList = Member::model()->findByPk($this->uid);
        if (!$uidList) {
            $this->addError('uid', '不存在该用户');
            return;
        }


    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Member', 'uid'),
            'm' => array(self::BELONGS_TO, 'Manage', 'mid'),
            'b'=>array(self::BELONGS_TO,'RomBoxPackage',array('box_number'=>'box_number'))
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户名',
            'createtime' => '创建时间',
            'updatetime' => '更新时间',
            'status' => '状态',
            "box_number"=>"设备码",
            "type"=>"接口型号",
            "mid"=>"操作者",
            "cat"=>"类型"
//            'pack_id'=>"套餐"

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

    public function uidsearch($uid)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $uid);
        $criteria->compare('box_number !',$no='MDAZRJ');
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
    public function uidNosearch($uid,$no)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $uid);
        $criteria->compare('box_number',$no);
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
            $data = "<font color=#006600><b>正常</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>禁用</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }

    public function getPackageNameById($id)
    {
            $sql = 'SELECT package_name FROM app_rom_package WHERE id = \'' . $id . '\' ';
            $name = Yii::app()->db->createCommand($sql)->queryAll();
            return $name[0]['package_name'];
    }

    //设备类型
    public static function getSoftType(){
        return array(
            'box'=>'装机盒子',
            'route'=>'路由器',
            'help'=>'装机助手'
        );
    }

}