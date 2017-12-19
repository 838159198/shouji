<?php

/**
 * Explain:平台相关-保留
 *
 */
class Ad
{
    /** 税率 */
    const TAX_RATE = 0.01;
    /** 扣量*/
    const DEDUCT = 0.8;
    const TYPE_ZMTB2= 'greter';
    const TYPE_YCGG= 'greter';
    const TYPE_DBT= 'greter';
    const TYPE_SYN= 'greter';
    const TYPE_SGTP= 'greter';
    const TYPE_SGTS= 'sgts';
    /** 推广提成（特殊类型，不属于普通业务。只用于展示用户推广提成数据。） */
    const TYPE_COMMISSION = 'commission';
    /********************************************************************/
    /** UC浏览器 */
const TYPE_UCLLQ= 'ucllq';
const TYPE_KSP= 'ksp';const TYPE_SGSRF= 'sgsrf';const TYPE_TTKB= 'ttkb';	/** 百度浏览器 */
    const TYPE_BDLLQ= 'bdllq';
    /** 应用宝 */
    const TYPE_YYB= 'yyb';
    /** 九游 **/
    const TYPE_JIUYOU = "jiuyou";
    /* 手机百度 百度搜索 */
    const TYPE_BAIDU = "baidu";
    const TYPE_KYLS = "kyls";
    const TYPE_WEIXIN = "weixin";
    const TYPE_SZDH = "szdh";
    const TYPE_YSDQ360 = "ysdq360";
    const TYPE_SDXW = "sdxw";
    const TYPE_ZHWNL = "zhwnl";
    const TYPE_AYD = "ayd";
    const TYPE_ZSSQ = "zssq";
    const TYPE_TQ = "tq";
    const TYPE_KXXXL = "kxxxl";
    const TYPE_DZDP = "dzdp";
    const TYPE_JD = "jd";
    const TYPE_ZKNS = "zkns";
    const TYPE_AZLLQ = "azllq";
    const TYPE_ZMTQ = "zmtq";
    const TYPE_YYSD = "yysd";
    const TYPE_WDJ= "wdj";
    const TYPE_TXXW = "txxw";
    const TYPE_TXSP = "txsp";
    //const TYPE_LSSP = "lssp";
    const TYPE_AQZM = "aqzm";
    const TYPE_BDDT = "bddt";
    const TYPE_JYYXZX = "jyyxzx";
    const TYPE_LLQ360 = "llq360";
    const TYPE_WZDQ = "wzdq";
    const TYPE_QSBK = "qsbk";
    const TYPE_GDDT = "gddt";
    const TYPE_YYZX = "yyzx";
    const TYPE_LLQ = "llq";
    const TYPE_ZYSCK = "zysck";
    /* 奇悠阅读 */
    const TYPE_QYYD = "qyyd";
    /* 360安全卫士 */
    const TYPE_AQWS360 = "aqws360";
    /* 讯飞输入法 */
    const TYPE_XFSRF = "xfsrf";
    /* 猎豹清理大师 */
    const TYPE_LBQLDS = "lbqlds";
    /* 爱奇艺 */
    const TYPE_AQY = "aqy";
    /* 万年历 */
    const TYPE_WNL = "wnl";
    /* PPS */
    const TYPE_PPS = "pps";
    /* 百度手机助手 */
    const TYPE_BDSJZS = "bdsjzs";
    /* 2345ydw 阅读王 */
    const TYPE_2345YDW = "2345ydw";
    /* jjddz 斗地主 */
    const TYPE_JJDDZ = "jjddz";
    /* 2345手机助手 */
    const TYPE_2345SJZS = "2345sjzs";
    /* 2345影视大全*/
    const TYPE_2345YSDQ = "2345ysdq";
    /* 触宝电话 */
    const TYPE_CBDH = "cbdh";
    /* 腾讯手机管家 */
    const TYPE_TXSJGJ = "txsjgj";
    /* 360手机助手 */
    const TYPE_SJZS360 = "sjzs360";
    /* pp助手 */
    const TYPE_PPZS = "ppzs";
    /* QQ浏览器 */
    const TYPE_QQLLQ = "qqllq";
    /* 手机淘宝 */
    const TYPE_TAOBAO = "taobao";
    /* 美拍 */
    const TYPE_MEIPAI = "meipai";
    /*搜狗市场*/
    const TYPE_SGSC = "sgsc";
    /*美团*/
    const TYPE_MEITUAN = "meituan";
    /*网易云音乐*/
    const TYPE_WYYYY = "wyyyy";
    /*2345王牌浏览器*/
    const TYPE_2345WPLLQ = "2345wpllq";
    /*2345天气王*/
    const TYPE_2345TQW = "2345tqw";
    /*搜狐视频*/
    const TYPE_SHSP = "shsp";
    /*掌阅书城*/
    const TYPE_ZYSC = "zysc";
    /*今日头条*/
    const TYPE_JRTT = "jrtt";
    /*酷我音乐*/
    const TYPE_KWYY = "kwyy";
    /*暴风影音*/
    const TYPE_BFYY = "bfyy";


    /*留存列表*/
    const DATE_7 = "7";
    const DATE_10 = "10";
    const DATE_15 = "15";
    const DATE_30= "30";

    /*留存分割线*/
    const CUT_10 = "10";
    const CUT_20 = "20";
    const CUT_30 = "30";
    const CUT_40 = "40";
    const CUT_50 = "50";
    const CUT_60 = "60";
    const CUT_70 = "70";
    const CUT_80 = "80";
    const CUT_90 = "90";
    const CUT_100 = "100";

    /*用户分组*/
    const GROUP_0 = '0fz';
    const GROUP_69 = '69fz';
    const GROUP_77 = '77fz';
    const GROUP_88 = '88fz';
    const GROUP_99 = '99fz';
    const GROUP_707 = '707fz';
    /**
     * 业务资源名称列表
     * @param bool $getall
     * @param string $userType
     * @return array
     */

    /*public static function getAdList($getall = true, $userType = Common::USER_TYPE_MANAGE)
    {
        $arr = array(
            self::TYPE_UCLLQ => 'UC浏览器',
            self::TYPE_PPZS => 'PP助手',
            self::TYPE_BAIDU => '手机百度',
            self::TYPE_KYLS => '手机铃声',
            self::TYPE_WEIXIN => '微信',
            self::TYPE_SZDH => '神指电话',
            self::TYPE_YSDQ360 => '360影视大全',
            self::TYPE_SDXW => '今日十大新闻',
            self::TYPE_ZHWNL => '中华万年历',
            self::TYPE_AYD => '爱阅读',
            self::TYPE_ZSSQ => '追书神器',
            self::TYPE_TQ => '天气',
            self::TYPE_KXXXL => '开心消消乐',
            self::TYPE_DZDP => '大众点评',
            self::TYPE_JD => '京东',
            self::TYPE_ZKNS => 'zaker新闻',
            self::TYPE_AZLLQ => '安卓浏览器',
            self::TYPE_ZMTQ => '最美天气',
            self::TYPE_YYSD => '应用商店',
            self::TYPE_WDJ => '豌豆荚',
            self::TYPE_TXXW => '腾讯新闻',
            self::TYPE_TXSP => '腾讯视频',
            //self::TYPE_LSSP => '乐视视频',
            //self::TYPE_AQZM => '安全桌面',
            self::TYPE_BDDT => '百度地图',
            self::TYPE_JYYXZX => '九游游戏中心',
            self::TYPE_LLQ360 => '360浏览器',
            //self::TYPE_QSBK=> '糗事百科',
            self::TYPE_GDDT => '高德地图',
            self::TYPE_BDLLQ => '百度浏览器',
            self::TYPE_YYB => '应用宝',
            //self::TYPE_SGSC => '搜狗市场',
            //self::TYPE_TAOBAO => '手机淘宝',
            //self::TYPE_QQLLQ => 'QQ浏览器',
            //self::TYPE_JIUYOU =>'九游',
            self::TYPE_XFSRF => '讯飞输入法',
            self::TYPE_LLQ => '浏览器',
            self::TYPE_YYZX=> '应用中心',
            //self::TYPE_2345WPLLQ => '2345王牌浏览器',
            //self::TYPE_2345SJZS => '2345手机助手',
            //self::TYPE_2345YDW => '2345阅读王',
            self::TYPE_2345TQW => '2345天气王',
            self::TYPE_2345YSDQ => '影视大全',
            self::TYPE_WZDQ => '浏览器2',
            self::TYPE_AQWS360 => '360安全卫士',
            self::TYPE_SJZS360 => '360手机助手',
            self::TYPE_ZYSCK=> '卓易市场',
            self::TYPE_BDSJZS => '百度手机助手',
            self::TYPE_JRTT => '今日头条',
            //self::TYPE_ZYSC => '掌阅书城',
            self::TYPE_SHSP => '搜狐视频',
            self::TYPE_MEITUAN => '美团',
            //self::TYPE_CBDH => '触宝电话',
            self::TYPE_JJDDZ=> 'JJ斗地主',
            self::TYPE_KWYY => '酷我音乐',
            self::TYPE_BFYY => '暴风影音',
            //self::TYPE_PPS => 'PPS',
            //self::TYPE_AQY => '爱奇艺',
            //self::TYPE_LBQLDS => '猎豹清理大师',

            //self::TYPE_QYYD => '奇悠阅读',
            self::TYPE_WNL => '万年历',
            self::TYPE_TXSJGJ => '腾讯手机管家',
            //self::TYPE_MEIPAI => '美拍',
            //self::TYPE_WYYYY => '网易云音乐',

        );
        if ($getall == false) {
            foreach (self::cancelAdList($userType) as $key) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }*/
    public static function getAdList()
    {
        return Product::model()->getKeywordList();
    }

    /**
     * 留存周期列表
     */
    public static function getAdDataretention(){
        $arr = array(
            self::DATE_7=> '7日',
            self::DATE_10 => '10日',
            self::DATE_15 => '15日',
            self::DATE_30 => '30日',
        );
        return $arr;
    }
    /**
     * 用户分组列表
     */
    public static function getAdMemberAgent(){
        $arr = array(
            self::DATE_7=> '7日',
            self::DATE_10 => '10日',
            self::DATE_15 => '15日',
            self::DATE_30 => '30日',
        );
        return $arr;
    }

    /**
     * 留存分割列表
     */
    public static function getAdRetentidonivision(){
        $arr = array(
            self::CUT_10=> '10%',
            self::CUT_20 => '20%',
            self::CUT_30 => '30%',
            self::CUT_40 => '40%',
            self::CUT_50=> '50%',
            self::CUT_60 => '60%',
            self::CUT_70 => '70%',
            self::CUT_80 => '80%',
            self::CUT_90=> '90%',
            self::CUT_100 => '100%',
        );
        return $arr;
    }

    /**
     * 留存分割列表
     */
    public static function getAdUsergroup(){
        $arr = array(
            self::GROUP_0=> '0',
            self::GROUP_69 => '69',
            self::GROUP_77 => '77',
            self::GROUP_88 => '88',self::GROUP_99 => '99',self::GROUP_707 => '707',
        );
        return $arr;
    }


    /*public static function getAdList2( $userType = Common::USER_TYPE_MANAGE)
    {
        $arr = array(
            self::TYPE_BDLLQ => '百度浏览器',
            self::TYPE_UCLLQ => 'UC浏览器',
            self::TYPE_YYB => '应用宝',
           // self::TYPE_SGSC => '搜狗市场',
            //self::TYPE_TAOBAO => '手机淘宝',
            //self::TYPE_QQLLQ => 'QQ浏览器',
            self::TYPE_PPZS => 'PP助手',
            //self::TYPE_JIUYOU =>'九游',
            self::TYPE_BAIDU => '手机百度',
            self::TYPE_KYLS => '手机铃声',
            self::TYPE_WEIXIN => '微信',
            self::TYPE_SZDH => '神指电话',
            self::TYPE_YSDQ360 => '360影视大全',
            self::TYPE_SDXW => '今日十大新闻',
            self::TYPE_ZHWNL => '中华万年历',
            self::TYPE_AYD => '爱阅读',
            self::TYPE_ZSSQ => '追书神器',
            self::TYPE_TQ => '天气',
            self::TYPE_KXXXL => '开心消消乐',
            self::TYPE_DZDP => '大众点评',
            self::TYPE_JD => '京东',
            self::TYPE_ZKNS => 'zaker新闻',
            self::TYPE_AZLLQ => '安卓浏览器',
            self::TYPE_ZMTQ => '最美天气',
            self::TYPE_YYSD => '应用商店',
            self::TYPE_WDJ => '豌豆荚',
            self::TYPE_TXXW => '腾讯新闻',
            self::TYPE_TXSP => '腾讯视频',
            //self::TYPE_LSSP => '乐视视频',
            //self::TYPE_AQZM => '安全桌面',
            self::TYPE_BDDT => '百度地图',
            self::TYPE_JYYXZX => '九游游戏中心',
            self::TYPE_LLQ360 => '360浏览器',
            //self::TYPE_QSBK => '糗事百科',
            self::TYPE_GDDT => '高德地图',
            self::TYPE_WZDQ => '浏览器2',
            self::TYPE_YYZX=> '应用中心',
            self::TYPE_LLQ => '浏览器',
            self::TYPE_ZYSCK => '卓易市场',
            //self::TYPE_QYYD=> '奇悠阅读',
            self::TYPE_AQWS360 => '360安全卫士',
            self::TYPE_XFSRF => '讯飞输入法',
            //self::TYPE_LBQLDS => '猎豹清理大师',
            //self::TYPE_AQY => '爱奇艺',
            self::TYPE_WNL => '万年历',
            //self::TYPE_PPS => 'PPS',
            self::TYPE_BDSJZS => '百度手机助手',
            self::TYPE_JJDDZ => 'JJ斗地主',
            //self::TYPE_2345YDW => '2345阅读王',
            //self::TYPE_2345SJZS => '2345手机助手',
            self::TYPE_2345YSDQ => '影视大全',
            //self::TYPE_CBDH => '触宝电话',
            self::TYPE_TXSJGJ => '腾讯手机管家',
            self::TYPE_SJZS360 => '360手机助手',
           // self::TYPE_MEIPAI => '美拍',
            self::TYPE_MEITUAN => '美团',
            //self::TYPE_WYYYY => '网易云音乐',
            //self::TYPE_2345WPLLQ=> '2345王牌浏览器',
            self::TYPE_2345TQW => '2345天气王',
            self::TYPE_SHSP => '搜狐视频',
            //self::TYPE_ZYSC => '掌阅书城',
            self::TYPE_JRTT => '今日头条',
            self::TYPE_KWYY => '酷我音乐',
            self::TYPE_BFYY => '暴风影音',

        );

        return $arr;
    }*/
    public static function getAdList2()
    {
        return Product::model()->getKeywordList();
    }

    /**
     * 返回已经取消或不向用户展示的业务
     * @param string $userType
     * @return array
     */
    public static function cancelAdList($userType = Common::USER_TYPE_MEMBER)
    {
        $type = array(
            /*self::TYPE_BDLLQ,
            self::TYPE_UCLLQ,
            self::TYPE_YYB,
            self::TYPE_SGSC,
            self::TYPE_TAOBAO,
            self::TYPE_QQLLQ,
            self::TYPE_PPZS,
            self::TYPE_JIUYOU,
            self::TYPE_BAIDU,
            self::TYPE_TXSJGJ,
            self::TYPE_SJZS360,
            self::TYPE_MEIPAI,*/
        );
        if ($userType == Common::USER_TYPE_MEMBER) {
            //$type[] = self::TYPE_SYN;
        }
        return $type;
    }

    /**
     * 清除不属于查询数据库内容的业务
     * @param $adList
     * @return array
     */
    public static function clearType($adList)
    {
        unset($adList[self::TYPE_COMMISSION]);
        return $adList;
    }

    /**
     * 业务资源keyword列表
     * @param string $userType
     * @return array
     */
    public static function getAdListKeys($userType = Common::USER_TYPE_MANAGE)
    {
        return array_keys(self::getAdList($userType));
    }

    /**
     * key值与关系ID相同的资源<br>
     * 这些业务没有ID，只需把关系ID赋予key即可
     * 公司内部业务
     * @return array
     */
/*    public static function getAplus()
    {
        return array(self::TYPE_AQWS360,self::TYPE_XFSRF,self::TYPE_LBQLDS,self::TYPE_AQY,self::TYPE_WNL,self::TYPE_PPS,self::TYPE_BDSJZS,self::TYPE_UCLLQ,self::TYPE_BDLLQ,self::TYPE_YYB,self::TYPE_JIUYOU,self::TYPE_BAIDU,self::TYPE_KYLS,self::TYPE_WEIXIN,self::TYPE_SZDH,self::TYPE_YSDQ360,self::TYPE_SDXW,self::TYPE_ZHWNL,self::TYPE_AYD,self::TYPE_ZSSQ,self::TYPE_TQ,self::TYPE_KXXXL,self::TYPE_DZDP,self::TYPE_JD,self::TYPE_ZKNS,self::TYPE_AZLLQ,self::TYPE_ZMTQ,self::TYPE_YYSD,self::TYPE_WDJ,self::TYPE_TXXW,self::TYPE_TXSP,self::TYPE_AQZM,self::TYPE_BDDT,self::TYPE_JYYXZX,self::TYPE_LLQ360,self::TYPE_QSBK,self::TYPE_GDDT,self::TYPE_WZDQ,self::TYPE_YYZX,self::TYPE_LLQ,self::TYPE_ZYSCK,self::TYPE_QYYD,self::TYPE_2345YDW,self::TYPE_JJDDZ,self::TYPE_2345SJZS,
            self::TYPE_2345YSDQ,self::TYPE_CBDH,self::TYPE_TXSJGJ,self::TYPE_SJZS360,self::TYPE_PPZS,self::TYPE_QQLLQ,self::TYPE_TAOBAO,
            self::TYPE_MEIPAI,self::TYPE_SGSC,self::TYPE_MEITUAN,self::TYPE_WYYYY,self::TYPE_2345WPLLQ,self::TYPE_2345TQW,self::TYPE_SHSP,self::TYPE_ZYSC,self::TYPE_JRTT,self::TYPE_KWYY,self::TYPE_BFYY,);
    }*/
    public static function getAplus()
    {
         return Product::model()->getKeywordList3();
    }


    /**
     * 计算代理商扣点后用户收入
     * @param $money
     * @param $point
     * @return float
     */
    public static function computeMemberSum($money, $point)
    {
        if (empty($money)) {
            return 0;
        }
        if (empty($point)) {
            return $money;
        }
        $sum = $money * (1 - $point);
        return round($sum, 2);
    }

    /**
     * 计算代理商收入
     * @param $money
     * @param $point
     * @return float
     */
    public static function computeAgentSum($money, $point)
    {
        if (empty($money)) {
            return 0;
        }
        if (empty($point)) {
            return 0;
        }
        $sum = self::computeMemberSum($money, $point);
        $sum = $money - $sum;
        return round($sum, 2);
    }

    /**
     * 根据用户收入合计和扣点数，计算代理商提成
     * @param $money
     * @param $point
     * @return float
     */
    public static function getOriginalSumByComputeSum($money, $point)
    {
        if (empty($money)) {
            return 0;
        }
        if (!is_numeric($money) || !is_numeric($point)) {
            return 0;
        }
        $sum = $money / (1 - $point);
        return round($sum, 2);
    }

    /**
     * 计算手续费
     * @param $money
     * @return float
     */
    public static function computeFees($money)
    {
        $tax = $money * self::TAX_RATE; //税率
        $fee = ($money - $tax) * self::TAX_RATE;
        if ($fee <= 5) {
            $fee = 5;
        } elseif ($fee >= 50) {
            $fee = 50;
        } else {
            $fee = round($fee, 2);
        }
        return $fee;
    }
    public static function getTypeNameByUid($uid)
    {
        return "";
    }

    /**
     * 根据获取的key值组PID
     * @param $adType
     * @param $key         create_new model->id;
     * @return string
     */
    public static function getPidByKey($adType, $key)
    {
        if (empty($key)) return '';

        $fix = self::getAdFixByAdType($adType);

        $len = self::getAdValueLengthByAdType($adType);

        return $fix . self::completionZero($key, $len);
    }

    /**
     * 为字符串前补全0
     * @param string $str 字符串 id
     * @param int $len 补全后长度
     * @return string
     */
    public static function completionZero($str, $len)
    {
        if ($len == 0) return $str;
        $strlen = strlen($str);
        if ($strlen >= $len) {
            return $str;
        }
        return str_repeat('0', $len - $strlen) . $str;
    }


    /**
     * 根据广告ID获取名称
     * @param string $id
     * @return string
     */
    public static function getAdNameById($id = '')
    {
        if (empty($id)) return '';
        $adList = self::getAdList();
        return isset($adList[$id]) ? $adList[$id] : '';
    }

}