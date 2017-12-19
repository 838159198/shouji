<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-3-28 下午3:23
 * Explain:Bootstrap前端框架相关
 */
class Bs
{
    const nbsp = '&nbsp;';
    const br = '<br />';
    const post = 'post';
    const get = 'get';
    const p = '<p>&nbsp;</p>';
    //---------------------------------------
    // 按钮相关样式
    //---------------------------------------
    const BTN = 'btn';
    const BTN_INFO = 'btn btn-info';
    const BTN_PRIMARY = 'btn btn-primary';
    const BTN_SUCCESS = 'btn btn-success';
    const BTN_WARNING = 'btn btn-warning';
    const BTN_DANGER = 'btn btn-danger';
    const BTN_INVERSE = 'btn btn-inverse';
    const BTN_LINK = 'btn btn-link';

    const BTN_LARGE = 'btn-lg';
    const BTN_SMALL = 'btn-sm';
    const BTN_MINI = 'btn-xs';

    //---------------------------------------
    // Form相关样式
    //---------------------------------------
    const FORM_HORIZONTAL = 'form-horizontal';
    const FORM_INLINE = 'form-inline';

    //---------------------------------------
    // alert相关样式
    //---------------------------------------
    const ALERT = 'alert';
    const ALERT_ERROR = 'alert alert-warning';
    const ALERT_INFO = 'alert alert-info';

    //---------------------------------------
    // Form-label
    //---------------------------------------
    const CONTROL_LABEL = 'control-label';

    //---------------------------------------
    // Label
    //---------------------------------------
    const LABEL = 'label';
    const LABEL_INFO = 'label label-info';
    const LABEL_SUCCESS = 'label label-success';
    const LABEL_IMPORTANT = 'label label-important';
    const LABEL_WARNING = 'label label-warning';
    const LABEL_INVERSE = 'label label-inverse';

    //---------------------------------------
    // Input
    //---------------------------------------
    const INPUT_MINI = 'input-mini';
    const INPUT_SMALL = 'input-sm';
    const INPUT_XXLARGE = 'input-lg';

    //---------------------------------------
    // Layout
    //---------------------------------------
    const INPUT_APPEND = 'input-group';

    //---------------------------------------
    // Icon
    //---------------------------------------
    const ICON_USER = '<span class="glyphicon glyphicon-user"></span>&nbsp;';
    const ICON_SEARCH = '<span class="glyphicon glyphicon-search"><span id="clickst"></span></span>&nbsp;';
    const ICON_HOME = '<span class="glyphicon glyphicon-th-large"></span>&nbsp;';
    const ICON_EDIT = '<span class="glyphicon glyphicon-edit"></span>&nbsp;';
    const ICON_SIGN = '<span class="glyphicon glyphicon-star"></span>&nbsp;';
    const ICON_CATALOG = '<span class="glyphicon glyphicon-certificate"></span>&nbsp;';
    const TABLE = 'table table-striped';

    /**
     * 文本框标签
     * @return array
     */
    public static function textArea()
    {
        return array('style' => 'width:500px; height:300px;');
    }

    /**
     * 日历插件标签
     * @return array
     */
    public static function dateInput()
    {
        return array('lang' => 'date');
    }

    /**
     * 向页面注册Bootstrap相关文件
     */
    public static function registerBootstrap()
    {
        $baseUrl = Script::getUrl();
        Yii::app()->clientScript->registerCoreScript('jquery');
        Script::registerScriptFile(Script::JQUERY_MIGRATE, CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/bootstrap/js/bootstrap.min.js');
        Yii::app()->clientScript->registerCssFile($baseUrl . '/bootstrap/css/bootstrap.min.css');
    }

    /**
     * 获得控件style的class数组(可变数量参数)
     * @param args ..
     * @return array
     */
    public static function cls()
    {
        $args = func_get_args();
        return array('class' => self::paramToString($args));
    }

    /**
     * 获得警告对话框
     * @param CActiveForm $form
     * @param $model
     * @param string $style
     * @return string
     */
    public static function formErrorSummary($form, $model, $style = null)
    {
        return $form->errorSummary($model, null, null, array('class' => self::paramToString($style)));
    }

    /**
     * 控件样式转字符串
     * @param null $style
     * @return string
     */
    private static function paramToString($style = null)
    {
        if (!empty($style)) {
            if (is_array($style)) {
                return implode(' ', $style);
            } else {
                return $style;
            }
        }
        return '';
    }
}