<?php

/**
 * 任务
 * This is the model class for table "{{task}}".
 *
 * The followings are the available columns in table '{{task}}':
 * @property string $id
 * @property string $publish
 * @property string $accept
 * @property integer $createtime
 * @property integer $motifytime
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property integer $isshow
 * @property string $mid
 *
 * The followings are the available model relations:
 * @property TaskWhen[] $taskWhens
 * @property Manage $managePublish
 * @property Manage $manageAccept
 * @property Member $member
 */
class Salary extends CActiveRecord {
	
	/** 新用户任务提成，普通客服权限 */
	const STAFF_PAYBACK_NEW =  DefaultParm::DEFAULT_TEN;
	/** 降量任务提成， - 普通客服权限 */
	const STAFF_PAYBACK_DROP =  DefaultParm::DEFAULT_TEN;

	/** 新用户任务提成，高级客服以上权限 */
	const ADVANCED_STAFF_PAYBACK_NEW =  DefaultParm::DEFAULT_FIFTEEN;
	/** 降量任务提成， - 高级客服以上权限 */
	const ADVANCED_STAFF_PAYBACK_DROP =  DefaultParm::DEFAULT_FIFTEEN;
	
	/** 见习客服， - 每个回访任务成功完成后的收益 */
	const PRACTICE_STAFF_PAYBACK_VISITE =  DefaultParm::DEFAULT_ONE;
	
	private $connection = null;  
    private $command = null;  
			
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Task the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}

	/**
	 * 任务状态
	 * @param args .. 
	 * @return array
	 */
	public static function getWeekTaskStatus() {
		$types = array (self::STATUS_NORMAL => '进行中', self::STATUS_PRO => '上报', self::STATUS_BACK => '无效任务', self::STATUS_DONE => '有效完成', self::STATUS_DELETE => '删除 ' );
		
		$args = func_get_args ();
		if (empty ( $args )) {
			return $types;
		} else {
			$_types = array ();
			foreach ( $args as $arg ) {
				if (! isset ( $types [$arg] ))
					continue;
				$_types [$arg] = $types [$arg];
			}
			return $_types;
		}
	}
	/**
	 * @param $id
	 * @return string
	 */
	public static function getStatusName($id) {
		$typeList = self::getWeekTaskStatus ();
		return isset ( $typeList [$id] ) ? $typeList [$id] : '';
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{salary}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array (array ('publish, accept, mid, createtime, motifytime, title, content, status', 'required' ), array ('status, isshow, type', 'numerical', 'integerOnly' => true ), array ('publish, accept, mid', 'length', 'max' => 11 ), array ('title', 'length', 'max' => 50 ), array ('content', 'length', 'max' => 255 ), // The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array ('id, publish, accept, mid, createtime, motifytime, title, content, type, status, isshow', 'safe', 'on' => 'search' ) );
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
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria ();
		
		$criteria->compare ( 'id', $this->id, true );
		$criteria->compare ( 'f_id', $this->f_id, true );
		$criteria->compare ( 'm_id', $this->m_id, true );
		$criteria->compare ( 'payback', $this->payback, true );
		$criteria->compare ( 'createtime', $this->createtime, true );
		$criteria->compare ( 'endtime', $this->endtime, true );
		$criteria->compare ( 'prottime', $this->prottime, true );
		$criteria->compare ( 'at_id', $this->at_id );
		
		return new CActiveDataProvider ( $this, array (
			'criteria' => $criteria,
			'pagination'=>array('pagesize'=>Common::PAGE_SIZE) 
		));
		
	}
    /**
     * 获取指定月份的任务收益
     */
    public function getSalaryByMounth($id,$mounth){
        $sql = 'SELECT week_payback,task_payback FROM app_salary
                                  WHERE uid = \''.$id.'\' AND t_prottime = \''.$mounth.'\' ';
        return  Yii::app()->db->createCommand($sql)->queryAll();
    }
/**
 * 根据权限，获取提成
 */
public function getPayByRole($role){
	$pay = array();
			switch ($role){
				case $role<=Role::ADVANCED_STAFF:
					$pay['drop'] = (Salary::ADVANCED_STAFF_PAYBACK_DROP)/100;
					$pay['new'] = (Salary::ADVANCED_STAFF_PAYBACK_NEW)/100;
				break;
				case $role>Role::ADVANCED_STAFF:
					$pay['drop'] = (Salary::STAFF_PAYBACK_DROP)/100;
					$pay['new'] = (Salary::STAFF_PAYBACK_NEW)/100;

				break;
			}
	return $pay;
}
	
	
	
}
