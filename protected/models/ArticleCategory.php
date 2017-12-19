<?php

/**
 * This is the model class for table "{{article_category}}".
 *
 * The followings are the available columns in table '{{article_category}}':
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
class ArticleCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article_category}}';
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
