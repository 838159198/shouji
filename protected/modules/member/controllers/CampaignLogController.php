<?php
class CampaignLogController extends MemberController
{
    //会员后台报名
    public function actionCommit($id)
    {
        header('content-type:text/html;charset=utf-8;');
        $user = Yii::app()->user;
        $uid=$user->getState('member_uid');

        if(empty($id))
        {
            echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('数据异常')){window.location.href='/member/product/campaignindex'}</script>";
        }
        $campaign = Campaign::model()->find('periods=:periods',array(':periods'=>$id));
        $member=Member::model()->findByPk($uid);
        $memberbill=MemberBill::model()->find('uid='.$uid);
        $count_days=$this->count_days(time(),$member["jointime"]);

        if(time()>strtotime($campaign["userendtime"]))
        {
            echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('报名已截止')){window.location.href='/member/product/campaignindex'}</script>";
        }
        elseif($member["type"]!=0)
        {
            echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('您的身份不能参与活动')){window.location.href='/member/product/campaignindex'}</script>";
        }
/*        elseif($count_days<30)
        {
            echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('注册未满30天不能参与')){window.location.href='/member/product/campaignindex'}</script>";
        }*/
/*        elseif(empty($memberbill) || $memberbill["paid"]<=0)
        {
            echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('未在平台有支付记录不能参与')){window.location.href='/member/product/campaignindex'}</script>";
        }*/
        else
        {
            $ucamplog=new CampaignLog();
            $ucamplog->cid=$id;
            $ucamplog->uid=$uid;
            $ucamplog->pid=$campaign["pid"];
            $ucamplog->title=$campaign["title"];
            $ucamplog->status=0;
            $ucamplog->createtime=date("Y-m-d H:i:s",time());

            if($ucamplog->save ()>0){
                echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('已提交报名，待审核')){window.location.href='/member/product/campaignindex'}</script>";
            }else{
                echo "<script language='javascript' charset='UTF-8' type='text/javascript'>if(confirm('报名失败')){window.location.href='/member/product/campaignindex'}</script>";
            }
        }


    }

    //活动页报名
    public function actionAjaxCommit()
    {
        header('content-type:text/html;charset=utf-8;');
        $user = Yii::app()->user;
        $uid=$user->getState('member_uid');
        if(empty($uid)){
            echo CJSON::encode(array('masszge' => "非法操作"));
            exit ();
        }
        if (Yii::app()->request->isAjaxRequest) {
            $id = ( int )Yii::app()->request->getParam('id');
            $campaign = Campaign::model()->find('periods=:periods',array(':periods'=>$id));
            $member=Member::model()->findByPk($uid);
            $memberbill=MemberBill::model()->find('uid='.$uid);
            $count_days=$this->count_days(time(),$member["jointime"]);

            if(time()>strtotime($campaign["userendtime"]))
            {
                echo CJSON::encode(array('masszge' => "报名已截止"));
                exit ();
            }
            elseif($member["type"]!=0)
            {
                echo CJSON::encode(array('masszge' => "您的身份不能参与活动"));
                exit ();
            }
/*            elseif($count_days<30)
            {
                echo CJSON::encode(array('masszge' => "注册未满30天不能参与"));
                exit ();
            }*/
/*            elseif(empty($memberbill) || $memberbill["paid"]<=0)
            {
                echo CJSON::encode(array('masszge' => "未在平台有支付记录不能参与"));
                exit ();
            }*/
            else
            {
                $ucamplog=new CampaignLog();
                $ucamplog->cid=$id;
                $ucamplog->uid=$uid;
                $ucamplog->pid=$campaign["pid"];
                $ucamplog->title=$campaign["title"];
                $ucamplog->status=0;
                $ucamplog->createtime=date("Y-m-d H:i:s",time());

                if($ucamplog->save ()>0){
                    echo CJSON::encode(array('masszge' => "21"));
                    exit ();
                }else{
                    echo CJSON::encode(array('masszge' => "报名失败"));
                    exit ();
                }
            }
        }



    }

    public  function count_days($a,$b){
        $a_dt=getdate($a);
        $b_dt=getdate($b);
        $a_new=mktime(12,0,0,$a_dt['mon'],$a_dt['mday'],$a_dt['year']);
        $b_new=mktime(12,0,0,$b_dt['mon'],$b_dt['mday'],$b_dt['year']);
        return round(abs($a_new-$b_new)/86400);
    }

}