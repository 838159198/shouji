<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/21
 * Time: 13:27
 */
class Link extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{link}}';
    }
    public function rules()
    {
        return array(
            //array('title, cid, createtime,lasttime,status', 'required','message'=>"请选择栏目"),
            array('name', 'required','message'=>"请填写网站名称"),
            array('website', 'required','message'=>"请填写网址"),
            array('status', 'required','message'=>"请选择状态"),
            array("cid",'required','message'=>"请选择分类"),
            array('name', 'length', 'max' => 30,'tooLong'=>'网站名称长度不超过20'),
            array('website', 'length', 'max' => 200,'tooLong'=>'网址长度不超过20'),
            array('remarks', 'length', 'max' => 200,'tooLong'=>'备注内容长度不超过200'),
            //array('qq', 'length', 'max' => 12,'message'=>'请填写正确的QQ号码'),
            //array('qq', 'length', 'min' => 5,'message'=>'QQ号码最少5位，请填写正确的QQ号码'),
            array('qq', 'length', 'max'=>12, 'min'=>5, 'tooLong'=>'QQ号码位数太长，请填写正确的QQ号码', 'tooShort'=>'QQ号码最少5位，请填写正确的QQ号码'),
            array('num', 'length', 'max' => 5,'message'=>'序号最大长度为5位数字'),
            //array('qq','match','pattern'=>'/^[a-z0-9\-_]+$/','message'=>'请填写数字'),
            array('qq','match','pattern'=>'/^[0-9]+$/','message'=>'QQ号码需为数字'),
            //array('qq', 'numerical', 'min'=>10000, 'max'=>999999999999, 'integerOnly'=>true,'message'=>'请填写正确的QQ号码'),
            //array('num','match','pattern'=>'/^[a-z0-9\-_]+$/','message'=>'请填写数字'),
            array('num', 'numerical', 'min'=>0, 'max'=>99999, 'integerOnly'=>true,'message'=>'请填写数字，0 - 99999之间'),

        );
    }
    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'LinkCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '网站名称',
            'website' => '网址',
            'createtime' => '添加时间',
            'lasttime' => '更新时间',
            "uid"=>"发布者",
            "remarks"=>"备注",
            "qq"=>"QQ",
            "num"=>"排序",
            'cid'=>"分类",
            'status'=>'状态',
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