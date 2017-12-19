<?php

/**
 * 后台首页
 */
class DefaultController extends MemberController
{
	/**
	 * 昨日收入概况
	 */
	public function actionIndex()
	{
        $uid = $this->uid;
		$date = date('Y-m-d', strtotime('-1 day'));
		$adList = Product::model()->getKeywordList();
		$adList = $this->cancelAd($adList);
		$adList[Ad::TYPE_COMMISSION] = '推广提成';
		$data = MemberIncome::getDataProviderByDate($this->uid, $date, $this->scale, $adList);


		$sql = 'SELECT id FROM app_member_bill where uid='.$this->uid.' and surplus<0';

		$memberbill = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($memberbill)){
			$sql_paylog= 'UPDATE app_member_paylog SET valid = 0 where uid='.$this->uid.' order by ask_time desc limit 1';
			$op_paylog = Yii::app()->db->createCommand($sql_paylog)->execute();

			$sql_bill= 'UPDATE app_member_bill SET surplus=(nopay+surplus),nopay = 0 where uid='.$this->uid.'';
			$op_bill = Yii::app()->db->createCommand($sql_bill)->execute();
		}

	//判断是否满足年度足迹条件--已注释掉,影响登录速度,来年可从新启用
//	  $sql1 = "(SELECT * FROM `app_rom_appresource` WHERE `uid` = '{$uid}'  AND `createtime` LIKE '%2016%'  ORDER BY id ASC limit 1)";
//        $installdata1 = Yii::app()->db->createCommand($sql1)->queryAll();
//        $sql2 = "(SELECT * FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 AND `ask_time` LIKE '%2016%' ORDER BY id ASC limit 1)";
//        $installdata2 = Yii::app()->db->createCommand($sql2)->queryAll();
		$installdata1 = array();
		$installdata2 = array();
		$this->render('index', array(
			'data' => $data,
			'memberbill' => $memberbill,
			'adList' => $adList,
            'uid'=>$this->uid,
            'data1' =>$installdata1,
            'data2' =>$installdata2
		));
	}
    /**
     * 用户首页邮件自动展示(ajax)
     *  $mid
     */
    public function actionInfo()
    {

        if (Yii::app()->request->isAjaxRequest) {
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id

            $mail = Mail::model()->findAll('recipient=:recipient and status=:status order by id desc limit 1',array(":recipient"=>$mid,":status"=>0));
            $jointime="";
            if ($mail == null) {
                    exit ();
            }
            else
            {
                $jointime=$mail[0]["jointime"];
                $model= MailContent::model()->findByPk($mail[0]["content"]);

                Mail::model()->updateByPk($mail[0]["id"],array('status'=>1));
                echo CJSON::encode(array('jointime' => $jointime,'title' => $model["title"],'content' => $model["content"]));
                exit ();
            }
        }
    }
	/**
	 * 用户按月收益明细
	 * @param string $m 年月 Y-m
     * @param string $id uid
     * @throws CHttpException
	 */
	public function actionIncome($m = null,$id = null)
	{
		//Script::registerScriptFile('/member/default.income.js');

		//当前查询月
		$date = empty($m) ? date('Y-m') : $m;

		if (DateUtil::dateDiff(date('Y-m-d'), $date) < -93) {
			throw new CHttpException(500, '只统计三个月内的数据');
		}

		$adList = Product::model()->getKeywordList2();

		$adList = $this->cancelAd($adList);

		//$adList[Ad::TYPE_COMMISSION] = '推广提成';
        if(empty($id))
        {
            $arr = MemberIncome::getIncomeListByUidAndDate($this->uid, $date, $this->scale, $adList);
            $uid=$this->uid;
        }
        else
        {
            $arr = MemberIncome::getIncomeListByUidAndDate($id, $date, $this->scale, $adList);
            $uid=$id;

        }
       
		$arr1 = array();
        $sum=0;
        // print_r($arr['data']->rawData);exit;
		foreach ($arr['data']->rawData AS $ak=>$val) {
			foreach ($val AS $key => $item) {
				if (in_array($key, array_keys($adList))) {
                    //判断收益显示:只有开关打开用户平台才正常显示
                    $user = Yii::app()->user;
                    if($user->member_manage===false){
                        $datetime=SystemLog::getLogDate($val['dates']);
                        $model=SystemLog::model()->find("type=:type and date =:date and target=:target and is_show=1 and status=1",
                            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime,":target"=>strtoupper($key)));
                        if($model){
                           $arr['data']->rawData[$ak]['total']='yes'; //有一个
                        }else{

                            $model=SystemLog::model()->find("type=:type and date =:date and target=:target and is_show=0  or type=:type and date =:date and target=:target and status=0",
                                array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime,":target"=>strtoupper($key)));
                            $income=0;
                            if($model){
                                $arr['data']->rawData[$ak]['amount']= round($arr['data']->rawData[$ak]['amount']- $arr['data']->rawData[$ak][$key], 2);
                                
                                $arr['data']->rawData[$ak][$key]='未发布';
                                $income +=$arr['data']->rawData[$ak][$key];
                                $arr['data']->rawData[$ak][$key]='未发布';
                                $item=0;
                            }
                            else{
                                $model=SystemLog::model()->find("type=:type and date =:date and target=:target",
                                array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime,":target"=>strtoupper($key)));
                                if(empty($model)){
                                    $arr['data']->rawData[$ak][$key]='未发布';
                                    // $arr['data']->rawData[$ak]['amount']='未发布';
                                }
                            }  
                        }

                        


                    }
					if (isset($arr1[$key])) {
						$arr1[$key] += $item;
					} else {
						$arr1[$key] = 0;
					}

                    //增加激活量计算
                    if(!in_array($key,array("dates","id","amount","temp")) && $item!="0.00")
                    {
                        $proList = Product::model()->getByKeyword2($key);
                        //代理商69子用户特殊单价
                        $membera=Member::model()->getById($this->uid);
                        if($key=="2345sjzs" && $membera["agent"]==69){$proList["quote"]=2.5;}
                        if($key=="yyzx" && $membera["agent"]==69){$proList["quote"]=2.5;}

                        //$arr['data']->rawData["20151101"][$key]="fsdf";
                       //豌豆荚单价9-13由2.5改为1.5
                       if($key=='wdj'){
                            if(strtotime($val['dates']) >= strtotime('2017-09-13') ){
                                $arr['data']->rawData[$ak][$key]="<span style='color:#0b88fd;font-weight: bold'>".round($item/$proList["quote"],2)."</span>/".$item;
                            }else{
                                $arr['data']->rawData[$ak][$key]="<span style='color:#0b88fd;font-weight: bold'>".round($item/2.5,2)."</span>/".$item;
                            }
                       }else{
                            $arr['data']->rawData[$ak][$key]="<span style='color:#0b88fd;font-weight: bold'>".round($item/$proList["quote"],2)."</span>/".$item;
                       }
                        
                    }

				} else if ($key == 'dates') {
					$arr1['dates'] = '单项业务收益总计';
				}
                else if ($key == 'temp') {
                    $arr1['dates'] = '活动收益';
                }else if ($key == 'amount') {
					if (isset($arr1[$key])) {
						$arr1[$key] += $item;
					} else {
						$arr1[$key] = 0;
					}
				} else {
					$arr1[$key] = 0;
				}
			}
            //计算合计
            $sum += $arr['data']->rawData[$ak]['amount'];
		}
        //推广提成
        if(empty($id))
        {
            $auid=$this->uid;
        }
        else
        {
            $auid = $id;
        }
        $agentincome=0;
        $connection = Yii::app()->db;
        $sql = "SELECT data FROM `app_import_agent_log` where uid={$auid} and `month`='{$date}' limit 1";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if(!empty($result))
        {
            $agentincome=$result[0]['data'];
        }
    

//         /*2017-12-07  业务流id=3321 zlb  begin*/
        $sql="select date from `app_system_log` where type='UPLOAD' order by date desc limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $final=date('Y-m-d',strtotime('-1 day',strtotime($result[0]['date'])));
            foreach ($arr['data']->rawData as $key => $value) {

                    if(!isset($arr['data']->rawData[$key]['total'])){
                        $arr['data']->rawData[$key]['amount']='未发布';
                    }
                    if(strtotime($arr['data']->rawData[$key]['dates'])>strtotime($final)){
                        foreach (array_keys($adList) as $k => $v) {
                            $arr['data']->rawData[$key][$v]='未发布';
                        }
                        $arr['data']->rawData[$key]['amount']='未发布';
                        
                    }
               
                
            } 
        }    

        /*2017-12-07  业务流id=3321 zlb  end*/

        if(empty($id))
        {
            $this->render('income', array(
                'dataProvider' => $arr['data'],
                'sum' => $sum,
                'date' => $date,
                'agentincome' => $agentincome,
                'adList' => $adList,
                'arr1' => $arr1
            ));
        }
        //客服跳转查看
        else
        {
            header('content-type:text/html;charset=utf-8;');
            $this->layout=false;
            $this->render('m_income', array(
                'dataProvider' => $arr['data'],
                'sum' => $arr['sum'],
                'date' => $date,
                'agentincome' => $agentincome,
                'adList' => $adList,
                'id' => $id,
                'arr1' => $arr1
            ));
        }

	}

	/**
	 * 去掉需取消的业务
	 * @param array $adList
	 * @return array
	 */
	private function cancelAd($adList)
	{
		//查看用户是否使用特殊业务
		$list = $this->getSelfType($this->uid, $adList);
		if (!empty($list)) return $list;

		//查看用户代理是否使用特殊业务，如果代理使用，通常情况下用户也使用
		$list = $this->getSelfType($this->agent, $adList);
		if (!empty($list)) return $list;

		//普通用户
		foreach ($adList as $key => $value) {
			if (in_array($key, Ad::cancelAdList())) {
				unset($adList[$key]);
			}
		}
		return $adList;
	}

	/**
	 * 使用特殊业务的用户
	 * @param $uid
	 * @param $adList
	 * @return mixed
	 */
	private function getSelfType($uid, $adList)
	{
		$typeNames = Ad::getTypeNameByUid($uid, $adList);
		if (empty($typeNames)) return array();
		foreach ($adList as $key => $value) {
			if (isset($typeNames[$key]) || array_key_exists($key, $typeNames)) {
				$adList[$key] = (!is_null($typeNames[$key])) ? $typeNames[$key] : $adList[$key];
			} else {
				unset($adList[$key]);
			}
		}
		return $adList;
	}

}