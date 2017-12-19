<?php

/**
 * Explain: 平台数据统计模型类
 */
class StatsModel
{
    /**
     * 曲线图
     * @param $firstDate
     * @param $lastDate
     * @return array
     */
    public static function actionGraphs($firstDate, $lastDate)
    {
        if (empty($firstDate) || empty($lastDate)) return array();

        $incomeList = MemberIncome::getAllIncomeCount($firstDate, $lastDate);
        $incomes = array();

        $adList = Ad::getAdList();
        foreach ($incomeList as $userIncome) {
            $income = array();
            foreach ($adList as $k => $v) {
                $income[$k] = isset($userIncome[$k]) ? $userIncome[$k] : '0.00';
                //js不支持数字开头，更改键名
                switch($k)
                {
                    case "2345ydw":
                        $income["ydw2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345sjzs":
                        $income["sjzs2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345ysdq":
                        $income["ysdq2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345wpllq":
                        $income["wpllq2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345tqw":
                        $income["tqw2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                }
            }
            $date = strtotime($userIncome['dates']);
            $income['y'] = intval(date('Y', $date));
            $income['m'] = intval(date('m', $date));
            $income['d'] = intval(date('d', $date));

            $incomes[] = $income;
        }
        return $incomes;
    }

    /**
     * 降量分析
     * @param $param
     * @param $id
     * @param int $pagesize
     * @return CArrayDataProvider
     */
    public static function actionDropdata($param, $id, $pagesize = Common::PAGE_SIZE)
    {
        $data = self::getDropDataList($param, $id);

        return new CArrayDataProvider($data, array(
            'id' => 'incomeSum',
            'sort' => array(
                'attributes' => array(
                    'username', 'firstSum', 'lastSum', 'difference', 'percent'
                ),
                'defaultOrder' => 'percent'
            ),
            'pagination' => array(
                'pageSize' => $pagesize,
            ),
        ));
    }

    /**
     * @param $param
     * @return array
     * @throws CHttpException
     */
    private static function getDropdataParams($param)
    {
        if (!isset($param['type'])) throw new CHttpException(500, '未选择业务类型');
        if (!isset($param['firstDate']['first'])) throw new CHttpException(500, '未选择开始时间');
        if (!isset($param['firstDate']['last'])) throw new CHttpException(500, '未选择开始时间');
        if (!isset($param['lastDate']['first'])) throw new CHttpException(500, '未选择开始时间');
        if (!isset($param['lastDate']['last'])) throw new CHttpException(500, '未选择开始时间');

        $type = $param['type'];
        $firstDateFirst = $param['firstDate']['first'];
        $firstDateLast = $param['firstDate']['last'];
        $lastDateFirst = $param['lastDate']['first'];
        $lastDateLast = $param['lastDate']['last'];
        return array($type, $firstDateFirst, $firstDateLast, $lastDateFirst, $lastDateLast);
    }

    /**
     * @param $param
     * @param $id
     * @return array
     */
    public static function getDropDataList($param, $id)
    {
        list($type, $firstDateFirst, $firstDateLast, $lastDateFirst, $lastDateLast) = self::getDropdataParams($param);

        $income = IncomeFactory::factory($type);

        $firstSumList = $income->getMemberDataSumListByDate($firstDateFirst, $firstDateLast);

        $lastSumList = $income->getMemberDataSumListByDate($lastDateFirst, $lastDateLast);

        $uidList = array_merge(array_keys($firstSumList), array_keys($lastSumList));

        $uidList = array_unique($uidList);
        $data = array();
        $categoryList = MemberCategory::model()->getListToArray();

        $MemberList = Member::model()->getListByUids($uidList);

        $role = Manage::model()->getRoleByUid($id);

        $mem = '';
        foreach ($uidList as $uid) {

            $_fIncome = isset($firstSumList[$uid]) ? $firstSumList[$uid] : null;
            $_lIncome = isset($lastSumList[$uid]) ? $lastSumList[$uid] : null;

            if (isset($MemberList[$uid])) {

                $mem = $MemberList[$uid];

                if (($mem->manage_id == 0) || (empty($mem->manage_id))) {
                    $t_status = CHtml::submitButton('可申请', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'ask_for_up(\'' . $mem->id . '\',\'' . $role . '\')')));
                    $sttus = '1';
                } else {
                    $t_status = CHtml::button('已发布', array_merge(Bs::cls(Bs::BTN_DANGER)));
                    $sttus = '0';
                }
            }
            $_fSum = is_null($_fIncome) ? 0 : $_fIncome->data;
            $_lSum = is_null($_lIncome) ? 0 : $_lIncome->data;
            $_difference = round($_lSum - $_fSum, 2);
            $_percent = ($_fSum==0) ? '100' : round(($_difference / $_fSum) * 100, 2);
//            $member = is_null($_fIncome) ? $_lIncome->u : $_fIncome->u;
            $member = $MemberList[$uid];
            $data[] = array(
                'id' => $uid,
                'username' => $member->username,
                'category' => $member->category,
                'categoryName' => isset($categoryList[$member->category]) ? $categoryList[$member->category] : '',
                'firstSum' => $_fSum,
                'lastSum' => $_lSum,
                'difference' => $_difference,
                'percent' => $_percent,
                't_status' => isset($t_status) ? $t_status : '',
//                't_status' => $t_status,
                'status' => isset($sttus) ? $sttus : '',
//                'status' => $sttus,
                't_type' => isset($askTask->t_type) ? $askTask->t_type : '',

            );
        }
        return $data;
    }
}