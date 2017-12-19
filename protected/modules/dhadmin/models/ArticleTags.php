<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:12
 */
class ArticleTags extends CActiveRecord
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
        return '{{article_tags}}';
    }

    /*public function rules()
    {
        return array(
            array('cd, name, title, alias, attrib, fromer, keyword, preview, content, times', 'required'),
            array('jointime, overtime, status', 'numerical', 'integerOnly' => true),
            array('cd, times', 'length', 'max' => 10),
            array('name, alias, attrib', 'length', 'max' => 32),
            array('title, fromer, keyword', 'length', 'max' => 96),
            array('preview', 'length', 'max' => 48),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, cd, name, title, alias, attrib, fromer, keyword, preview, content, jointime, overtime, times, status', 'safe', 'on' => 'search'),
        );
    }*/

    /*public function relations()
    {
        return array(
            'Category' => array(self::BELONGS_TO, 'Category', '', 'on' => 'cd=Category.id')
        );
    }*/

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cd' => '分类ID',
            'name' => '作者',
            'title' => '标题',
            'alias' => '副标题',
            'attrib' => '属性',
            'fromer' => '来自',
            'keyword' => '关键字',
            'preview' => '缩略图',
            'content' => '内容',
            'jointime' => '发布时间',
            'overtime' => '过期时间',
            'times' => '点击',
            'status' => '状态',
            'auth' => '权限',
        );
    }
    public static function string2array($tags)
    {
        //return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
        //先将中文，替换为英文,  然后进行字符串转数组
        return explode(",",str_replace("，",",",$tags));
    }

    public static function array2string($tags)
    {
        return implode(',',$tags);
    }
    public function updateFrequency($id,$oldTags, $newTags)
    {
        $oldTags=self::string2array($oldTags);
        $newTags=self::string2array($newTags);
        $this->addTags($id,array_values(array_diff($newTags,$oldTags)));
        $this->removeTags($id,array_values(array_diff($oldTags,$newTags)));
    }
    public function addTags($id,$tags)
    {
        if(!empty($tags)){
            $criteria=new CDbCriteria;
            $criteria->addInCondition('name',$tags);
            $this->updateCounters(array('frequency'=>1),$criteria);
            foreach($tags as $name)
            {
                if(!$this->exists('name=:name',array(':name'=>$name)))
                {
                    $tag = new ArticleTags();
                    $tag->name=$name;
                    //$tag->seotitle=$name;
                    $tag->frequency=1;
                    $tag->createtime = time();
                    $tag->save();

                }
                //添加到tagslist
                $tagdata = ArticleTags::model()->find("name=:name",array(":name"=>$name));
                ArticleTagsList::model()->addTags($id,$tagdata->id,$name);

            }
        }


    }

    public function removeTags($id,$tags)
    {
        if(empty($tags))
            return;
        $criteria=new CDbCriteria;
        $criteria->addInCondition('name',$tags);
        $this->updateCounters(array('frequency'=>-1),$criteria);
        //$this->deleteAll('frequency<=0');

    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    /*public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('cd', $this->cd, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('attrib', $this->attrib, true);
        $criteria->compare('fromer', $this->fromer, true);
        $criteria->compare('keyword', $this->keyword, true);
        $criteria->compare('preview', $this->preview, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('jointime', $this->jointime);
        $criteria->compare('overtime', $this->overtime);
        $criteria->compare('times', $this->times, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('auth', $this->auth);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }*/
}