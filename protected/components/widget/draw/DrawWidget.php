<?php
class DrawWidget extends CWidget
{
    public $num = 1;
    public $order = "id";
    public function init()
    {
        $model = new YearendDraw();
        $criteria = new CDbCriteria();
        $criteria -> limit = $this->num;
        $criteria -> order = "id DESC";
        $data = $model -> findAll($criteria);
        $this->render("draw",array("data"=>$data));
    }

    public function run()
    {

    }
}