<?php
class IndexNoticeWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public $cid = 1;
    public function init()
    {
        $model = new Article();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1 and cid ={$this->cid}";
        $criteria -> limit = $this->num;
        $criteria -> order = "{$this->order} DESC";
        $data = $model -> findAll($criteria);
        $this->render("index_notice",array("data"=>$data));
    }

    public function run()
    {
    }
}