<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:12
 */
class ArticleCategory extends CActiveRecord
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
        return '{{article_category}}';
    }

    public function rules()
    {
        return array(
            array('name, seotitle, createtime,lasttime,status', 'required'),
            //array('jointime, overtime, status', 'numerical', 'integerOnly' => true),
            array('name,createtime,lasttime', 'length', 'max' => 10),
            array('pathname','unique'),
            //array('name, alias, attrib', 'length', 'max' => 32),
            //array('title, fromer, keyword', 'length', 'max' => 96),
            //array('preview', 'length', 'max' => 48),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('id, cd, name, title, alias, attrib, fromer, keyword, preview, content, jointime, overtime, times, status', 'safe', 'on' => 'search'),
        );
    }

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
            'name' => '栏目名称',
            'createtime' => '创建时间',
            'lasttime' => '更新时间',
            'status' => '状态',
            "keywords"=>"关键字",
            "descriptions"=>"描述",
            "seotitle"=>"seo标题",
            'pathname'=>'url路径',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('lasttime', $this->lasttime, true);
        $criteria->compare('status', $this->status, true);


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