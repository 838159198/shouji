<?php
class Softroute extends CActiveRecord
{

    private $_username = null;
    public function getUsername()
    {
        if ($this->_username === null && $this->user !== null)
        {
            $this->_username = $this->user->username;
        }
        return $this->_username;
    }
    public function setUsername($value)
    {
        $this->_username = $value;
    }
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{rom_softroute}}';
    }
    public function rules()
    {
        return array(
            array('route_number,uid', 'required'),
            array('uid', 'authenticate'),
            array('route_number','unique'),
            array('route_number', 'length', 'max' => 12,'message'=>'长度最大为12位'),
            array('route_number', 'length', 'is' => 12,'message'=>'长度必须为12位'),
            array('route_number','match','pattern'=>'/^[a-z0-9\-_]+$/','message'=>'必须为字母或数字'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,route_number,status,createtime,updatetime,mid,type', 'safe', 'on' => 'search'),
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
            'b'=>array(self::BELONGS_TO,'RomBoxPackage',array('route_number'=>'box_number'))
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户名',
            'createtime' => '添加时间',
            'updatetime' => '更新时间',
            'status' => '状态',
            "route_number"=>"设备码",
            "type"=>"类型",
            "mid"=>"操作者",
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = 'user';
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.route_number', $this->route_number, true);
        $criteria->compare('t.createtime', $this->createtime, true);
        $criteria->compare('t.updatetime', $this->updatetime, true);
        $criteria->compare('user.username', $this->uid,true);
        $criteria->with = 'm';
        $criteria->compare('m.name', $this->mid,true);
        $criteria->compare('t.status', $this->status);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    //根据用户名查找所属路由器
    public function uidsearch($uid)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $uid);
        $criteria->compare('status',1);
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

    /*
* 状态列表下拉
* */
    public function getlistDataStatus()
    {
        $data = array(array("key"=>1,"value"=>"正常"),array("key"=>0,"value"=>"禁用"));
        return $data;
    }

    //获取路由器套餐
    public function getPackageNameById($id)
    {
        $sql = 'SELECT package_name FROM app_rom_package WHERE  id = \'' . $id . '\' ';
        $name = Yii::app()->db->createCommand($sql)->queryAll();
        return $name[0]['package_name'];
    }


}