<?php

/**
 * This is the model class for table "{{serach_info}}".
 *
 * The followings are the available columns in table '{{serach_info}}':
 * @property string $id
 * @property string $name
 * @property string $tel
 * @property string $mail
 * @property string $qq
 * @property integer $reg_status
 * @property integer $status
 * @property string $content
 * @property string $com
 * @property string $area
 * @property string $source
 * @property string $search_id
 * @property string $manage_id
 * @property string $createtime
 * @property string $motifytime
 * @property string $tixingtime
 * @property string $zixuntime
 * @property string $type
 * @property string $userarea
 * @property string $username
 */
class SerachInfo extends CActiveRecord
{
	public $pro_name;
	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return '{{serach_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reg_status, status,type', 'numerical', 'integerOnly'=>true),
			array('name, area', 'length', 'max'=>20),
			array('qq', 'length', 'max'=>30),
			array('com,tel', 'length', 'max'=>50),
			array('tel,qq,id', 'numerical', 'integerOnly'=>true),
			array('mail', 'email'),

            array('tel', 'numerical', 'integerOnly'=>true),
            array('tel','length','is'=>11),

			array('tel', 'checkTel'),
			array('qq', 'checkQq'),
			array('mail', 'checkMail','on'=>'create,update'),

			array('mail, source,userarea,username', 'length', 'max'=>30),
			array('content', 'length', 'max'=>200),
			array('search_id', 'length', 'max'=>11),
			array('manage_id', 'length', 'max'=>11),
			array('createtime', 'safe'),
			array('motifytime', 'safe'),
			array('tixingtime', 'safe'),
			array('zixuntime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, tel, mail, qq, reg_status, status, content, com, area, source, search_id, manage_id, createtime,tixingtime, motifytime, zixuntime,userarea,type,username,pro_name', 'safe', 'on'=>'search'),
		);
	}
	public function checkTel($attribute,$params){
		if($this->tel==""){}
		else if(($this->tel!="")&&(ctype_digit($this->tel)))
		{
			if(($this->id!=0) && ($this->id!="") && !is_null($this->id))
			{
				$oldtel = Member::model()->findByAttributes(array('tel'=>$this->tel));
				$old_tel = SerachInfo::model()->findAllBySql('select * from app_serach_info where tel='.$this->tel.' and id!='.$this->id);
				if(!empty($oldtel) || !empty($old_tel)){
					$this->addError($attribute, '该电话已经存在!');
				}
			}
			else
			{
				$oldtel = Member::model()->findByAttributes(array('tel'=>$this->tel));
				$old_tel = SerachInfo::model()->findByAttributes(array('tel'=>$this->tel));
				if(!empty($oldtel) || !empty($old_tel)){
					$this->addError($attribute, '该电话已经存在!');
				}
			}

		}
	}
	public function checkQq($attribute,$params){
		if($this->qq==""){}
		else if(($this->qq!="")&&(ctype_digit($this->qq)))
		{
			if(($this->id!=0) && ($this->id!="") && !is_null($this->id))
			{
				$oldqq = Member::model()->findByAttributes(array('qq'=>$this->qq));
				$old_qq = SerachInfo::model()->findAllBySql('select * from app_serach_info where qq='.$this->qq.' and id!='.$this->id);
				if(!empty($oldqq) || !empty($old_qq)){
					$this->addError($attribute, '该QQ已经存在!');
				}
			}
			else
			{
				$oldqq = Member::model()->findByAttributes(array('qq'=>$this->qq));
				$old_qq = SerachInfo::model()->findByAttributes(array('qq'=>$this->qq));
				if(!empty($oldqq) || !empty($old_qq)){
					$this->addError($attribute, '该QQ已经存在!');
				}
			}

		}
	}
	public function checkMail($attribute,$params){
		if($this->mail==""){}
		else
		{
			if(($this->id!=0) && ($this->id!="") && !is_null($this->id))
			{
				$oldmail = Member::model()->findByAttributes(array('mail' => $this->mail));
				$old_mail = SerachInfo::model()->findAllBySql("select * from app_serach_info where id!=".$this->id." and mail=:mail",array(':mail'=>$this->mail));
				if (!empty($oldmail) || !empty($old_mail)) {
					$this->addError($attribute, '该邮箱已经存在!');
				}
			}
			else
			{
				$oldmail = Member::model()->findByAttributes(array('mail' => $this->mail));
				$old_mail = SerachInfo::model()->findByAttributes(array('mail' => $this->mail));
				if (!empty($oldmail) || !empty($old_mail)) {
					$this->addError($attribute, '该邮箱已经存在!');
				}
			}

		}
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pro' => array(self::BELONGS_TO, 'Manage', 'manage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '姓名',
			'tel' => '电话',
			'mail' => '邮箱',
			'qq' => 'QQ',
			'reg_status' => '注册状态',//:0=>未注册1=>已注册
			'status' => '有效状态',//:0=>未审核1=>有效2=>无效
			'content' => '备注',
			'com' => '公司名称',
			'area' => '公司地址',
			'source' => '来源',
			'search_id' => '录入ID',
			'pro_name' => '客服',
			'createtime' => '录入时间',
			'motifytime' => '更新时间',
			'tixingtime' => '提醒时间',
			'zixuntime' => '咨询时间',
            'userarea' => '所属地区',
            'username' => '用户名',
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
	public function search($type,$search_id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

        if(empty($type)){$type=0;}
        $criteria->with = array('pro');
		$criteria->compare('id',$this->id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('t.mail',$this->mail,true);
		$criteria->compare('t.qq',$this->qq,true);
		$criteria->compare('reg_status',$this->reg_status);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('com',$this->com,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('source',$this->source,true);

        if($search_id==0)
        {
            $criteria->compare('search_id',$this->search_id,true);
        }
        else
        {
            $criteria->compare('search_id',$search_id);
        }

		$criteria->compare('pro.name',$this->pro_name,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('motifytime',$this->motifytime,true);
		$criteria->compare('tixingtime',$this->tixingtime,true);
        $criteria->compare('userarea',$this->userarea,true);
        $criteria->compare('t.username',$this->username,true);
        $criteria->compare('type',$type);

		//$id=Yii::app()->session['dataid'];

		$criteria->compare('zixuntime',$this->zixuntime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 'motifytime DESC',
				'attributes'=>array(   
                    'pro_name'=>array(
                        'asc'=>'pro.name',
                        'desc'=>'pro.name DESC',
                    ),
                    '*',
                ),
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SerachInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function button($id,$status,$red){
		
			return '<a title="有效" style="border:1px #333 solid;font-size:10px" href="/dhadmin/serachInfo/updatestatus?id='.$id.'&isv='.$status.'&isr='.$red.'&status=1">有效</a>  <a title="无效" style="border:1px #333 solid;font-size:10px" href="/dhadmin/serachInfo/updatestatus?id='.$id.'&isv='.$status.'&isr='.$red.'&status=2">无效</a>';
		
		
	}

	  /*
 * 状态列表下拉
 * */
    public function getlistDatacurStatus()
    {
        $data = array(array("key"=>0,"value"=>"未注册"),array("key"=>1,"value"=>"已注册"));
        return $data;
    }
     public function getlistDatacur()
    {
        $data = array(array("key"=>0,"value"=>"未审核"),array("key"=>1,"value"=>"有效"),array("key"=>2,"value"=>"无效"));
        return $data;
    }
}
