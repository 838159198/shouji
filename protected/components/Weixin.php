<?php
/**
 * 微信
 */
class Weixin{
    /*
     * 微信公共部分
     * */
    CONST APP_ID = "wx41668cbb5ab78db6";//应用ID
    CONST APP_SECRET = "94e6c9555dd28f4cc4f88c4674476e83";//应用密钥
    CONST TOKEN = "sutui3324157389";
    CONST WX_WEB = "http://www.sutuiapp.com/";
    /*
     * 获取token
     * */
    public static function getToken(){
        $time = time();
        $tokenModel = new WeixinToken();
        $tokenData = $tokenModel->find(array('order' => 'id DESC'));
        if($tokenData && ($time - $tokenData['create_times'] < 1800 )){
            return $tokenData['access_token'];
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token";
            $params = array("grant_type" => "client_credential",
                "appid" => self::APP_ID,
                "secret" => self::APP_SECRET
            );
            $resultJson = self::curlGet("GET",$url,$params);
            //$url = $url."?".http_build_query($params);
            //$resultJson = file_get_contents($url."?".http_build_query($params));
            $resultArray = json_decode($resultJson,true);

            if(is_array($resultArray) && array_key_exists( "access_token" , $resultArray )){
                //提交到数据库
                $tokenModel = new WeixinToken();
                $tokenModel -> access_token = $resultArray['access_token'];
                $tokenModel -> create_times = $time;
                $tokenModel -> save();
                return $resultArray['access_token'];
            }else{
                return "请求失败";
            }
        }
    }
    /*
     * CURL GET
     * */
    public static function curlGet($method,$url,$param=array()){
        //$url = "http://apitest.921zs.com/".$url;
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        if($method == "GET"){
            $url = $url."?".http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //GET
        if($method == "GET"){
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        //POST
        if($method == "POST"){
            $post_data = $param;
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
    /*
     * 用户绑定查询
     * */
    public static function bindUser($userid){
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if($bindData){
            return $bindData;
        }else{
            return false;
        }
    }
    /*
     * 获取用户信息
     * http请求方式: GET
     * https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
     * */
    public static function getUserData($openid,$token){
        $jsonData = self::curlGet("GET","https://api.weixin.qq.com/cgi-bin/user/info",
            array("access_token"=>$token,
                "openid"=>$openid,
                "lang"=>"zh_CN")
        );
        $arrayData = CJSON::decode($jsonData,true);
        /*if(!array_key_exists("errcode",$arrayData)){

        }*/
        return $arrayData;
    }

    /*
    * 获取分组信息
    * http请求方式: GET
    *https://api.weixin.qq.com/cgi-bin/groups/get?access_token=ACCESS_TOKEN
    * */
    public static function getGroupsData($token){
        $jsonData = self::curlGet("GET","https://api.weixin.qq.com/cgi-bin/groups/get",
            array("access_token"=>$token)
        );
        $arrayData = CJSON::decode($jsonData,true);
        /*if(!array_key_exists("errcode",$arrayData)){

        }*/
        return $arrayData;
    }
    /*
    * 指定用户分组
    * http请求方式: POST
    *https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=ACCESS_TOKEN
    * */
    public static function moveGroups($token,$openid,$to_groupid){
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token,json_encode(array("openid"=>$openid,"to_groupid"=>$to_groupid)));
        $arrayData = CJSON::decode($result,true);
        /*if(!array_key_exists("errcode",$arrayData)){

        }*/
        return $arrayData;
    }
    /*
     * 回复文本消息
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140543&token=&lang=zh_CN
     * */
    public static function handleText($object,$content,$flag=0){
        $tpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                </xml>";
        return sprintf($tpl, $object->FromUserName, $object->ToUserName, time(), $content,$flag);
    }
    /*
     * 回复图片消息
     * */
    public static function handlePic($object,$MediaId){
        $tpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                        <MediaId><![CDATA[%d]]></MediaId>
                    </Image>
                </xml>";
        return sprintf($tpl,$object->FromUserName, $object->ToUserName, time(),$MediaId);
    }
    /*
     * 回复语音消息
     * */
    public static function handleVoice($object,$MediaId){
        $tpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>
                </xml>";
        return sprintf($tpl,$object->FromUserName, $object->ToUserName, time(),$MediaId);
    }
    /*
     * 回复视频消息
     * */
    public static function handleVideo($object,$MediaId,$title,$description){
        $tpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                    </Video> 
                </xml>";
        return sprintf($tpl,$object->FromUserName, $object->ToUserName, time(),$MediaId,$title,$description);
    }
    /*
     * 回复音乐消息
     * */
    public static function handleMusic($object,$ThumbMediaId,$title="",$description="",$music_url="",$hq_music_url=""){
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[music]]></MsgType>
                <Music>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <MusicUrl><![CDATA[%s]]></MusicUrl>
                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                </Music>
                </xml>";
        return sprintf($tpl,$object->FromUserName, $object->ToUserName, time(),$title,$description,$music_url,$hq_music_url,$ThumbMediaId);
    }
    /*
     * 回复图文消息
     * */
    public static function handlePicText(){
        $tpl = "<xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>12345678</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>2</ArticleCount>
                    <Articles>
                        <item>
                            <Title><![CDATA[title1]]></Title> 
                            <Description><![CDATA[description1]]></Description>
                            <PicUrl><![CDATA[picurl]]></PicUrl>
                            <Url><![CDATA[url]]></Url>
                        </item>
                        <item>
                            <Title><![CDATA[title]]></Title>
                            <Description><![CDATA[description]]></Description>
                            <PicUrl><![CDATA[picurl]]></PicUrl>
                            <Url><![CDATA[url]]></Url>
                        </item>
                    </Articles>
                </xml>";
    }
    /**
     * 模板消息接口--上下架通知
     * @return mixed
     */
    public static function handleTemplateByProduct($product_name,$content,$date,$openid,$username,$status,$product_type="增值业务")//发送模板消息
    {
        $json['touser'] = $openid;
        $json['template_id'] = "QoABHMlVnVl36xxqYDThexBWMzksgdY_siF0anNAuF0";
        //$json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的用户您好，{$status}通知\n";
        $json['data']['first']['color'] = "#666666";
        $json['data']['keyword1']['value'] = $product_type;//产品类型：
        $json['data']['keyword1']['color'] = "#333333";
        $json['data']['keyword2']['value'] = $product_name;//产品名称：
        $json['data']['keyword2']['color'] = "#ff0000";
        $json['data']['keyword3']['value'] = $date;//时间：
        $json['data']['keyword3']['color'] = "#333333";
        $json['data']['keyword4']['value'] = $status;//状态：
        $json['data']['keyword4']['color'] = "#ff0000";
        $json['data']['remark']['value'] = "\n\n{$content}\n\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
    /*
     * 模板消息：A
     * */
    public static function handleTemplateA(){
        $json['touser'] = "oFspSwazn4KfZCZkMsa23R7WlmLI";
        $json['template_id'] = "9oc0YBCeSl7EGeHJ1m97JOzNeNLAIiLTPb_Gb0drLuM";
        $json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "账户资产收益统计";
        $json['data']['first']['color'] = "#173177";
        $json['data']['keyword1']['value'] = "100.00";
        $json['data']['keyword1']['color'] = "#173177";
        $json['data']['keyword2']['value'] = "200.00";
        $json['data']['keyword2']['color'] = "#0000ff";
        $json['data']['keyword3']['value'] = "360.12";
        $json['data']['keyword3']['color'] = "#ff0000";
        $json['data']['remark']['value'] = "感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：每天收益通知 -收益发放通知
     * */
    public static function handleTemplateB($touser,$money,$date){
        $json['touser'] = $touser;
        $json['template_id'] = "wimLeNpcokFKQn8uRtVIPQ0PhpvEee5wbqMm0zzHLjM";
        $json['url'] = "http://www.sutuiapp.com/weixin/web/yesdaysEarnings?date=".$date."&weixin_openid=".$touser;
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的用户，日预估收益已发布\n";
        $json['data']['first']['color'] = "#666666";
        $json['data']['keyword1']['value'] = $money."元";//收益金额：
        $json['data']['keyword1']['color'] = "#ff0000";
        $json['data']['keyword2']['value'] =$date ;//到账时间：
        $json['data']['keyword2']['color'] = "#333333";
        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：上月收益通知 -收益发放通知
     * */
    public static function handleTemplateMonthIncome($touser,$money,$username){
        $date=date('Y-m-d H:i:s');
        $json['touser'] = $touser;
        $json['template_id'] = "wimLeNpcokFKQn8uRtVIPQ0PhpvEee5wbqMm0zzHLjM";
       // $json['url'] = "http://www.sutuiapp.com/weixin/web/yesdaysEarnings?date=".$date."&weixin_openid=".$touser;
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的{$username}，上月收益已发放\n";
        $json['data']['first']['color'] = "#666666";
        $json['data']['keyword1']['value'] = $money."元";//收益金额：
        $json['data']['keyword1']['color'] = "#ff0000";
        $json['data']['keyword2']['value'] =$date ;//到账时间：
        $json['data']['keyword2']['color'] = "#333333";
        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：提现成功通知
     * */
    public static function handleTemplateC($touser,$username,$bank,$bankNo,$holder,$money){
        $json['touser'] = $touser;
        $json['template_id'] = "giZpyFawA__kC3wXGj0GPb3XD176BjJuacptp6kbS9g";
        //$json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的{$username}，您已申请提现成功。\n";
        $json['data']['first']['color'] = "#666666";
        $json['data']['keyword1']['value'] = $bank;//提现银行：
        $json['data']['keyword1']['color'] = "#333333";
        $json['data']['keyword2']['value'] = substr($bankNo,0,2)."***".substr($bankNo,-6);//银行卡号：
        $json['data']['keyword2']['color'] = "#333333";
        $json['data']['keyword3']['value'] = $holder;//开户人：
        $json['data']['keyword3']['color'] = "#333333";
        $json['data']['keyword4']['value'] = $money;//提现金额：
        $json['data']['keyword4']['color'] = "#ff0000";
        $json['data']['keyword5']['value'] = date("Y-m-d H:i:s");//时间：
        $json['data']['keyword5']['color'] = "#333333";
        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：登录通知
     * */
    public static function handleTemplateByLogin($touser,$username,$ip){
        $json['touser'] = $touser;
        $json['template_id'] = "duWCFjjOOP4kEDfF9Iv5CsoGqydYfAdW1yJ-i4vHzXc";
        //$json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "您好，您的账号已成功登录速推联盟！\n";
        $json['data']['first']['color'] = "#333333";
        $json['data']['keyword1']['value'] = $username;//登录账号：
        $json['data']['keyword1']['color'] = "#333333";
        $json['data']['keyword2']['value'] = $ip;//登录ip：
        $json['data']['keyword2']['color'] = "#333333";
        $json['data']['keyword3']['value'] = date("Y-m-d H:i:s");//登录时间：
        $json['data']['keyword3']['color'] = "#ff0000";
        $json['data']['remark']['value'] = "\n※速推APP推广联盟安全提示：如非本人操作，请及时联系客服人员。";
        $json['data']['remark']['color'] = "#333333";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：密码修改通知
     * */
    public static function handleTemplateByPassword($touser,$username,$ip="127.0.0.1"){
        $json['touser'] = $touser;
        $json['template_id'] = "lDd3qvZFS8QTyeR7HYehInxDOvofn7ipbwBcWQI7eRU";
        //$json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的{$username}，您的账户密码已被修改，请知悉并确定这是您本人的操作。\n";
        $json['data']['first']['color'] = "#333333";
        $json['data']['keyword1']['value'] = date("Y-m-d H:i:s");//修改时间：
        $json['data']['keyword1']['color'] = "#ff0000";
        $json['data']['keyword2']['value'] = $ip;//登录ip：
        $json['data']['keyword2']['color'] = "#0000ff";

        $json['data']['remark']['value'] = "\n※速推APP推广联盟安全提示：如非本人操作，请及时联系客服人员。";
        $json['data']['remark']['color'] = "#333333";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：积分商城-发货通知
     * */
    public static function handleTemplateByShopSend($touser,$username,$shop_title,$credits,$orderNo){
        $json['touser'] = $touser;
        $json['template_id'] = "OMY22GSf54oIoYUF8EXhhs8mm7e_GFUOdZGfm_tHAMM";
        //$json['url'] = "http://www.sutuiapp.com";
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的{$username}，您在积分商城兑换的商品已发货，请注意查收。\n";
        $json['data']['first']['color'] = "#333333";
        $json['data']['keyword1']['value'] = $orderNo;////订单号：
        $json['data']['keyword1']['color'] = "#333333";
        $json['data']['keyword2']['value'] = $shop_title;//商品名称：
        $json['data']['keyword2']['color'] = "#333333";
        $json['data']['keyword3']['value'] = $credits."积分";//消耗积分：
        $json['data']['keyword3']['color'] = "#333333";

        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：可提现提醒
     * */
    public static function handleTemplateByWithdraw($touser="oFspSwfA8h19mhasevdGsYqZQu8o",$username="zhangkaining",$money="123.00"){
        $json['touser'] = $touser;
        $json['template_id'] = "MSMVq9RHlh8MTgn20viBQvDoYW3d5DVTI7dXkddXDYw";
        $json['url'] = self::WX_WEB."weixin/web/withdraw?userid=".$touser;
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的{$username}，您有余额可提现。\n";
        $json['data']['first']['color'] = "#333333";
        $json['data']['keyword1']['value'] = $money."元";////可提现金额：：
        $json['data']['keyword1']['color'] = "#ff0000";
        $json['data']['keyword2']['value'] = "每月11日~15日";//可提现时间：
        $json['data']['keyword2']['color'] = "#333333";

        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如有疑问请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 模板消息：打款通知
     * */
    public static function handleTemplateByPay($touser,$username,$money,$month){
        $json['touser'] = $touser;
        $json['template_id'] = "zW1po7RH2QsBmiPeGffp9AaAUT0CVaosYgHu-2xBad0";
        //$json['url'] = self::WX_WEB."weixin/web/withdraw?userid=".$touser;
        $json['data'] = array();
        $json['data']['first']['value'] = "尊敬的用户，您申请的提现已打款，请注意查收。\n";
        $json['data']['first']['color'] = "#333333";
        $json['data']['keyword1']['value'] = $username;////用户ID：：
        $json['data']['keyword1']['color'] = "#333333";
        $json['data']['keyword2']['value'] = $money;//打款金额：
        $json['data']['keyword2']['color'] = "#ff0000";
        $json['data']['keyword3']['value'] = date("Y-m-d H:i:s");//打款时间：
        $json['data']['keyword3']['color'] = "#333333";

        $json['data']['remark']['value'] = "\n感谢您使用速推APP推广联盟，如在2个工作日内没有收到付款，请与客服人员联系。";
        $json['data']['remark']['color'] = "#666666";
        $jsonData = CJSON::encode($json);
        $result = self::curlGet("POST","https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".self::getToken(),$jsonData);
        //return $result;
        $resultArray = CJSON::decode($result,true);
        if(is_array($resultArray) && array_key_exists("errcode",$resultArray)){
            if($resultArray['errcode'] == 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * 截取字符串
     * Helper::truncate_utf8_string($content,20,false); //不显示省略号
     * Helper::truncate_utf8_string($content,20); //显示省略号
     * */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }
    /*
     * 时间转成xx分钟前、xx小时前、xx天前
     * */
    public static function time_tran($the_time=0){
        $now_time = time();
        $dur = $now_time - $the_time;
        if($dur < 60){
            return $dur.'秒前';
        }else{
            if($dur < 3600){
                return floor($dur/60).'分钟前';
            }else{
                if($dur < 86400){
                    return floor($dur/3600).'小时前';
                }else{
                    if($dur < 25920000){//300天内
                        return floor($dur/86400).'天前';
                    }else{
                        return date("Y-m-d",$the_time);
                    }
                }
            }
        }
    }
}