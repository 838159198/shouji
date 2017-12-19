<?php
class Campaign extends CActiveRecord
{


	public function tableName()
	{
		return '{{campaign}}';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, pid, title, status,instruction, starttime,endtime,userstarttime,userendtime,createtime', 'safe', 'on'=>'search'),
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
            'p' => array(self::BELONGS_TO, 'Product', 'pid'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pid' => '	产品ID',
			'title' => '活动标题',
			'status' => '状态',
			'instruction' => '说明',
            'starttime' => '活动开始时间',
            'endtime' => '活动截止时间',
            'userstarttime' => '报名开始时间',
            'userendtime' => '报名截止时间',
            'createtime' => '添加时间',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('instruction',$this->instruction);
		$criteria->compare('starttime',$this->starttime);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('userstarttime',$this->userstarttime,true);
		$criteria->compare('userendtime',$this->userendtime,true);
        $criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 获取所有可用
	 */
	public function getAll($auth = 1)
	{
		$c = new  CDbCriteria();
		$c->addCondition('status=:status');

		$c->params = array(':status' => Common::STATUS_TRUE);
		$c->order = '`order` DESC';
		return $this->findAll($c);
	}
    //获取正在进行的活动
    public  function getCampaign(){
        $campaign = Campaign::model()->with('p')->findAll(array(
            'select' =>array('t.id','t.periods','t.starttime','t.endtime'),
            'order' => 't.id DESC',
            'condition' => 't.status=:status AND starttime<=:starttime AND endtime >=:endtime',
            'params' => array(':status'=>'1',':starttime' => date('Y-m-d H:i:s',time()),':endtime' => date('Y-m-d H:i:s',time())),
            'limit'=>1,
        ));
        return $campaign;
    }

    //获取已结束的活动
    public  function getCampaignEnd(){
        $campaign = Campaign::model()->with('p')->findAll(array(
            'select' =>array('t.id','t.periods','t.starttime','t.endtime'),
            'order' => 't.periods DESC',
            'condition' => 't.status=:status AND endtime <=:endtime',
            'params' => array(':status'=>'1',':endtime' => date('Y-m-d H:i:s',time())),
            'limit'=>1,
        ));
        return $campaign;
    }
    //获取未开始的活动和正在报名的
    public  function getCampaignStart(){
        $campaign = Campaign::model()->with('p')->findAll(array(
            'select' =>array('t.id','t.periods','t.starttime','t.endtime','t.userstarttime','t.userendtime'),
            'order' => 't.periods DESC',
            'condition' => 't.status=:status AND starttime>=:starttime',
            'params' => array(':status'=>'1',':starttime' => date('Y-m-d H:i:s',time())),
            'limit'=>2,
        ));
        return $campaign;
    }
    //获取所有活动de状态
    public  function getXStatus(){
        $data= "";
        if($this->starttime<=date('Y-m-d H:i:s',time()) && $this->endtime>=date('Y-m-d H:i:s',time())){
            $data = "<span id='btn_status' style='background-color:#e14632'>正在进行</span>";
        }elseif($this->userstarttime>date('Y-m-d H:i:s',time())) {
            $data = "<span id='btn_status'>未开始</span>";
        }elseif($this->userstarttime<=date('Y-m-d H:i:s',time()) && $this->starttime>date('Y-m-d H:i:s',time())){
            $data = "<span id='btn_status'>火热报名</span>";
        }else{
            $data = "<span id='btn_status' style='background-color: #8b8b8b'>已结束</span>";
        }
        return $data;
    }

    public static function findByPeriods($periods){
        $campaign = Campaign::model()->findAll("periods={$periods}");
        return $campaign[0]->publishtime;
    }
}
