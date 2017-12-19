<?php

/**
 * Explain: 用户收入数据相关类--保留
 */
class MemberIncome
{
    /**
     * 根据日期获得当天数据
     * 注：推广提成没有代理商扣量
     * @param $uid
     * @param $date
     * @param $point
     * @param null $adList
     * @return array
     */
    public static function getDataProviderByDate($uid, $date, $point, $adList =null)
    {
        // 查询各资源当日收入
        $adList = is_null($adList) ? Product::model()->getKeywordList() : $adList;
        $typeList = array_keys($adList);
        $fix = Yii::app()->db->tablePrefix;
        $sql = 'SELECT ';
        //地推专用
        if($date=="7days")
        {
            $date = date('Y-m-d', strtotime('-1 day'));
            $date2 = date('Y-m-d', strtotime('-7 days'));
            foreach ($typeList as $type) {
                if ($type == Ad::TYPE_COMMISSION) continue;
                if ($sql != 'SELECT ') {
                    $sql .= ',';
                }
                $tableName = $fix . 'income_' . $type;
                $sql .= '(SELECT SUM(`data`) FROM ' . $tableName . ' WHERE uid=' . $uid . ' AND `status`=1 AND createtime>=\'' . $date2 . '\'  AND createtime<=\'' . $date . '\') AS ' . $type;
            }
        }
        else
        {
            foreach ($typeList as $type) {
                if ($type == Ad::TYPE_COMMISSION) continue;
                if ($sql != 'SELECT ') {
                    $sql .= ',';
                }
                $tableName = $fix . 'income_' . $type;
                $sql .= '(SELECT SUM(`data`) FROM ' . $tableName . ' WHERE uid=' . $uid . ' AND `status`=1 AND createtime=\'' . $date . '\') AS ' . $type;
            }
        }

        $incomeList = array(); //收入合计
        if ($sql != 'SELECT ') {
            $incomeList = Yii::app()->db->createCommand($sql)->queryRow();
        }
        //地推不使用
        if($date!="7days")
        {
            if (in_array(Ad::TYPE_COMMISSION, $typeList)) {
                $memberIncomeList = MemberIncome::getMemberIncomeListByAgentId($uid, $date, $date);
                $memberIncomeSum = MemberIncome::getSumByMemberIncomeList($memberIncomeList, 'agentSum');
                $incomeList[Ad::TYPE_COMMISSION] = $memberIncomeSum;
            }
        }


        $data = array(); //数据
        $amount = 0;
        foreach ($adList as $k => $v) {
            $_data = empty($incomeList[$k]) ? 0 : $incomeList[$k];
            if ($k != Ad::TYPE_COMMISSION) {
                $_data = empty($point) ? $_data : Ad::computeMemberSum($_data, $point);
            }
            if(!empty($_data))
            {
                $data[$k] = $_data;
                $amount += $_data;
            }

        }

        $data['id'] = 1;
        $data['amount'] = $amount;
        $data['dates'] = $date;
        return $data;
    }
    //7日历史收益
    public static function getDataProviderByWeek($uid, $date, $point, $adList =null)
    {
        // 查询各资源当日收入
        $adList = is_null($adList) ? Product::model()->getKeywordList() : $adList;
        $typeList = array_keys($adList);
        $fix = Yii::app()->db->tablePrefix;
        $sql = 'SELECT ';
        //导入日期7日收益专用
        $date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $date2 = date('Y-m-d', strtotime('-7 days', strtotime($date)));
        foreach ($typeList as $type) {
            if ($type == Ad::TYPE_COMMISSION) continue;
            if ($sql != 'SELECT ') {
                $sql .= ',';
            }
            $tableName = $fix . 'income_' . $type;
            $sql .= '(SELECT SUM(`data`) FROM ' . $tableName . ' WHERE uid=' . $uid . ' AND `status`=1 AND createtime>=\'' . $date2 . '\'  AND createtime<=\'' . $date . '\') AS ' . $type;
        }


        $incomeList = array(); //收入合计
        if ($sql != 'SELECT ') {
            $incomeList = Yii::app()->db->createCommand($sql)->queryRow();
        }
        if (in_array(Ad::TYPE_COMMISSION, $typeList)) {
            $memberIncomeList = MemberIncome::getMemberIncomeListByAgentId($uid, $date, $date);
            $memberIncomeSum = MemberIncome::getSumByMemberIncomeList($memberIncomeList, 'agentSum');
            $incomeList[Ad::TYPE_COMMISSION] = $memberIncomeSum;
        }


        $data = array(); //数据
        $amount = 0;
        foreach ($adList as $k => $v) {
            $_data = empty($incomeList[$k]) ? 0 : $incomeList[$k];
            if ($k != Ad::TYPE_COMMISSION) {
                $_data = empty($point) ? $_data : Ad::computeMemberSum($_data, $point);
            }
            if(!empty($_data))
            {
                $data[$k] = $_data;
                $amount += $_data;
            }

        }

        $data['id'] = 1;
        $data['amount'] = $amount;
        $data['dates'] = $date;
        return $data;
    }

    /**
     * 根据年月获取收入数据
     * @param $yearMonth
     * @param $uid
     * @param null $adList
     * @param null $bod
     * @return array
     */
    public static function getListByDate($yearMonth, $uid, $adList = null, $bod = null)
    {
        $data = array();
        $adList = ($adList === null) ? Product::model()->getKeywordList() : $adList;
        $incomeList = self::getIncomeList($yearMonth, $uid, $adList, $bod);
        if (empty($incomeList)) {
            return $data;
        }

        //整理数据
        $_incomeList = array();
        foreach ($incomeList as $income) {
            $t = $income['type'];
            $_incomeList[$t][] = $income;
        }

        //合并到整体表
        foreach ($adList as $k => $v) {
            if (!isset($_incomeList[$k])) {
                continue;
            }
            foreach ($_incomeList[$k] as $income) {
                $_dates = $income['createtime'];
                $data[$_dates][$k] = $income['data'];
                $data[$_dates]['dates'] = $_dates;
            }
        }
        return $data;
    }

    /**
     * 获取有数据的最后一天日期
     * @param null $adList
     * @return array
     */
    public static function getLastDate($adList = null)
    {
        $adList = ($adList === null) ? Product::model()->getKeywordList() : $adList;
        $adList = Ad::clearType($adList);
        $dateList = array();
        $sql = 'SELECT ';
        $fix = Yii::app()->db->tablePrefix;
        foreach ($adList as $k => $v) {
            if ($sql != 'SELECT ') {
                $sql .= ',';
            }
            $tableName = $fix . 'income_' . $k;
            $sql .= '(SELECT createtime FROM ' . $tableName . ' ORDER BY createtime DESC LIMIT 1) as ' . $k;
        }
        if (!empty($sql)) {
            $dateList = Yii::app()->db->createCommand($sql)->queryRow();
        }

        return $dateList;
    }
    /**
     * 获取有数据的最后一天日期
     * @param null $adList
     * @return array
     */
    public static function getUserLastDate($uid,$adList = null)
    {
        $adList = ($adList === null) ? Product::model()->getKeywordList() : $adList;
        $adList = Ad::clearType($adList);
        $dateList = array();
        $sql = 'SELECT ';
        $fix = Yii::app()->db->tablePrefix;
        foreach ($adList as $k => $v) {
            if ($sql != 'SELECT ') {
                $sql .= ',';
            }
            $tableName = $fix . 'income_' . $k;
            $sql .= '(SELECT createtime FROM ' . $tableName . ' where uid='.$uid.' ORDER BY createtime DESC LIMIT 1) as ' . $k;
        }
        if (!empty($sql)) {
            $dateList = Yii::app()->db->createCommand($sql)->queryRow();
        }

        return $dateList;
    }

    /**
     * 根据日期列表获取最大日期
     * @param $dateList array
     * @return string
     */
    public static function getMaxDate($dateList)
    {
        $maxDate = '';
        foreach ($dateList as $date) {
            if (empty($maxDate)) {
                $maxDate = $date;
                continue;
            }
            if (DateUtil::dateDiff($maxDate, $date) > 0) {
                $maxDate = $date;
            }
        }
        return $maxDate;
    }

    /**
     * 根据Y-m获取收益合计
     * @param $yearMonth
     * @param $uid
     * @param null|Product[] $adList
     * @return int
     */
    public static function getSumByMonth($yearMonth, $uid, $adList = null)
    {
        $adList = is_null($adList) ? Product::model()->getKeywordList() : $adList;
        $sum = 0;
        $incomeList = self::getIncomeList($yearMonth, $uid, $adList);
        foreach ($incomeList as $income) {
            if (isset($income['data'])) {
                $sum += $income['data'];
            }
        }
        return $sum;
    }
    //总和
    public static function getSumByMonthSum($yearMonth, $uid, $adList = null)
    {
        $adList = is_null($adList) ? MemberResource::getByUidAdlist($uid) : $adList;

        $sum = 0;
        $incomeList = self::getIncomeListSum($yearMonth, $uid, $adList);
        foreach ($incomeList as $income) {
            if (isset($income['data'])) {
                $sum += $income['data'];
            }
        }
        return $sum;
    }

    /**
     * 根据日期区间获取收益数据列表
     * @param $uid
     * @param $firstDate
     * @param string $lastDate
     * @return array
     */
    public static function getListByDateInterval($uid, $firstDate, $lastDate = '')
    {
        $data = array();
        $adlist = Ad::getAdList();
        foreach ($adlist as $type => $name) {
            $model = IncomeFactory::factory($type);
            $incomeList = $model->getListByDateInterval($uid, $firstDate, $lastDate);
            foreach ($incomeList as $income) {
                /** @var $income Income */
                $data[$income->createtime][$type] = $income->data;
                $data[$income->createtime]['dates'] = $income->createtime;
            }
        }
        ksort($data);
        return $data;
    }

    /**
     * 根据年月获取所有用户收益合计
     * @param $yearMonth
     * @return array
     */
    public static function getAllMemberIncomeCount($yearMonth)
    {
        $typeList = array_keys(Ad::getAdList());
        $fix = Yii::app()->db->tablePrefix;
        $sql = '';
        foreach ($typeList as $type) {
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $type;
            $sql .= '(SELECT SUM(`data`) as `sum`,`uid` FROM ' . $tableName .
                ' WHERE `status`=1 AND createtime LIKE \'' . $yearMonth . '%\' GROUP BY `uid`)';
        }

        $sql = 'SELECT SUM(`sum`) as `sum`,`uid` FROM (' . $sql . ') as a GROUP BY `uid`';

        if (!empty($sql)) {
            $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
            $arr = array();
            foreach ($incomeList as $item) {
                $arr[$item['uid']] = $item['sum'];
            }
            return $arr;
        }
        return array();
    }

    /**
     * 根据月份获取当月用户所有收益列表<br>
     * array(uid,mrid,data,createtime,type)
     * @param $yearMonth
     * @param $uid
     * @param $adList
     * @param null $bod
     * @return array
     */
    public static function getIncomeList($yearMonth, $uid, $adList, $bod = null)
    {
        $adList = Ad::clearType($adList);
        $typeList = array_keys($adList);
        $fix = Yii::app()->db->tablePrefix;

        $bodAdKeyList = MemberResource::model()->getBodAdKeyList($uid, $bod);

        $sql = '';
        foreach ($typeList as $_type) {
            //如果按网吧查询，如果没有网吧ID，跳过查询该表
            if (!is_null($bod) && !isset($bodAdKeyList[$_type])) continue;

            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $_type;
            $sql .= '(SELECT *,\'' . $_type . '\' AS `type` FROM ' . $tableName .
                ' WHERE uid = ' . $uid . ' AND `status`=1 AND createtime LIKE \'' . $yearMonth . '%\'';
            if (isset($bodAdKeyList[$_type])) {
                $sql .= ' AND mrid IN(0,' . implode(',', $bodAdKeyList[$_type]) . ')';
            }
            $sql .= ')';
        }
        if (!empty($sql)) {
            $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
            return $incomeList;
        }
        return array();
    }

    //获取本月收益综合 包括下架
    public static function getIncomeListSum($yearMonth, $uid, $adList, $bod = null)
    {
        $fix = Yii::app()->db->tablePrefix;
        $bodAdKeyList = MemberResource::model()->getBodAdKeyList($uid, $bod);

        $sql = '';
        foreach ($adList as $_type) {
            //如果按网吧查询，如果没有网吧ID，跳过查询该表
            if (!is_null($bod) && !isset($bodAdKeyList[$_type])) continue;

            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $_type;
            $sql .= '(SELECT *,\'' . $_type . '\' AS `type` FROM ' . $tableName .
                ' WHERE uid = ' . $uid . ' AND status=1 AND createtime LIKE \'' . $yearMonth . '%\'';
            if (isset($bodAdKeyList[$_type])) {
                $sql .= ' AND mrid IN(0,' . implode(',', $bodAdKeyList[$_type]) . ')';
            }
            $sql .= ')';
        }
        if (!empty($sql)) {
            $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
            return $incomeList;
        }
        return array();
    }

    /**
     * 根据日期获取所有用户当日收益
     * @param $date
     * @return array
     */
    public static function getAllIncomeListByDate($date)
    {
        $adList = Ad::getAdList();
        $typeList = array_keys($adList);
        $fix = Yii::app()->db->tablePrefix;
        $sql = '';
        foreach ($typeList as $type) {
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $type;
            $sql .= '(SELECT *,\'' . $type . '\' AS `type` FROM ' . $tableName .
                ' WHERE `status`=1 AND `createtime` = \'' . $date . '\')';
        }

        if (empty($sql)) {
            return array();
        }
        $incomeList = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        foreach ($incomeList as $_income) {
            $uid = $_income['uid'];
            if (!isset($data[$uid])) {
                $data[$uid]['username'] = '';
                $data[$uid]['type'] = '';
                foreach ($typeList as $_type) {
                    $data[$uid][$_type] = 0;
                }
            }

            $type = $_income['type'];
            $data[$uid]['id'] = $uid;
            $data[$uid][$type] = $_income['data'];
            if (in_array($type, $typeList)) {
                $data[$uid]['sdata'] = $_income['data'];
            }
            $data[$uid]['date'] = $_income['createtime'];
        }

        $uidList = array_keys($data);
        $memberList = Member::model()->getListByIds($uidList);
        foreach ($memberList as $member) {
            /** @var $member Member */
            $data[$member->id]['username'] = $member->username;
            $data[$member->id]['type'] = $member->type;
        }
        return $data;
    }

    /**
     * 根据类型和天数，获取有收入的用户ID列表
     * @param $type
     * @param $datenum
     * @return array
     * @throws Exception
     */
    public static function getHaveIncomUidList($type, $datenum)
    {
        $model = IncomeFactory::factory($type);
        if (is_null($model)) {
            throw new Exception('Income is null');
        }
        return $model->getHaveIncomeUidList($datenum);
    }

    /**
     * 根据代理商ID，获取该代理商相关用户的收入列表
     * @param $agentId
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param bool $haveDate
     * @return array
     */
    public static function getMemberIncomeListByAgentId($agentId, $startDate, $endDate, $status = Income::STATUS_TRUE, $haveDate = false)
    {
        $memberList = Member::model()->getListByAgent($agentId);
        $memberIdList = array_keys($memberList);
        $memberIncomeList = self::getIncomeListByUidList($memberIdList, $startDate, $endDate, $status, $haveDate);
        $arr = array();
        foreach ($memberIncomeList as $mid => $income) {
            $member = $memberList[$mid];
            if ($haveDate) {
                foreach ($income as $date => $sum) {
                    $arr[$mid][$date] = array(
                        'sum' => $sum,
                        'memberSum' => Ad::computeMemberSum($sum, $member->scale),
                        'agentSum' => Ad::computeAgentSum($sum, $member->scale)
                    );
                }
            } else {
                $arr[$mid] = array(
                    'uid' => $mid,
                    'sum' => $income,
                    'memberSum' => Ad::computeMemberSum($income, $member->scale),
                    'agentSum' => Ad::computeAgentSum($income, $member->scale),
                );
            }
        }
        return $arr;
    }
    /**
     * 根据代理商ID，获取该代理商相关用户的收入列表
     * @param $agentId
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param bool $haveDate
     * @return array
     */
    public static function getMemberIncomeListByAgentId2($agentId, $startDate, $endDate, $status = Income::STATUS_TRUE, $haveDate = false)
    {
        $memberList = Member::model()->getListByAgent2($agentId);
        $memberIdList = array_keys($memberList);
        $memberIncomeList = self::getIncomeListByUidList($memberIdList, $startDate, $endDate, $status, $haveDate);
        $arr = array();
        foreach ($memberIncomeList as $mid => $income) {
            $member = $memberList[$mid];
            if ($haveDate) {
                foreach ($income as $date => $sum) {
                    $arr[$mid][$date] = array(
                        'sum' => $sum,
                        'memberSum' => Ad::computeMemberSum($sum, $member->scale),
                        'agentSum' => Ad::computeAgentSum($sum, $member->scale)
                    );
                }
            } else {
                $arr[$mid] = array(
                    'uid' => $mid,
                    'sum' => $income,
                    'memberSum' => Ad::computeMemberSum($income, $member->scale),
                    'agentSum' => Ad::computeAgentSum($income, $member->scale),
                );
            }
        }
        return $arr;
    }

    /**
     * 根据代理商相关用户的收入列表计算收入合计
     * @param $memberIncomeList
     * @param string $type (sum-全部收入,memberSum-用户收入,agentSum-代理商收入)
     * @param bool $haveDate
     * @return int
     */
    public static function getSumByMemberIncomeList($memberIncomeList, $type = 'sum', $haveDate = false)
    {
        $data = 0;
        foreach ($memberIncomeList as $income) {
            if ($haveDate) {
                foreach ($income as $item) {
                    $data += $item[$type];
                }
            } else {
                $data += $income[$type];
            }
        }
        return $data;
    }

    /**
     * 根据uid列表和日期，获取用户收入金额
     * @param $uidList
     * @param $startDate
     * @param $endDate
     * @param int $status 是否封号，默认0
     * @param bool $haveDate 是否按日期排列，默认否
     * @return array
     */
    public static function getIncomeListByUidList($uidList, $startDate, $endDate, $status = Income::STATUS_FALSE, $haveDate = false)
    {
        if (empty($uidList)) return array();

        $typeList = array_keys(Ad::getAdList());
        $fix = Yii::app()->db->tablePrefix;
        $uids = implode(',', $uidList);
        $sql = '';
        foreach ($typeList as $type) {
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $type;
            $sql .= '(SELECT SUM(`data`) as `sum`,`uid`,`createtime` FROM ' . $tableName .
                ' WHERE `status`=' . $status . ' AND uid in(' . $uids . ') ' .
                ' AND createtime >= \'' . $startDate . '\' ' .
                ' AND createtime <= \'' . $endDate . '\' ' .
                ' GROUP BY `uid`,`createtime`)';
        }

        $sql = 'SELECT SUM(`sum`) as `sum`,`uid`,`createtime` FROM (' . $sql . ') as a GROUP BY `uid`,`createtime`';

        $list = array();
        if (!empty($sql)) {
            $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($incomeList as $_v) {
                if ($haveDate) {
                    $list[$_v['uid']][$_v['createtime']] = $_v['sum'];
                } else {
                    if (isset($list[$_v['uid']])) {
                        $list[$_v['uid']] += $_v['sum'];
                    } else {
                        $list[$_v['uid']] = $_v['sum'];
                    }
                }
            }
        }
        return $list;
    }
    /**
     * 根据uid列表和日期，获取用户收入金额--线下地推二级代理商subagent--查找自己
     * @param $memberIdLista
     * @param $startDate
     * @param $endDate
     * @param int $status 是否封号，默认0
     * @param bool $haveDate 是否按日期排列，默认否
     * @return array
     */
    public static function getIncomeListByUidLista($memberIdLista, $startDate, $endDate, $status = Income::STATUS_FALSE, $haveDate = false)
    {
        if (empty($memberIdLista)) return array();

        $typeList = array_keys(Ad::getAdList());
        $fix = Yii::app()->db->tablePrefix;
        $uids = implode(',', $memberIdLista);
        $sql = '';
        foreach ($typeList as $type) {
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $type;
            $sql .= '(SELECT SUM(`data`) as `sum`,`uid`,`createtime` FROM ' . $tableName .
                ' WHERE `status`=' . $status . ' AND uid in(' . $uids . ') ' .
                ' AND createtime >= \'' . $startDate . '\' ' .
                ' AND createtime <= \'' . $endDate . '\' ' .
                ' GROUP BY `uid`,`createtime`)';
        }

        $sql = 'SELECT SUM(`sum`) as `sum`,`uid`,`createtime` FROM (' . $sql . ') as a GROUP BY `uid`,`createtime`';

        $list = array();
        if (!empty($sql)) {
            $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($incomeList as $_v) {
                if ($haveDate) {
                    $list[$_v['uid']][$_v['createtime']] = $_v['sum'];
                } else {
                    if (isset($list[$_v['uid']])) {
                        $list[$_v['uid']] += $_v['sum'];
                    } else {
                        $list[$_v['uid']] = $_v['sum'];
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 获得时间段内网站各资源总收入列表
     * @param $firstDate
     * @param $lastDate
     * @return array
     */
    public static function getAllIncomeCount($firstDate, $lastDate)
    {
        $typeList = Ad::getAdListKeys();
        $fix = Yii::app()->db->tablePrefix;
        $sql = '';
        foreach ($typeList as $_type) {
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $tableName = $fix . 'income_' . $_type;
            $sql .= '(SELECT SUM(`data`) as `sum`,`createtime`,\'' . $_type . '\' AS `type` ' .
                'FROM ' . $tableName . ' WHERE `status`=1 ' .
                'AND createtime >= \'' . $firstDate . '\' ' .
                'AND createtime <=\'' . $lastDate . '\' GROUP BY `createtime`)';
        }

        $data = array();
        if (empty($sql)) return $data;

        $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($incomeList)) return $data;

        foreach ($incomeList as $income) {
            $_date = $income['createtime'];
            $_type = $income['type'];
            $data[$_date][$_type] = $income['sum'];
            $data[$_date]['dates'] = $_date;
        }
        ksort($data);
        return $data;
    }

    /**
     * 根据年月获取所有业务收入合计
     * @param $yearMonth
     * @param $uids
     * @param $type
     * @return array
     */
    public static function getAllIncomeSumByYearMonth($yearMonth,$uids,$type)
    {
        $typeList = Ad::getAdListKeys();
        $fix = Yii::app()->db->tablePrefix;
        $sql = 'SELECT';
        if($type==0)
        {
            foreach ($typeList as $type) {
                $tableName = $fix . 'income_' . $type;
                $sql .= "(SELECT SUM(`data`) FROM " . $tableName . " WHERE `status`=1 AND uid not in(" . $uids . ") AND createtime LIKE '" . $yearMonth . "%') AS " . $type . ",";
            }
        }
        elseif($type==2)
        {
            foreach ($typeList as $type) {
                $tableName = $fix . 'income_' . $type;
                $sql .= "(SELECT SUM(`data`) FROM " . $tableName . " WHERE `status`=1 AND createtime LIKE '" . $yearMonth . "%') AS " . $type . ",";
            }
        }
        else
        {
            foreach ($typeList as $type) {
                $tableName = $fix . 'income_' . $type;
                $sql .= "(SELECT SUM(`data`) FROM " . $tableName . " WHERE `status`=1 AND uid in(" . $uids . ") AND createtime LIKE '" . $yearMonth . "%') AS " . $type . ",";
            }
        }


        //去除最后一个“,”
        $sql = substr($sql, 0, (strlen($sql) - 1));

        $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
        return $incomeList;
    }
    /**
     * 根据年月获取所有业务收入合计-------新版
     * 同时计算出和上月业务收入合计对比
     * @param $yearMonth
     * @param $type
     * @return array
     * @author zkn
     * @date 2015-4-10 09:18:44
     */
    public static function getAllIncomeSumByYearMonthNew($yearMonth,$uids,$uidsyes,$uidsno,$type)
    {

        $typeList = Ad::getAdListKeys();
        //当月收入
        $xxsumyes=0;
        $xxsumno=0;
        if($type==1)
        {
            $monthSumyes = self::getAllIncomeSumByYearMonth($yearMonth,$uidsyes,$type);
            if(empty($monthSumyes))
            {
                $xxsumyes=0;
            }
            else
            {
                foreach($monthSumyes[0] as $mk=>$mv)
                {
                    $xxsumyes=$xxsumyes+$mv;
                }
            }
            $monthSumno = self::getAllIncomeSumByYearMonth($yearMonth,$uidsno,$type);
            if(empty($monthSumno))
            {
                $xxsumno=0;
            }
            else
            {
                foreach($monthSumno[0] as $mkm=>$mvm)
                {
                    $xxsumno=$xxsumno+$mvm;
                }
            }
        }

         $monthSum = self::getAllIncomeSumByYearMonth($yearMonth,$uids,$type);


        //获取上月日期
        $lastMonth = self::getLastMonth($yearMonth);
        //获取上月业务收入合计
        $lastMonthSum = self::getAllIncomeSumByYearMonth($lastMonth,$uids,$type);
        $newDataList = array();
        for ($b = 0; $b < count($typeList); $b++) {
            $_type = $typeList[$b];
            $newDataList[0][$_type] = self::getContrastData($monthSum[0][$_type],$lastMonthSum[0][$_type]);
        }
        /*foreach ($typeList as $type) {
            $newDataList[0][$type] = self::getContrastData($monthSum[0][$type],$lastMonthSum[0][$type]);
        }*/
        return array($newDataList,$xxsumyes,$xxsumno);
    }
    /*
     * 当月和上月所有业务收入合计对比
     * @data 2015-4-10 10:18:55
     * @author zkn
     * */
    public static function getAllIncomeSumByYearMonthTotal($yearMonth,$xxsumyes,$xxsumno)
    {

        //当月收入
        $monthSum = self::getAllIncomeSumByYearMonth($yearMonth,$uids="",$type=2);
        //获取上月日期
        $lastMonth = self::getLastMonth($yearMonth);
        //获取上月业务收入合计
        $lastMonthSum = self::getAllIncomeSumByYearMonth($lastMonth,$uids="",$type=2);
        //当月所有业务收入合计
        $SumNow = array_sum($monthSum[0]);
        //上月所有业务收入合计
        $SumLast = array_sum($lastMonthSum[0]);
        if(empty($SumNow)){
            if(empty($SumLast)){
                $data = "本月和上月都没有收入！";
            }else{
                $data = "本月收入合计：--元，上月收入合计：{$SumLast}元。本月和上月对比结果：<font color=#ff0000><b>{$SumLast}</b></font>";
            }
        }else{
            if(empty($SumLast)){
                $c = $SumNow - $SumLast;
                $data = "本月收入合计：{$SumNow}元(含线下-有销售{$xxsumyes}：无销售{$xxsumno})，上月收入合计：--元。本月和上月对比结果：<font color=#006600><b>+{$c}</b></font>";
            }elseif($SumNow == $SumLast){
                $data = "本月收入合计：{$SumNow}元(含线下-有销售{$xxsumyes}：无销售{$xxsumno})，上月收入合计：{$SumLast}元。本月和上月对比结果：--";
            }elseif($SumNow > $SumLast){
                $c = $SumNow - $SumLast;
                $data = "本月收入合计：{$SumNow}元(含线下-有销售{$xxsumyes}：无销售{$xxsumno})，上月收入合计：{$SumLast}元。本月和上月对比结果：<font color=#006600><b>+{$c}</b></font>";
            }elseif($SumNow < $SumLast){
                $c = $SumNow - $SumLast;
                $data = "本月收入合计：{$SumNow}元(含线下-有销售{$xxsumyes}：无销售{$xxsumno})，上月收入合计：{$SumLast}元。本月和上月对比结果：<font color=#ff0000><b>{$c}</b></font>";
            }else{
                $data = "本月收入合计：{$SumNow}元(含线下-有销售{$xxsumyes}：无销售{$xxsumno})，上月收入合计：{$SumLast}元。本月和上月对比结果：数据出错，请联系技术人员。";
            }
        }
        //$data = self::getContrastData($SumNow,$SumLast);
        return $data;
    }


    /**
     * 根据年月获取所有用户所有业务的收入合计
     * @param $yearMonth
     * @return array
     */
    public static function getAllMemberIncomeSumByYearMonth($yearMonth)
    {
        $typeList = Ad::getAdListKeys();
        $fix = Yii::app()->db->tablePrefix;

        $length = count($typeList);
        $sql = 'SELECT m.`id`,m.`username`,m.`holder`';
        foreach ($typeList as $type) {
            $sql .= ',' . $type;
        }
        $sql .= ' FROM app_member AS m ';
        for ($i = 0; $i < $length; $i++) {
            $type = $typeList[$i];
            $tableName = $fix . 'income_' . $type;
            $sql .= ' LEFT JOIN (SELECT uid,SUM(`data`) AS ' . $type . ' FROM ' . $tableName . ' WHERE `status`=1 AND createtime LIKE \'' . $yearMonth . '%\' GROUP BY uid) AS t_' . $type . ' ON m.`id`=t_' . $type . '.uid';
        }

        $sql .= ' WHERE NOT (';
        foreach ($typeList as $type) {
            $sql .= 'ISNULL(' . $type . ') AND ';
        }

        //去除最后一个“AND”
        $sql = substr($sql, 0, (strlen($sql) - 4));
        $sql .= ')';


        $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
        return $incomeList;
    }
    /**
     * 根据年月获取所有用户所有业务的收入合计
     * @param $yearMonth
     * @return array
     * @author zkn
     * @data 2015-4-9 15:31:43
     * 修改版，包含和上月对比数据
     */
    public static function getAllMemberIncomeSumByYearMonthContrast($yearMonth,$uid="0",$typeas)
    {
        $typeList = Ad::getAdListKeys();
        $fix = Yii::app()->db->tablePrefix;

        $length = count($typeList);
        $sql = 'SELECT m.`id`,m.`username`,m.`type`,m.`holder`';
        foreach ($typeList as $type) {
            $sql .= ',' . $type;
        }
        $sql .= ' FROM app_member AS m ';
        if($uid=="0"){
            for ($i = 0; $i < $length; $i++) {
                $type = $typeList[$i];
                $tableName = $fix . 'income_' . $type;
                $sql .= ' LEFT JOIN (SELECT uid,SUM(`data`) AS ' . $type . ' FROM ' . $tableName . ' WHERE `status`=1 AND createtime LIKE \'' . $yearMonth . '%\' GROUP BY uid) AS t_' . $type . ' ON m.`id`=t_' . $type . '.uid';
            }
        }else{
            for ($i = 0; $i < $length; $i++) {
                $type = $typeList[$i];
                $tableName = $fix . 'income_' . $type;
                $sql .= ' LEFT JOIN (SELECT uid,SUM(`data`) AS ' . $type . ' FROM ' . $tableName . ' WHERE `uid`='.$uid.' AND `status`=1 AND createtime LIKE \'' . $yearMonth . '%\' GROUP BY uid) AS t_' . $type . ' ON m.`id`=t_' . $type . '.uid';
            }
        }


        $sql .= ' WHERE NOT (';
        foreach ($typeList as $type) {
            $sql .= 'ISNULL(' . $type . ') AND ';
        }

        //去除最后一个“AND”
        $sql = substr($sql, 0, (strlen($sql) - 4));
        $sql .= ')';


        $incomeList = Yii::app()->db->createCommand($sql)->queryAll();
        /*
         * 上一个月数据查询
         * */
        $last_month = MemberIncome::getLastMonth($yearMonth);
        $newDataList = array();
        for($a=0;$a<count($incomeList);$a++){
            $uid = $incomeList[$a]['id'];
            $lastData = self::getLastMonthUid($last_month,$uid);
            $newDataList[$a]['id'] = $incomeList[$a]['id'];
            $newDataList[$a]['username'] = $incomeList[$a]['username'];
            $newDataList[$a]['holder'] = $incomeList[$a]['holder'];
            $newDataList[$a]['type'] = $incomeList[$a]['type'];
            for ($b = 0; $b < $length; $b++) {
                $_type = $typeList[$b];
                $newDataList[$a][$_type] = self::getContrastData($incomeList[$a][$_type],$lastData[0][$_type]);
            }
            //合计计算
            $row = $incomeList[$a];
            unset($row['id'], $row['username'], $row['holder'], $row['type']);
            $DataListSum = array_sum($row);
            //上月合计
            $lastRow = $lastData[0];
            unset($lastRow['id'], $lastRow['username'], $lastRow['holder'], $row['type']);
            $lastDataListSum = array_sum($lastRow);
            //计算本月和上月差额
            $newDataList[$a]['temp'] = $DataListSum;
            $newDataList[$a]['sum'] = self::getContrastData($DataListSum,$lastDataListSum);
        }
        if(!empty($newDataList))
        {
            if(!empty($typeas))
            {
                foreach ($newDataList as $key => $row) {
                    if(empty($row[$typeas]))
                    {
                        $row[$typeas]=0;
                    }
                    $volume[$key]  = $row[$typeas];
                }
            }
            else
            {
                foreach ($newDataList as $key => $row) {
                    if(empty($row['temp']))
                    {
                        $row['temp']=0;
                    }
                    $volume[$key]  = $row['temp'];
                }
            }
            array_multisort($volume, SORT_DESC, SORT_NUMERIC, $newDataList);
        }
        return $newDataList;
    }
    /*
     * 数据对比
     * $a:本月数据
     * $b:上月数据
     * */
    public static function getContrastData($a,$b)
    {
        $data = "--";
        if(empty($a)){
            if(empty($b)){
                $data = "--";
            }else{
                if($b='0.00')
                {
                    $data = "--";
                }
                else
                {
                    $data = "0<br>(<font color=#ff0000><b>-".$b."</b></font>)";
                }

            }
        }else{
            if($a==$b){
                $data = $a."<br>(--)";
            }elseif($a>$b){
                $c = round($a-$b,2);
                $data = $a."<br>(<font color=#006600><b>+".$c."</b></font>)";
            }elseif($a<$b){
                $c = round($a-$b,2);
                $data = $a."<br>(<font color=#ff0000><b>".$c."</b></font>)";
            }else{
                $data = "数据出错";
            }
        }
        /*if($a==$b){
            $data = $a."(--)";
        }elseif($a>$b){
            $c = $a-$b;
            $data = $a."(<font color=#006600><b>+".$c."</b></font>)";
        }elseif($a<$b){
            $c = $a-$b;
            $data = $a."(<font color=#ff0000><b>".$c."</b></font>)";
        }else{
            $data = "数据出错";
        }*/

        return $data;
    }
    /*
     * 计算上一个月的月份
     * */
    public static function getLastMonth($yearmonth)
    {
        $timestamp = strtotime($yearmonth);
        $old_year = date("Y",$timestamp);
        $old_month = date("m",$timestamp);
        if($old_month == "1"){
            $new_year = $old_year-1;
            $lastMonth = $new_year."-12";
        }else{
            $new_month = $old_month - 1;
            if($new_month<10){
                $new_month = "0".$new_month;
            }
            $lastMonth = $old_year."-".$new_month;
        }
        return $lastMonth;
    }
    /*
     * 计算用户某月收益数据
     * */
    public static function  getLastMonthUid($yearmonth,$uid)
    {

        $last_month = $yearmonth;
        $userid = $uid;
        $typeList = Ad::getAdListKeys();
        $fix = Yii::app()->db->tablePrefix;

        $length = count($typeList);
        $last_sql = 'SELECT m.`id`,m.`username`,m.`holder`';
        foreach ($typeList as $type) {
            $last_sql .= ',' . $type;
        }
        $last_sql .= ' FROM app_member AS m ';
        for ($i = 0; $i < $length; $i++) {
            $type = $typeList[$i];
            $tableName = $fix . 'income_' . $type;
            $last_sql .= ' LEFT JOIN (SELECT uid,SUM(`data`) AS ' . $type . ' FROM ' . $tableName . ' WHERE `status`=1 AND uid='. $userid .' AND createtime LIKE \'' . $last_month . '%\' GROUP BY uid) AS t_' . $type . ' ON m.`id`=t_' . $type . '.uid';
        }

        $last_sql .= ' WHERE NOT (';
        foreach ($typeList as $type) {
            $last_sql .= 'ISNULL(' . $type . ') AND ';
        }

        //去除最后一个“AND”
        $last_sql = substr($last_sql, 0, (strlen($last_sql) - 4));
        $last_sql .= ')';
        $last_incomeList = Yii::app()->db->createCommand($last_sql)->queryAll();
        if(empty($last_incomeList)){
            $last_incomeList[0]['id']=$userid;
            $last_incomeList[0]['sum']="0";
            for ($ii = 0; $ii < $length; $ii++) {
                $last_incomeList[0][$typeList[$ii]]="0";
            }
            return $last_incomeList;
        }
        return $last_incomeList;
    }


    /**
     * 根据日期和业务类型获取用户收入峰值与日期当天收入对比
     * @param $type
     * @param $date
     * @return array
     */
    public static function getAllMemberIncomeSpike($type, $date)
    {
        $member = Member::model()->tableName();
        $income = IncomeFactory::factory($type)->tableName();
        $sql = 'SELECT m.id,m.username,m.holder,s.data,f.fdata,f.ftime,(ifnull(f.fdata,0)-ifnull(s.data,0)) AS cha FROM ' . $member . ' AS m' .
            ' LEFT JOIN (SELECT * FROM ' . $income . ' WHERE createtime=:createtime AND `status`=1) AS s ON m.id=s.uid' .
            ' LEFT JOIN (SELECT a.uid,a.createtime AS ftime,MAX(`data`) AS fdata FROM (SELECT * FROM ' .
            $income . ' WHERE `status`=1 ORDER BY `data` DESC ) AS a GROUP BY a.uid) AS f ON m.id=f.uid' .
            ' WHERE NOT(ISNULL(s.`data`) AND ISNULL(f.`fdata`)) ORDER BY cha DESC';
        return Yii::app()->db->createCommand($sql)->queryAll(true, array(':createtime' => $date));
    }

    /**
     * 根据日期获取所有用户所有业务类型收入峰值与日期当天收入对比
     * @param $date
     * @return array
     */
    public static function getAllMemberAllTypeSpike($date)
    {
        $types = Ad::getAdList();
        $arr = array();
        foreach (array_keys($types) as $type) {
            $incomes = self::getAllMemberIncomeSpike($type, $date);
            foreach ($incomes as $income) {
                $_id = $income['id'];
                if (isset($arr[$_id])) {
                    $arr[$_id]['fdata'] = $income['fdata'];
                    $arr[$_id]['data'] = $income['data'];
                    $arr[$_id]['cha'] = $income['cha'];
                } else {
                    $arr[$_id] = array(
                        'id' => $_id,
                        'username' => $income['username'],
                        'holder' => $income['holder'],
                        'data' => $income['data'],
                        'fdata' => $income['fdata'],
                        'ftime' => '-',
                        'cha' => $income['cha'],
                    );
                }
            }
        }
        return $arr;
    }

    /**
     * 根据日期和业务列表，查询用户收入明细记录，并补全空余数据
     * @param int $uid 查询的用户ID
     * @param string $date 查询日期（年-月）
     * @param float $point 代理商扣点
     * @param array $adList 业务列表
     * @param null $bod
     * @return array
     */
    public static function getIncomeListByUidAndDate($uid, $date, $point, $adList, $bod = null)
    {
        $nowTime = date('Y-m', time());
        if ($date == $nowTime) {
            $lastDay = date('j', time()) - 1; //月份中的第几天，没有前导零
        } else {
            $lastDay = date('t', strtotime($date)); //给定月份所应有的天数
        }

        //收入明细
        $info = self::getListByDate($date, $uid, $adList, $bod);
        $newInfo = array();
        $sum = 0;
        $keys = array_keys($adList);
        foreach ($info as $ask=>$row) {
            $d = str_replace('-', '', $row['dates']);
            $amount = 0;
            $temp= 0;
            $tempw= 0;
            foreach ($keys as $key) {
                if (isset($row[$key])) {
                    $caminfo=CampaignIncome::model()->find('status =1 and type="'.$key.'" and createtime="'.$row['dates'].'" and uid='.$uid);
                    $v = Ad::computeMemberSum($row[$key], $point);
                    $amount += $v;
                    //综合包如果有数据，可查看详细
                    if ($key == Ad::TYPE_ZMTB2 && $v > 0) {
                        $row[$key] = $v . '&nbsp;<a href="javascript:;" class="zmtb2detail" title="' . $row['dates'] . '">详细</a>';
                    }
                    elseif ($v > 0) {
                        if(!empty($caminfo))
                        {
                            if($caminfo["olddata"]>0)
                            {
                                $temp = $caminfo["campaigndata"];
                                $row[$key] = $caminfo["olddata"];
                                $amount = $amount-$v+$caminfo["olddata"]+$temp;
                                $tempw=$tempw+$temp;
                            }
                        }

                    }
                    else {
                        $row[$key] = $v;
                    }
                } else {
                    $row[$key] = '0.00';
                }
            }
            $row['id'] = '0';
            $row['amount'] = $amount;
            $row['temp'] = $tempw;
            $sum += $amount;
            $newInfo[$d] = $row;
        }

        //推广提成明细
        $commissionList = self::getCommissionList($uid, $date, $lastDay);
        foreach ($commissionList as $key => $item) {
            $_sum = $item['sum'];
            if ($_sum > 0) {
                $_sumStr = number_format($_sum, 2) . CHtml::link(' 详细', array('members/index', 'startdate' => $item['date'], 'enddate' => $item['date']));
            } else {
                $_sumStr = '0.00';
            }
            if (isset($newInfo[$key])) {
                $newInfo[$key][Ad::TYPE_COMMISSION] = $_sumStr;
                $newInfo[$key]['amount'] += $_sum;
            } else {
                foreach ($keys as $typekey) {
                    $newInfo[$key][$typekey] = '0.00';
                }
                $newInfo[$key][Ad::TYPE_COMMISSION] = $_sumStr;
                $newInfo[$key]['id'] = '0';
                $newInfo[$key]['amount'] = empty($_sum) ? '0.00' : $_sum;
                $newInfo[$key]['dates'] = $item['date'];
            }
            $sum += $_sum;
        }

        $nowDays = array();
        for ($i = 1; $i <= $lastDay; $i++) {
            $_date = $date . '-' . sprintf('%02d', $i);
            $nowDays[str_replace('-', '', $_date)] = $_date;
        }

        //获得数据中没有的日期
        $diffDays = array_diff_key($nowDays, $newInfo);
        foreach ($diffDays as $k => $v) {
            $str = '0.00';
            $newInfo[$k] = array(
                'id' => '0',
                'temp' => '0',
                'amount' => $str,
                'dates' => $v
            );
            foreach ($keys as $key) {
                $newInfo[$k][$key] = $str;
            }
        }

        //判断用户隐藏广告业务是否开启，如果开启并且无数据，显示“未发布”。如果未开启，显示“0.00”
        $memberResource = MemberResource::model()->getBidValue($uid, Ad::TYPE_YCGG);
        $ycggOpenStatus = (!is_null($memberResource) && $memberResource->openstatus == MemberResource::OPEN_TRUE);

        $memberResource1 = MemberResource::model()->getBidValue($uid, Ad::TYPE_DBT);
        $dbtOpenStatus = (!is_null($memberResource1) && $memberResource1->openstatus == MemberResource::OPEN_TRUE);

        //获得有数据的最后一天值，没有导入数据的业务，数据改为未发布
        $nowDate = intval(date('Ymd', strtotime('-1 day')));
        $lastDateList = MemberIncome::getLastDate($adList);
        foreach ($lastDateList as $type => $date) {
            if (in_array($type, Ad::cancelAdList()) && $type != Ad::TYPE_SYN) {
                $newInfo[$nowDate][$type] = '未发布';
                continue;
            }
            $lastDay = intval(str_replace('-', '', $date));
            foreach ($newInfo as $d => $item) {
                if ($d <= $lastDay) continue;
                if ($type == Ad::TYPE_SGTP) {
                    $newInfo[$d][$type] = '0.00';
                } elseif ($type == Ad::TYPE_YCGG) {
                    $newInfo[$d][$type] = $ycggOpenStatus ? '0.00' : '0.00';
                }  elseif ($type == Ad::TYPE_DBT) {
                    $newInfo[$d][$type] = $dbtOpenStatus ? '0.00' : '0.00';
                } else {
                    $newInfo[$d][$type] = '0.00';
                }
            }
        }

        ksort($newInfo);
        return array(
            'sum' => $sum,
            'data' => new CArrayDataProvider(
                $newInfo,
                array(
                    'pagination' => array('pageSize' => Common::PAGE_INFINITY)
                )
            ),
        );
    }

    /**
     * @param $uid
     * @param $date
     * @param $lastDay
     * @return array
     */
    public static function getCommissionList($uid, $date, $lastDay)
    {
        $commissionList = array();
        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-' . $lastDay, strtotime($date));
        $incomeList = self::getMemberIncomeListByAgentId($uid, $startDate, $endDate, Income::STATUS_TRUE, true);
        foreach ($incomeList as $item) {
            foreach ($item as $k => $v) {
                $d = str_replace('-', '', $k);
                if (isset($commissionList[$d])) {
                    $commissionList[$d]['sum'] += $v['agentSum'];
                } else {
                    $commissionList[$d] = array('date' => $k, 'sum' => $v['agentSum']);
                }
            }
        }
        return $commissionList;
    }

    /**
     * 按年月统计用户所有收入合计
     * @param $uid
     * @return array [array,float]
     */
    public static function getAllIncomGroupByYearMonth($uid)
    {
        $typeList = Ad::getAdListKeys();
        $typeKey = array();
        $sql = '';
        foreach ($typeList as $type) {
            $typeKey[$type] = 0;
            if (!empty($sql)) {
                $sql .= ' UNION ALL ';
            }
            $income = IncomeFactory::factory($type);
            $tableName = $income->tableName();
            $sql .= "SELECT uid,'{$type}' AS `type`,SUM(DATA) AS `data`,DATE_FORMAT(`createtime`,'%Y-%m') AS `times` FROM `{$tableName}` WHERE uid=:uid GROUP BY times";
        }
        $incomeList = Yii::app()->db->createCommand($sql)->queryAll(true, array(':uid' => $uid));
        $tmpList = array();
        foreach ($incomeList as $item) {
            $tmpList[$item['times']][$item['type']] = $item['data'];
        }
        ksort($tmpList);
        //组合收入数据成为表格形式
        $arr = array();
        $sum = 0;
        foreach ($tmpList as $date => $item) {
            $_typeKey = $typeKey;
            $_typeKey['date'] = $date;
            $_sum = 0;
            foreach ($item as $k => $v) {
                $_typeKey[$k] = $v;
                $_sum += $v;
            }
            $_typeKey['count'] = $_sum;
            $sum += $_sum;
            $arr[] = $_typeKey;
        }
        return array($arr, $sum);
    }
    //获取用户某业务当天收益
    public static function getIncome($uid,$type,$date){
        // 查询各资源当日收入
        $fix = Yii::app()->db->tablePrefix;
        $tableName = $fix . 'income_' . $type;
        $sql = 'SELECT `data` FROM ' . $tableName . ' WHERE uid=' . $uid . ' AND `status`=1 AND createtime=\'' . $date.'\'' ;
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data;
    }
    //获取隐藏的所有业务收益
    public static function getHideIncome($uid){
        $start_date=date("Y-m-01");
        $end_date=date("Y-m-d");
        $sum_hide=0;
        $model=SystemLog::model()->findAll("type=:type and date >=:date and date <=:date1 and status=1 and is_show=0",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$start_date,":date1"=>$end_date));
        if($model){
            foreach($model as $v){
                $type=strtolower($v->target);
                $date=date("Y-m-d",strtotime("-1 day",strtotime($v->date)));
                $data=self::getIncome($uid,$type,$date);
                $sum_hide+=$data['data'];
            }
        }
        return $sum_hide;
    }
}