<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-7-24 下午2:22
 * Explain: 业务封号模型类
 */
class CloseaccountModel
{
    /**
     * 封号  更新缓存版
     * @param string $type 要封号的业务类型
     * @param string[] $accList 封号列表
     * @param string $closedate 封号时间
     * @return int
     * 2015-6-19 09:25:22
     * by zkn
     */
    public static function closeAccountCache($type, $accList, $closedate)
    {
        //封号数量
        $count = 0;
        //被封号用户ID
        $uidList = array();

        foreach ($accList as $acc) {
            //获取资源编号列表
//            Log::closeAccount('[key]' . $acc);
            if(empty($acc)) continue;
            $memberResourceList = MemberResource::model()->getListByKey($acc);
            foreach ($memberResourceList as $memberResource) {
                //已封过
                if ($memberResource->status == MemberResource::STATUS_FALSE) continue;
                if ($memberResource->type != $type) continue;

                $memberResource->motifytime = time();
                $memberResource->closestart = date('Y-m-d', strtotime($closedate));
                $memberResource->status = MemberResource::STATUS_FALSE;
                $memberResource->openstatus = MemberResource::OPEN_FALSE;
                $memberResource->update();

                //修改item
                $item_model = MemberResourceItem::model();
                $item_model -> updateAll(array("status"=>0,'motifytime'=>time()),"`uid`=:uid and `type`=:type and `key`=:key",array(":uid"=>$memberResource['uid'],":type"=>$type,":key"=>$acc));
                //更新用户缓存
                //Cache::UserGroup($memberResource['uid']);

                //修改用户xml相关配置文件
                $status=0;

                if (!is_numeric($status) || !in_array($status, array('0', '1')) || empty($type)) {
                    throw new CHttpException(500, '数据错误');
                }

                //$username = Member::model()->getUsernameByMid($memberResource->uid);
                $resource = Product::model()->getByKeyword($type);

                if (is_null($resource)) {
                    throw new CHttpException(500, '没有此广告类型或此类型广告不允许修改');
                }
                if ($resource->auth == Product::AUTH_CLOSED) {
                    throw new CHttpException(500, '此项目已关闭');
                }

                /*$xml = AdvertXml::getXml($username);
                if ($xml === false) {
                    throw new CHttpException(500, '该用户没有配置文件，请联系客服');
                }*/
                $MemberResource = MemberResource::model();
                $bindResource = $MemberResource->getBidValue($memberResource->uid, $resource->pathname);

                //如果没有绑定表中没有值
                if (is_null($bindResource)) {
                    if ($resource->auth == Product::AUTH_MANAGE) {
                        throw new CHttpException(500, '此项目只有客服可以开启');
                    }

                    if ($MemberResource->bindMemberByType($memberResource->uid, $type, BindSample::ALLOT_AUTO)) {
                        $bindResource = $MemberResource->getBidValue($memberResource->uid, $resource->pathname);
                    }
                }

                if ($bindResource == null) {
                    throw new Exception('该业务已没有可使用的ID，请联系客服');
                }

                //添加LOG
                MemberResourceLog::model()->add($bindResource, $status);

                //$config = AdvertXml::getBindConfig($resource->keyword, $bindResource->key);

                //status为0，即关闭业务
                if ($status == 0) {
                    $config['value'] = '';
                }

                /*AdvertXml::setXml(array($config), $username, $xml);
                Common::push($username);*/

                $logStr = '[gainadvert] ';
                $logStr .= $status == 0 ? '[关闭]' : '[开启]';
                $logStr .= ' [type]' . $type . ' [key]' . $bindResource->key;
                Log::memberEvent($logStr);

                //修改收入数据为不可用
                IncomeFactory::factory($memberResource->type)->closeIncome($memberResource->key, $closedate);
                //修改用户所有统计app资源为不可用
                $rmodel = RomAppresource::model()->findAll('uid=:uid AND type=:type AND createtime>=:createtime',array(':uid'=>$memberResource->uid,':type'=>$resource->pathname,':createtime'=>$closedate));
                if (is_null($rmodel)) continue;
                foreach ($rmodel as $a=>$b)
                {
                    $rmodel[$a]["closeend"] = date('Y-m-d H:i:s');
                    $rmodel[$a]["finishstatus"] = 0;
                    $rmodel[$a]["status"] = 0;
                    $rmodel[$a]->update();
                }



                $message = '[key]' . $memberResource->key;
                $message .= ' [uid]' . $memberResource->uid;
                $message .= ' [closedate]' . $closedate;
                Log::closeAccount($message);

                $uidList[] = $memberResource->uid;
                $count++;
            }
            BindSample::model()->closeVal($acc);
        }


        if (count($uidList) > 0) {
            //判断系统是否已经统计上月用户收入合计并导入用户余额
            //如果已经统计，需把封号的收入从用户余额中减去
            //如果未统计，不用去除
            if (self::checkIsStats()) {
                self::deductCloseSum($closedate, $uidList);
            }
        }

        return $count;
    }

    /**
     * 封号
     * @param string $type 要封号的业务类型
     * @param string[] $accList 封号列表
     * @param string $closedate 封号时间
     * @return int
     */
    public static function closeAccount($type, $accList, $closedate)
    {
        //封号数量
        $count = 0;
        //被封号用户ID
        $uidList = array();

        foreach ($accList as $acc) {
            //获取资源编号列表
//            Log::closeAccount('[key]' . $acc);
            $memberResourceList = MemberResource::model()->getListByKey($acc);
            foreach ($memberResourceList as $memberResource) {
                //已封过
                if ($memberResource->status == MemberResource::STATUS_FALSE) continue;
                if ($memberResource->type != $type) continue;

                $memberResource->motifytime = time();
                $memberResource->closestart = date('Y-m-d', strtotime($closedate));
                $memberResource->status = MemberResource::STATUS_FALSE;
                $memberResource->openstatus = MemberResource::OPEN_FALSE;
                $memberResource->update();

               //修改用户xml相关配置文件
                $status=0;

                if (!is_numeric($status) || !in_array($status, array('0', '1')) || empty($type)) {
                    throw new CHttpException(500, '数据错误');
                }

                $username = MemberInfo::model()->getUsernameByMid($memberResource->uid);
                $resource = Resource::model()->getByKeyword($type);

                if (is_null($resource)) {
                    throw new CHttpException(500, '没有此广告类型或此类型广告不允许修改');
                }
                if ($resource->auth == Resource::AUTH_CLOSED) {
                    throw new CHttpException(500, '此项目已关闭');
                }

                $xml = AdvertXml::getXml($username);
                if ($xml === false) {
                    throw new CHttpException(500, '该用户没有配置文件，请联系客服');
                }
                $MemberResource = MemberResource::model();
                $bindResource = $MemberResource->getBidValue($memberResource->uid, $resource->keyword);

                //如果没有绑定表中没有值
                if (is_null($bindResource)) {
                    if ($resource->auth == Resource::AUTH_MANAGE) {
                        throw new CHttpException(500, '此项目只有客服可以开启');
                    }

                    if ($MemberResource->bindMemberByType($memberResource->uid, $type, BindSample::ALLOT_AUTO)) {
                        $bindResource = $MemberResource->getBidValue($memberResource->uid, $resource->keyword);
                    }
                }

                if ($bindResource == null) {
                    throw new Exception('该业务已没有可使用的ID，请联系客服');
                }

                //添加LOG
                MemberResourceLog::model()->add($bindResource, $status);

                $config = AdvertXml::getBindConfig($resource->keyword, $bindResource->key);

                //status为0，即关闭业务
                if ($status == 0) {
                    $config['value'] = '';
                }

                AdvertXml::setXml(array($config), $username, $xml);
                Common::push($username);

                $logStr = '[gainadvert] ';
                $logStr .= $status == 0 ? '[关闭]' : '[开启]';
                $logStr .= ' [type]' . $type . ' [key]' . $bindResource->key;
                Log::memberEvent($logStr);

                //修改收入数据为不可用
                IncomeFactory::factory($memberResource->type)->closeIncome($memberResource->id, $closedate);

                $message = '[key]' . $memberResource->key;
                $message .= ' [uid]' . $memberResource->uid;
                $message .= ' [closedate]' . $closedate;
                Log::closeAccount($message);

                $uidList[] = $memberResource->uid;
                $count++;
            }
            BindSample::model()->closeVal($acc);
        }


        if (count($uidList) > 0) {
            //判断系统是否已经统计上月用户收入合计并导入用户余额
            //如果已经统计，需把封号的收入从用户余额中减去
            //如果未统计，不用去除
            if (self::checkIsStats()) {
                self::deductCloseSum($closedate, $uidList);
            }
        }

        return $count;
    }

    /**
     * 获取上传Excel文件并转换为数组返回
     * @param string $closedate
     * @return string[]
     * @throws CHttpException
     */
    public static function loadExcel2Array($closedate)
    {
        $file = CUploadedFile::getInstanceByName('account');
        if (!is_object($file) || get_class($file) !== 'CUploadedFile') {
            throw new CHttpException(500, '上传数据错误');
        }

        $filename = Common::getAppParam(Common::DIR_EXCEL);
        $fix = explode(' . ', $file->getName());
        $filename .= uniqid() . ' . ' . $fix[1];
        $file->saveAs(Common::getPath($filename));

        $excel = new Excel();
        $objExcel = $excel->getByFile(Common::getPath($filename));
        $arrExcel = $excel->excel2Array($objExcel);
        unlink(Common::getPath($filename));
        if (count($arrExcel) == 0) {
            throw new CHttpException(500, '请检查数据是否存在');
        }
        if (empty($closedate)) {
            Common::redirect(Yii::app()->request->urlReferrer, '请填写封闭时间');
        }

        //取封号值
        $accList = array();
        foreach ($arrExcel as $acc) {
            $accList[] = $acc[0];
        }
        return $accList;
    }

    /**
     * 验证是否已经把上月用户收入统计计入用户提现余额
     * @return bool
     */
    private static function checkIsStats()
    {
        $isStats = false;
        $logs = Log::getSystemLog(SystemLog::TARGET_MEMBER, SystemLog::TYPE_COUNT);
        if (count($logs) > 0) {
            $isStats = true;
        }
        return $isStats;
    }

    /**
     * 扣除被封号用户的多余收入
     * @param $closedate
     * @param $uidList
     */
    private static function deductCloseSum($closedate, $uidList)
    {
        //封号时间是在下月月初，计算多余收入是在封号日期的上一月
        //封号收入应计算封号日期开始，至当月月末
        $startdate = date('Y-m-01', strtotime('-1 month'));
        $enddate = date('Y-m-t', strtotime('-1 month'));

        //如果封号日期在1号之后，如：5日
        if (strtotime($startdate) < strtotime($closedate)) {
            $startdate = $closedate;
        }

        //获取封号用户被封禁的收入金额
        //只计算封号上月收入合计，再上月因已经付款，无计算必要
        $closeSumList = MemberIncome::getIncomeListByUidList($uidList, $startdate, $enddate);

        //申请提现日期
        $payLogDate = date('Y-m', time());
        foreach ($uidList as $uid) {
            $askSum=0;
            //判断用户是否已申请提现
            $payLog = MemberPaylog::model()->getByUidAndDate($uid, $payLogDate);
            //如果用户已经付款，则跳过
            if (!is_null($payLog) && $payLog->status == MemberPaylog::STATUS_TRUE) {
                continue;
            }
            if (!is_null($payLog) && $payLog->status == MemberPaylog::STATUS_FALSE) {
                //获取用户申请提现金额
                //用户申请提现
                $askSum = $payLog->ask_sum;

                $memberBill = MemberBill::model()->getByUid($uid);
                if (!is_null($memberBill)) {
                    $closeSum = isset($closeSumList[$uid]) ? $closeSumList[$uid] : 0;
                    $surplus = $memberBill->surplus;
                    //现有余额-封号金额>=0：用户余额够扣封号金额情况
                    if(($surplus-$closeSum)>=0){
                        //封号后用户余额=原余额(surplus) - 封号金额(closeSum)
                        $price = $surplus - $closeSum;
                        $memberBill->surplus = $price;
                        $memberBill->update();
                    }
                    else{
                        //提现申请置为无效：用户余额不够扣封号金额，则取消提现，余额+提现-封号
                        $payLog->valid = MemberPaylog::VALID_FALSE;
                        $payLog->update();
                        //封号后用户余额=原余额(surplus) + 申请提现金额(askSum) - 封号金额(closeSum)
                        $price = $surplus + $askSum - $closeSum;
                        $memberBill->surplus = $price < 0 ? 0 : $price;
                        $memberBill->nopay = 0;
                        $memberBill->update();
                        echo '<script type="text/javascript">alert("此用户已申请提款，剩余余额不够减去封号金额--已将申请提现打回！"); </script>';
                    }
                    $message = '[uid]' . $uid;
                    $message .= ' [closedate]' . $closedate;
                    $message .= ' [old_surplus]' . $surplus;
                    $message .= ' [+ask_sum]' . $askSum;
                    $message .= ' [-close_sum]' . $closeSum;
                    $message .= ' [=new_surplus]' . $price;
                    Log::closeAccount($message);
                }
            }

            if (is_null($payLog)) {
                //没有提现：现有余额-封号金额
                $memberBill = MemberBill::model()->getByUid($uid);
                if (!is_null($memberBill)) {
                    $closeSum = isset($closeSumList[$uid]) ? $closeSumList[$uid] : 0;
                    $surplus = $memberBill->surplus;

                    //封号后用户余额=原余额(surplus) - 封号金额(closeSum)
                    $price = $surplus - $closeSum;
                    $memberBill->surplus = $price;
                    $memberBill->update();

                    $message = '[uid]' . $uid;
                    $message .= ' [closedate]' . $closedate;
                    $message .= ' [old_surplus]' . $surplus;
                    $message .= ' [+ask_sum]' . $askSum;
                    $message .= ' [-close_sum]' . $closeSum;
                    $message .= ' [=new_surplus]' . $price;
                    Log::closeAccount($message);
                }
            }


        }
    }

    /**
     * 解除封号
     * @param $account
     * @param $closedate
     * @return string 最后一天有数据日期
     */
    public static function releaseAccount($account, $closedate)
    {
        $memberResourceList = MemberResource::model()->getListByKey($account, MemberResource::STATUS_ALL);
        $startDate = ''; // 导入新数据日期，此日期是用户封号前有数据的最后一天
        foreach ($memberResourceList as $memberResource) {
            //跳过未封号的key
            if ($memberResource->status == MemberResource::STATUS_TRUE) continue;

            $income = IncomeFactory::factory($memberResource->type);
            $incomeList = $income->getListByMrid($memberResource->id, $closedate);
            if (count($incomeList) > 0) $startDate = $incomeList[0]->createtime;

            //封号的数据改为可用
            foreach ($incomeList as $_income) {
                $_income->status = Income::STATUS_TRUE;
                $_income->update();
            }

            $memberResource->status = MemberResource::STATUS_TRUE;
            $memberResource->update();
        }
        return $startDate;
    }
    /**
     * 解除封号 缓存版
     * @param $account
     * @param $closedate
     * @return string 最后一天有数据日期
     * 2015-6-19 14:12:32
     */
    public static function releaseAccountCache($account, $closedate)
    {
        $memberResourceList = MemberResource::model()->getListByKey($account, MemberResource::STATUS_ALL);
        $startDate = ''; // 导入新数据日期，此日期是用户封号前有数据的最后一天
        foreach ($memberResourceList as $memberResource) {
            //跳过未封号的key
            if ($memberResource->status == MemberResource::STATUS_TRUE) continue;

            $income = IncomeFactory::factory($memberResource->type);
            $incomeList = $income->getListByMrid($memberResource->id, $closedate);
            if (count($incomeList) > 0) $startDate = $incomeList[0]->createtime;

            //封号的数据改为可用
            foreach ($incomeList as $_income) {
                $_income->status = Income::STATUS_TRUE;
                $_income->update();
            }

            $memberResource->status = MemberResource::STATUS_TRUE;
            $memberResource->update();
            //item的status改为1
            //修改item
            $item_model = MemberResourceItem::model();
            $item_model -> updateAll(array("status"=>1,'motifytime'=>time()),"`uid`=:uid and `type`=:type and `key`=:key",array(":uid"=>$memberResource['uid'],":type"=>$memberResource['type'],":key"=>$memberResource['key']));
            //更新用户缓存
            Cache::UserGroup($memberResource['uid']);

        }
        return $startDate;
    }


    /**
     * 按业务编号导入数据
     * @param $type
     * @param $account
     * @param $importDate
     * @throws CHttpException
     */
    public static function import($type, $account, $importDate)
    {
        if (empty($account)) {
            Common::redirect(Yii::app()->request->urlReferrer, '请填写要导入数据的业务编号');
        }
        if (empty($importDate) || DateUtil::isDate($importDate) == false) {
            Common::redirect(Yii::app()->request->urlReferrer, '请填写导入时间');
        }

        $logDate = SystemLog::getLogDate($importDate);
        $logs = Log::getSystemLog($type, SystemLog::TYPE_UPLOAD, $logDate);
        if (count($logs) == 0) {
            throw new CHttpException(500, $importDate . '网站数据未导入，不能导入单个用户数据');
        }

        $memberResource = MemberResource::model()->getByKey($account, $type);
        if (is_null($memberResource)) {
            $typeName = Ad::getAdNameById($type);
            throw new CHttpException(500, $typeName . '无此业务编号:' . $account);
        }
        $incomeModel = IncomeFactory::factory($memberResource->type);
        $income = $incomeModel->getByuid($memberResource->uid, $importDate);

        //如果当天有数据则不用导入
        if (is_null($income) === false) {
            throw new CHttpException(500, '已有数据，不能重复导入');
        };

        $url = $incomeModel->getApiUrl($importDate);

        $needText = array(Ad::TYPE_JSDH);
        $needSelf = array(Ad::TYPE_YCGG);
        $needSelfdbt = array(Ad::TYPE_DBT);

        if (in_array($type, $needText)) {
            $txt = Common::createGet($url);
            if (empty($txt)) {
                throw new CHttpException(500, '接口中没有数据，请检查数据是否存在。');
            }
            $incomeModel->createByText($txt, $account);
        } elseif (in_array($type, $needSelf)) {
            $incomeModel->createBySelf($account);
        } elseif (in_array($type, $needSelfdbt)) {
            $incomeModel->createBySelf($account);
        }else {
            $dom = new DOMDocument();
            if (@$dom->load($url) === false) {
                throw new CHttpException(500, '接口中没有数据，请检查数据是否存在。');
            }
            $incomeModel->createByXml($dom, $account);
        }

        $log = ' [import] [key]' . $account . ' [date]' . $importDate;
        Log::closeAccount('导入封号数据，' . $log);
    }

    /**
     * 业务特殊封号
     * @param string[] $accountList
     * @param string $closedate
     * @param $type
     * @throws Exception
     * @return int
     */
    public static function closeType($accountList, $closedate, $type)
    {
        $bindSamples = MemberResource::model()->getByKeys($accountList, $type);
        if (empty($bindSamples)) {
            throw new Exception('没有找到相关业务编号');
        }
        $income = IncomeFactory::factory($type);
        $incomes = $income->getListByDateTime($closedate, 'mrid');
        $updateCount = 0;
        foreach ($bindSamples as $bindSample) {
            if (isset($incomes[$bindSample->id])) {
                $income = $incomes[$bindSample->id];

                if ($income->status == Income::STATUS_FALSE) continue;

                $income->status = Income::STATUS_FALSE;
                $result = $income->update();
                $updateCount += $result ? 1 : 0;
            }
        }

        return $updateCount;
    }

}