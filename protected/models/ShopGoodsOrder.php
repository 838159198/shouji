<?php
class ShopGoodsOrder extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{shop_goods_order}}';
    }
    public function rules()
    {
        return array(
            array('gid,mid,status,address,tel,username,mailname,mailcode', 'required'),
            array('gid,status,mid,tel', 'numerical', 'integerOnly' => true),
            array('gid','length','max'=>5),
            array('mid','length','max'=>5),
            array('status','length','is'=>1),
            array('tel,mailname,mailcode','length','max'=>30),

        );
    }
    public function relations()
    {
        return array(
            //'category' => array(self::BELONGS_TO, 'ArticleCategory', 'cid'),
            'user' => array(self::BELONGS_TO, 'Manage', 'uid'),
            'member'=>array(self::BELONGS_TO,'MemberInfo','mid'),
            'goods'=>array(self::BELONGS_TO,'ShopGoods','gid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'gid'=>'商品ID',
            'mid'=>'用户ID',
            'gname' => '商品名称',
            'create_datetime' => '创建时间',
            'update_datetime' => '更新时间',
            'status' => '状态',
            'address'=>'收货地址',
            'username'=>'姓名',
            'tel'=>'电话',
            'remarks'=>'备注',
            'credits'=>'积分',
            'opid'=>'操作员',
            'realcredits'=>'使用积分',
            'mailcode'=>'快递单号',
            'mailname'=>'物流名称',
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
        $criteria->with = array('member');

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
            $data = "<font color=#006600><b>已发货</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#0000ff><b>待发货</b></font>";
        }elseif($this->status==2){
            $data = "<font color=#ff0000><b>取消订单</b></font>";
        }elseif($this->status==3){
            $data = "<font color=#006600><b>确认收货</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    public function getXuser()
    {
        $data = Manage::model()->findByPk($this->opid);
        return $data;
    }
}