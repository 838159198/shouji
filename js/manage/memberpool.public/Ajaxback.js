var data_back =
{
    /************************/
    /*	ajax返回值-成功失败     */
    /************************/
    'DATA_ERROR': 0, /** 无效返回-执行失败 */
'DATA_SUCCESS': 1, /** 有效返回-执行成功 */
'DATA_ERROR_AEXISTS': 2, /** 无效返回-已存在 */
'DATA_ERROR_NOEXISTS': 3, /** 无效返回-不存在  */
'DATA_ERROR_OTHER': 4, /** 无效返回-其他错误  */
'DATA_ERROR_ADONE': 5, /** 无效返回-任务已完成  */
'DATA_ERROR_ADEL': 6, /** 无效返回-任务已删除 */
'DATA_ERROR_APRO': 7, /** 无效返回-任务已被申请*/
'DATA_ERROR_NOPOWER': 8, /** 无效返回-没有权限*/
'DATA_ERROR_NODONE': 9, /** 无效返回-任务未完成*/
'DATA_ERROR_NOSCORE': 10, /** 无效返回-任务未评分*/
'DATA_ERROR_NO_PRO': 11, /** 无效返回-任务未上报*/
'DATA_ERROR_MSG_EMPTY': 12, /** 无效返回-任务信息为空*/
'DATA_ERROR_CEILING': 15,   /** 无效返回-已达任务最大上限*/
'DATA_ERROR_DOWN':  16      /** 无效返回-已达底级菜单，无法继续添加*/
};

var data_back_msg =
{
    /************************/
    /*	ajax返回值-成功失败     */
    /************************/
    'DATA_SUCCESS': '执行成功', /** 有效返回-执行成功 */
'DATA_SUCCESS_ADD_THIS': '执行成功！已添加至本周周任务', /** 执行成功*/
'DATA_SUCCESS_ADD_NEXT': '执行成功！已添加至下周周任务', /** 执行成功*/
'DATA_ERROR': '执行失败', /** 无效返回-执行失败 */
'DATA_ERROR_DOWN': '执行失败,已是底级三级菜单列表，无法继续添加', /** 无效返回-执行失败 */
'DATA_ERROR_AEXISTS': '执行失败！已存在', /** 无效返回-已存在 */
'DATA_ERROR_NOEXISTS': '执行失败！不存在', /** 无效返回-不存在  */
'DATA_ERROR_ADONE': '执行失败！已完成', /** 无效返回-已完成  */
'DATA_ERROR_NOPRO': '执行失败！未上报', /** 无效返回-未上报  */
'DATA_ERROR_APRO': '执行失败！以上报', /** 无效返回-以上报  */
'DATA_ERROR_NOCHOOSE': '执行失败！未选中目标', /** 无效返回-未选中  */
'DATA_ERROR_HTML': 'error', /** 无效返回-error  */
'DATA_ERROR_TOOLONG': '执行失败！文本框键入内容过长', /** 无效返回-键入内容过长  */
'DATA_ERROR_EMPTY': '执行失败！文本内容不能为空', /** 无效返回-键入内容不能为空  */
'DATA_ERROR_NOT_NUM': '执行失败！文本框键入内容只能为数字', /** 无效返回-键入内容只能为数字  */
'DATA_ERROR_NO_POWEER': '执行失败！没有权限', /** 无效返回-没有权限  */
'DATA_ERROR_ENOUGHT': '执行失败！已达到数量上限', /** 已达到数量上限  */
'DATA_ERROR_NODONE': '执行失败！任务未完成', /** 任务未完成限  */
'DATA_ERROR_OTHER': '执行失败！错误404，请联系管理员', /** 无效返回-其他错误  */
'DATA_ERROR_NOSCORE': '执行失败！任务未评分', /** 无效返回-任务未评分  */
'DATA_ERROR_CHOOSE_TIME': '执行失败！选择结束时间', /** 无效返回-选择结束时间  */
'DATA_ERROR_MOUNTH': '执行失败！当前月份不可小于或等于所要执行的月份', /** 无效返回-选择结束时间  */
'DATA_ERROR_CEILING': '执行失败！已达任务上限' ,                /** 无效返回-任务信息为空*/
'DATA_ERROR_NO_SCALE': '执行失败！未填写工资比例'                 /** 无效返回-任务信息为空*/
};

var question_before_action =
{
    /*****************************/
    /* 动作执行前确认弹窗 发布收益  */
    /*****************************/
    'MAKE_SURE_SEND_PAY_BACK': '确认发布任务收益？',
    'MAKE_SURE_SEND_TASK': '确认发布任务？',
    'MAKE_SURE_SEND_REJECT_TASK': '确认驳回任务？',
    'MAKE_SURE_SEND_DEL_TASK': '确认清除任务？',
    'MAKE_SURE_SEND_DEL_TASK_fail_false': '此任务上报成功！！确认清除任务？',
    'MAKE_SURE_PRO_TASK_FOR_TRUE': '确认提交任务？',
    'MAKE_SURE_PRO_TASK_REMARK': '确认提交备注信息？',
    'MAKE_SURE_PRO_TASK_FOR_FALSE': '确认提交并放弃任务？',
    'MAKE_SURE_ASK_FOR_WEEK_TASK': '确定标记为周任务？周任务进行期间，关于此用户的其他类型任务无法提交？',
    'MAKE_SURE_ASK_FOR_WEEK_TASK_TO_NEXT_WEEK': '已超过本周周任务标记时间，继续标记将把此任务放入下周周任务列表内？',
    'MAKE_SURE_CHANGE_TASK_TYPE': '确认修改任务类型？',
    'MAKE_SURE_DEL_AND_CHANGE': '确认清除并变更任务？',
    'MAKE_SURE_YOUX_AND_CHANGE': '确认申请任务为有效回访？',
    'MAKE_SURE_SEL_DATE': '确认填写日期？',
    'MAKE_SURE_ISALLOW_TRUE': '确认批准任务？',
    'MAKE_SURE_ISALLOW_FALSE': '确认拒绝任务？',
    'MAKE_SURE_CHANGE_END_TIME': '确认修改周任务结束时间？',
    'MAKE_SURE_GET_SCORE': '确认对该任务评分？',
    'MAKE_SURE_REPEAL_LAST_ACTION': '确认撤销最近一次发布的任务？',
    'MAKE_SURE_UPLOAD_RECORD': '确认上报员工资料？',
    'MAKE_SURE_MANAGE_LEAVE_TRUE': '确认准许该假条？',
    'MAKE_SURE_MANAGE_LEAVE_FALSE': '确认拒绝该假条？',
    'MAKE_SURE_SEND_WAGE': '确认发布工资条？',
    'MAKE_SURE_ADD_DEDUCT': '确认添加该扣款信息？',
    'MAKE_SURE_PROMOTION_FALSE': '确认放弃晋升高级客服？',
    'MAKE_SURE_PROMOTION_FALSE_MSG': '两周内无法晋升高级！！确认放弃晋升高级客服？',
    'MAKE_SURE_PROMOTION_TRUE': '确认晋升为高级客服？',
    'MAKE_SURE_PROMOTION_TRUE_MSG': '本月起，任务提成升高！！确认晋升为高级客服？',
    'MAKE_SURE_CONTINUE_THIS_TASK': '确认继续跟进该任务？',
    'MAKE_SURE_SEND_TO_SPARE': '确认放入备选用户池中？确认将不再显示此任务在主用户池内！</br>在备选池内查找此任务可将其移回主用户池',
    'MAKE_SURE_SEND_TO_MEMBERPOOL': '确认将此任务移出备选用户池并放入主用户池中？</br>确认将不再显示此任务在备选用户池内！</br>在主池内查找此任务可将其移回备选用户池',
    'MAKE_SURE_CHECKOUT_PAYBACK': '确认将更新当前任务收益。确认变更？'
};


var title =
{
    /************************/
    /* 动作执行前确认弹窗内容  */
    /************************/
    'QUESTION': '问题',
    'TITLE': '后台操作弹窗',
    'TITLE_SUCCESS': '操作成功（后台操作）',
    'TITLE_ERROR': '操作失败（后台操作）'
}


var base_parm =
{
    /************************/
    /*		基础参数	        */
    /************************/
    'MAX_TOTAL_INSERT': 500, /** 文本框最大输入值 */
'MIN_TOTAL_INSERT': 1, /** 文本框最小输入值 */
'MAX_THIS_WEEK_TASK_NUM': 20, /** 本周周任务最大数量 */
'MAX_NEXT_WEEK_TASK_NUM': 20, /** 下一周周任务最大数量 */
'TOTAL_WEEK_TASK_NUM': 20, /** 周任务最大数量 */
'DEFAULT': 0, /** 默认值 0 */
'DEFAULT_ONE': 1, /** 默认值 1 */
'DEFAULT_TWO': 2, /** 默认值 2 */
'VALID': '有效',
    'VOID': '无效'
};

var task_type =
{
    /************************/
    /*		任务类型			*/
    /************************/
    'TYPE_NEW': 1, /** 任务类型 - 新客户任务 */
'TYPE_DROP': 2, /** 任务类型 - 降量任务 */
'TYPE_WEEK': 3, /** 任务类型 - 周任务 */
'TYPE_OTHER': 4, /** 任务类型 - 其他任务 */
'TYPE_VISIT': 5        /** 任务类型 - 回访任务 */

};
var task_type_name =
{
    /************************/
    /*		任务类型			*/
    /************************/
    'TYPE_NEW': '新用户任务', /** 任务类型 - 新客户任务 */
'TYPE_DROP': '降量任务', /** 任务类型 - 降量任务 */
'TYPE_WEEK': '周任务', /** 任务类型 - 周任务 */
'TYPE_OTHER': '其他任务', /** 任务类型 - 其他任务 */
'TYPE_VISIT': '回访任务'        /** 任务类型 - 回访任务 */

};
var week_task_type =
{
    /************************/
    /*		周任务类型			*/
    /************************/
    'LAST_WEEK': 1, /** 周任务类型 - 上周 */
'THIS_WEEK': 2, /** 周任务类型 - 本周 */
'NEXT_WEEK': 3    /** 周任务类型 - 下周 */

};

var task_status =
{
    /************************/
    /*		任务状态			*/
    /************************/
    'STATUS_CASK': 1, /** 任务状态  -可申请*/
'STATUS_AASK': 2, /** 任务进度 - 已被申请 */
'STATUS_APRO': 3, /** 任务进度 - 已上报 */
'STATUS_ADONE': 4, /** 任务进度 - 已完成 */
'STATUS_DEL': 5        /** 任务状态 - 已删除*/

};

var task_isallow =
{
    /************************/
    /*		任务批准状态		*/
    /************************/
    'ISALLOW_FALSE': 0, /** 任务批准状态 - 拒绝 */
'ISALLOW_TRUE': 1, /** 任务批准状态  -准许*/
'ISALLOW_WAITE': 2    /** 任务批准状态 - 等待中 */

};

var task_isfail =
{
    /************************/
    /*		任务批准状态		*/
    /************************/
    'ISFAIL_FALSE': 0, /** 任务完结状态 - 没失败 */
'ISFAIL_TRUE': 1    /** 任务完结状态  -失败*/

};

var manage_deduct_type =
{
    /************************/
    /*		扣款类型		    */
    /************************/
    'DEDUCT_LEAVE_AFFAIR': 1, /** 扣款类型  -事假*/
'DEDUCT_LEAVE_ILL': 2, /** 扣款类型 - 病假 */
'DEDUCT_LEAVE_LATE': 3, /** 扣款类型  -迟到*/
'DEDUCT_LEAVE_INSURANCE': 4, /** 扣款类型  -保险*/
'DEDUCT_LEAVE_ABSENTEEISM': 5, /** 扣款类型 - 旷工 */
'DEDUCT_LEAVE_GAME': 6, /** 扣款类型  -游戏*/
'DEDUCT_LEAVE_OTHER': 7        /** 扣款类型  -其他*/

};


var manage_leave_check =
{
    /************************/
    /*		请假批准状态		*/
    /************************/
    'ISCHECK_WAITE': 0, /** 请假批准状态  -等待*/
'ISCHECK_TRUE': 1, /** 请假批准状态 - 批准 */
'ISCHECK_FALSE': 2        /** 请假批准状态  -拒绝*/

};

var manage_role =
{
    /************************/
    /*		任务状态			*/
    /************************/
    'ROLE_ADMIN': 1, /** 管理员*/
'ROLE_AMALDAR': 2, /** 经理 */
'SUPERVISOR': 3, /** 客服主管 */
'PRACTICE_VISOR': 4, /** 见习主管 */
'ADVANCED_STAFF': 5, /** 高级客服*/
'SUPPORT_STAFF': 6, /** 客服 */
'PRACTICE_STAFF': 7        /** 见习客服*/

};
        
 	
   

