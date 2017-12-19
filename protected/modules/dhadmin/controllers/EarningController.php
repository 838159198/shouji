<?php

/**
 * 查看用户收益
 */
class EarningController extends DhadminController
{
    /**
     * @param string $date
     * 昨日收益-用户列表
     */
    public function actionIndex($date = '')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $incomeList = MemberIncome::getAllIncomeListByDate($date);

        //计算合计数
        $incomeSumList = array('id' => 1, 'username' => '合计','type' => '2', 'date' => 0);
        //线下
        $incomeSumListxx = array('id' => 1, 'username' => '合计','type' => '3', 'date' => 0);
        $incomeSum = 0;
        $incomeSumxx = 0;
        $adTypeList = array_keys(Ad::getAdList2());
        foreach ($adTypeList as $key) {
            $incomeSumList[$key] = 0;
            $incomeSumListxx[$key] = 0;
        }

        foreach ($incomeList as $key=>$income) {
            //个人总计,使用date字段
            $sdatav=0;

            //线下
            if ($income['type']==3)
            {
                foreach ($income as $k => $v) {
                    if (in_array($k, $adTypeList)) {
                        isset($incomeSumListxx[$k]) ? $incomeSumListxx[$k] += $v : $incomeSumListxx[$k] = $v;
                        $sdatav += $v;
                        $incomeSumxx += $v;
                    }
                }
            }
            else
            {
                foreach ($income as $k => $v) {
                    if (in_array($k, $adTypeList)) {
                        isset($incomeSumList[$k]) ? $incomeSumList[$k] += $v : $incomeSumList[$k] = $v;
                        $sdatav += $v;
                        $incomeSum += $v;
                    }
                }
            }

            $incomeList[$key]['date']=$sdatav;
        }
        $incomeSumList['date'] = '合计：' . $incomeSum;
        //线下
        $incomeSumListxx['date'] = '合计：' . $incomeSumxx;

        $this->render('index', array(
            'dataProvider' => new CArrayDataProvider($incomeList, array(
                    'id' => 'income',
                    'pagination' => array(
                        'pageSize' => Common::PAGE_SIZE,
                    ),
                )),
            'date' => $date,
            'totalData' => new CArrayDataProvider(array($incomeSumList)),
            'totalDataxx' => new CArrayDataProvider(array($incomeSumListxx))
        ));
    }
    /**
     * @param string $date
     * 用户开启业务列表
     */
    public function actionMemberpro($uname = '')
    {
        $date = empty($uname) ? "": $uname;
        $uname = trim($date);
        if(!empty($uname))
        {
            $userid=Member::model()->getByUserName($uname);
            $userid->id;
            $unameList=MemberResource::model()->findAllBySql('SELECT uid,GROUP_CONCAT(type) as type FROM app_member_resource WHERE uid= '.$userid->id.' and status = 1 AND openstatus = 1 GROUP BY uid;');
        }
        else
        {
            $unameList=MemberResource::model()->findAllBySql('SELECT uid,GROUP_CONCAT(type) as type FROM app_member_resource WHERE status = 1 AND openstatus = 1 GROUP BY uid;');
        }
        $this->render('memberpro', array(
            'dataProvider' => new CArrayDataProvider($unameList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'uname' => $uname,
        ));
    }
    /**
     * @param $uid
     * @name 用户提现明细--未使用
     */
    public function actionPaylog($uid)
    {
        $data = MemberPaylog::model()->getDataProviderByUid(Common::PAGE_SIZE, $uid);
        $member = Member::model()->getById($uid);
        $this->render('paylog', array('data' => $data, 'member' => $member));
    }

    /**
     * @param $uid
     * @throws CHttpException
     * @name 用户收入明细--未使用
     */
    public function actionMember($uid, $date = '')
    {
        //当前查询月
        $date = empty($date) ? DateUtil::getDate(null, DateUtil::F_MONTH) : DateUtil::getDate(strtotime($date), DateUtil::F_MONTH);

        $member = Member::model()->getById($uid);
        $adList = Product::model()->getKeywordList();
        $adList[Ad::TYPE_COMMISSION] = '推广提成';
        $arr = MemberIncome::getIncomeListByUidAndDate($uid, $date, $member->scale, $adList);
        $this->render('member', array(
            'member' => $member,
            'uid' => $uid,
            'dataProvider' => $arr['data'],
            'sum' => $arr['sum'],
            'date' => $date,
            'adList' => $adList,
        ));
    }

    /**
     * @param $uid
     * @name 用户收入合计--未使用
     */
    public function actionCount($uid)
    {
        $adList[Ad::TYPE_COMMISSION] = '推广提成';
        $member = Member::model()->getById($uid);
        list ($arr, $sum) = MemberIncome::getAllIncomGroupByYearMonth($uid);

        $paylogData = MemberPaylog::model()->getDataProviderByUid(Common::PAGE_INFINITY, $uid);

        $memberBill = MemberBill::model()->getByUid($uid);

        $this->render('count', array(
            'member' => $member,
            'memberBill' => $memberBill,
            'sum' => $sum,
            'paylogData' => $paylogData,
            'dataProvider' => new CArrayDataProvider($arr, array(
                    'keyField' => false,
                    'pagination' => array('pageSize' => Common::PAGE_INFINITY)
                )),
        ));
    }
}