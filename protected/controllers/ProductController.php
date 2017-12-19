<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/20
 * Time: 9:22
 * 产品
 */
class ProductController extends Controller
{
    /*
     * 产品
     * */
    public function actionIndex()
    {
        $this->pageTitle = "APP列表";
        $model = new Product();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=5;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        $this->render("index",array("data"=>$data,"pages"=>$pager));
    }
    public function actionReview(){
        $this->pageTitle = "登陆提醒";
        $this->render("review");
    }
}