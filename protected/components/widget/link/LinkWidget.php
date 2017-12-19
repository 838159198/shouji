<?php
class LinkWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public $cid = "1";
    public function init()
    {
        $model = new Link();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1 and `cid`={$this->cid}";
        $criteria -> limit = $this->num;
        $criteria -> order = "num ASC";
        $data = $model -> findAll($criteria);
        $this->render("view",array("data"=>$data));
    }

    public function run()
    {
    }
}