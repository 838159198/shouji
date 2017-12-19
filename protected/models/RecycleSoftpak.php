<?php

class RecycleSoftpak extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RomSoftpak the static model class
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
        return '{{recycle_softpak}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('imeicode,tjcode', 'required'),
//            array('uid,type', 'numerical', 'integerOnly' => true),
//            array('sys,models', 'length', 'max' => 20),
//            array('simcode,brand', 'length', 'max' => 30),
//            array('imeicode,ip', 'length', 'max' => 15),
//            array('mac', 'length', 'max' => 17),
//            array('com', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,createtime, install_day, logout_day,income_day,status,username,serial_number', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => '用户名',
            'createtime' => '创建时间',
            'serial_number' => '统计ID',

            'logout_day' => '未登录(天)',
            'income_day' => '零收益(天)',
            'install_day' => '零安装(天)',
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
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.username', $this->username,true);
        $criteria->compare('t.serial_number', $this->serial_number, true);
        $criteria->compare('t.createtime', $this->createtime, true);
       /* $criteria->compare('t.logout_day', $this->logout_day, true);
        $criteria->compare('t.income_day', $this->income_day, true);
        $criteria->compare('t.install_day', $this->install_day, true);*/

        $criteria->order = 't.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>已恢复</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>恢复</b></font>";
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
        $data = array(array("key"=>1,"value"=>"已恢复"),array("key"=>0,"value"=>"恢复"));
        return $data;
    }
    public function getXtype()
    {
        $data = "";
        if($this->type==1){
            $data = "<font color=#006600><b>门店</b></font>";
        }elseif($this->type==0){
            $data = "<font color=#ff0000><b>ROM</b></font>";
        }elseif($this->type==3){
            $data = "<font color=#ff0000><b>门店桌面</b></font>";
        }else{
            $data = "<font color=#000000><b>其他</b></font>";
        }
        return $data;
    }

    /*
* 状态列表下拉
* */
    public function getlistDataType()
    {
        $data = array(array("key"=>1,"value"=>"门店"),array("key"=>0,"value"=>"ROM"),array("key"=>3,"value"=>"门店桌面"));
        return $data;
    }

    public static function button($tid,$username){
        return '<a title="回收" style="cursor:hand" onclick="view('.$tid.','.$username.')">回收</a>';
    }
    /*
     * 恢复统计后 添加该统计的数据
     */
    public static function addReplyData($uid,$serial_number,$username,$agent){
        $member=Member::model()->findByPk($uid);
        $overtime=$member->overtime;
        $lougt_day = ceil((time() -$overtime)/86400);//未登录天数

        $sql = "SELECT MAX(installtime)installtime FROM app_rom_appresource WHERE uid={$uid}";
        $model_install = Yii::app()->db->createCommand($sql)->queryRow();

        $installtime=strtotime($model_install['installtime']);
        if(is_null($model_install['installtime'])){
            $installtime=$member->jointime;
        }
        $install_day = ceil((time() -$installtime)/86400);//零安装天数

        //零收益天数
        if ($member->agent== 707) {
            $sql = "SELECT MAX(datetimes) datetimes FROM app_rom_subagentdata WHERE uid={$uid}";
            $model = Yii::app()->db->createCommand($sql)->queryRow();
            if (is_null($model['datetimes'])) {
                $income_day = $member->jointime;
            } else {
                $income_day =$model['datetimes'];
            }
        }else {
            $data_income = MemberIncome::getUserLastDate($uid);
            $pos = array_search(max($data_income), $data_income);
            if (is_null($data_income[$pos])) {
                $income_day =$member->jointime;
            }else{
                $income_day = strtotime($data_income[$pos]);
            }
        }
        $income_day=ceil((time() -$income_day)/86400);//零收益天数


        $model=new RecycleSoftpak();
        $model->uid=$uid;
        $model->username=$username;
        $model->createtime=date("Y-m-d H:i:s");
        $model->agent=$agent;
        $model->logout_day=$lougt_day;
        $model->install_day=$install_day;
        $model->income_day=$income_day;
        $model->serial_number=$serial_number;
        $model->insert();
    }


  /*
   * 获取回收的统计的数据
   */
    public static function getRecycleData(){
        //先清空回收表
        $sql_del="delete from app_recycle_softpak";
        Yii::app()->db->createCommand($sql_del)->execute();

        //未登录天数计算
        $sql = "SELECT id,username,overtime,agent,jointime FROM app_member";
        $members = Yii::app()->db->createCommand($sql)->queryAll();
        $data_member = array();
        foreach ($members as $member) {
            $data_member[$member['id']]=array("jointime"=>$member['jointime'],"overtime"=>$member['overtime'],"username"=>$member['username'],"agent"=>$member['agent']);
        }//var_dump($data_member);exit;

        //零安装
        $sql = "SELECT uid,MAX(installtime)installtime FROM app_rom_appresource GROUP BY uid";
        $model_install = Yii::app()->db->createCommand($sql)->queryAll();
        $data_install = array();
        foreach ($model_install as $v) {
            $uid = $v['uid'];
            $data_install[$uid] = strtotime($v['installtime']);
        }//var_dump($model_install);exit;

        //已分配统计的
        $softPaks=RomSoftpak::model()->findAll("status=0 and closed=0 and uid !=0");
        $sqll = "INSERT INTO `app_recycle_softpak` (`id`,`uid`,`serial_number`,`createtime`,`install_day`, `logout_day`,`income_day`,`username`,`agent`)VALUES";
        foreach($softPaks as $softPak){
            $uid = $softPak['uid'];
            $day = time() - $data_member[$uid]['overtime'];
            $logout_day = ceil($day /86400-1);//向上取整

            //收益天数计算-----707收益不进income表
            if ($data_member[$uid]['agent'] == 707){
                $sql = "SELECT MAX(datetimes) datetimes FROM app_rom_subagentdata WHERE uid={$uid}";
                $model = Yii::app()->db->createCommand($sql)->queryRow();
                if (is_null($model['datetimes'])) {
                    //不存在收益数据  当前时间-注册时间
                    $income_day = $data_member[$uid]['jointime'];
                } else {
                    $income_day =$model['datetimes'];
                }
            }else{
                //获取最大收益时间
                $data_income = MemberIncome::getUserLastDate($uid);
                $pos = array_search(max($data_income), $data_income);
                if (is_null($data_income[$pos])){
                    //不存在收益数据  当前时间-注册时间
                    $income_day =$data_member[$uid]['jointime'];
                }else{
                    $income_day = strtotime($data_income[$pos]);
                }
            }
            $income_day=ceil((time() -$income_day)/86400-1);//零收益天数

            $install = isset($data_install[$uid]) ? $data_install[$uid] : $data_member[$uid]['jointime'];
            $install_day=ceil((time() -$install)/86400-1);//零安装天数
            $sqll .= "('','" . $uid . "','" . $softPak->serial_number . "','" . date('Y-m-d H:i:s') . "','" . $install_day . "','" . $logout_day . "','" . $income_day . "','" . $data_member[$uid]['username'] . "','" . $softPak->type . "'),";
        }
        $sqll = substr($sqll, 0, -1) . ';';
        $aaa=Yii::app()->db->createCommand($sqll)->execute();
        return $aaa;
    }
}