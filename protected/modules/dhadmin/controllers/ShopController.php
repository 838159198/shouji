<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/6/9
 * Time: 9:25
 * @name 商城
 */
class ShopController extends DhadminController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        $model = new ShopGoods();
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ShopGoods'])) {
            $model->attributes = $_GET['ShopGoods'];
        }
        $this->render("index",array('model'=>$model));
    }
    /**
     * @name 添加商品
     */
    public function actionGoodsAdd()
    {
        $model = new ShopGoods();
        if(isset($_POST['ShopGoods'])){
            foreach($_POST['ShopGoods'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> create_datetime = time();
            $model -> update_datetime = time();
            $model -> uid = Yii::app()->user->manage_id;
            if($model -> save()){
                //跳转到列表页面
                //Common::redirect(Yii::app()->createUrl("manage/shop"), '信息已修改，请重新登录');
                $this->redirect(array("index"));
            }
        }
        $this->render("goods_add",array("model"=>$model));
    }
    /**
     * @name 编辑商品
     */
    public function actionGoodsUpdate($id)
    {
        $model = ShopGoods::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['ShopGoods'])){
                foreach($_POST['ShopGoods'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> update_datetime = time();
                if($data -> save()){
                    $this->redirect(array("index"));
                }
            }
            $this->render("goods_update",array("model"=>$data));
        }
    }
    /**
     * @name 商品详情
     */
    public function actionGoodsDetail($id)
    {
        $model = new ShopGoods();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $this->render("goods_detail",array("data"=>$data));
        }
    }
    /**
     * @name 订单列表
     */
    public function actionGoodsOrder()
    {
        $model = new ShopGoodsOrder();
        $this->render("goods_order",array("model"=>$model));
    }
    /**
     * @name 订单详情
     */
    public function actionGoodsOrderDetail($id)
    {
        $id = intval($id);
        $goods_order_model = ShopGoodsOrder::model();
        $goods_order_data = $goods_order_model -> findByPk($id);
        if(empty($goods_order_data)) {
            throw new CHttpException(404, "不存在的id");
        }
        //var_dump($goods_order_data);exit;
        if(isset($_POST['ShopGoodsOrder'])){
            $goods_order_data -> status = $_POST['ShopGoodsOrder']['status'];
            $goods_order_data -> reply = $_POST['ShopGoodsOrder']['reply'];
            $goods_order_data -> mailname = $_POST['ShopGoodsOrder']['mailname'];
            $goods_order_data -> mailcode = $_POST['ShopGoodsOrder']['mailcode'];
            $goods_order_data -> update_datetime = time();
            $goods_order_data -> opid = Yii::app()->user->manage_id;
            if($goods_order_data->update()){
                $member=Member::model()->findByPk($goods_order_data->mid);
                if($_POST['ShopGoodsOrder']['status']==2){
                    //取消订单 返还积分和库存
                    ShopGoods::model()->updateCounters(array("kucun"=>$goods_order_data->num),"id={$goods_order_data->gid}");
                    Member::model()->updateCounters(array("credits"=>$goods_order_data->realcredits),"id={$goods_order_data->mid}");
                    //积分日志
                    $model = new MemberCreditsLog();
                    $model -> create_datetime = time();
                    $model -> opid = $this->uid;
                    $model -> memberId = $goods_order_data->mid;
                    $model -> credits =$goods_order_data->realcredits;
                    $model -> account_credits = $member->credits;
                    $model ->source ="system";
                    $model ->remarks ="取消订单返还";
                    $model->save();
                }
                if($_POST['ShopGoodsOrder']['status']==='1' && isset($member)){
                    //发货信息推送到微信接口
                    if($member->weixin_openid !='' && $member->weixin_openid !=null){
                        $openid=$member->weixin_openid;
                        $username=$member->username;
                        $goodsname=$goods_order_data->gname;
                        $credits=$goods_order_data->realcredits;
                        $orderNo=$goods_order_data->mailcode;
                        Weixin::handleTemplateByShopSend($openid,$username,$goodsname,$credits,$orderNo);
                    }

                }
                $this->redirect("/dhadmin/shop/goodsOrder");
            }
        }
        $this->render("order_detail",array("data"=>$goods_order_data));
    }

    /**
     * @name 添加商品分类
     */
    public function actionAddCategory(){
        $category_model=new ShopGoodsCategory();
        if(isset($_POST['ShopGoodsCategory'])){
            foreach($_POST['ShopGoodsCategory'] as $_k => $_v){
                $category_model->$_k = $_v;
            }
            $category_model -> add_time = time();
            $category_model -> mid = Yii::app()->user->manage_id;

            if($category_model->save()){
                //更新用户资料积分
                Yii::app()->user->setFlash("credits_status","商品分类添加成功");
                $this->redirect("/dhadmin/shop/Addcategory");
            }

        }

        $this->render("add_category",array("model"=>$category_model));
    }
    /**
     * @name 更新商品分类
     */
    public function actionCategoryUpdate($id)
    {
        $model = ShopGoodsCategory::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['ShopGoodsCategory'])){
                foreach($_POST['ShopGoodsCategory'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                if($data -> save()){
                    $this->redirect(array("/dhadmin/shop/Addcategory"));
                }
            }
            $this->render("category_update",array("model"=>$data));
        }
    }

}