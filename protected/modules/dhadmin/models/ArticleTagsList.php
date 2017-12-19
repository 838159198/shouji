<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:12
 */
class ArticleTagsList extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{article_tagslist}}';
    }
    public function addTags($id,$tid,$tname)
    {
        if(!$this->exists('`id`=:id and `tid`=:tid',array(':tid'=>$tid,":id"=>$id)))
        {
            $tag = new ArticleTagsList();
            $tag->name=$tname;
            $tag->tid=$tid;
            $tag->aid = $id;
            $tag->createtime = time();
            $tag->save();

        }

    }

}