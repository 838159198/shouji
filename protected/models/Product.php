<?php

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property string $id
 * @property string $name
 * @property string $pathname
 * @property string $pic
 * @property integer $status
 * @property integer $auth
 * @property string $officialprice
 * @property string $price
 * @property string $quote
 * @property integer $createtime
 * @property integer $updatetime
 * @property integer $order
 * @property string $install_instructions
 * @property string $activate_instructions
 * @property string $content
 * @property string $enrollment
 * @property string $under_instructions
 * @property string $ptype
 * @property string $category
 */
class Product extends CActiveRecord
{
	/** 权限 - 用户 */
	const AUTH_MEMBER = 0;
	/** 权限 - 管理 */
	const AUTH_MANAGE = 1;
	/** 权限 - 临时关闭 */
	const AUTH_CLOSED = 2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, pathname, pic, createtime, updatetime, install_instructions, activate_instructions', 'required'),
			array('status, auth, createtime, updatetime, order,ptype,category', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
            array('name','unique'),
            array('pathname','unique'),
			array('pathname', 'length', 'max'=>10),
			array('pic', 'length', 'max'=>60),
			array('officialprice, price, quote', 'length', 'max'=>7),
			array('install_instructions, activate_instructions,under_instructions', 'length', 'max'=>255),
			array('enrollment', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, pathname, pic, status, auth, officialprice, price, quote, createtime, updatetime, order, install_instructions, activate_instructions, under_instructions,content, enrollment,ptype,category,sign', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'name' => '资源名称',
			'pathname' => '标识',
			'pic' => '图标',
			'status' => '状态1可用0不可用',
			'auth' => '开通权限0客户1管理员2临时关闭',
			'officialprice' => '官方单价',
			'price' => '用户默认实际单价',
			'quote' => '用户默认报价',
			'createtime' => '创建时间',
			'updatetime' => '修改时间',
			'order' => '排序',
			'install_instructions' => '安装说明',
            'under_instructions' => '下架说明',
			'activate_instructions' => 'Activate Instructions',
			'content' => '产品介绍',
			'enrollment' => '人数',
            'ptype' => '类型',
			'category'=>'分类',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pathname',$this->pathname,true);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('auth',$this->auth);
		$criteria->compare('officialprice',$this->officialprice,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('quote',$this->quote,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('order',$this->order);
		$criteria->compare('install_instructions',$this->install_instructions,true);
		$criteria->compare('activate_instructions',$this->activate_instructions,true);
        $criteria->compare('under_instructions',$this->under_instructions,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('enrollment',$this->enrollment,true);
        $criteria->compare('ptype',$this->ptype,true);
		$criteria->compare('category',$this->category,true);

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
     * 开通权限
     * */
    public function getXauth()
    {
        $data = array();
        $data[0] = "客户自己开通";
        $data[1] = "管理员OR客服";
        $data[2] = "临时关闭";
        return $data;
    }
	/**
	 * 获取所有可用
	 * @param int $auth
	 * @return Resource[]
	 */
	public function getAll($auth = 1)
	{
		$c = new  CDbCriteria();
		$c->addCondition('status=:status');
		$c->addCondition('auth<=:auth');
		$c->params = array(':status' => Common::STATUS_TRUE, ':auth' => $auth);
		$c->order = '`order` DESC';
		return $this->findAll($c);
	}
	/**
	 * 根据keyword获得对象
	 * @param $keyword
	 * @return Resource
	 */
	public function getByKeyword($keyword)
	{
		$c = new CDbCriteria();
		$c->addCondition('pathname=:keyword');
		$c->addCondition('status=:status');
		$c->params = array(
			':keyword' => $keyword,
			':status' => Common::STATUS_TRUE,
		);
		return $this->find($c);
	}
    /**
     * 根据keyword获得对象 包括下架的
     * @param $keyword
     * @return Resource
     */
    public function getByKeyword2($keyword)
    {
        $c = new CDbCriteria();
        $c->addCondition('pathname=:keyword');
        //$c->addCondition('status=:status');
        $c->params = array(
            ':keyword' => $keyword,
            //':status' => Common::STATUS_TRUE,
        );
        return $this->find($c);
    }
	/**
	 * 获得可用的keyword
	 * @param int $status
	 * @return Resource[]
	 */
	public function getKeywordList($status = Common::STATUS_TRUE)
	{
		$keys = array();
		$list = $this->findAll('status=:status',array(":status"=>$status));
		foreach ($list as $v) {
			/** @var $v Product */
			$keys[$v->pathname] = $v->name;
		}
		return $keys;
	}

    /**
     * 获得可用的keyword包括下架
     * @param int $status
     * @return Resource[]
     */
    public function getKeywordList2()
    {
        $keys = array();
        $list = $this->findAll('status in (0,1)');
        foreach ($list as $v) {
            /** @var $v Product */
            $keys[$v->pathname] = $v->name;
        }
        return $keys;
    }
    /**
     * 获得可用的typeList
     * @param int $status
     * @return Resource[]
     */
    public function getKeywordList3()
    {
        $keys = array();
        $list = $this->findAll('status in (0,1)');
        foreach ($list as $v) {
            /** @var $v Product */
            $keys[] = $v->pathname;
        }
        return $keys;
    }
}
