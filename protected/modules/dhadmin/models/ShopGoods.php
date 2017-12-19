<?php
class ShopGoods extends CActiveRecord
{
    private $_oldTags;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{shop_goods}}';
    }
    public function rules()
    {
        return array(
            array('title, credits,status,content,coverimage,previewimage,order', 'required'),
            array('credits,status,order', 'numerical', 'integerOnly' => true),
            array('status','length','is'=>1),
            array('order','length','max'=>5),
            array('credits','length','max'=>10),
            array('title', 'length', 'max' => 30,'message'=>'标题长度不能超过30'),
            array("down_datetime",'required','message'=>"请选择下架日期"),
            /*array('title', 'required','message'=>"请填写文章标题",'on'=>'admin'),
            array('status', 'required','message'=>"请选择状态",'on'=>'admin'),
            array("cid",'required','message'=>"请选择栏目"),
            array('title', 'length', 'max' => 60,'message'=>'标题长度不能超过60'),
            array('keywords', 'length', 'max' => 120,'message'=>'关键字长度不能超过120'),
            array('descriptions', 'length', 'max' => 250,'message'=>'描述长度不能超过250'),
            array('tags', 'length', 'max' => 100,'message'=>'标签长度不能超过100','on'=>'admin'),
            array('content', 'required','message'=>"请填写文章内容"),
            array('content', 'length', 'min' => 10,'message'=>'文章内容不能少于10个字符'),
            array('tags', 'normalizeTags','on'=>'admin'),*/
            //array('jointime, overtime, status', 'numerical', 'integerOnly' => true),

            //array('name,createtime,lasttime', 'length', 'max' => 10),
            //array('name, alias, attrib', 'length', 'max' => 32),
            //array('title, fromer, keyword', 'length', 'max' => 96),
            //array('preview', 'length', 'max' => 48),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,uid,recommend,cd,name,title,alias,attrib,fromer,keyword,preview,content,jointime,overtime,times,status,kucun,down_datetime，address', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
            'category' => array(self::BELONGS_TO, 'ShopGoodsCategory', 'cid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '商品名称',
            'create_datetime' => '创建时间',
            'update_datetime' => '更新时间',
            'status' => '状态',
            'recommend' => '推荐',
            "uid"=>"发布者",
            "hits"=>"点击率",
            "credits"=>"积分",
            "intro"=>"简介",
            'content'=>'内容',
            "num"=>"订购人数",
            "coverimage"=>"封面图片",
            "previewimage"=>"预览图",
            "order"=>"排序",
            "kucun"=>"库存",
            "cid"=>"商品分类",
            "address"=>"商品链接",
            "down_datetime"=>"下架日期"
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
                'defaultOrder'=>'`order` DESC,id DESC', //设置默认排序
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
            $data = "<font color=#ff0000><b>隐藏</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }


}