<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-7-5 下午3:48
 * Explain: Javascript相关
 */
class Script
{
    const MARQUEE = 'jquery/jquery.marquee.js';
    const JQUERY_UI = 'core/jquery-ui-1.10.2.custom.min.js';
    const JQUERY_UI_CN = 'core/jquery.ui.datepicker-zh-CN.min.js';
    //const JQUERY_UI_CSS = 'core/css/redmond/jquery-ui-1.10.2.custom.min.css';
    const JQUERY_UI_CSS = 'core/css/custom-theme/jquery-ui-1.10.0.custom.css';
    const JQUERY_TOOLS = 'jquery/jquery.tools.min.js';
    /** jquery1.9.1 向下兼容 */
    const JQUERY_MIGRATE = 'core/jquery-migrate-1.1.1.js';


    /** 提示气泡 */
    const QTIP = 'qtip/jquery.qtip.min.js';
    const QTIP_CSS = 'qtip/jquery.qtip.min.css';

    /** 日历 */
    const WDATE_PICKER = 'My97DatePicker/WdatePicker.js';
    /** 曲线图 */
    const HIGHSTOCK = 'highcharts/highstock.js';
    /** 在线编辑器 */
    const UEDITOR_CONFIG = 'ueditor/ueditor.config.js';
    const UEDITOR = 'ueditor/ueditor.all.min.js';

    /**
     * @return string
     */
    public static function getPath()
    {
        return Common::getAppParam(Common::DIR_JS);
    }

    /**
     * 获取静态文件域名
     * @return string
     */
    public static function getUrl()
    {
        //development,test
        return Yii::app()->baseUrl;
    }

    /**
     * 获取静态文件地址
     * @param string $dir
     * @return string
     */
    public static function getStaticUrl($dir ='/js/')
    {
        return Script::getUrl() . Common::getAppParam($dir);
    }

    /**
     * 注册jqueryUI
     */
    public static function registerJqueryUI()
    {
        Script::registerScriptFile(Script::JQUERY_UI);
        Script::registerScriptFile(Script::JQUERY_UI_CN);
        Script::registerCssFile(Script::JQUERY_UI_CSS, Script::getPath());
    }

    /**
     * 注册页面编辑器
     */
    public static function registerUeditor()
    {
        Script::registerScriptFile(Script::UEDITOR_CONFIG);
        Script::registerScriptFile(Script::UEDITOR);
    }

    /**
     * 注册js文件到页面（页面级js专用，调用根目录js中的文件）
     * @param $filename
     * @param $pos
     */
    public static function registerScriptFile($filename, $pos = CClientScript::POS_END)
    {
        if (empty($filename)) return;
        Yii::app()->clientScript->registerScriptFile('/js/' . $filename, $pos);
    }

    /**
     * 注册css文件到页面
     * @param $filename
     * @param null $dir
     */
    public static function registerCssFile($filename, $dir = null)
    {
        if (empty($filename)) return;
        if (empty($dir)) {
            Yii::app()->clientScript->registerCssFile('/css/' . $filename);
        } else {
            Yii::app()->clientScript->registerCssFile(self::getUrl() . $dir . $filename);
        }
    }

}