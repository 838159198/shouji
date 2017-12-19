<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/9
 * Time: 10:20
 */
class ApiController extends Controller {
    public function actionIndex(){
        //验证签名
        /*$echostr = Yii::app()->request->getParam("echostr",'');

        if (!empty($echostr)) {
            if( $this->checkSignature() ) {
                echo $echostr;
                exit;
            }
        }else{
            $this->responseMsg();
        }*/
        //$this->responseMsg();
        if(Weixin::handleTemplateByPay()){
            echo "ok";
        }else{
            echo "fail";
        }
        //var_dump(Weixin::handleTemplateA());
        exit;
        echo "hello world!";
    }
    public function actionTencent(){
        //验证签名
        /*$echostr = Yii::app()->request->getParam("echostr",'');

        if( $this->checkSignature() ) {
            echo $echostr;
            exit;
        }*/
        $this->responseMsg();
    }
    private function responseMsg()
    {
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        if (!empty($postStr)){
            //Disable the ability to load external entities
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $post_type = trim($postObj->MsgType);
            switch ($post_type){
                //事件
                case "event":
                    $this->typeEvent($postObj);
                    break;
                //文本消息
                case "text":
                    $this->typeText($postObj);
                    break;
                //图片消息
                case "image":
                    echo Weixin::handleText($postObj,"小速正在努力学习，目前无法识别图片消息。",0);
                    break;
                //语音消息
                case "voice":
                    echo Weixin::handleText($postObj,"小速正在努力学习，目前无法识别语音消息。",0);
                    break;
                //视频消息
                case "video":
                    echo Weixin::handleText($postObj,"小速正在努力学习，目前无法识别视频消息。",0);
                    break;
                //小视频消息
                case "shortvideo":
                    echo Weixin::handleText($postObj,"小速正在努力学习，目前无法识别小视频消息。",0);
                    break;
                //地理位置消息
                case "location":
                    echo Weixin::handleText($postObj,"小速正在努力学习，目前无法识别位置消息。",0);
                    break;
                //链接消息
                case "link":
                    echo Weixin::handleText($postObj,"小速还在学习，还无法识别链接消息。",0);
                    break;
                default:
                    echo "";
                    break;
            }

            /*$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if( $post_type == "event" ) {
                if($postObj->Event == "subscribe" ){
                    $msgType = "text";
                    $contentStr = "感谢关注【速推app推广联盟】微信公众号，微信号：sutuiapp";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    echo Weixin::handleText($postObj,$contentStr,0);
                    exit;
                }
                if($postObj->Event == "CLICK" ){
                    $msgType = "event";
                    if($postObj->EventKey == "ST_KEY_1001"){
                        $a_tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[event]]></MsgType>
                        <Event><![CDATA[CLICK]]></Event>
                        <EventKey><![CDATA[ST_KEY_1001]]></EventKey>
                        </xml>";
                    }
                    $contentStr = "亲爱的用户，你刚刚点击了联系我们";
                    //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo Weixin::handleText($postObj,$contentStr,0);
                    //echo Weixin::handleText($postObj,$contentStr,0);
                    exit;
                }
            }
            if( $post_type == "text" ) {
                $keyword = trim($postObj->Content);
                if(!empty( $keyword ))
                {
                    $msgType = "text";
                    $contentStr = "【".$keyword."】\n我不知道这是什么意思。";
                    //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    $resultStr = Weixin::handleText($postObj,$contentStr,0);
                    echo $resultStr;
                }else{
                    echo "";
                }
            }*/


        }else {
            echo "";
            exit;
        }
    }
    /*
     * 文本回复消息
     * */
    protected function typeText($obj){
        $content = trim($obj->Content);
        $contentStr = "小速还在努力学习ing，您可以联系微信号sutuixiaonuo，让她帮你解答疑惑呦。";
        //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo Weixin::handleText($obj,$contentStr,0);
    }
    /*
     * 类型：事件
     * */
    protected function typeEvent($obj){
        switch ($obj->Event){
            //关注/取消关注事件
            case "subscribe":
                $contentStr = " ^_^尊敬的用户，感谢您关注【速推APP推广联盟】微信公众号，我们的微信号：sutuiapp";
                echo Weixin::handleText($obj,$contentStr,0);
                break;
            //取消关注事件
            case "unsubscribe":
                $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$obj->FromUserName));
                $bindData->weixin_openid="";
                $bindData->weixin_createtimes=null;
                $bindData->save();
                break;
            //自定义菜单事件
            case "CLICK":
                $this->eventKey($obj);
                break;
            default:
                echo "";
        }
        exit;
    }
    /*
     * click 自定义菜单点击事件
     * */
    protected function eventKey($obj){
        switch ($obj->EventKey){
            //联系我们
            case "ST_KEY_1001":
                $contentStr = "联系方式：\n客服QQ：3135539730\n客服电话：4000918058转2\n工作时间：09:00-18:00（工作日）";
                echo Weixin::handleText($obj,$contentStr,0);
                break;
            //站内信
            case "ST_KEY_1002":
                if(Weixin::bindUser($obj->FromUserName)){
                    $contentStr = "站内信功能还未开通";
                    $contentStr = "更多站内信请<a href='".Weixin::WX_WEB."weixin/web/mail?userid=".$obj->FromUserName."'>点击查看</a>。";
                    echo Weixin::handleText($obj,$contentStr,0);
                }else{
                    $this->bindSt($obj);
                }
                break;
            //活动按钮
            case "ST_KEY_1003":
                $contentStr = "亲爱的用户，暂时没有可以参加的活动！";
                echo Weixin::handleText($obj,$contentStr,0);
                break;
            //今日收益
            case "ST_KEY_1004":
                $memberData = Weixin::bindUser($obj->FromUserName);
                if($memberData){
                    $adList = Product::model()->getKeywordList();//全部业务
                    $sql="select date from `app_system_log` where type='UPLOAD' order by id desc limit 1 ";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    $date=$result[0]['date'];
                    for($i=0;$i<7;$i++){//七天
                        $array[]=date('Y-m-d', strtotime('-'.$i.' day', strtotime($date)));
                    }
                    foreach ($array as $key => $value) {
                        //判断业务隐藏开关
                        // $datetime=SystemLog::getLogDate($value);
                        $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
                            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$value));
                        if($model){
                            foreach($model as $v){
                                $key=strtolower($v->target);
                                unset($adList[$key]);
                            }
                        }
                        $day=date('Y-m-d',strtotime('-1 day',strtotime($value)));
                        $data[] = MemberIncome::getDataProviderByDate($memberData->id, $day, $memberData->scale, $adList);
                    }

                    $contentStr = "尊敬的用户，您".$data[0]['dates']."日的收益总额为：".$data[0]['amount']."元<a href='".Weixin::WX_WEB."weixin/web/weeksEarnings?userid=".$memberData->id."&scal=".$memberData->scale."&openid=".$obj->FromUserName."'>点击查看收益详情</a>";
                    echo Weixin::handleText($obj,$contentStr,0);
                }else{
                    $this->bindSt($obj);
                }
                break;
            //收益明细
            case "ST_KEY_1005":
                $memberData = Weixin::bindUser($obj->FromUserName);
                if($memberData){
                    //$userData = Weixin::getUserData($userid,$this->token);
                    //查询会员结款信息
                    $modelBill = MemberBill::model()->getByUid($memberData['id']);
                    //统计会员当月收入合计
                    $date = date('Y-m');
                    $incomeSum = MemberIncome::getSumByMonthSum($date, $memberData['id']);
                    //隐藏收益
                    $hideSum=MemberIncome::getHideIncome($memberData['id']);
                    //将合计数计入结款信息表
                    $modelBill->cy = $incomeSum-$hideSum;
                    $modelBill->save();
                    $cy=$modelBill->cy <=0 ? '0.00':$modelBill->cy;
                    $contentStr = "尊敬的用户，您好：\n\n本月预估收益：".$cy."元\n您的余额：".$modelBill->surplus."元\n已支付金额：".$modelBill->paid."元\n未支付金额：".$modelBill->nopay."元\n\n※温馨提示：速推APP推广联盟每月8-10日发布上个月预估收益，其它时间请在收益明细查看。";
                    //$contentStr = "刚刚点击了收益明细按钮。（时间：2016-10-13）";
                    echo Weixin::handleText($obj,$contentStr,0);
                }else{
                    $this->bindSt($obj);
                }
                break;
            case "ST_KEY_1006":
                if(Weixin::bindUser($obj->FromUserName)){
                    $tixian_date = date("j");
                    //$tixian_date=12;
                    if($tixian_date > 15 || $tixian_date < 10){
                        $contentStr = "联盟每月10-15号申请提现，16-20日统一打款，遇节假日或周休顺延。";
                    }else{
                        $contentStr = "尊敬的用户您好，<a href='".Weixin::WX_WEB."weixin/web/withdraw?userid=".$obj->FromUserName."'>点击此处可进行提现操作</a>。（时间：".date("Y-m-d H:i:s")."）";
                    }

                    echo Weixin::handleText($obj,$contentStr,0);
                }else{
                    $this->bindSt($obj);
                }
                break;
            default:
                echo "";
                break;
        }
    }
    /*
     * 提示绑定账号
     * */
    protected function bindSt($obj){
        $contentStr = "您好，您还未绑定速推APP推广联盟账号，请<a href='".Weixin::WX_WEB."weixin/web/bindAccount?userid=".$obj->FromUserName."'>点击此处绑定</a>，然后再次查询信息。";
        echo Weixin::handleText($obj,$contentStr,0);
    }
    private function checkSignature()
    {
        $signature = Yii::app()->request->getParam("signature","");
        $timestamp = Yii::app()->request->getParam("timestamp","");
        $nonce = Yii::app()->request->getParam("nonce","");

        $token = Weixin::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}