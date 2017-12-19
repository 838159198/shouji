<?php

/**
 * This is the model class for table "{{posts_category}}".
 *
 * The followings are the available columns in table '{{posts_category}}':
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $descriptions
 * @property integer $status
 * @property string $seotitle
 * @property integer $createtime
 * @property integer $lasttime
 * @property string $pathname
 */
class PostsCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{posts_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, keywords, descriptions, seotitle, createtime, lasttime', 'required'),
			array('status, createtime, lasttime', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>10),
			array('keywords, descriptions', 'length', 'max'=>255),
			array('seotitle', 'length', 'max'=>100),
			array('pathname', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, keywords, descriptions, status, seotitle, createtime, lasttime, pathname', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'keywords' => 'Keywords',
			'descriptions' => 'Descriptions',
			'status' => '状态1正常0隐藏',
			'seotitle' => 'Seotitle',
			'createtime' => 'Createtime',
			'lasttime' => 'Lasttime',
			'pathname' => 'Pathname',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('descriptions',$this->descriptions,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('lasttime',$this->lasttime);
		$criteria->compare('pathname',$this->pathname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PostsCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*
    * 分类数据
    * */
    public function categoryByPathname($pathname)
    {
        $model = new PostsCategory();
        $data = $model -> find("pathname=:pathname",array(":pathname"=>$pathname));
        if(empty($data)){
            throw new CHttpException(404,"你访问的页面不存在");
        }else{
            return $data;
        }
    }
}
