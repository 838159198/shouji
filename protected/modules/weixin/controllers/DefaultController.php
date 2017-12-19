<?php

class DefaultController extends Controller
{
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
		$array=array(
			'id'     =>123,
			'name'   =>'dopost'
		);

		echo http_build_query($array);
	}
	private function responseMsg()
	{
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");

		//extract post data
		if (!empty($postStr)){
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$time = time();
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			if(!empty( $keyword ))
			{
				$msgType = "text";
				$contentStr = "Welcome to wechat world!";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else{
				echo "Input something...";
			}

		}else {
			echo "";
			exit;
		}
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