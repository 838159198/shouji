<?php

/**
 * ajax
 * This is the model class for jquery_ajax data".
 */
class AjaxBack
{
    /** 有效返回-执行成功 */
    const DATA_SUCCESS = 1;

    /** 无效返回-执行失败 */
    const DATA_ERROR = 0;

    /** 无效返回-已存在 */
    const DATA_ERROR_AEXISTS = 2;

    /** 无效返回-不存在  */
    const DATA_ERROR_NOEXISTS = 3;

    /** 无效返回-其他错误  */
    const DATA_ERROR_OTHER = 4;

    /** 无效返回-任务已完成  */
    const DATA_ERROR_ADONE = 5;

    /** 无效返回-任务已删除 */
    const DATA_ERROR_ADEL = 6;

    /** 无效返回-任务已被申请*/
    const DATA_ERROR_APRO = 7;

    /** 无效返回-没有权限*/
    const DATA_ERROR_NOPOWER = 8;

    /** 无效返回-任务未完成*/
    const DATA_ERROR_NODONE = 9;

    /** 无效返回-任务未评分*/
    const DATA_ERROR_NOSCORE = 10;

    /** 无效返回-任务未上报*/
    const DATA_ERROR_NO_PRO = 11;

    /** 无效返回-任务信息为空*/
    const DATA_ERROR_MSG_EMPTY = 12;

    /** 无效返回-图片过大*/
    const DATA_ERROR_PIC_SIZE_BIGGER = 13;

    /** 无效返回-类型不符合*/
    const DATA_ERROR_PIC_TYPE = 14;

    /** 无效返回-已达任务上限*/
    const DATA_ERROR_CEILING = 15;

    /** 无效返回-已经是底级菜单，无法继续添加*/
    const DATA_ERROR_DOWN = 16;


}