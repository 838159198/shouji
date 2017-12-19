<?php

/**
 * This is the model class for table "{{rom_appupdata}}".
 *
 * The followings are the available columns in table '{{rom_appupdata}}':
 * @property string $id
 * @property string $uid
 * @property string $simcode
 * @property string $sys
 * @property string $mac
 * @property string $imeicode
 * @property string $models
 * @property string $type
 * @property string $tjcode
 * @property string $createtime
 * @property string $reportime
 * @property string $reportimestamp
 * @property string $brand
 * @property string $ip
 * @property string $com
 * @property string $count
 */
class RomSubagentdata extends CActiveRecord
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
        return '{{rom_subagentdata}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid,imeicode, install, uninstall, activation, status,createtime,price,datetimes,real_price', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'member' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'activation' => '激活数量',
            'install' => '安装数量',
            'uninstall' => '卸载数量',
            'imeicode' => 'imeicode',
            'createtime' => '创建时间',
            'status' => '状态',
            'price' => '价格',
            'real_price' => '实际价格',
            'datetimes' => '判定日期',
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
        $criteria->compare('id', $this->id, true);
        $criteria->order = 'id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    //导入代理商子用户数据
    public static function insertData($imeicode,$uid,$install,$uninstall,$activation,$date,$status){
        $model=RomSubagentdata::model()->find("imeicode=:imeicode and uid=:uid",array(":imeicode"=>$imeicode,":uid"=>$uid));
        $price=SubagentPrice::findPrice($uid);
        $member=Member::model()->findByPk($uid);
        $real_price=SubagentPrice::findRealPrice($member->agent);
        if($model){
//            $model->install=$install;
//            $model->uninstall=$uninstall;
//            $model->activation=$activation;
//            $model->price=$price;
//            $model->createtime=date('Y-m-d H:i:s');
//            $model->update();
        }else{
            $smodel=new RomSubagentdata();
            $smodel->imeicode=$imeicode;
            $smodel->uid=$uid;
            $smodel->install=$install;
            $smodel->uninstall=$uninstall;
            $smodel->activation=$activation;
            $smodel->status=$status;
            $smodel->price=$price;
            $smodel->real_price=$real_price;
            $smodel->datetimes=$date;
            $smodel->createtime=date('Y-m-d H:i:s');
            $smodel->insert();
        }
    }
    //子用户所有效安装量
    public static function getActivationNum($uid,$startdate,$enddate,$sign=''){
        $member=Member::model()->findByPk($uid);
        if(empty($enddate)){
            $sql="SELECT count(*)activation,SUM(price) income FROM app_rom_subagentdata WHERE uid={$uid} AND `status`=1 AND datetimes >={$startdate}";
        }else{
            $sql="SELECT count(*)activation,SUM(price) income FROM app_rom_subagentdata WHERE uid={$uid} AND `status`=1 AND datetimes BETWEEN {$startdate} AND {$enddate} ";
        }
        if($sign==2){
            //2级代理
            $members = Member::model()->findAll(array(
                'select'=>array('id'),
                'condition' => 'status=:status AND agent=:agent AND subagent=:subagent',
                'params' => array(':status'=>1,':agent' => $member->agent,':subagent'=>$uid)
            ));
            $uids='';
            if($members){
                foreach($members as $v){
                    $uids.=$v->id.',';
                }
                $uids=substr($uids,0,-1);
            }
            $uids=empty($uids)?"('')":'('.$uids.')';
            if(empty($enddate)){
                $sql="SELECT count(*)activation,SUM(price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes >={$startdate}";
            }else{
                $sql="SELECT count(*)activation,SUM(price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes BETWEEN {$startdate} AND {$enddate} ";
            }
        }
        $activationNum = Yii::app()->db->createCommand($sql)->queryAll();
        if($activationNum){
            return $activationNum;
        }else{
            return '--';
        }
    }

    //子用户某天效安装量
    public static function getDayActivationNum($uid,$startdate){
        $sql="SELECT count(*)activation,SUM(price) income FROM app_rom_subagentdata WHERE uid={$uid} AND `status`=1 AND datetimes ={$startdate}";

        $activationNum = Yii::app()->db->createCommand($sql)->queryAll();
        if($activationNum[0]['activation']==null){
            return $activationNum;
        }else{
            return $activationNum;
        }
    }
    //按实际价格代理商子用户所有收益
   public static function getRealData($uid,$startdate,$enddate){
        $members = Member::model()->findAll(array(
           'select'=>array('id'),
           'condition' => 'status=:status AND agent=:agent AND subagent=:subagent',
           'params' => array(':status'=>1,':agent' => $uid,':subagent'=>$uid)
       ));
       $uids='';
       if($members){
           foreach($members as $v){
               $uids.=$v->id.',';
           }
           $uids=substr($uids,0,-1);
       }
       $uids=empty($uids)?"('')":'('.$uids.')';
       if(empty($enddate)){
           $sql="SELECT SUM(real_price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes >={$startdate}";
       }else{
           $sql="SELECT SUM(real_price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes BETWEEN {$startdate} AND {$enddate} ";
       }
       $activationNum = Yii::app()->db->createCommand($sql)->queryAll();

       return $activationNum[0]['income'];

   }
    //按实际价格2级代理商子用户所有收益
    public static function getRealDataTwo($uid,$startdate,$enddate){
        $members = Member::model()->findAll(array(
            'select'=>array('id'),
            'condition' => 'status=:status AND agent=:agent AND sign=:sign',
            'params' => array(':status'=>1,':agent' => $uid,':sign'=>2)
        ));
        $uids='';
        if($members){
            foreach($members as $v){
                $uids.=$v->id.',';
            }
            $uids=substr($uids,0,-1);
        }
         $uids=empty($uids)?"('')":'('.$uids.')';
        $members = Member::model()->findAll(array(
            'select'=>array('id'),
            'condition' => 'status=:status AND agent=:agent AND subagent in'.$uids,
            'params' => array(':status'=>1,':agent' => $uid)
        ));
        $uids='';
        if($members){
            foreach($members as $v){
                $uids.=$v->id.',';
            }
            $uids=substr($uids,0,-1);
        }
        $uids=empty($uids)?"('')":'('.$uids.')';
        if(empty($enddate)){
            $sql="SELECT SUM(real_price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes >={$startdate}";
        }else{
            $sql="SELECT SUM(real_price) income FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes BETWEEN {$startdate} AND {$enddate} ";
        }
        $activationNum = Yii::app()->db->createCommand($sql)->queryAll();
        return $activationNum[0]['income'];
    }
    //代理子用户收益
    public static function getSubIncome($agent=''){
        $members=Member::model()->findAll("status=1 and agent=:agent and sign=1",array(":agent"=>$agent));
        $uids='';
        if($members){
            foreach($members as $v){
                $uids.=$v->id.',';
            }
            $uids=substr($uids,0,-1);
        }
        $uids=empty($uids)?"('')":'('.$uids.')';

        $startdate= strtotime(date('Y-m-01', strtotime('-1 month')));
        $enddate= strtotime(date('Y-m-t', strtotime('-1 month')));
        $sql="SELECT sum(price)income,uid,sum(real_price-price)cha FROM app_rom_subagentdata WHERE uid IN {$uids} AND `status`=1 AND datetimes BETWEEN {$startdate} AND {$enddate} GROUP BY uid";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }

}