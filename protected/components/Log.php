<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-14 上午10:15
 * Explain: Log
 */
class Log 
{
    /** 应用 */
    const CATEGROY_APPLICATION = 'info';
    /** 资源回收 */
    const CATEGROY_RECYCLE = 'recycle';
    /** 资源封号 */
    const CATEGROY_CLOSEACCOUNT = 'closeaccount';
    /** 修改用户信息 */
    const CATEGROY_EDIT_MEMBER = 'editmember';
    /** 修改用户单价 */
    const CATEGROY_MEMBER_RESOURCE_PRICE = 'memberResourcePrice';
    /** 用户动作记录 */
    const CATEGROY_MEMBER_EVENT = 'memberEvent';
    /** 用户注册 */
    const CATEGROY_REGISTER = 'register';

    /** ERROR */
    const CATEGROY_ERROR = 'error';

    public static function error($message)
    {
        self::info($message, self::CATEGROY_ERROR);
    }

    /**
     * @param $message
     * @param string $category
     * @param string $level
     */
    public static function info($message, $category = self::CATEGROY_APPLICATION, $level = CLogger::LEVEL_INFO)
    {
        $user = '[manage]' . Yii::app()->user->getState('username') . ' ';
        $message = $user . $message;
        $log = Yii::getLogger();
        $log->log($message, $level, $category);
    }

    /**
     * @param $message
     * @param string $category
     * @param string $level
     */
    public static function memberEvent($message, $category = self::CATEGROY_MEMBER_EVENT, $level = CLogger::LEVEL_INFO)
    {
        $user = '[Member]' . Yii::app()->user->getState('member_username') . ' ';
        $message = $user . $message;
        $log = Yii::getLogger();
        $log->log($message, $level, $category);
    }

    /**
     * @param $message
     */
    public static function infoManage($message)
    {
        self::info($message);
    }

    /**
     * 记录回收业务资源log
     * @param $message
     */
    public static function recycle($message)
    {
        self::info($message, self::CATEGROY_RECYCLE);
    }

    /**
     * 业务封号log
     * @param $message
     */
    public static function closeAccount($message)
    {
        self::info($message, self::CATEGROY_CLOSEACCOUNT);
    }

    /**
     * 修改用户信息
     * @param $message
     */
    public static function editMember($message)
    {
        self::info($message, self::CATEGROY_EDIT_MEMBER);
    }

    /**
     * 修改用户单价
     * @param $message
     */
    public static function editMemberResourcePrice($message)
    {
        self::info($message, self::CATEGROY_MEMBER_RESOURCE_PRICE);
    }

    /**
     * 用户注册信息
     * @param $message
     */
    public static function register($message)
    {
        self::info($message, self::CATEGROY_REGISTER);
    }

    /**
     * 添加历史记录
     * @param $type
     * @param $content
     * @param $target
     * @param $operate
     * @param int $status
     * @param string $date
     */
    public static function addSystemLog($type, $content, $target, $operate, $status = 0, $date = '')
    {
        $systemLog = new SystemLog();
        $systemLog->type = $type;
        $systemLog->content = $content;
        $systemLog->target = $target;
        $systemLog->operate = $operate;
        $systemLog->status = $status;
        $systemLog->date = empty($date) ? date('Y-m-d H:i:s') : $date;
        $systemLog->insert();
    }

    /**
     * 根据资源类型获取历史记录
     * @param $resourceType
     * @param string $type
     * @param string $date
     * @return array
     */
    public static function getSystemLog($resourceType, $type = SystemLog::TYPE_UPLOAD, $date = '')
    {
        $systemLog = new SystemLog();
        $systemLog->unsetAttributes();
        $systemLog->type = $type;
        $systemLog->target = strtoupper($resourceType);
        $systemLog->status = Common::STATUS_TRUE;
        if ($type == SystemLog::TYPE_UPLOAD) {
            if (empty($date)) {
                $systemLog->date = date('Y-m-d');
            } else {
                $systemLog->date = $date;
            }
        }
        if ($type == SystemLog::TYPE_COUNT) {
            if (empty($date)) {
                $systemLog->date = date('Y-m');
            } else {
                $systemLog->date = Common::getNextMonth($date);
            }

        }
        return SystemLog::model()->getByObj($systemLog);
    }
    
    /**
     * 根据日期和业务判断此业务是否已导入
     * @param $resourceType
     * @param string $type
     * @param $date
     * @return static[]
     */
    public static function getTargetBySystemLog($resourceType,$type = SystemLog::TYPE_UPLOAD,$date){

        $target = strtoupper($resourceType);
        $status = Common::STATUS_TRUE;
        
        $s = " type='".$type."' and target='".$target."' and status=".$status." and is_show=1 and content LIKE '%".$date."%'";

        return SystemLog::model()->findAll($s);
    }

    public static function getCountLog($resourceType='MANAGE', $type = SystemLog::TYPE_COUNT){
        $systemLog = new SystemLog();
        $systemLog->unsetAttributes();
        $target = strtoupper($resourceType);
        $status = Common::STATUS_TRUE;
        $s = " type='".$type."' and target='".$target."' and status=".$status;
        return SystemLog::model()->findAll($s);
    }

}