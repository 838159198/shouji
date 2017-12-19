<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/5
 * Time: 11:31
 */
class ApiCommon extends Controller{
    /*
     * Name : 返回参数不正确消息
     * */
    protected function ReturnError($error=""){
        $data = array();
        $data['appList'] = array();
        $data['stateCode'] = '2';
        $data['stateMsg'] = ($error=="")?"参数错误":$error;
        $this->ReturnJson($data);
    }
    /*
     * Name : 数据转换成json格式
     * */
    protected function ReturnJson($data){
        exit(CJSON::encode($data));
    }

    /*
     * Name : 短信接口
     * */
    protected function SmsApi($tel,$text)
    {
        $url='http://sms.webchinese.cn/web_api/?Uid=awangba&Key=7ba1d4d297a32b67d184&smsMob='.$tel.'&smsText='.$text;
        echo $this->Get($url);
    }

    function Get($url)
    {
        if(function_exists('file_get_contents'))
        {
            $file_contents = file_get_contents($url);
        }
        else
        {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }


}