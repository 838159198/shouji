<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property string $title
 * @property integer $cid
 * @property string $tags
 * @property string $content
 * @property integer $createtime
 * @property integer $lasttime
 * @property integer $status
 * @property integer $uid
 * @property integer $hits
 * @property string $keywords
 * @property string $descriptions
 */
class Share extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{share_count}}';
	}
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'hits' => '点击次数',
            'ip' => 'IP',
            'date' => '分享时间',
            'uid'=>'用户名',
            'sid'=>'渠道名',
            'is_login'=>'登录状态',
        );
    }
    public function rules()
    {
        return array(
            array('id, hits, ip, date, uid, sid, is_login', 'safe', 'on' => 'search'),
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->with = array('m','s');
        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.hits',$this->hits);
        $criteria->compare('t.ip',$this->ip,true);
        $criteria->compare('t.date',$this->date,true);
        $criteria->compare('m.username',$this->uid,true);
        $criteria->compare('s.source_name',$this->sid,true);
        $criteria->compare('login.is_login',$this->is_login);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'm'=>array(self::BELONGS_TO,"Member","uid"),
            'login'=>array(self::BELONGS_TO,"Member","is_login"),
            's'=>array(self::BELONGS_TO,"SpreadSource","sid"),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * url
     * */
    public function getUrl()
    {
        return "/article/{$this->id}";
    }
    /*
    * 登录状态
    * */
    public function getXstatus()
    {
        $data = "";
        if($this->is_login==1){
            $data = "<font color=#006600><b>登录</b></font>";
        }elseif($this->is_login==0){
            $data = "<font color=#ff0000><b>未登录</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }

    public function getlistDataStatus()
    {
        $data = array(array("key"=>0,"value"=>"未登录"),array("key"=>1,"value"=>"登录"));
        return $data;
    }
}
