<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/6/10
 * Time: 9:17
 * 积分商城
 */
class ShopController extends Controller
{
    /*
     * 商城封面
     * */
    public function actionIndex()
    {
        $this->pageTitle = "积分兑换 - ".Yii::app()->name;
        $cate_model=new ShopGoodsCategory();
        $result = $cate_model->findAll(array(
            'select'=>array('id','cname'),
           // 'order' => 'id DESC',
            'condition' => 'status=:status',
            'params' => array(':status'=>'1'),
        ));

        $this->render("shop_index",array("categorys"=>$result));
    }
    /*
     * 商城列表
     * */
    public function actionAll()
    {
        $cid=isset($_GET['id']) ? $_GET['id']:0 ;

        $this->pageTitle = "积分兑换商品列表 - ".Yii::app()->name;
        $model = new ShopGoods();
        $criteria = new CDbCriteria();
        if($cid !=0){
            $criteria -> condition = "status=1 and cid={$cid}";
        }else{
            $criteria -> condition = "status=1";
        }

        //条件查询
        //$criteria->addCondition('status=1');      //根据条件查询
        //$criteria->addCondition('cid=0'.$category_data['id']);
        $criteria -> order = "`order` desc,id desc";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=18;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);

        $cate_model=new ShopGoodsCategory();
        $result =  $cate_model->findAll("status=1");
        $this->render("shop_list",array("data"=>$data,"pages"=>$pager,"categorys"=>$result));
    }
    /*
     * 商品详情
     * */
    public function actionGoods($id)
    {
        $id = intval($id);
        $model = ShopGoods::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"你访问的页面不存在");
        }else{
            $this->pageTitle = $data['title']." - ".Yii::app()->name;
            $model->updateCounters(array("hits"=>1),"id={$id}");
            //$article_data->updateCounters(array("hits" => 1));
            $this->render("shop_goods",array("data"=>$data));
        }
    }
}