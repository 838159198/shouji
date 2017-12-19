<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17
 * Time: 10:26
 */
class CampaignController extends Controller{
    //所有活动产品显示页面
    public function actionList(){
        $this->pageTitle = "活动中心";
        $model = new Campaign();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=4;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        //var_dump($data);exit;
        $this->render("list",array("data"=>$data,"pages"=>$pager));
    }
}