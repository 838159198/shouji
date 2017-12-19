<?php
class AgentPrice extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{agent_price}}';
    }
    public function rules()
    {
        return array(
            array('id,pid,agent,price', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Procudt', 'pid'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => '产品',
            'price' => '价格',
            'agent' => '分组',
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
                //'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    public static function findPrice($pid,$agent){
        $product_price=AgentPrice::model()->find("pid=:pid and agent=:agent",array(":pid"=>$pid,":agent"=>$agent));
        if(!empty($product_price)){
            $price= $product_price['price'];
        }
        else
        {
            $price=99999;
        }
        return $price;
    }

}