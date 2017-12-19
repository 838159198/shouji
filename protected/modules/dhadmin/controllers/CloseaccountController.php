<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-16 上午10:00
 * Explain: 处理业务号码关闭、开启
 * @name 封号管理
 */
class CloseaccountController extends DhadminController
{
    /**
     * @name 首页 、封号
     * @throws CHttpException
     */
    public function actionIndex()
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $type = $request->getPost('type');
            $account = $request->getPost('account');
            $accountall = $request->getPost('accountall');
            $closedate = $request->getPost('closedate');

            if (!empty($accountall) && $accountall!=1) {
                Common::redirect(Yii::app()->request->urlReferrer, '输入错误');
            }
            if (empty($account) && empty($accountall)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写要封闭的号码');
            }
            if (empty($closedate)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写封闭时间');
            }
            $mid = Yii::app()->user->manage_id;
            $detail=' [manager] '.$mid.' [业务类别] '.$type.' [要封闭的资源编号] '.$account.' [散量业务所属全部用户] '.$accountall.'[封号开始日期]'.$closedate;
            $log = ' [closedate]' . $closedate;
            Log::closeAccount('开始单个封号业务，' . $log);
            $transaction = Yii::app()->db->beginTransaction();
            $accList=array();
            $accChar="";
            try {
                if($accountall==1)
                {
                    $msList = MemberResource::model()->findAll('type=:type and status=:status',array(':type'=>$type,':status'=>1));
                    if(!empty($msList))
                    {
                        foreach($msList as $msv)
                        {
                            $accChar.=$msv["key"].",";
                        }
                        $accList = explode(',', $accChar);
                    }
                }
                else
                {
                    $accList = explode(',', $account);
                }

                $count = CloseaccountModel::closeAccountCache($type, $accList, $closedate);
                $transaction->commit();
                Log::closeAccount('单个封号业务成功，' . $log . ' [num]' . $count);
                //日志记录
                NoteLog::addLog($detail,$mid,$uid='',$tag='封号管理',$update='');
                echo "<script language='javascript' charset='GBK' type='text/javascript'>if(confirm('成功封号')){window.location.href='/dhadmin/closeaccount/index'}</script>";

                //Common::redirect($this->createUrl('index'), '成功封号');
            } catch (Exception $e) {
                $transaction->rollback();
                Log::closeAccount('单个封号业务失败，' . $log . $e->getMessage());
                throw new CHttpException(500, '封号失败，请重试');
            }
        }
        $this->render('index');
    }

    /**
     * @name 批量封号
     * @throws CHttpException
     */
    public function actionBatch()
    {
        if (isset($_POST['closedate'])) {
            $request = Yii::app()->request;
            $type = $request->getPost('type');
            $closedate = $request->getPost('closedate');

            $accList = CloseaccountModel::loadExcel2Array($closedate);

            $log = ' [closedate]' . $closedate;
            Log::closeAccount('开始批量封号业务，' . $log);
            $t = Yii::app()->db->beginTransaction();
            try {
                $count = CloseaccountModel::closeAccount($type, $accList, $closedate);
                $t->commit();
                Log::closeAccount('批量封号业务成功，' . $log . ' [num]' . $count);
                Common::redirect($this->createUrl('index'), '成功封号');
            } catch (Exception $e) {
                $t->rollback();
                Log::closeAccount('批量封号业务失败，' . $log);
                throw new CHttpException(500, '封号失败，请重试。' . $e->getMessage());
            }
        }
        $this->render('batch');
    }

    /**
     * @name 解除封号
     * @throws CHttpException
     */
    public function actionRelease()
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $type = $request->getPost('type');
            $account = $request->getPost('account');
            $closedate = $request->getPost('closedate');

            if (empty($account)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写要解除封闭的号码');
            }
            if (empty($closedate) || DateUtil::isDate($closedate) == false) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写解除封闭时间');
            }

            $log = ' [releasedate]' . $closedate . ' [type]' . $type . ' [key]' . $account;
            Log::closeAccount('开始解除封号业务，' . $log);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $bindSample = BindSample::model()->getByVal($account, $type);
                if ($bindSample == null) {
                    throw new Exception('无此编号');
                }
                if ($bindSample->status == BindSample::STATUS_TRUE) {
                    throw new Exception('ID未分配给用户');
                }
                if ($bindSample->closed == BindSample::CLOSED_FALSE) {
                    throw new Exception('ID未封号');
                }

                $lastDate = CloseaccountModel::releaseAccountCache($account, $closedate);
                $bindSample->closed = BindSample::CLOSED_FALSE;
                $bindSample->update();

                $transaction->commit();
                Log::closeAccount('解除封号，' . $log . ' [msg]' . '解除封号成功');
                Common::redirect($this->createUrl('index'), '解除封号成功，最后数据日期：' . $lastDate);
            } catch (Exception $e) {
                $transaction->rollback();
                $msg = $e->getMessage();
                Log::closeAccount('解除封号业务失败，' . $log);
                throw new CHttpException(500, '解除封号失败。' . $msg);
            }
        }
        $this->render('release');
    }

    /**
     * @param string $type
     * @throws CHttpException
     * @name 按业务编号导入数据
     */
    public function actionImport($type = '')
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $account = $request->getPost('account');
            $importDate = $request->getPost('closedate');
            $type = $request->getPost('type');

            $transaction = Yii::app()->db->beginTransaction();
            try {
                CloseaccountModel::import($type, $account, $importDate);
                $transaction->commit();
                Common::redirect($this->createUrl('import', array('type' => $type)), '导入成功');
            } catch (Exception $e) {
                $transaction->rollback();
                $msg = $e->getMessage();
                throw new CHttpException(500, '导入失败，请重试。' . $msg);
            }
        }
        $this->render('import', array('type' => $type));
    }

    /**
     * @throws CHttpException
     * @name 金山导航特殊封号
     */
    public function actionJsdh()
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $account = $request->getPost('account');
            $closedate = $request->getPost('closedate');

            if (empty($account)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写要封闭的号码');
            }
            if (empty($closedate)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写封闭时间');
            }

            $log = ' [closedate]' . $closedate . ' [accounts]' . $account;
            Log::closeAccount('开始金山导航特殊封号业务，' . $log);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $accList = explode(',', $account);
                $count = CloseaccountModel::closeType($accList, $closedate, Ad::TYPE_JSDH);
                $transaction->commit();
                Log::closeAccount('金山导航特殊封号业务成功，' . $log . ' [num]' . $count);
                Common::redirect($this->createUrl('index'), '成功封号[' . $count . ']');
            } catch (Exception $e) {
                $transaction->rollback();
                Log::closeAccount('金山导航特殊封号业务失败，' . $log . $e->getMessage());
                throw new CHttpException(500, '封号失败，请重试。' . $e->getMessage());
            }
        }
        $this->render('jsdh');
    }

    /**
     * @throws CHttpException
     * @name 隐藏广告特殊封号
     */
    public function actionYcgg()
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $account = $request->getPost('account');
            $closedate = $request->getPost('closedate');

            if (empty($account)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写要封闭的号码');
            }
            if (empty($closedate)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写封闭时间');
            }

            $log = ' [closedate]' . $closedate . ' [accounts]' . $account;
            Log::closeAccount('开始隐藏广告特殊封号业务，' . $log);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $accList = explode(',', $account);
                $count = CloseaccountModel::closeType($accList, $closedate, Ad::TYPE_YCGG);
                $transaction->commit();
                Log::closeAccount('隐藏广告特殊封号业务成功，' . $log . ' [num]' . $count);
                Common::redirect($this->createUrl('index'), '成功封号[' . $count . ']');
            } catch (Exception $e) {
                $transaction->rollback();
                Log::closeAccount('隐藏广告特殊封号业务失败，' . $log . $e->getMessage());
                throw new CHttpException(500, '封号失败，请重试。' . $e->getMessage());
            }
        }
        $this->render('ycgg');
    }
    /**
     * @throws CHttpException
     * @name 底边条特殊封号
     */
    public function actionDbt()
    {
        if (isset($_POST['account'])) {
            $request = Yii::app()->request;
            $account = $request->getPost('account');
            $closedate = $request->getPost('closedate');

            if (empty($account)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写要封闭的号码');
            }
            if (empty($closedate)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请填写封闭时间');
            }

            $log = ' [closedate]' . $closedate . ' [accounts]' . $account;
            Log::closeAccount('开始底边条特殊封号业务，' . $log);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $accList = explode(',', $account);
                $count = CloseaccountModel::closeType($accList, $closedate, Ad::TYPE_DBT);
                $transaction->commit();
                Log::closeAccount('底边条特殊封号业务成功，' . $log . ' [num]' . $count);
                Common::redirect($this->createUrl('index'), '成功封号[' . $count . ']');
            } catch (Exception $e) {
                $transaction->rollback();
                Log::closeAccount('底边条特殊封号业务失败，' . $log . $e->getMessage());
                throw new CHttpException(500, '封号失败，请重试。' . $e->getMessage());
            }
        }
        $this->render('dbt');
    }


}