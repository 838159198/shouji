<?php

/**
 * 后台首页
 */
class DefaultController extends MsgController
{
	/**
	 * 昨日收入概况
	 */
	public function actionIndex()
	{
		$uid = $this->uid;
		//$date = date('Y-m-d', strtotime('-1 day'));
        $date="7days";
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
//		$sql1 = "(SELECT * FROM `app_rom_appresource` WHERE `uid` = '{$uid}'  AND `createtime` LIKE '%2016%'  ORDER BY id ASC limit 1)";
//		$installdata1 = Yii::app()->db->createCommand($sql1)->queryAll();
//		$sql2 = "(SELECT * FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 AND `ask_time` LIKE '%2016%' ORDER BY id ASC limit 1)";
//		$installdata2 = Yii::app()->db->createCommand($sql2)->queryAll();
		$installdata1 = array();
		$installdata2 = array();
		
		$dataMember = Member::model()->findAll('id=:id',array(':id'=>$this->uid));
		$agent = $dataMember[0]['agent'];
		$agentPrice = $this::agentPrice($agent);
		$this->render('index', array(
			'data' => $data,
			'memberbill' => $memberbill,
			'adList' => $adList,
			'data1' =>$installdata1,
			'data2' =>$installdata2,
			'agentprice'=>$agentPrice
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
            if ($mail === null) {

                if (Yii::app()->request->isAjaxRequest) {
                    exit ();
                } else {
                    throw new CHttpException (404, 'The requested page does not exist.');
                }
            }
            else
            {
                $jointime=$mail[0]["jointime"];
                $model= MailContent::model()->findByPk($mail[0]["id"]);

                Mail::model()->updateByPk($mail[0]["id"],array('status'=>1));
                echo CJSON::encode(array('jointime' => $jointime,'title' => $model["title"],'content' => $model["content"]));
                exit ();
            }
        }
    }
	/**
	 * 用户按月收益明细
	 * @param string $m 年月 Y-m
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
        }
        else
        {
            $arr = MemberIncome::getIncomeListByUidAndDate($id, $date, $this->scale, $adList);
        }

		$array =(array)$arr['data'];
		$array = $array['rawData'];
		$array_str = serialize($array);// 序列化数组
		// 数组存缓存中
		Yii::app()->cache->set($date, $array_str,10800);
		$arr1 = array();
		foreach ($arr['data']->rawData AS $ak=>$val) {
			foreach ($val AS $key => $item) {
				if (in_array($key, array_keys($adList))) {
					if (isset($arr1[$key])) {
						$arr1[$key] += $item;
					} else {
						$arr1[$key] = 0;
					}
                    //增加激活量计算
                    if(!in_array($key,array("dates","id","amount")) && $item!="0.00")
                    {
                        $proList = Product::model()->getByKeyword2($key);
                        //代理商69子用户特殊单价
                        $membera=Member::model()->getById($this->uid);
                        if($key=="2345sjzs" && $membera["agent"]==69){$proList["quote"]=2.5;}
                        if($key=="yyzx" && $membera["agent"]==69){$proList["quote"]=2.5;}


                        //$arr['data']->rawData["20151101"][$key]="fsdf";
                        //$arr['data']->rawData[$ak][$key]="<span style='color:#0b88fd;font-weight: bold'>".round($item/$proList["quote"],2)."</span>/".$item;
                        $arr['data']->rawData[$ak]["id"]+=round($item/$proList["quote"],0);
                    }

				} else if ($key == 'dates') {
					$arr1['dates'] = '单项业务收益总计';
				} else if ($key == 'amount') {
					if (isset($arr1[$key])) {
						$arr1[$key] += $item;
					} else {
						$arr1[$key] = 0;
					}
				} else {
					$arr1[$key] = 0;
				}
			}
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
            $agentincome=$result[0]["data"];
        }


		$arrFinal = $this::getIncomeFinalData($date,$arr['data']);


        if(empty($id))
        {
            $this->render('income', array(
                'dataProvider' => $arrFinal['data'],
                'sum' => $arr['sum'],
                'date' => $date,
                'agentincome' => $agentincome,
                'adList' => $adList,
                'arr1' => $arr1
            ));
        }
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
	 * 获得app_income_final中数据
	 */
	private function getIncomeFinalData($date,$dataOld){
		$nowTime = date('Y-m', time());
		if ($date == $nowTime) {
			$lastDay = date('j', time()) - 1; //月份中的第几天，没有前导零
		} else {
			$lastDay = date('t', strtotime($date)); //给定月份所应有的天数
		}

		// 获取当月所有日期
		$nowDays = array();
		for ($i = 1; $i <= $lastDay; $i++) {
			$_date = $date . '-' . sprintf('%02d', $i);
			$nowDays[str_replace('-', '', $_date)] = $_date;
		}
		$uid = $this->uid;
		$sql = "SELECT * FROM `app_income_final` WHERE `uid` = '{$uid}' AND `date` LIKE '%{$date}%' ORDER by `date` ASC ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		$newData=array();
		if (!empty($data)){
			foreach ($data as $vt){
				$_date = str_replace('-', '',$vt['date']);
				$newData[$_date] = $vt;
			}
		}


		$dataOld = (array)$dataOld;

		// 给已有数据添加添加激活收益
		foreach ($newData as $k=>$v){
			$newData[$k]['activate_income'] = $dataOld['rawData'][$k]['amount'];
		}

		//获得数据中没有的日期
		$diffDays = array_diff_key($nowDays, $newData);
		foreach ($diffDays as $k=>$v){
			$str = '0.00';
			$newData[$k] = array(
				'id' => '0',
				'uid' => $uid,
				'install_income' => $str,
				'arrive_income' => $str,
				'activate_income' => $dataOld['rawData'][$k]['amount'],
				'all_income' => $str,
				'date' => $v
			);
		}
		ksort($newData);
		return array(
			'data' => new CArrayDataProvider(
				$newData,
				array(
					'pagination' => array('pageSize' => Common::PAGE_INFINITY)
				)
			),
		);
	}

	/**
	 * ajax返回数据,从缓存中获取数据,实现触摸详情,显示业务详请
	 */
	public function actionAjaxCache(){
		if (Yii::app()->request->isAjaxRequest) {
			$date = Yii::app()->request->getParam('date');
			$index = Yii::app()->request->getParam('index');
			// 从缓存中获取数据
			$cache = Yii::app()->cache->get($date);
//			if ($cache == false){
//				exit(CJSON::encode(array('val' => 'fail')));
//			}
			// 反序列化
			$array = unserialize($cache);
			$arr = $array[$index];

			$dataMember = Member::model()->findAll('id=:id',array(':id'=>$this->uid));
			$agent = $dataMember[0]['agent'];

			$arrproduct = $this::agentPrice($agent);
			$a = array();
			foreach ($arr as $k=> $vt){
				$b=array();
				if ($vt!=0.00 && $k!='dates' && $k!='amount' && $k!='id' && $k!='amount' && $k!='temp'){
					$b['name'] = $arrproduct[$k]['name'];
					$b['bill'] = $vt;
					$b['count'] = $vt/$arrproduct[$k]['price'];
					$a[]=$b;
				}
			}
			echo CJSON::encode(array('val' => $a));
		}
	}

	// 如果表app_agent_price存在对应代理商的的产品价格,则用这个价格
	private function agentPrice($agent){
		$sql_agent_price = "SELECT pid,price FROM `app_agent_price` WHERE agent='{$agent}'";
		$data_agent_price = Yii::app()->db->createCommand($sql_agent_price)->queryAll();
		$arr_agent=array();
		foreach ($data_agent_price as $vt){
			$arr_agent[$vt['pid']]=$vt['price'];
		}

		$sql = "SELECT id,name,pathname,price FROM `app_product` WHERE 1";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		$arrproduct = array();
		foreach ($data as $vt){
			if (array_key_exists($vt['id'],$arr_agent)){
				$arrproduct[$vt['pathname']]['name'] = $vt['name'];
				$arrproduct[$vt['pathname']]['price'] = $arr_agent[$vt['id']];
			}else{
				$arrproduct[$vt['pathname']]['name'] = $vt['name'];
				$arrproduct[$vt['pathname']]['price'] = $vt['price'];
			}

		}
		return $arrproduct;
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