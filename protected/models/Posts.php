<?php

/**
 * This is the model class for table "{{posts}}".
 *
 * The followings are the available columns in table '{{posts}}':
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
class Posts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{posts}}';
	}
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'category'=>array(self::BELONGS_TO, 'PostsCategory', 'cid'),
		);
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * 增加点击数
     * */
    public function getAddHits()
    {
        $this->updateCounters(array("hits"=>1),"id=".$this->id);
    }
    /*
     * 获取当前页面的url
     * */
    public function getUrl()
    {
        switch($this->cid){
            case 1:
                $cate = "notice";
                break;
            case 2:
                $cate = "help";
                break;
            case 3:
                $cate = "question";
                break;
            default:
                $cate = "error";
                break;
        }
        $data = "/{$cate}/{$this->id}";
        return $data;
    }
}
