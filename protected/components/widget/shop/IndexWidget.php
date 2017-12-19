<?php
class IndexWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public function init()
    {
        $model = new ShopGoods();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1 and recommend =1";
        $criteria -> limit = $this->num;
        $criteria -> order = "`{$this->order}` DESC,`id` DESC";
        $data = $model -> findAll($criteria);
        $this->render("index",array("data"=>$data));
    }

    public function run()
    {
    }
}