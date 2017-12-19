<?php
class CampaignLog extends CActiveRecord
{

	public function tableName()
	{
		return '{{campaign_log}}';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, pid, title, status,cid, uid,bak,createtime', 'safe', 'on'=>'search'),
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
			'pid' => '	产品ID',
			'title' => '活动标题',
			'status' => '状态',
			'bak' => '拒绝理由',
            'cid' => '活动ID',
            'uid' => '用户ID',
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
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('bak',$this->bak,true);
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

    public static function findByPeriods($periods,$status){
        $pcamlog_model = new CampaignLog();
        $criteria = new CDbCriteria();
        $criteria -> condition = 'status =:status and cid=:periods';
        $criteria ->params = array (':status' =>$status,':periods'=>$periods ) ;
        $count = $pcamlog_model->count($criteria);
        if(!isset($count)){
            $count=0;
        }
        return $count;
    }
	
}
