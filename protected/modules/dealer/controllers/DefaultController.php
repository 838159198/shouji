<?php

/**
 * 后台首页
 */
class DefaultController extends DealerController
{
	/**
	 * 昨日收入概况
	 */
	public function actionIndex()
	{
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

		$this->render('index', array(
			'data' => $data,
			'memberbill' => $memberbill,
			'adList' => $adList
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
        }
        else
        {
            $arr = MemberIncome::getIncomeListByUidAndDate($id, $date, $this->scale, $adList);
        }

		$arr1 = array();
        $sum=0;
		foreach ($arr['data']->rawData AS $ak=>$val) {
			foreach ($val AS $key => $item) {
				if (in_array($key, array_keys($adList))) {
                    //判断收益显示:只有开关打开用户平台才正常显示
                    $user = Yii::app()->user;
                    if($user->member_manage===false){
                        $datetime=SystemLog::getLogDate($val['dates']);
                        $model=SystemLog::model()->find("type=:type and date =:date and target=:target and status=1 and is_show=0",
                            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime,":target"=>strtoupper($key)));
                        $income=0;
                        if($model){
                            $arr['data']->rawData[$ak]['amount']= round($arr['data']->rawData[$ak]['amount']- $arr['data']->rawData[$ak][$key], 2);
                            $arr['data']->rawData[$ak][$key]='0.00';
                            $income +=$arr['data']->rawData[$ak][$key];
                            $item=0;
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
                        $arr['data']->rawData[$ak][$key]="<span style='color:#0b88fd;font-weight: bold'>".round($item/$proList["quote"],2)."</span>/".$item;
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
            $agentincome=$result[0]["data"];
        }

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