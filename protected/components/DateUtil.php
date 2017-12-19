<?php

/**
 * Explain: 日期相关工具--保留
 */
class DateUtil
{
    /** 标准日期 */
    const F_DATE = 'Y-m-d';
    /** 标准年月 */
    const F_MONTH = 'Y-m';
    /** 标准日期+时间 */
    const F_DATE_TIME = 'Y-m-d H:i:s';
    /** 标准时间 */
    const F_TIME = 'H:i:s';

    /**
     * 获取星期
     * @param $w
     * @return string
     */
    public static function getWeek($w)
    {
        $weekarray = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
        return $weekarray[$w];
    }

    /**
     * 根据日期获取当天开始结束时间戳
     * @param $date
     * @return array
     */
    public static function getTimeByDate($date)
    {
        try {
            $time = strtotime($date);
            $y = date('Y', $time);
            $m = date('m', $time);
            $d = date('d', $time);

            $first = mktime(0, 0, 0, $m, $d, $y);
            $last = mktime(0, 0, 0, $m, $d + 1, $y) - 1;
            return array('first' => $first, 'last' => $last);
        } catch (Exception $e) {
            return array('first' => 0, 'last' => 0);
        }
    }

    /**
     * 计算两个日期相差天数
     * @param $date1
     * @param $date2
     * @return int
     */
    public static function dateDiff($date1, $date2)
    {
        $d1 = strtotime($date1);
        $d2 = strtotime($date2);
        return round(($d2 - $d1) / 3600 / 24);

        // PHP5.3以上才能使用，现在服务器是5.2版
        //        $time1 = new DateTime($date1);
        //        $time2 = new DateTime($date2);
        //        return $time1->diff($time2)->format('%r%a');
    }

    /**
     * @param $str
     * @param string $format
     * @return bool
     */
    public static function isDate($str, $format = self::F_DATE)
    {
        if (empty($str)) return false;
        $str1 = date($format, strtotime($str));
        return $str == $str1;
    }

    /**
     * 格式化日期
     * @param $str
     * @param string $format
     * @return bool|string
     */
    public static function dateFormate($str, $format = self::F_DATE)
    {
        $time = is_numeric($str) ? $str : strtotime($str);
        return date($format, $time);
    }

    /**
     * 创建年列表
     * @param int $sum
     * @return array
     */
    public static function getYearList($sum = 5)
    {
        $year = date('Y');
        $yearList = array();
        for ($i = 0; $i < $sum; $i++) {
            $y = $year - $i;
            $yearList[$y] = $y;
        }
        return $yearList;
    }

    /**
     * 传教日列表，当前日向前sum天
     * @param int $sum
     * @return array
     */
    public static function getDayList($sum = 1)
    {
        $dayList = array();
        for ($i = 0; $i < $sum; $i++) {
            $dayList[] = self::getDate(strtotime('-' . $i . ' day'));
        }
        return $dayList;
    }

    /**
     * 创建月份列表
     * @return array
     */
    public static function getMonthList()
    {
        return array(
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12'
        );
    }

    /**
     * @param null|int $time
     * @param string $format
     * @return string
     */
    public static function getDate($time = null, $format = self::F_DATE)
    {
        if (is_null($time)) {
            return date($format);
        } elseif (empty($time) || $time < 0) {
            return '';
        } else {
            return date($format, $time);
        }
    }

    /**
     * @param $date
     * @return int
     */
    public static function time($date = null)
    {
        if ($date === null) {
            return isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
        }

        return strtotime($date);
    }
}