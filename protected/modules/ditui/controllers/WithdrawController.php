<?php

/**
 * Explain:用户财务提现
 */
class WithdrawController extends DituiController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        //Script::registerScriptFile('member/withdraw.index.js');
        //查询会员结款信息
        $modelBill = MemberBill::model()->getByUid($this->uid);
        //统计会员当月收入合计
        $date = date('Y-m');
        $incomeSum = MemberIncome::getSumByMonth($date, $this->uid);
        //将合计数计入结款信息表
        $modelBill->cy = $incomeSum;
        $modelBill->save();

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        $memberIncomeList = MemberIncome::getMemberIncomeListByAgentId($this->uid, $startDate, $endDate);
        $commission = MemberIncome::getSumByMemberIncomeList($memberIncomeList, 'agentSum');

        //计算代理商扣点后用户收入
        $modelBill->cy = Ad::computeMemberSum($modelBill->cy, $this->scale);

        $modelMember = Member::model()->getById($this->uid);
        $this->render('index', array(
            'modelMember' => $modelMember,
            'modelBill' => $modelBill,
            'commission' => $commission
        ));
    }

    /**
     * 确认提现
     */
    public function actionSave()
    {
        /*
         * 判断是否有财务信息
         * 2015年6月4日10:47:59
         * */
        $uid = $this->uid;
        $member_model = new Member();
        $member_data = $member_model -> findByPk($uid);
        if($member_data['bank']=="" || $member_data['bank_no']=="" || $member_data['bank_site']=="" || $member_data['holder']==""){
            Common::redirect($this->createUrl('/ditui/info/bank'), '请先填写财务信息再进行财务提现');
            exit;
        }
        $d = intval(date('j', time()));
        if (($d > 15) || ($d < 11)) {
            Common::redirect($this->createUrl('index'), '请于每月11-15日提交申请');
        }

        $price = Yii::app()->request->getPost('price');
        if (empty($price)) {
            throw new CHttpException(500, '程序错误，没有提现数值');
        }



        $date = date('Y-m', time());
        $modelPalylog = MemberPaylog::model()->getByUidAndDate($uid, $date);
        if (!is_null($modelPalylog)) {
            Common::redirect($this->createUrl('index'), '您本月已提交过申请');
        }

        //计算手续费
        //Ad::computeFees($price);
        $fee = 0; //2013-12-23起免收手续费
        //支付金额
        $pay = $price - $fee;

        if ($pay <= 0) {
            Common::redirect($this->createUrl('index'), '提现金额输入错误，不能为0');
        }
        if ($pay < 50) {
            Common::redirect($this->createUrl('index'), '提现金额输入错误，提现金额必须≥50元');
        }
        $memberBill = MemberBill::model()->getByUid($uid);
        if ($pay > $memberBill->surplus) {
            Common::redirect($this->createUrl('index'), '提现金额输入错误，大于可提现的余额');
        }

        //添加提现记录
        $t = Yii::app()->db->beginTransaction();
        try {
            MemberPaylog::model()->addOne($uid, $pay, $price);
            MemberBill::model()->setPay($uid, $price, $pay);
            $t->commit();
            Common::redirect($this->createUrl('index'), '保存成功，每月16-20日左右统一打款');
        } catch (Exception $e) {
            $t->rollback();
            Common::redirect($this->createUrl('index'), '保存失败，请稍后重试');
        }
    }
}