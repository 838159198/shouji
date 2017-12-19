<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/18
 * Time: 10:25
 */
class HdController extends Controller{
    public $layout = "hd";
    /*
     * 周周赢手机 天天高收益
     * 2016-10-18 10:26:18
     * */
    public function actionZzysj(){
        //echo "1";
        $this->pageTitle = "《周周赢手机 天天高收益》";
        Yii::app()->clientScript->registerMetaTag('《周周赢手机 天天高收益》','keywords');
        Yii::app()->clientScript->registerMetaTag('为回馈长期以来在速推平台稳定推广的老客户，提高各位大大做包的积极性。联盟每周推出一款“新产品”或“人气产品”，活动期内可享受更高收益！','description');
        $this->render("zzysj");
    }
    /*
     * 《周周赢手机 天天高收益》
     * 每期页面不同
     * 2016-10-28 13:07:09
     * */
    public function actionZzysjs(){
        $request = Yii::app()->request;
        $periods = $request -> getParam('periods','');
        $uid=Yii::app()->user->getState('member_uid');

        if(empty($periods)){
            //错误页
            throw new CHttpException(404,"您查看的活动页面不存在");
        }
        $campaignModel = new Campaign();
        $campaignData = $campaignModel -> find("periods=:periods",array(":periods"=>$periods));
        $campaignlog = new CampaignLog();
        $campaignlog = $campaignlog -> find("uid=:uid and cid=:cid",array(":uid"=>$uid,":cid"=>$periods));

        $campaignlogModel = new CampaignLog();
        $campaignlogData = $campaignlogModel -> findAll("cid=:cid",array(":cid"=>$periods));
        foreach($campaignlogData as $ck=>$cv)
        {
            $username=Member::model()->getUsernameByMid($cv["uid"]);
            $campaignlogData[$ck]["uid"]=$username;
        }

        if(!$campaignData){
            throw new CHttpException(404,"您查看的活动页面不存在");
        }
        $this->pageTitle = $campaignData['title'];
        Yii::app()->clientScript->registerMetaTag($campaignData['title'],'keywords');
        Yii::app()->clientScript->registerMetaTag('速推APP推广联盟为回馈长期以来在速推平台稳定推广的老客户，提高各位大大做包的积极性。联盟每周推出一款“新产品”或“人气产品”，活动期内可享受更高收益！','description');
        $this->render("zzysj_".$periods,array('campaignlogData'=>$campaignlogData,'campaignlog'=>$campaignlog,'uid'=>$uid,'periods'=>$periods));
    }
}