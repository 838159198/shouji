<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/12
 * Time: 15:03
 */
class DrawStatusWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public function init()
    {
        $model = new ShopGoodsOrder();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria->addCondition('gid=0');
        $criteria -> limit = $this->num;
        $criteria -> order = "id DESC";
        $data = $model -> findAll($criteria);
        $this->render("draw_status",array("data"=>$data));
    }

    public function run()
    {

    }
}