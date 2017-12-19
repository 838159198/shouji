<?php
class RecommendWidget extends CWidget {
    public $num = 1;
    public $order = "`order` desc,id";
    public $cid=0;
    public function init()
    {
        $model = new ShopGoods();
        $criteria = new CDbCriteria();
        if($this->cid==0){
            $criteria -> condition = "status=1";
        }else{
            $criteria -> condition = "status=1 and cid={$this->cid}";
        }

        $criteria -> limit = $this->num;
        $criteria -> order = "{$this->order} DESC";
        $data = $model -> findAll($criteria);
        $this->render("shop_recommend",array("data"=>$data));
    }

    public function run()
    {

    }
}