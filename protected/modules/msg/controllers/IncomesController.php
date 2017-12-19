<?php

/**
 * Date: 14-12-14 上午11:06
 * Explain: 推广用户及提成管理
 */
class IncomesController extends MsgController
{
    public $incomeList = array();
    /**
     * @param null $startdate
     * @param null $enddate
     * @throws CHttpException
     */
    public function actionIndex($startdate = null, $enddate = null)
    {
        if (empty($startdate)) $startdate = DateUtil::getDate(strtotime('-1 day'));
        if (empty($enddate)) $enddate = DateUtil::getDate(strtotime('-1 day'));

        if (DateUtil::dateDiff(date('Y-m-d'), $startdate) < -93) {
            throw new CHttpException(500, '只统计三个月内的数据');
        }

        $data = Member::model()->getDataProviderByAgent2($this->uid);

        $this->incomeList = MemberIncome::getMemberIncomeListByAgentId2($this->uid, $startdate, $enddate, Income::STATUS_TRUE);

        //代理商自己总提成
        $memberLista = Member::model()->getListByAgentSelf($this->uid);
        $memberIdLista = array_keys($memberLista);
        $agentincome = MemberIncome::getIncomeListByUidLista($memberIdLista, $startdate, $enddate, 1, false);

        //
        $incomeTotal = MemberIncome::getSumByMemberIncomeList($this->incomeList, 'memberSum');
        $commission = MemberIncome::getSumByMemberIncomeList($this->incomeList, 'agentSum');

        $this->render('index', array(
            'data' => $data,
            'incomeTotal' => $incomeTotal,
            'commission' => $commission,
            'agentincome' => $agentincome,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ));
    }

    /**
     * @param $id
     * @return Member|null
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->getById($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}