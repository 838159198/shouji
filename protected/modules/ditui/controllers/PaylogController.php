<?php
/**
 * Explain:用户付款记录
 */
class PaylogController extends DituiController
{
    /**
     * Index
     */
    public function actionIndex()
    {
        $data = MemberPaylog::model()->getDataProviderByUid(Common::PAGE_SIZE, $this->uid, date('Y'));
        $this->render('index', array('data' => $data));
    }
}