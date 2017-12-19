<?php

/**
 * Explain: 手动填写用户每种业务的收入，导入各个收入数据明细
 * 业务收入数据导入
 */
class ImportController extends DhadminController
{
    /**
     * @param string $type
     * @param string $date
     * @throws CHttpException
     * @name 导入首页
     */
    public function actionIndex($type = '', $date = '')
    {
        if (empty($type)) {
            $type = Ad::TYPE_UCLLQ;
        }

        if (empty($date) || $this->checkDate($date)) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        //历史记录时间是产生数据时间的第二天
        $logDate = SystemLog::getLogDate($date);
        $models = Log::getSystemLog($type, SystemLog::TYPE_UPLOAD, $logDate);

        $haveImport = false; //是否已导入数据
        $show=0;//开关
        if (count($models) > 0) {
            $haveImport = true;
            $show=$models[0]['is_show'];
        }
        //导入数据源
        $need = Income::getTypeByResourceName($type);
        if (empty($need)) throw new CHttpException(500, '此类型无法导入数据');

        $this->render('index', array(
            'type' => $type,
            'need' => $need,
            'success' => $haveImport,
            'date' => $date,
            'show' => $show
        ));
    }

    /**
     * @throws CHttpException
     * @throws CHttpException|Exception
     * @name Excel导入---未使用
     */
    public function actionExcel()
    {
        $request = Yii::app()->request;
        $type = $request->getPost('type');
        $date = $request->getPost('date');

        $this->checkParam($type, $date);

        if (Income::getTypeByResourceName($type) != Income::TYPE_EXCEL) {
            throw new CHttpException(500, '参数错误，此类型不允许使用Excel');
        }

        /* @var $file CUploadedFile */
        $file = CUploadedFile::getInstanceByName('excel');
        if (!is_object($file) || get_class($file) !== 'CUploadedFile') {
            throw new CHttpException(500, '上传数据错误');
        }

        $filename = Common::getAppParam(Common::DIR_EXCEL);
        $fix = explode('.', $file->getName());
        $filename .= uniqid() . '.' . $fix[1];
        $file->saveAs(Common::getPath($filename));
        $excel = new Excel();
        $objExcel = $excel->getByFile(Common::getPath($filename));
        $arrExcel = $excel->excel2Array($objExcel);

        array_shift($arrExcel); //删除第一个元素（标题）
        unlink(Common::getPath($filename));
        if (count($arrExcel) == 0) {
            $this->throws();
        }

        $t = Yii::app()->db->beginTransaction();
        try {
            $model = IncomeFactory::factory($type);
            $model->setImportDate($date);
            $sum = $model->createByArray($arrExcel);
            if ($sum == 0) {
                $this->throws();
            }

            $this->addSystemLog($type, $date);

            $t->commit();
            $this->myredirect($type, $date, '导入成功');
        } catch (CHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            $t->rollback();
            $this->myredirect($type, $date, '导入失败' . $e->getMessage());
        }
    }

    /**
     * @throws CHttpException
     * @throws CHttpException|Exception
     * name Self数据导入--目前平台导入方式
     */
    public function actionSelf()
    {
        $request = Yii::app()->request;
        $type = $request->getPost('type');
        $date = $request->getPost('date');
        $this->checkParam($type, $date);

        if (Income::getTypeByResourceName($type) != Income::TYPE_SELF) {
            throw new CHttpException(500, '参数错误，该类型不允许使用此方法导入');
        }
        //获取特殊参数
        $deductComputeList = $this->getDeductComputeList();
        //业务扣量比例换算
        if(!empty($deductComputeList))
        {
            foreach ($deductComputeList as $dkr => $dvr)
            {
                $group = substr($dkr, 0, 6);
                if ($group == "999999")
                {
                    $groupid = substr($dkr, 6, 2);
                    $bs_klradio=BindSample::model()->find('type=\''.$type.'\' and status = 0 and closed=0 and utype=1 and uid='.$groupid);
                    $deductComputeList[$dkr]=floor($dvr*$bs_klradio["klradio"]);
                }
                else
                {
                    $bs_klradio=BindSample::model()->find('type=\''.$type.'\' and status = 0 and closed=0 and utype=0 and uid='.$dkr);
                    $deductComputeList[$dkr]=floor($dvr*$bs_klradio["klradio"]);
                }
            }
        }


        $t = Yii::app()->db->beginTransaction();
        try {
            $model = IncomeFactory::factory($type);
            $model->setImportDate($date);

            foreach (Ad::getAdList() as $adk => $adv)
            {
                if ($type == $adk)
                {
                    $model->setDeductComputeList($deductComputeList);
                }
            }

            $sum = $model->createBySelf();
            if ($sum == 0) {
                $this->throws();
            }
            $this->addSystemLog($type, $date);
            $t->commit();
            //激活量记录
            if(!empty($deductComputeList))
            {
                foreach ($deductComputeList as $dk => $dv)
                {
                    $group=substr($dk,0,6);
                    if ($group == "999999")
                    {
                        $groupid=substr($dk,6,2);
                        $sqlex = 'SELECT * FROM app_import_log where date = \'' . $date . '\' and type=\'' . $type . '\' and gid=\'' . $groupid . '\'';
                        $importlog = Yii::app()->db->createCommand($sqlex)->queryAll();

                        if(empty($importlog))
                        {
                            $sql = 'INSERT INTO app_import_log (gid,type,data,date) VALUES (\'' . $groupid . '\',\'' . $type . '\',\'' . $dv . '\',\'' . $date . '\')';
                            Yii::app()->db->createCommand($sql)->execute();
                        }
                        else
                        {
                            $sql = 'UPDATE app_import_log SET data =  \'' . $dv . '\'  WHERE id = \'' . $importlog[0]["id"] . '\' ';
                            Yii::app()->db->createCommand($sql)->execute();
                        }

                    }
                }
            }
            $this->myredirect($type, $date, '导入成功');
        } catch (CHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            $t->rollback();
            $this->myredirect($type, $date, '导入失败' . $e->getMessage());
        }
    }
    /**
     * @name 清理已导入的业务数据
     */
    public function actionClear()
    {
        $request = Yii::app()->request;
        if ($request->isPostRequest) {
            $type = $request->getPost('type');
            $date = $request->getPost('date');

            $t = Yii::app()->db->beginTransaction();
            try {
                //删除income表数据
                $income = IncomeFactory::factory($type);
                $count = $income->clear($date);

                //删除log表数据
                SystemLog::model()->clear($type, $date);

                $t->commit();

                // /*写入收益操作日志 2017-11-14 start*/
                
                //     $mid=yii::app()->user->getState('uid');
                //     $content=$type.'收益清除';
                //     $ip=$_SERVER['SERVER_ADDR'];
                //     $before_content='';
                //     $title="清理数据";
                //     Income::addlogincome($mid,0,$content,$ip,$before_content,$title);  
                // /*2017-11-14 end*/

                Common::redirect($this->createUrl('clear'), '清理完毕，共清理' . $count . '条记录');
            } catch (Exception $e) {
                $t->rollback();
                Common::redirect($this->createUrl('clear'), '清理失败。' . $e->getMessage());
            }
        }
        $this->render('clear');
    }

    /**
     * 添加LOG--无需权限
     * @param $type
     * @param $date
     */
    private function addSystemLog($type, $date)
    {
        $content = "更新" . Ad::getAdNameById($type) . $date . "日收益";
        $logDate = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date)));
        $username = Yii::app()->user->getState('manage_username');
        Log::addSystemLog(SystemLog::TYPE_UPLOAD, $content, strtoupper($type), $username, Common::STATUS_TRUE, $logDate);
    }

    /**
     * 验证日期是否是当日之前--无需权限
     * @param $date
     * @return bool
     */
    private function checkDate($date)
    {
        return ($date == date('Y-m-d')) || (strtotime($date) >= time());
    }

    /**
     * 抛出空数据异常--无需权限
     * @throws CHttpException
     */
    private function throws()
    {
        throw new CHttpException(500, '接口中没有数据，请检查数据是否存在');
    }

    /**
     * 导入结束跳转
     * @param $type
     * @param $date
     * @param $msg
     */
    private function myredirect($type, $date, $msg)
    {
        Common::redirect($this->createUrl('index', array('type' => $type, 'date' => $date)), $msg);
    }


    /**
     * 验证传入参数是否合法
     * @param $type
     * @param $date
     * @throws CHttpException
     */
    private function checkParam($type, $date)
    {
        if (!in_array($type, Ad::getAdListKeys())) {
            throw new CHttpException(500, '参数错误，无此类型');
        }
        if ($this->checkDate($date)) {
            throw new CHttpException(500, '日期错误');
        }

        $logDate = SystemLog::getLogDate($date);
        $logs = Log::getSystemLog($type, SystemLog::TYPE_UPLOAD, $logDate);
        if (count($logs) > 0) {
            throw new CHttpException(500, '数据已上传过，不能重复导入');
        }
    }

    /**
     * 获取添加的新参数
     * @return array
     */
    private function getDeductComputeList()
    {
        $request = Yii::app()->request;
        $type = $request->getPost('type');
        $paramSel = '';


        foreach (Ad::getAdList() as $adk => $adv)
        {
            if ($type == $adk)
            {
                $paramSel = $request->getPost($adk.'ParamSel');
            }
        }


        if ($paramSel != 'param') {
            return array();
        }

        $date = $request->getPost('date');
        $deductId = $request->getPost('DeductId');
        $deductNum = $request->getPost('DeductNum');
        if(empty($deductNum))
        {
            $this->myredirect($type, $date, '导入失败，无数据传输');
        }

        foreach ($deductNum as $value) {
            if ($value == '' || !is_numeric($value)) {
                $this->myredirect($type, $date, '导入失败，请填写扣量基数');
            }
        }
        $length = count($deductId);
        $deductComputeList = array();
        for ($i = 0; $i < $length; $i++) {
            if ($deductNum[$i] == 0) continue;
            $deductComputeList[$deductId[$i]] = $deductNum[$i];
        }
        return $deductComputeList;
    }

    /**
     * @name 收益显示开关
     */
    public function actionShow($date,$type,$show)
    {
        $date2=SystemLog::getLogDate($date);
        $model=SystemLog::model()->find("type=:type and date =:date and target=:target and status=1",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$date2,":target"=>strtoupper($type)));
        if($model){
            $show=$show==1? 0:1;
            $model->is_show=$show;
            $update=json_encode($model->attributes);//数据没有修改之前
            if($model->update()){
                /*2017-11-15 记录在log_income日志中*/
                    $mid=yii::app()->user->getState('uid');
                    $content='表[system_log]中数据修改为'.json_encode($model->attributes);
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title='数据导入-收益显引';
                    $before_content=$update;
                    Income::addlogincome($mid,0,$content,$ip,$before_content,$title);
                /*2017-11-15 end*/

                $this->redirect(array('/dhadmin/import/index',"type"=>$type,"date"=>$date));
            }
        }
    }

}