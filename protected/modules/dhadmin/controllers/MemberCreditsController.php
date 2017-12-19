<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/6/11
 * Time: 17:55
 * @name 用户积分
 */
class MemberCreditsController extends DhadminController
{
    /**
     * @name 积分日志列表、增加
     */
    public function actionIndex()
    {
        $model = new MemberCreditsLog('logcreate');
        if(isset($_POST['MemberCreditsLog'])){
            foreach($_POST['MemberCreditsLog'] as $_k => $_v){
                $model->$_k = $_v;
            }
            $model -> create_datetime = time();
            $model -> opid = $this->uid;
            if($model->validators){
                //用户信息
                $member_model = Member::model();
                $member_data = $member_model->find("username=:username",array(":username"=>$model->username));
                $model -> memberId = $member_data['id'];
                $model -> account_credits = $member_data['credits'] + $model->credits;
                $model ->source ="system";
                if($model->save()){
                    //更新用户资料积分
                    $member_model->updateCounters(array("credits"=>$model->credits),"id={$member_data['id']}");
                    Yii::app()->user->setFlash("credits_status","积分添加成功");
                    $this->redirect("/dhadmin/memberCredits/index");
                }
            }

        }
        $this->render("index",array("model"=>$model));
    }


    public function actionCheck()
    {
        $credits = $_POST['credits'];
        $remarks = $_POST['remarks'];
        $username = $_POST['username'];
        $usernameArr=explode(",",$username);
        if($remarks=='周周赢手机 天天高收益活动赠送'){
            $remarks="《周周赢手机 天天高收益》活动赠送";
        }
        if(!is_array($usernameArr)){
            exit(CJSON::encode(array("status"=>403,"message"=>"字符串格式有误，请重新填写用户名")));
        }

        foreach($usernameArr as $k=>$v){
            $member_model = Member::model();
            $member_data = $member_model->find("username=:username",array(":username"=>$v));
            if(!$member_data)continue;
            $model = new MemberCreditsLog();
            $model -> memberId = $member_data['id'];
            $model ->remarks =$remarks;
            $model ->credits =$credits;
            $model -> account_credits = $member_data['credits'] + $credits;
            $model -> create_datetime = time();
            $model -> opid = Yii::app()->user->manage_id;
            $model ->source ="system";
            if($model->save()){
                //更新用户资料积分
                $member_model->updateCounters(array("credits"=>$model->credits),"id={$member_data['id']}");
            }
        }
        if($k>=0){
            exit(CJSON::encode(array("status"=>200,"message"=>"积分添加成功")));
        }else{
            exit(CJSON::encode(array("status"=>403,"message"=>"积分添加失败")));
        }




    }
}