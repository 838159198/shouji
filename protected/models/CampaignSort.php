<?php

/**

 */
class CampaignSort extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{campaign_sort}}';
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
            array('id,periods, username,type, num,sort,createtime', 'safe', 'on'=>'search'),
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

        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'periods' => '活动期数',
            'username' => '用户名',
            'type' => '业务类型',
            'num' => '激活量',
            'sort' => '排序',
            'del' => '状态',
            'createtime' => '创建时间',

        );
    }

    public function search($periods)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->addCondition("del=1");
        $criteria->compare('periods',$periods);
        $criteria->compare('username',$this->username);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('num',$this->num,true);
        $criteria->compare('sort',$this->sort,true);
        $criteria->compare('createtime',$this->createtime);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder' => 'sort DESC,createtime DESC'
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

    //获取主页面的前三名
    public static  function findByPeriods($periods){
        $criteria = new CDbCriteria() ;
        $criteria -> select = array('id','username');
        $criteria -> condition = 'del = 1 and periods=:periods';
        $criteria -> order = 'sort desc';
        $criteria -> limit = 3;
        $criteria ->params = array (':periods' => $periods) ;
        $result = CampaignSort::model()->findAll($criteria);
        $sortArr=array();
        if($result){
            foreach($result as $k=> $v){
                $sortArr[]="第".($k+1)."名 ".substr($v->username,0,2)."******";
            }
        }

        return $sortArr;
    }

}
