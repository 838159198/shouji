<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php echo $model['name'];?> <small>权限设置</small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu",array('data'=>$model));?>
        </div>
        <!--左侧-->
        <script language="javascript">
            function cli(Obj)
            {
                var collid = document.getElementById("all");
                var coll = document.getElementsByName(Obj);
                if (collid.checked){
                    for(var i = 0; i < coll.length; i++)
                        coll[i].checked = true;
                }else{
                    for(var i = 0; i < coll.length; i++)
                        coll[i].checked = false;
                }
            }
        </script>
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div style="display: none;">
                <!--默认必须勾选-->
                <label for="inputEmail3" class="col-sm-2 control-label">后台首页</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="manage_mypassword" checked="checked"> 修改密码</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="manage_myinfo" checked="checked"> 资料修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="manageMessage_myWageList" checked="checked"> 工资查看</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">首页</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="default_index" <?php if(in_array("default_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="default_testdata" <?php if(in_array("default_testdata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 测试数据管理</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">业务产品</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_index" <?php if(in_array("product_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_prompt" <?php if(in_array("product_prompt", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 温馨提示</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_addCategroy" <?php if(in_array("product_addCategroy", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 增加分类</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_create" <?php if(in_array("product_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务创建</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_edit" <?php if(in_array("product_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务编辑</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="kindedit_upload" <?php if(in_array("kindedit_upload", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 图片上传</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="kindedit_manageJson" <?php if(in_array("kindedit_manageJson", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 图片编辑</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_huodong" <?php if(in_array("product_huodong", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 活动管理</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_huodongedit" <?php if(in_array("product_huodongedit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 活动审核</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_hdorder" <?php if(in_array("product_hdorder", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 活动上报数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_usernames" <?php if(in_array("product_usernames", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 获取用户名</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_huodong2" <?php if(in_array("product_huodong2", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 活动管理查看</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_showcampaign" <?php if(in_array("product_showcampaign", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 活动管理修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_sort" <?php if(in_array("product_sort", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 导入排名</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_activation" <?php if(in_array("product_activation", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务激活量相关</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_excel" <?php if(in_array("product_excel", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务激活量相关Excel</label></div>

                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_add"
                                                                 <?php if(in_array("product_add", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>添加用户</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_sortadd"
                                                                 <?php if(in_array("product_sortadd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>添加排名</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_sortpublish" <?php if(in_array("product_sortpublish", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>发布排名</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_del" <?php if(in_array("product_del", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除排名</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_hddata" <?php if(in_array("product_hddata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>活动数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="product_delall"<?php if(in_array("product_delall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>一键删除</label></div>

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">业务资源</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="bindSample_admin" <?php if(in_array("bindSample_admin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 独立资源</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="bindSample_create" <?php if(in_array("bindSample_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 创建资源</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="bindSample_update" <?php if(in_array("bindSample_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 资源修改</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">数据管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="import_index" <?php if(in_array("import_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 导入数据首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="import_clear" <?php if(in_array("import_clear", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 清理已导入的数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="import_self" <?php if(in_array("import_self", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 导入数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="earning_index" <?php if(in_array("earning_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户收益</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="earning_memberpro" <?php if(in_array("earning_memberpro", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户业务</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="closeaccount_index" <?php if(in_array("closeaccount_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务封号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="import_show" <?php if(in_array("import_show", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益隐藏开关</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_blacklist" <?php if(in_array("productList_blacklist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 黑名单</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_feng" <?php if(in_array("productList_feng", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 封号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_maxnum" <?php if(in_array("productList_maxnum", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 封号查询</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_imeikill" <?php if(in_array("productList_imeikill", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 定时封号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_dingshi" <?php if(in_array("productList_dingshi", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 定时安装次数</label></div>


                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">统计信息</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="stats_graphs" <?php if(in_array("stats_graphs", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务收入曲线图</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="stats_dropdata" <?php if(in_array("stats_dropdata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 降量分析与降量任务</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="stats_think" <?php if(in_array("stats_think", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 安装降量分析</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="stats_select" <?php if(in_array("stats_select", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务数据查询</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">财务管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_index" <?php if(in_array("pay_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 财务说明</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_stats" <?php if(in_array("pay_stats", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 统计上月收益余额</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_excel" <?php if(in_array("pay_excel", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 导出excel</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_member" <?php if(in_array("pay_member", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 未/已支付记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_update" <?php if(in_array("pay_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 点击支付</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_updates" <?php if(in_array("pay_updates", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量支付</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_pay" <?php if(in_array("pay_pay", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 具体支付</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_datainfo" <?php if(in_array("tongji_datainfo", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据分析</label></div>

                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_statement" <?php if(in_array("pay_statement", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 月财务统计</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_mendIncome" <?php if(in_array("pay_mendIncome", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_systemlog" <?php if(in_array("pay_systemlog", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入1</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_updateincome" <?php if(in_array("pay_updateincome", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入2</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_mendLog" <?php if(in_array("pay_mendLog", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入3</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_revoke" <?php if(in_array("pay_revoke", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入4</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_surplus" <?php if(in_array("pay_surplus", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入5</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="pay_surplusAjax" <?php if(in_array("pay_surplusAjax", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益补入6</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">统计APP</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appresourceSee" <?php if(in_array("tongji_appresourceSee", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 安装量分析</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appresourceList" <?php if(in_array("tongji_appresourceList", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 安装量排行</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appdetailtest" <?php if(in_array("tongji_appdetailtest", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 地推安装详情</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appdetail" <?php if(in_array("tongji_appdetail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> APP业务详情</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_admin" <?php if(in_array("tongji_admin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> APP资源</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appresource" <?php if(in_array("tongji_appresource", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 激活统计</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appupdata" <?php if(in_array("tongji_appupdata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 上报数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_clientdata" <?php if(in_array("tongji_clientdata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 地推安装上报</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_confirm" <?php if(in_array("tongji_confirm", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 激活判定</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_updates" <?php if(in_array("tongji_updates", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 具体激活</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_noupdates" <?php if(in_array("tongji_noupdates", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 具体封号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_dtconfirm" <?php if(in_array("tongji_dtconfirm", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 激活判定(地推)</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_appdataretention" <?php if(in_array("tongji_appdataretention", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_ywajax" <?php if(in_array("tongji_ywajax", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存-业务</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_fzajax" <?php if(in_array("tongji_fzajax", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存-分组</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_cxajax" <?php if(in_array("tongji_cxajax", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存-查询</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_dealdata" <?php if(in_array("tongji_dealdata", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存-处理数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_alluserajax" <?php if(in_array("tongji_alluserajax", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 数据留存-所有用户</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_market" <?php if(in_array("tongji_market", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 地推数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_newdtconfirm" <?php if(in_array("tongji_newdtconfirm", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>代理商激活判定</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_newdtconfirm2" <?php if(in_array("tongji_newdtconfirm2", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>代理商激活判定</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_newdtconfirm3" <?php if(in_array("tongji_newdtconfirm3", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>代理商数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_subupdate" <?php if(in_array("tongji_subupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>子用户数据判定</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="tongji_nosubupdate" <?php if(in_array("tongji_nosubupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>子用户数据封号</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">文章资讯</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_category" <?php if(in_array("article_category", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 栏目列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_categorycreate" <?php if(in_array("article_categorycreate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 栏目创建</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_categoryupdate" <?php if(in_array("article_categoryupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 栏目修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_categorydel" <?php if(in_array("article_categorydel", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除栏目</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_index" <?php if(in_array("article_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 文章列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_create" <?php if(in_array("article_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 创建文章</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_update" <?php if(in_array("article_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 编辑文章</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="article_del" <?php if(in_array("article_del", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除文章</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">内容管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="posts_index" <?php if(in_array("posts_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 内容列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="posts_list" <?php if(in_array("posts_list", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 内容分类列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="posts_create" <?php if(in_array("posts_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 创建内容</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="posts_update" <?php if(in_array("posts_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改内容</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="posts_del" <?php if(in_array("posts_del", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除内容</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">页面管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="page_index" <?php if(in_array("page_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 页面列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="page_create" <?php if(in_array("page_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 创建页面</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="page_update" <?php if(in_array("page_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改页面</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="page_del" <?php if(in_array("page_del", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除页面</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">录入库管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_create" <?php if(in_array("serachInfo_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_admin" <?php if(in_array("serachInfo_admin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 录入首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_view" <?php if(in_array("serachInfo_view", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息查看</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_update" <?php if(in_array("serachInfo_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_zxjlcreate" <?php if(in_array("serachInfo_zxjlcreate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 咨询记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfo_updatestatus" <?php if(in_array("serachInfo_updatestatus", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 审核</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">销售入库管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_create" <?php if(in_array("serachInfoSale_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_admin" <?php if(in_array("serachInfoSale_admin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 录入首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_view" <?php if(in_array("serachInfoSale_view", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息查看</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_update" <?php if(in_array("serachInfoSale_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_zxjlcreate" <?php if(in_array("serachInfoSale_zxjlcreate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 咨询记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="serachInfoSale_updatestatus" <?php if(in_array("serachInfoSale_updatestatus", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 审核</label></div>

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">用户管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_index" <?php if(in_array("member_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户列表</label></div>

                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_mlogin" <?php if(in_array("member_mlogin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 登陆用户后台（慎给）</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_detail" <?php if(in_array("member_detail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户资料</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_edit" <?php if(in_array("member_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改基本信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_bank" <?php if(in_array("member_bank", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改银行信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_resetPassword" <?php if(in_array("member_resetPassword", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 重置密码</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_lock" <?php if(in_array("member_lock", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 锁定账号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_unlock" <?php if(in_array("member_unlock", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 解锁账号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="advisoryrecords_index" <?php if(in_array("advisoryrecords_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加咨询记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_category" <?php if(in_array("member_category", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改用户类别</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_mark" <?php if(in_array("member_mark", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 追踪用户状态</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_graphs" <?php if(in_array("member_graphs", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 曲线图</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_searchlogindate" <?php if(in_array("member_searchlogindate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 查询登录情况</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_info" <?php if(in_array("member_info", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 查看用户详情(弹窗)</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_memberLastContacetime" <?php if(in_array("member_memberLastContacetime", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 会员联络统计</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_task" <?php if(in_array("member_task", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 发布任务</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_taskinfo" <?php if(in_array("member_taskinfo", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 发布任务2</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_askfortask" <?php if(in_array("member_askfortask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页 -会员列表管理-任务申请</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_sendtask" <?php if(in_array("member_sendtask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务批量发布</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_log" <?php if(in_array("member_log", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改信息历史记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_operation" <?php if(in_array("member_operation", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改已判定业务数据</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_fenghao" <?php if(in_array("member_fenghao", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 封号和解封</label></div>
                    <div class="checkbox col-sm-3" style="display: none;"><label><input checked="checked" type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_feng" <?php if(in_array("member_feng", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 确认封号和解封</label></div>
                    <div class="checkbox col-sm-3" style="display: none;"><label><input checked="checked" type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_jiefeng" <?php if(in_array("member_jiefeng", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量封号</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_checkmark" <?php if(in_array("member_checkmark", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 追踪用户状态列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_giveupthismember" <?php if(in_array("member_giveupthismember", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 放弃追踪用户</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_showmanagelist" <?php if(in_array("member_showmanagelist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 显示用户 -客服历史记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_showadvrec" <?php if(in_array("member_showadvrec", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 显示用户 -客服历史记录-客服回复查询</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="member_getmemberincome" <?php if(in_array("member_getmemberincome", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 客服查看用户收益</label></div>



                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">用户角色管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="role_index" <?php if(in_array("role_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="role_create" <?php if(in_array("role_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="role_update" <?php if(in_array("role_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="role_delete" <?php if(in_array("role_delete", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="role_updatemanagerolebyweektaskcallback" <?php if(in_array("role_updatemanagerolebyweektaskcallback", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 客服晋升</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">站内信管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_logmail" <?php if(in_array("mail_logmail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <!-- <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_create" <?php if(in_array("mail_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 写邮件</label></div> -->
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_createMailToUidList" <?php if(in_array("mail_createMailToUidList", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 群发站内信</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_del" <?php if(in_array("mail_del", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除邮件</label></div>
                    <div style="display: none" class="checkbox col-sm-3"><label><input checked="checked" type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_dell" <?php if(in_array("mail_dell", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 确认删除邮件</label></div>
                     <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_delall" <?php if(in_array("mail_delall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量删除邮件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_look" <?php if(in_array("mail_look", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 查看邮件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_updel" <?php if(in_array("mail_updel", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除邮件详情</label></div>
                    <div style="display: none" class="checkbox col-sm-3"><label><input checked="checked" type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mail_updell" <?php if(in_array("mail_updell", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 确认删除邮件详情</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">我的任务</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_staffprovisitetask" <?php if(in_array("mytask_staffprovisitetask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 见习客服任务处理 -普通权限</label></div>
                    <!--<div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_weekly" <?php /*if(in_array("mytask_weekly", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 周任务列表 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_updateweektask" <?php /*if(in_array("mytask_updateweektask", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 周任务重新计算</label></div>-->
                    <!--<div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_showlastweektask" <?php /*if(in_array("mytask_showlastweektask", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 查询上一周周任务-见习以上权限</label></div>-->
                    <!--<div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_continue" <?php /*if(in_array("mytask_continue", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 跟进周任务 -普通权限</label></div>-->
                    <!--<div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_addweektask" <?php /*if(in_array("mytask_addweektask", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 添加周任务 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_updateweektaskendtime1" <?php /*if(in_array("mytask_updateweektaskendtime1", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 修改结束时间1 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="mytask_updateweektaskendtime2" <?php /*if(in_array("mytask_updateweektaskendtime2", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 修改结束时间2 -高级权限</label></div>-->
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">任务管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_checklist" <?php if(in_array("task_checklist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务审核 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_deltaskbymsg" <?php if(in_array("task_deltaskbymsg", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务详情删除任务 —高级权限，</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_deltask" <?php if(in_array("task_deltask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除已完成的任务 ,释放用户 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_checkout" <?php if(in_array("task_checkout", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 审核任务的信息 - 高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_getscore" <?php if(in_array("task_getscore", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务评分ajax -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_updetasktype" <?php if(in_array("task_updetasktype", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 准许或拒绝任务 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_updetaskvtype" <?php if(in_array("task_updetaskvtype", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 准许或拒绝任务 -高级权限--有效回访审核</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_updetasktypeall" <?php if(in_array("task_updetasktypeall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 准许或拒绝任务2 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_checkalltask" <?php if(in_array("task_checkalltask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量审核任务 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_showtasklist" <?php if(in_array("task_showtasklist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务查询 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="task_managemsgtodel" <?php if(in_array("task_managemsgtodel", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 清除一个客服的所有任务信息 --最高权限</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">用户池</label>
            <div class="col-sm-9">
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_backtask" <?php if(in_array("memberpool_backtask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务驳回 -高级权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_droptask" <?php if(in_array("memberpool_droptask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 降量任务表 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_payback" <?php if(in_array("memberpool_payback", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务收益表 -高级权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_taskcount" <?php if(in_array("memberpool_taskcount", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 查询见习客服日期内条数</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_indexpro" <?php if(in_array("memberpool_indexpro", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户池列表1 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_indexnopro" <?php if(in_array("memberpool_indexnopro", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户池列表2 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_indexspare" <?php if(in_array("memberpool_indexspare", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 备选用户池 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_memberpoolbak" <?php if(in_array("memberpool_memberpoolbak", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 备选用户池(新) -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_refusetask" <?php if(in_array("memberpool_refusetask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 被拒绝 /待批准任务 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_tasktype" <?php if(in_array("memberpool_tasktype", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务类型跳转 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_delnotallowtask" <?php if(in_array("memberpool_delnotallowtask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 清除被拒任务 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_delwaittoallowtask" <?php if(in_array("memberpool_delwaittoallowtask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 删除等待批准的任务 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_delvisitetask" <?php if(in_array("memberpool_delvisitetask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 放弃回访任务 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_delvisitetaskall" <?php if(in_array("memberpool_delvisitetaskall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 客服释放此页客户 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_askforvisitetask" <?php if(in_array("memberpool_askforvisitetask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 回访任务变更 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_askforvisitevtask" <?php if(in_array("memberpool_askforvisitevtask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 有效回访状态变更 - 见习客服</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_visit" <?php if(in_array("memberpool_visit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 回访任务表 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_gettask" <?php if(in_array("memberpool_gettask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务发布 (new) -高级权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_mytask" <?php if(in_array("memberpool_mytask", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 任务信息 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_reply" <?php if(in_array("memberpool_reply", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 提交任务 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_usermsg" <?php if(in_array("memberpool_usermsg", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息操作1 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_setmsg" <?php if(in_array("memberpool_setmsg", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 信息操作2 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_changepool" <?php if(in_array("memberpool_changepool", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 切换用户池 -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_info" <?php if(in_array("memberpool_info", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 用户信息 (弹窗) -普通权限</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_changepoolall" <?php if(in_array("memberpool_changepoolall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量移动任务</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_lastcontactlist" <?php if(in_array("memberpool_lastcontactlist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 逾期未联系列表</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_help" <?php if(in_array("memberpool_help", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 客服帮助文档</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_help2" <?php if(in_array("memberpool_help2", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务流文档</label></div>
                <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="memberpool_getweeekcount" <?php if(in_array("memberpool_getweeekcount", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 见习客服-查看安装量</label></div>
            </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">员工个人信息</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_index" <?php if(in_array("managemessage_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页列表项 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_record" <?php if(in_array("managemessage_record", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 员工信息录入 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_messgae" <?php if(in_array("managemessage_messgae", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 员工信息查看 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_mymessage" <?php if(in_array("managemessage_mymessage", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 个人信息查看 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_deduct" <?php if(in_array("managemessage_deduct", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 扣款列表项 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_payback" <?php if(in_array("managemessage_payback", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 工资查看与发布 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_wagecount" <?php if(in_array("managemessage_wagecount", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 工资条发布 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_manageleave" <?php if(in_array("managemessage_manageleave", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 员工请假申请 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_checkmanageleave" <?php if(in_array("managemessage_checkmanageleave", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批准 /拒绝请假 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_adddeductbyadmin" <?php if(in_array("managemessage_adddeductbyadmin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加扣款项 -高级权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_mywagelist" <?php if(in_array("managemessage_mywagelist", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 查看工资单 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_wagelistpower" <?php if(in_array("managemessage_wagelistpower", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 工资查看导航栏 -普通权限</label></div>
                    <!--<div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_showweektaskmsglistbydate" <?php /*if(in_array("managemessage_showweektaskmsglistbydate", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 周任务导航 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_showweektaskearningsbydate" <?php /*if(in_array("managemessage_showweektaskearningsbydate", explode(',', $model->auth))): */?>checked="checked"<?php /*endif */?>> 周任务收益列表 -普通权限</label></div>-->
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_showwagebydate" <?php if(in_array("managemessage_showwagebydate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 非周任务导航变更信息 -普通权限</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="managemessage_gettasknewmsgbydate" <?php if(in_array("managemessage_gettasknewmsgbydate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 获取任务数据 -普通权限</label></div>
                </div>

            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">积分商城</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_index" <?php if(in_array("shop_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 商城首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_goodsadd" <?php if(in_array("shop_goodsadd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加商品</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_goodsupdate" <?php if(in_array("shop_goodsupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 编辑商品</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_goodsdetail" <?php if(in_array("shop_goodsdetail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 商品详情</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_addcategory" <?php if(in_array("shop_addcategory", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 商品分类添加</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_categoryupdate" <?php if(in_array("shop_categoryupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 商品分类更新</label></div>


                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_goodsorder" <?php if(in_array("shop_goodsorder", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 订单列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="shop_goodsorderdetail" <?php if(in_array("shop_goodsorderdetail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 订单详情</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="membercredits_index" <?php if(in_array("membercredits_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 积分记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="membercredits_check" <?php if(in_array("membercredits_check", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 手动积分</label></div>

                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">招聘管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recruitmanage_category" <?php if(in_array("recruitmanage_category", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 招聘分类管理</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recruitmanage_resume" <?php if(in_array("recruitmanage_resume", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 招聘简历管理</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">微信管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="sendmessage_weixinmessage" <?php if(in_array("sendmessage_weixinmessage", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="sendmessage_weixin" <?php if(in_array("sendmessage_weixin", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 发送微信</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="sendmessage_weixinsj" <?php if(in_array("sendmessage_weixinsj", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 具体发送</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="sendmessage_yestodayincome" <?php if(in_array("sendmessage_yestodayincome", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 收益模板页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="sendmessage_income" <?php if(in_array("sendmessage_income", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 发送收益</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">刷机盒子管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softbox_index" <?php if(in_array("softbox_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softbox_boxAdd" <?php if(in_array("softbox_boxAdd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加盒子信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softbox_boxUpdate" <?php if(in_array("softbox_boxUpdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 编辑盒子信息</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdata_index" <?php if(in_array("boxdata_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 刷机盒子文件包列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdata_create" <?php if(in_array("boxdata_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 上传文件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdata_edit" <?php if(in_array("boxdata_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 更新文件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdesk_index" <?php if(in_array("boxdesk_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 盒子桌面列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdesk_create" <?php if(in_array("boxdesk_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 上传桌面文件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdesk_edit" <?php if(in_array("boxdesk_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 更新桌面文件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="boxdesk_moreUpload" <?php if(in_array("boxdesk_moreUpload", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 批量上传</label></div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">路由器管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softroute_index" <?php if(in_array("softroute_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 首页</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softroute_routeadd" <?php if(in_array("softroute_routeadd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 添加路由器</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softroute_routeupdate" <?php if(in_array("softroute_routeupdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 编辑路由器</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softroute_test2" <?php if(in_array("softroute_test2", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 在线路由器</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="softroute_info" <?php if(in_array("softroute_info", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 在线路由器信息</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">业务包管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_index" <?php if(in_array("productList_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务包列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_create" <?php if(in_array("productList_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 上传业务包</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_edit" <?php if(in_array("productList_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 编辑业务包</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="productList_confirm" <?php if(in_array("productList_confirm", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务包二次确认</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">分享回流管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="share_index" <?php if(in_array("share_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 分享统计列表</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">渠道管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="spreadSource_index" <?php if(in_array("spreadSource_index_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 渠道列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="spreadSource_sourceAdd" <?php if(in_array("spreadSource_sourceAdd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 渠道添加</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="spreadSource_sourceUpdate" <?php if(in_array("spreadSource_sourceUpdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 渠道更新</label></div>

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">统计软件管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="romSoftpak_index" <?php if(in_array("romSoftpak_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 统计软件列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="romSoftpak_create" <?php if(in_array("romSoftpak_create", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>上传统计软件</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="romSoftpak_edit" <?php if(in_array("romSoftpak_edit", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>统计软件更新</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="romSoftpak_moreUpload" <?php if(in_array("romSoftpak_moreUpload", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>批量上传</label></div>

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">一键关闭管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="closeAll_closeall" <?php if(in_array("closeAll_closeall", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 业务关闭历史记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="closeAll_openAll" <?php if(in_array("closeAll_openAll", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>查询开通人数</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="closeAll_closeType" <?php if(in_array("closeAll_closeType", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>一键关闭</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="closeAll_detail" <?php if(in_array("closeAll_detail", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>操作记录详情</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">代理商单价管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="agentprice_subprice" <?php if(in_array("agentprice_subprice", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 单价列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="agentprice_update" <?php if(in_array("agentprice_update", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>子用户价格修改</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="agentprice_delete" <?php if(in_array("agentprice_delete", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>价格删除</label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">监控包管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="controlPackage_index" <?php if(in_array("controlPackage_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>> 监控包列表</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="controlPackage_packageAdd" <?php if(in_array("controlPackage_packageAdd", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>添加监控包</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="controlPackage_packageUpdate" <?php if(in_array("controlPackage_packageUpdate", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>更新监控包</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="controlPackage_packageAll" <?php if(in_array("controlPackage_packageAll", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>一键导入</label></div>

                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">统计软件回收管理</label>
                <div class="col-sm-9">
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_index" <?php if(in_array("recycleSoftpak_index", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>统计软件回收记录</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_recycle" <?php if(in_array("recycleSoftpak_recycle", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>统计软件回收</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_reply" <?php if(in_array("recycleSoftpak_reply", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>恢复回收功能</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_recycleTj" <?php if(in_array("recycleSoftpak_recycleTj", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>统计回收</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_recycleAll" <?php if(in_array("recycleSoftpak_recycleAll", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>批量回收</label></div>
                    <div class="checkbox col-sm-3"><label><input type="checkbox" name="Manage[auth][]" id="Manage_auth" value="recycleSoftpak_updateRecycle" <?php if(in_array("recycleSoftpak_updateRecycle", explode(',', $model->auth))): ?>checked="checked"<?php endif ?>>数据更新</label></div>
                </div>
            </div>
            <div class="form-group">

                <div class="col-sm-offset-2 col-sm-9">
                    <div class="checkbox">
                        <label><input type="checkbox"  id="all" onclick="cli('Manage[auth][]')" > 全选</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">确认提交</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>


        </div>
    </div>
</div>