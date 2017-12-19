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
class Article extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article}}';
	}



	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'category'=>array(self::BELONGS_TO,"ArticleCategory","cid"),
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
}
