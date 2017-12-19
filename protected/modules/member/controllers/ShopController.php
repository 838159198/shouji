<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/6/10
 * Time: 10:28
 */
class ShopController extends MemberController
{
    /*
     * 商品列表
     * */
    public function actionIndex()
    {
        //用户信息

        $uid = $this->uid;
        $member_model = new Member();
        $member_data = $member_model -> findByPk($uid);
        //商品

        $model = new ShopGoods();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> order = "credits DESC";

        $count = $model -> count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        $this->render("shop_index",array('data'=>$data,'pages'=>$pager,'member'=>$member_data));
    }
    /*
     * 兑换商品
     * */
    public function actionBuy($id)
    {
        $goods_model = new ShopGoods();
        $goods_data = $goods_model -> findByPk($id);
        if(empty($goods_data)){
            throw new CHttpException(404,"你访问的页面不存在");
        }else{
            if($goods_data['status']!=1){
                throw new CHttpException(404,"你访问的页面不存在");
            }else{
                $uid = $this->uid;
                $member_model = new Member();
                $member_data = $member_model->findByPk($uid);
                if($goods_data['kucun'] <= 0 ){
                    throw new CHttpException(403,"该商品已兑换完，库存已不足");
                }elseif(strtotime($goods_data['down_datetime'])< time()){
                    throw new CHttpException(403,"该商品您无法兑换，已下架");
                }
                elseif($member_data['credits'] < $goods_data['credits']){
                    throw new CHttpException(403,"您的积分好像不够哦");
                }else{
                    //到这里才可以购买
                    //地址
                    $memberLocation_model = new MemberLocation();
                    $memberLocation_data = $memberLocation_model->findAll("mid={$uid}");
                    $this->render("goods_buy",array("goods"=>$goods_data,'memberLocation'=>$memberLocation_data,"member"=>$member_data));
                }
            }
        }
    }
    /*
     * 兑换确认
     * */
    public function actionBuyok()
    {
        if(Yii::app()->request->isAjaxRequest) {
            //exit(json_encode("ok", array("message" => "呵呵")));
            if(!empty($_POST)){
                $goods_id = intval($_POST['goods_id']);
                $goods_num = intval($_POST['goods_num']);
                $location_id = $_POST['location'];
                $remarks = $_POST['remarks'];
                if($goods_id<=0 || $goods_num <=0){
                    exit(CJSON::encode(array("msg"=>"error","message"=>"请输入正确的数量")));
                }else{
                    //判断商品是否存在
                    $goods_model = new ShopGoods();
                    $goods_data = $goods_model->findByPk($goods_id);
                    if(empty($goods_data)) {
                        exit(CJSON::encode(array("msg" => "error", "message" => "商品不存在")));
                    }elseif($goods_data['kucun']<$goods_num) {
                        exit(CJSON::encode(array("msg" => "error", "message" => "商品库存不足")));
                    }elseif(strtotime($goods_data['down_datetime'])<time()){
                        exit(CJSON::encode(array("msg" => "error", "message" => "商品已下架")));
                    }else{
                        $uid = $this->uid;
                        $member_data = $this->loadMember($uid);
                        //需要消费的积分
                        $credits_count = $goods_data['credits']*$goods_num;
                        //判断用户积分是否够用
                        if($member_data['credits'] >= $credits_count){
                            //获取地址信息
                            $member_location_data = $this->loadMemberLocation($location_id);
                            if(empty($member_location_data)){
                                //地址不存在
                                exit(CJSON::encode(array("msg"=>"error","message"=>"请选择正确的收货地址")));
                            }else{
                                if($member_location_data['mid']!=$uid){
                                    exit(CJSON::encode(array("msg"=>"error","message"=>"请选择正确的收货地址")));
                                }else{
                                    //提交订单 扣除积分   数据回滚
                                    $transaction = Yii::app()->db->beginTransaction(); //创建事务
                                    try{
                                        //$model->getErrors();
                                        $order_model = new ShopGoodsOrder();
                                        $order_model -> gid = $goods_id;
                                        $order_model -> mid = $uid;
                                        $order_model -> create_datetime = time();
                                        $order_model -> update_datetime =time();
                                        $order_model -> status = 0;
                                        $order_model -> remarks = $remarks;
                                        $order_model -> num = $goods_num;
                                        $order_model -> credits = $goods_data['credits'];
                                        $order_model -> realcredits = $credits_count;
                                        $order_model -> gname = $goods_data['title'];
                                        //收货信息
                                        $order_model -> address = $member_location_data['address'];
                                        $order_model -> tel = $member_location_data['tel'];
                                        $order_model -> username = $member_location_data['name'];
                                        $order_model -> mailname = "--";
                                        $order_model -> mailcode = "--";
                                        if($order_model->save()){
                                            //扣除用户积分
                                            Member::model()->updateCounters(array("credits"=>"-".$credits_count),"id={$uid}");
                                            //增加销量
                                            ShopGoods::model()->updateCounters(array("num"=>1),"id={$goods_id}");
                                            //减少库存
                                            ShopGoods::model()->updateCounters(array("kucun"=>"-".$goods_num),"id={$goods_id}");
                                            //积分日志
                                            $member_credits_model = new MemberCreditsLog();
                                            $member_credits_model->create_datetime=time();
                                            $member_credits_model->memberId = $uid;
                                            $member_credits_model->credits ="-".$credits_count;
                                            $member_credits_model->remarks= "兑换商品{$goods_data['title']}，数量{$goods_num}";
                                            $member_credits_model->account_credits =$member_data['credits'] -$credits_count;
                                            $member_credits_model->source = "shop";
                                            $member_credits_model->save();
                                            echo CJSON::encode(array("msg"=>"ok","message"=>"恭喜你，兑换成功！"));
                                        }else{
                                            //echo $order_model->getError("remarks");
                                            echo CJSON::encode(array("msg"=>"error","message"=>"提交失败，请重新提交"));
                                        }
                                        $transaction->commit(); //提交事务
                                    }catch(Exception $e){
                                        $transaction->rollback(); //回滚事务
                                        exit(CJSON::encode(array("msg"=>"error","message"=>"提交失败，请重新提交~")));
                                    }
                                }
                            }

                        }else{
                            exit(CJSON::encode(array("msg"=>"error","message"=>"您的积分不够啦")));
                        }
                    }
                }
            }else{
                //没有接收到值
                exit(CJSON::encode(array("msg"=>"error","message"=>"发生错误")));
            }
        }else{
            exit(CJSON::encode(array("msg"=>"error","message"=>"发生错误~")));
        }

    }
    /*
     * 订单列表
     * */
    public function actionOrder()
    {
        $uid = $this->uid;
        $goods_order_model = new ShopGoodsOrder();
        $criteria = new CDbCriteria();
        $criteria -> condition = "`mid`={$uid}";
        $criteria -> order = "id DESC";

        $count = $goods_order_model -> count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);
        $data = $goods_order_model -> findAll($criteria);
        $this->render("order_index",array("data"=>$data,"pages"=>$pager));
    }
    /**
     * 请编辑收货地址
     */
    public function actionDrawAddress()
    {
        $uid = $this->uid;
        $model = new ShopGoodsOrder();
        if(isset($_POST['ShopGoodsOrder'])){
            foreach($_POST['ShopGoodsOrder'] as $_k => $_v){
                $arr[$_k] = $_v;
            }
            $data = $model->findAllByAttributes(array ('gid' => 0, 'mid' => $uid));
            $count = $model->updateAll(array('username'=>$arr['username'],'tel'=>$arr['tel'],'address'=>$arr['address']),'id=:id', array(':id'=>$data[0]['id']));
            if($count>0){
//                throw new CHttpException("/member/shop/order/index","编辑地址成功");
                $this->redirect("/member/shop/order/index");
            }
        }
        $this->render("draw_address",array('model'=>$model));
    }

    /*
     * 订单详情
     * */
    public function actionOrderdetail($id)
    {
        $uid = $this->uid;
        $goods_order_model = new ShopGoodsOrder();
        $goods_order_data = $goods_order_model->findByPk($id);
        if(empty($goods_order_data)){
            throw new CHttpException(404,"该订单不存在");
        }else{
            if($goods_order_data['mid']==$uid){
                $this->render("order_detail",array("data"=>$goods_order_data));
            }else{
                throw new CHttpException(403,"您无权查看该订单信息");
            }
        }
    }
    /*
     * 用户信息查询
     * */
    private function loadMember($id)
    {
        $member_model = new Member();
        $member_data = $member_model->findByPk($id);
        return $member_data;
    }
    /*
     * 收货地址
     * */
    private function loadMemberLocation($id)
    {
        $location_model = new MemberLocation();
        $location_data = $location_model->findByPk($id);
        return $location_data;
    }


}