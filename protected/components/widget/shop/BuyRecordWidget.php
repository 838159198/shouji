<?php
class BuyRecordWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public function init()
    {

        $model = new ShopGoodsOrder();
        $criteria = new CDbCriteria();
        $criteria -> limit = $this->num;
        $criteria -> order = "`{$this->order}` DESC";
        $data = $model -> findAll($criteria);
        $this->render("buy_record",array("data"=>$data));
    }

    public function run()
    {
    }
}