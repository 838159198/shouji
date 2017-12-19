<?php
class NumHotWidget extends CWidget {
    public $num = 1;
    public $order = "num";
    public function init()
    {
        $model = new ShopGoods();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> limit = $this->num;
        $criteria -> order = "{$this->order} DESC";
        $data = $model -> findAll($criteria);
        $this->render("shop_num_hot",array("data"=>$data));
    }

    public function run()
    {
    }
}