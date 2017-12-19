<?php
class SendMessageController extends DhadminController
{
    /**
     * @name 微信平台
     */
    public function actionWeixinMessage()
    {
        $this->render('weixin_message');
    }
    /**
     * @name 发送数据
     */
    public function actionWeixin(){
        if (Yii::app()->request->isAjaxRequest && isset($_POST['theme']) && isset($_POST['content']) && isset($_POST['date']) && isset($_POST['type']) && isset($_POST['resource'])) {
            $theme = $_POST['theme'];//上下架
            $content =$_POST['content'];
            $date = $_POST['date'];//上下架日期
            $resource = $_POST['resource'];//发送对象
            $type=trim($_POST['type']);//上下架产品
            $product=Product::model()->getByKeyword($type);
            $p_name= $product->name;
            $tt=$this->actionWeixinSj($p_name,$content,$date,$theme,$resource,$type);
            if($tt){
                exit(CJSON::encode(array("status"=>200,"message"=>"发送成功")));
            }else{
                exit(CJSON::encode(array("status"=>403,"message"=>"发送失败")));
            }

        }
    }

    /**
     * @name 调取业务上架微信接口
     */
    protected function actionWeixinSj($p_name,$content,$date,$theme,$resource,$type){

        if($theme==1){
            if($resource==10){
                $data= Member::model()->findAll('status=:status',array(':status'=>1));
            }else{
                $data= Member::model()->findAll('status=:status and type=:type',array(':status'=>1,':type'=>$resource));
            }
        }else{
           $memberRes=MemberResource::model()->findAll('status=:status and openstatus=:openstatus and type=:type',array(':status'=>1,':openstatus'=>1,':type'=>$type));
           foreach($memberRes as $v){
               $data[]=Member::model()->findByPk($v->uid);
           }
        }
      $t=array();
        foreach ($data as $vt){
            if (strlen($vt['weixin_openid']) != 0){
                //推送至微信接口
                $openid = $vt['weixin_openid'];
                $username = $vt['username'];
                $status =$theme==='2' ? '产品下架': '产品上架';
                if(!empty($openid) && $openid!="NULL")
                {
                    $t= Weixin::handleTemplateByProduct($p_name,$content,$date,$openid,$username,$status);//产品上下架模板消息
                    $t= json_decode($t,true);
                }
            }
        }


        if ($t['errcode']==0) {
            $themee = $theme === '2' ? '业务下架' : '业务上架';
            //将发送的消息存到日志表
            $message = MessageLog::model()->noteMessageLog($themee, $content, $p_name);
            if ($message) {
                return true;
            } else {
                return false;
            }
        }

    }

    public function actionYestodayIncome(){
        $this->render('income_message');
    }
    public function actionIncome(){
        if (Yii::app()->request->isAjaxRequest && isset($_POST['date'])) {
            $date = $_POST['date'];//上下架日期
            $data= Member::model()->findAll('status=:status',array(':status'=>1));
            $t=array();
            foreach ($data as $vt){
                if (strlen($vt['weixin_openid']) != 0){
                    //推送至微信接口
                    $openid = $vt['weixin_openid'];
                    //$username = $vt['username'];
                    //昨日收益计算
                    $adList = Product::model()->getKeywordList();
                    $data = MemberIncome::getDataProviderByDate($vt['id'], $date, $vt['scale'], $adList);
                    if(!empty($openid) && $openid!="NULL" && $data['amount']>0)
                    {
                        $t= Weixin::handleTemplateB($openid,$data['amount'],$date);//昨日收益模板消息
                        $t= json_decode($t,true);
                    }
                }
            }
            if($t['errcode']==0){
                //将发送的消息存到日志表
                $theme="{$date}日收益发放通知";
                $username=Yii::app()->user->id;
                $message = new MessageLog();
                $message->theme =$theme;
                $message->username = $username;
                $message->createtime = time();
                $message->insert();
                exit(CJSON::encode(array("status"=>200,"message"=>"发送成功")));
            }else{
                exit(CJSON::encode(array("status"=>403,"message"=>"发送失败")));
            }

        }

    }
}