<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:12
 */
class Article extends CActiveRecord
{
    private $_oldTags;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{article}}';
    }
    public function rules()
    {
        return array(
            //array('title, cid, createtime,lasttime,status', 'required','message'=>"请选择栏目"),
            array('title', 'required','message'=>"请填写文章标题"),
            array('status', 'required','message'=>"请选择状态"),
            array("cid",'required','message'=>"请选择栏目"),
            array('title', 'length', 'max' => 60,'message'=>'标题长度不能超过60'),
            array('keywords', 'length', 'max' => 120,'message'=>'关键字长度不能超过120'),
            array('descriptions', 'length', 'max' => 250,'message'=>'描述长度不能超过250'),
            array('tags', 'length', 'max' => 100,'message'=>'标签长度不能超过100','on'=>'admin'),
            array('content', 'required','message'=>"请填写文章内容"),
            array('content', 'length', 'min' => 10,'message'=>'文章内容不能少于10个字符'),
            array('tags', 'normalizeTags'),
            //array('jointime, overtime, status', 'numerical', 'integerOnly' => true),

            //array('name,createtime,lasttime', 'length', 'max' => 10),
            //array('name, alias, attrib', 'length', 'max' => 32),
            //array('title, fromer, keyword', 'length', 'max' => 96),
            //array('preview', 'length', 'max' => 48),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('id, cd, name, title, alias, attrib, fromer, keyword, preview, content, jointime, overtime, times, status', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '文章标题',
            'createtime' => '创建时间',
            'lasttime' => '更新时间',
            'status' => '状态',
            "keywords"=>"关键字",
            "descriptions"=>"描述",
            "uid"=>"发布者",
            "hits"=>"点击率",
            'cid'=>"所属栏目",
            'tags'=>'标签',
            'content'=>'内容',

        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        /*$criteria->compare('title', $this->title, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('lasttime', $this->lasttime, true);*/
        //$criteria->compare('status', $this->status);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    public function normalizeTags($attribute,$params)
    {
        $this->tags=ArticleTags::array2string(array_unique(ArticleTags::string2array($this->tags)));
    }
    protected function afterFind()
    {
        parent::afterFind();
        $this->_oldTags=$this->tags;
    }
    protected function afterSave()
    {
        ArticleTags::model()->updateFrequency($this->id,$this->_oldTags, $this->tags);
    }
    protected function afterDelete()
    {
        parent::afterDelete();
        ArticleTags::model()->updateFrequency($this->id,$this->tags, '');
        ArticleTagsList::model()->deleteAll("aid=:aid",array(":aid"=>$this->id));
    }
    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>正常</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>关闭</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
}