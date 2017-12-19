<?php

/**
 * Date: 15-5-13 下午4:42
 * Explain:用户资源收益工厂类
 */
class IncomeFactory
{
//    private static $income = null;

    /**
     * @param $type
     * @return Income
     * @throws CHttpException
     */
    /*public static function factory($type)
    {
        try {
            switch ($type) {
                case Ad::TYPE_UCLLQ:
                    return IncomeUcllq::model();
                    break;
                case Ad::TYPE_BAIDU:
                    return IncomeBaidu::model();
                    break;
                case Ad::TYPE_KYLS:
                    return IncomeKyls::model();
                    break;
                case Ad::TYPE_WEIXIN:
                    return IncomeWeixin::model();
                    break;
                case Ad::TYPE_SZDH:
                    return IncomeSzdh::model();
                    break;
                case Ad::TYPE_YSDQ360:
                    return IncomeYsdq360::model();
                    break;
                case Ad::TYPE_SDXW:
                    return IncomeSdxw::model();
                    break;
                case Ad::TYPE_ZHWNL:
                    return IncomeZhwnl::model();
                    break;
                case Ad::TYPE_AYD:
                    return IncomeAyd::model();
                    break;
                case Ad::TYPE_ZSSQ:
                    return IncomeZssq::model();
                    break;
                case Ad::TYPE_TQ:
                    return IncomeTq::model();
                    break;
                case Ad::TYPE_KXXXL:
                    return IncomeKxxxl::model();
                    break;
                case Ad::TYPE_DZDP:
                    return IncomeDzdp::model();
                    break;
                case Ad::TYPE_JD:
                    return IncomeJd::model();
                    break;
                case Ad::TYPE_ZKNS:
                    return IncomeZkns::model();
                    break;
                case Ad::TYPE_AZLLQ:
                    return IncomeAzllq::model();
                    break;
                case Ad::TYPE_ZMTQ:
                    return IncomeZmtq::model();
                    break;
                case Ad::TYPE_YYSD:
                    return IncomeYysd::model();
                    break;
                case Ad::TYPE_WDJ:
                    return IncomeWdj::model();
                    break;
                case Ad::TYPE_TXXW:
                    return IncomeTxxw::model();
                    break;
                case Ad::TYPE_TXSP:
                    return IncomeTxsp::model();
                    break;
                case Ad::TYPE_AQZM:
                    return IncomeAqzm::model();
                    break;
                case Ad::TYPE_BDDT:
                    return IncomeBddt::model();
                    break;
                case Ad::TYPE_JYYXZX:
                    return IncomeJyyxzx::model();
                    break;
                case Ad::TYPE_LLQ360:
                    return IncomeLlq360::model();
                    break;
                case Ad::TYPE_QSBK:
                    return IncomeQsbk::model();
                    break;
                case Ad::TYPE_GDDT:
                    return IncomeGddt::model();
                    break;
                case Ad::TYPE_WZDQ:
                    return IncomeWzdq::model();
                    break;
                case Ad::TYPE_YYZX:
                    return IncomeYyzx::model();
                    break;
                case Ad::TYPE_LLQ:
                    return IncomeLlq::model();
                    break;
                case Ad::TYPE_BDLLQ:
                    return IncomeBdllq::model();
                break;
                case Ad::TYPE_YYB:
                    return IncomeYyb::model();
                    break;
                case Ad::TYPE_SGSC:
                    return IncomeSgsc::model();
                    break;
                case Ad::TYPE_TAOBAO:
                    return IncomeTaobao::model();
                    break;
                case Ad::TYPE_PPZS:
                    return IncomePpzs::model();
                    break;
                case Ad::TYPE_QQLLQ:
                    return IncomeQqllq::model();
                    break;
                case AD::TYPE_JIUYOU:
                    return IncomeJiuyou::model();
                    break;
                case AD::TYPE_ZYSCK:
                    return IncomeZysck::model();
                    break;
                case AD::TYPE_QYYD:
                    return IncomeQyyd::model();
                    break;
                case AD::TYPE_AQWS360:
                    return IncomeAqws360::model();
                    break;
                case AD::TYPE_XFSRF:
                    return IncomeXfsrf::model();
                    break;
                case AD::TYPE_LBQLDS:
                    return IncomeLbqlds::model();
                    break;
                case AD::TYPE_AQY:
                    return IncomeAqy::model();
                    break;
                case AD::TYPE_WNL:
                    return IncomeWnl::model();
                    break;
                case AD::TYPE_PPS:
                    return IncomePps::model();
                    break;
                case AD::TYPE_BDSJZS:
                    return IncomeBdsjzs::model();
                    break;
                case AD::TYPE_2345YDW:
                    return Income2345ydw::model();
                    break;
                case AD::TYPE_JJDDZ:
                    return IncomeJjddz::model();
                    break;
                case AD::TYPE_2345YSDQ:
                    return Income2345ysdq::model();
                    break;
                case AD::TYPE_2345SJZS:
                    return Income2345sjzs::model();
                    break;
                case AD::TYPE_CBDH:
                    return IncomeCbdh::model();
                    break;
                case Ad::TYPE_TXSJGJ:
                    return IncomeTxsjgj::model();
                    break;
                case Ad::TYPE_SJZS360:
                    return IncomeSjzs360::model();
                    break;
                case Ad::TYPE_MEIPAI:
                    return IncomeMeipai::model();
                    break;
                case Ad::TYPE_MEITUAN:
                    return IncomeMeituan::model();
                    break;
                case Ad::TYPE_WYYYY:
                    return IncomeWyyyy::model();
                    break;
                case Ad::TYPE_2345WPLLQ:
                    return Income2345wpllq::model();
                    break;
                case Ad::TYPE_2345TQW:
                    return Income2345tqw::model();
                    break;
                case Ad::TYPE_SHSP:
                    return IncomeShsp::model();
                    break;
                case Ad::TYPE_ZYSC:
                    return IncomeZysc::model();
                    break;
                case Ad::TYPE_JRTT:
                    return IncomeJrtt::model();
                    break;
                case Ad::TYPE_KWYY:
                    return IncomeKwyy::model();
                    break;
                case Ad::TYPE_BFYY:
                    return IncomeBfyy::model();
                    break;
            }
        } catch (Exception $e) {
            throw new CHttpException(500, '错误！无此类型');
        }
    }*/
    public static function factory($type)
    {
        $typeList=Ad::getAplus();
        if(in_array($type,$typeList)){
            $model='Income'.ucfirst($type);
            return $model::model();
        }else{
            throw new CHttpException(500, '错误！无此类型');
        }
    }
}