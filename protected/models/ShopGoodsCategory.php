<?php
class ShopGoodsCategory extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{shop_goods_category}}';
    }
    public function rules()
    {
        return array(
            array('cname,status', 'required'),
            array('cname','unique'),
            array('id, mid, cname, add_time, status', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'mid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mid'=>'添加人',
            'cname' => '分类名',
            'add_time' => '添加时间',
            'status' => '状态',
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
            $data = "<font color=#ff0000><b>禁用</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    //获取商品分类下拉列表
    public   function getDownList()
    {
        /* @var $result Role[] */
        $result = $this->findAll("status=1");
        $list = array(0 => '== 请选择 ==');
        foreach ($result as $role) {
            $list[$role->id] = $role->cname;
        }
        return $list;
    }

    //获取商品分类下拉列表
    public static   function getList()
    {
        /* @var $result Role[] */
        $result =  ShopGoodsCategory::model()->findAll("status=1");

        foreach ($result as $role) {
            $list = $role->cname;
        }
        return $list;
    }
}