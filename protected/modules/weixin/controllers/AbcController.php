<?php
/**
 * 管理操作
 */
class AbcController extends Controller {
    public $token;
    public function init()
    {
        $this->token = Weixin::getToken();
    }
    public function actionIndex(){
        var_dump($this->token);
    }
    /*
     * 创建菜单
     * https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
     * */
    public function actionCreateMenu(){

        $data = '{
                 "button":[
                 
                  {	
                      "name":"速来报道",
                      "sub_button":[
                       {	
                           "type":"click",
                           "name":"🎁精彩活动",
                           "key":"ST_KEY_1003"
                        }]
                  },
                  {
                       "name":"小推部落",
                       "sub_button":[
                       {	
                           "type":"view",
                           "name":"📢公告",
                           "url":"http://www.sutuiapp.com/weixin/web/notice"
                        },
                        {
                           "type":"click",
                           "name":"站内信",
                           "key":"ST_KEY_1002"
                        },
                        {
                           "type":"click",
                           "name":"联系我们",
                           "key":"ST_KEY_1001"
                        }]
                   },
                   {	
                      "name":"我的钱包",
                      "sub_button":[
                        {
                           "type":"click",
                           "name":"财务信息",
                           "key":"ST_KEY_1005"
                        },
                        {
                           "type":"click",
                           "name":"财务提现",
                           "key":"ST_KEY_1006"
                        },
                        {
                           "type":"click",
                           "name":"历史收益",
                           "key":"ST_KEY_1004"
                        }]
                  }]
             }';
        //echo $data;
        $resultJson = Weixin::curlGet("POST","https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->token,$data);
        $resultArray = CJSON::decode($resultJson,true);
        print_r($resultArray);
    }
}