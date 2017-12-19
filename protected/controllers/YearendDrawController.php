<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/10
 * Time: 15:25
 */
class YearendDrawController extends Controller
{
//    public $layout = false;
    
    public function actionIndex(){
        $this->pageTitle='速推2017感恩回馈';
        Yii::app()->clientScript->registerMetaTag('速推2017感恩回馈','keywords');
        Yii::app()->clientScript->registerMetaTag('速推app推广平台2017年感恩回馈,年货免费抽回家,赶快来参加吧','description');
        $this->render("index");
    }







    // 点击抽奖ajax返回数据
    public function actionDrawAjax(){


        if (Yii::app()->request->isAjaxRequest) {
            //先判断用户是否登录

            if(Yii::app()->user->isGuest)
            {
                //游客
                exit(CJSON::encode(array("val"=>"fail","message"=>"请先登录后再抽取奖品哦！")));
            }else{
                // 获取用户信息
                $userInfo = Yii::app()->user;
                if (empty($userInfo->member_uid)){
                    exit(CJSON::encode(array("val"=>"fail","message"=>"请先登录后再抽取奖品哦！")));
                }else{
                    $currentTime = date('Ymd',time());
                    // 活动日期截止到20170120
                    if ((int)$currentTime>=20170120){
                     exit(CJSON::encode(array("val"=>"1","message"=>"很遗憾!活动日期已过!")));
                    }
                    $uid = $userInfo->member_uid;
                    $username = $userInfo->name;
                    // 抽奖记录
                    $drawModel = new YearendDraw();
                    $data = $drawModel->findAll('uid=:uid',array(':uid'=>$uid));
                    if (empty($data)){
                        $num = $this->getUser_Prize($username);
                        $arr = $this->getprobabilityMethod($num);

                        $drawModel->uid = $uid;
                        $drawModel->username = $username;
                        $drawModel->draw = $arr[1].$arr[2];
                        $drawModel->createtime = time();
                        if ($drawModel->insert()){
                            // 积分日志
                            if ($num == 4 || $num ==5 ||$num==6){
                                $cre = str_replace('积分','',$arr[2]);
                                $member_data = Member::model()->findAll('id=:id',array(':id'=>$uid));
                                $member_credits_model = new MemberCreditsLog();
                                $member_credits_model->create_datetime=time();
                                $member_credits_model->memberId = $uid;
                                $member_credits_model->credits ="+".$cre;
                                $member_credits_model->remarks= "新年礼包抽抽抽,抽到{$cre}";
                                $member_credits_model->account_credits =$member_data[0]['credits'] +$cre;
                                $member_credits_model->source = "system";
                                $member_credits_model->save();
                                // 总积分修改
                                Member::model()->updateCounters(array('credits'=>+$cre),'id=:id',array(':id'=>$uid));

                            }else{
                                // 存入订单列表
                                $shopGoodOrder_model = new ShopGoodsOrder();
                                $shopGoodOrder_model->gid = 0;
                                $shopGoodOrder_model->mid = $uid;
                                $shopGoodOrder_model->gname = $arr[1].$arr[2];
                                $shopGoodOrder_model->create_datetime = time();
                                $shopGoodOrder_model->update_datetime = time();
                                $shopGoodOrder_model->num = 1;
                                $shopGoodOrder_model->insert();
                            }
                        }



                       $link = "";
                        // 判断用户类型
                            if($userInfo->getState('type')=="Member" || $userInfo->getState('type')=="Agent" || $userInfo->getState('type')=="Synthsize"){
                                $link = "/member/shop/order/index";
                            }elseif ($userInfo->getState("type")=="Ditui"){
                                $link="/";//ditui后台没有订单列表
                            }elseif ($userInfo->getState("type")=="Newdt"){
                                $link="/";
                            }elseif ($userInfo->getState("type")=="Dealer"){
                                $link = "/dealer/shop/order/index";
                            }elseif ($userInfo->getState("type")=="Msg" || $userInfo->getState("type")=="Other"){
                                $link = "/";
                            }elseif ($userInfo->getState("type")=="Manage"){
                                $link = "/";
                            }else{

                            }

                        echo CJSON::encode(array('val' => $arr,'link'=>$link));
                    }else{
                        exit(CJSON::encode(array("val"=>"1","message"=>"您已经抽取过奖品,每人限抽一次!")));
                    }

                }
            }

        }
    }

    // 奖品设置,确定用户获取几等奖
    private function getUser_Prize($username){
/*        $firstPrice = array('worldlet','cwz1996','loner','pixle','machao44','chigy2012','ycjeson','lenovohome','xxsnzw','diors007');
        $secondPrize=array('mengzhiwei','017118','liutao2012201','lejia163','yangzhe9211','mn2378');
        $thirdPrize=array('kanis','dgtl198312','atsor','123077518','d1860','15695929064','sajangnim','zhwlgzs','wy124','wazls');*/
        $fourthPrize=array('haohao3344','qazccvb0708','qwerty','hxhhh','lcz1490372326','taoxiaofan168','xx_022','yiran88','290130400','lanswxg','zhdroid','xx_009','xiangjiaozai','aochen','moonlight','lovelu','azlinxy');
        $fifthPrize=array('xx_016','verchem','kaka44444','yudong2','qy520','william','bstaryou','18299874784','zhongguo','xx_003','nick95914','anxiaozhu1005','super_lin1995','romer333','sydk001','18130018116','hqapk','zhang67697544','xx_018','250543222','liaerbing','xiao_dong','e82529726','weincheng','fengyuanpeng');
        $username = strtolower($username);// 把大写全部转为小写
/*        if (in_array($username,$firstPrice)){
            return 1;
        }elseif (in_array($username,$secondPrize)){
            return 2;
        }
        if (in_array($username,$thirdPrize)){
            return 3;
        }else*/
        if (in_array($username,$fourthPrize)){
            return 4;
        }elseif (in_array($username,$fifthPrize)){
            return 5;
        }else{
            return 6;
        }


    }
    
    
    // 抽奖概率方法计算
    private function  getprobabilityMethod($rnd){
        $arr = array();
        $angles="";
        $txt="";
        $price = "";

        switch ($rnd){
            case 1:
                $angles = 300;
                $txt = '一等奖';
                $price ='海鲜大礼盒';
                break;
            case 2:
                $angles = 180;
                $txt = '二等奖';
                $price ='海鲜小礼盒';
                break;
            case 3:
                $angles = 62;
                $txt = '三等奖';
                $price ='50元话费';
                break;
            case 4:
                $angles = 120;
                $txt = '四等奖';
                $price ='3000积分';
                break;
            case 5:
                $angles = 358;
                $txt = '五等奖';
                $price ='1000积分';
                break;
            case 6:
                $angles = 240;
                $txt = '六等奖';
                $price ='500积分';
                break;
        }

        $arr[] = $angles;
        $arr[] = $txt;
        $arr[] = $price;
        return $arr;
    }
    
}