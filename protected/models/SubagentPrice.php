<?php
class SubagentPrice extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{subagent_price}}';
    }
    public function rules()
    {
        return array(
            array('price', 'required'),
            array('price', 'numerical'),
            //array(‘Price’,’numerical’, ‘integerOnly’=>true),
            array('uid','authenticate'),
            array('id,uid,agent,price,updatetime,sign', 'safe', 'on' => 'search'),
        );
    }
    public function authenticate()
    {
        $uidList = SubagentPrice::model()->find("uid=:uid",array(":uid"=>$this->uid));
        if ($uidList) {
            $this->addError('uid', '该用户已添加');
            return;
        }


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
            'uid' => '子用户',
            'price' => '单价',
            'agent' => '代理商',
            'updatetime' => '更新日期',
            'sign' => '代理分级标志',
        );
    }

    public function search($agent='')
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('member');
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.price', $this->price, true);
        $criteria->compare('t.agent', $this->agent, true);
        $criteria->compare('t.updatetime', $this->updatetime, true);
        if(!empty($agent)){
            $criteria->addCondition("t.agent= :agent");
            $criteria->params[':agent']=$agent;
        }

        $criteria->compare('member.username',$this->uid,true);

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
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    //导入价格查询
    public static   function findPrice($uid){

        // 我们要查询id在1、2、3的范围内
        $member=Member::model()->findByPk($uid);
        $uids='( 0,'.$uid.')';
        $subprice = SubagentPrice::model()->findAll(array(
            'select'=>array('price,uid'),
            'condition' => 'sign=0 and agent=:agent and uid in '.$uids,
            'params' => array(':agent' => $member->agent),
        ));
        if($subprice){
            $prices=array();
            foreach($subprice as $v){
                $prices[$v->uid]=$v->price;
            }
            return count($prices)>1? $prices[$uid]: $prices[0];
        }else{
            return $prices=0;
        }

    }
    //平台实际价格
    public static function findRealPrice($agent){
        $subprice = SubagentPrice::model()->find(array(
            'select'=>array('price,uid'),
            'condition' => 'sign=1 and agent=:agent and uid=:uid ',
            'params' => array(':agent' => $agent,':uid'=>$agent),
            'limit'=>1
        ));
        $real_price=10;
        if($subprice){
            $real_price=$subprice->price;
        }
        return $real_price;
    }

}