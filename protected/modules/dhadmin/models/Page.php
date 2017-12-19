<?php

/**
 * This is the model class for table "{{page}}".
 *
 * The followings are the available columns in table '{{page}}':
 * @property integer $id
 * @property string $title
 * @property integer $cid
 * @property string $content
 * @property integer $createtime
 * @property integer $lasttime
 * @property integer $status
 * @property integer $uid
 * @property integer $hits
 */
class Page extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,content,pathname, createtime, lasttime, uid', 'required'),
			array('createtime,uid', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>60),
            array('pathname', 'length', 'max'=>20),
            array('pathname','unique'),
            array('pathname','match','pattern'=>'/^[a-z]+$/','message'=>'必须英文'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title,pathname,  content, createtime, lasttime, status, uid, hits', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '标题',
			'content' => '内容',
			'createtime' => '创建时间',
			'lasttime' => '更新时间',
			'status' => '状态',
			'uid' => '发布者',
			'hits' => '热度',
            'pathname'=>'唯一标识',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		//$criteria->compare('createtime',">=".strtotime($this->createtime),true);
        //$criteria->compare('createtime',"<".strtotime("+1 day",$this->createtime),true);
		//$criteria->compare('lasttime',$this->lasttime);
		//$criteria->compare('status',$this->status);
		//$criteria->compare('uid',$this->uid);
		$criteria->compare('hits',$this->hits);
        /*if($this->createtime != ""){
            $createtime = strtotime($this->createtime);
            $createtime2 = $createtime+86400;
            $criteria->addCondition("createtime>{$createtime}");
            $criteria->addCondition("createtime<{$createtime2}");
        }*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>正常</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>隐藏</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
}
