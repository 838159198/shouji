<?php

/**

 */
class CampaignIncome extends CActiveRecord
{
    public $username;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{campaign_income}}';
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
            // @todo Please remove those attributes that should not be searched.
            array('cid, uid,username, type, olddata, campaigndata, status, createtime', 'safe', 'on'=>'search'),
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
            'm'=>array(self::BELONGS_TO,'Member','uid'),
        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'cid' => '活动期数',
            'uid' => '	用户名',
            'username' => '用户名',
            'type' => '业务类型',
            'olddata' => '原单价收益',
            'campaigndata' => '活动赠送',
            'status' => '状态',
            'createtime' => '数据时间',
        );
    }

    public function search($periods)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        //var_dump($uidArr);exit;
        $criteria=new CDbCriteria;
        $criteria->with = array('m');
        $criteria->select ='t.cid,t.uid,t.type,t.status,max(t.createtime) as createtime,sum(t.olddata) as olddata,sum(t.campaigndata) as campaigndata';
        $camlog_model = CampaignLog::model()->findAll("cid={$periods} and status=1");
        if($camlog_model){//
            foreach($camlog_model as  $v){
                $uidArr[]= $v->uid;
            }
            if(count($uidArr)>=1){
                $criteria->addInCondition('uid', $uidArr);//与上面正好相法，是NOT IN
            }
        }

        $criteria->compare('t.cid',$periods);
        $criteria->compare('t.uid',$this->uid);
        $criteria->compare('t.type',$this->type);
        $criteria->compare('t.status',$this->status);
        $criteria->compare('olddata',$this->olddata);
        $criteria->compare('t.campaigndata',$this->campaigndata);
        $criteria->compare('t.createtime',$this->createtime);
        $criteria->compare('m.username', $this->username,true);

        $criteria->group =  't.uid';
        //var_dump($criteria);exit;
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder' => 'olddata DESC'
            ),
            'pagination' => array(
                'pageSize' => 30,
            ),

        ));
    }



    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

}
