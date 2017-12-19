<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cn" lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="zh-cn" />
    <?php Yii::app()->clientScript->registerCoreScript('jquery',CClientScript::POS_HEAD);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap/js/bootstrap.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap/css/bootstrap.min.css");?>

    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.datepicker-zh-CN.min.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.min.js");?>


    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/manage/memberpool.public/Ajaxback.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/uploadify/jquery.uploadify-3.1.min.js");?>

    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.1.10.0.ie.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.0.custom.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/admin/base.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/asyncbox.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.css");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/js/uploadify/uploadify.css");?>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/css/respond/respond.min.js"></script>
    <script type="text/javascript" src="/css/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
<style type="text/css">
    .tooltip .label {
        line-height: 3;
        height: 3px;
        padding: .5em .9em .6em;
    }
</style>
    <title>管理系统</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <script type="text/javascript">
        var BASE_URL = '<?php echo Yii::app()->baseUrl.'/'?>';
        $(function () {
            $('#download_now').click(function() {
                $('.tooltip').css('opacity','1');
                $('.tooltip').show();
            })
        })

    </script>
</head>
<body>
<?php $url =  $this->createUrl ( 'manageMessage/myMessage' );
/*$last_contact = TaskWhen::model()->checkMemberNoContactMoreThenMounth(Yii::app()->user->manage_id);*/
$last_contact = MemberpoolBak::model()->getListAll(Yii::app()->user->manage_id);
if(count($last_contact)==0){
    $tit = '无最新消息';
}else{
    $tit = '新的消息';
}
$last = $this->createUrl('memberPool/memberpoolBak');
$url3 = $this->createUrl('/development/demand/overView');


?>
<nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img width="166" height="37" alt="Brand" src="<?php echo Yii::app()->request->baseUrl;?>/css/admin/images/logo.png">
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><?php if (Auth::check('default_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/");?>">首页</a><?php endif ?> </li>
                <li class="dropdown">
                <?php if (Auth::check('productList_index') || Auth::check('product_index') || Auth::check('bindSample_admin') || Auth::check('closeaccount_index') || Auth::check('product_huodong') || Auth::check('product_activation') || Auth::check('closeAll_closeall') || Auth::check('product_confirm') || Auth::check('productList_blacklist')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">业务管理 <span class="caret"></span></a><?php endif ?>
                    

                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('productList_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/productList");?>">业务包管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('product_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product");?>">业务列表</a><?php endif ?> </li>
                        <li><?php if (Auth::check('bindSample_admin')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/bindSample/admin");?>">独立资源</a><?php endif ?> </li>
                        <li><?php if (Auth::check('closeaccount_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/closeaccount/index");?>">业务封号</a><?php endif ?> </li>
                        <li><?php if (Auth::check('product_huodong')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/huodong");?>">活动管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('product_activation')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/activation");?>">业务激活量相关</a><?php endif ?> </li>
                        <li><?php if (Auth::check('closeAll_closeall')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/closeAll/closeall");?>">一键关闭</a><?php endif ?> </li>
                        <li><?php if (Auth::check('productList_confirm')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/productList/confirm");?>">业务包二次确认</a><?php endif ?> </li>
                        <li><?php if (Auth::check('productList_blacklist')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/productList/blacklist");?>">黑名单</a><?php endif ?> </li>
                        <li><?php if (Auth::check('page_luyou')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/page/luyou");?>">路由设备会员管理</a><?php endif ?> </li>
                    </ul>
                </li>
                <li><?php if (Auth::check('earning_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/earning/index");?>">数据管理</a><?php endif ?></li>
                <li class="dropdown">
                <?php if (Auth::check('stats_graphs') || Auth::check('stats_dropdata') || Auth::check('pay_statement') || Auth::check('stats_think') || Auth::check('stats_select')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">统计信息<span class="caret"></span></a><?php endif ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('stats_graphs')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/stats/graphs");?>">业务收入曲线图</a><?php endif ?> </li>
                        <li><?php if (Auth::check('stats_dropdata')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/stats/dropdata");?>">降量分析与降量任务</a><?php endif ?> </li>
                        <li><?php if (Auth::check('pay_statement')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/statement");?>">月财务统计</a><?php endif ?> </li>
                        <li><?php if (Auth::check('stats_think')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/stats/think");?>">安装降量分析</a><?php endif ?> </li>
                        <li><?php if (Auth::check('stats_select')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/stats/select");?>">业务数据查询</a><?php endif ?> </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <?php if (Auth::check('pay_index') || Auth::check('memberPool_PayBack')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">财务管理 <span class="caret"></span></a><?php endif ?>

                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('pay_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/index");?>">平台财务</a><?php endif ?> </li>
                        <li><?php if (Auth::check('memberPool_PayBack')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/memberPool/PayBack");?>">员工财务</a><?php endif ?> </li>
                    </ul>
                </li>

                <li class="dropdown">
                <?php if (Auth::check('tongji_datainfo') || Auth::check('tongji_appdetail') || Auth::check('tongji_appdetailtest') || Auth::check('tongji_appresourceSee') || Auth::check('tongji_appresourceList') || Auth::check('tongji_admin') || Auth::check('tongji_appresource') || Auth::check('tongji_clientdata') || Auth::check('tongji_appupdata') || Auth::check('tongji_confirm') || Auth::check('tongji_dtconfirm') || Auth::check('tongji_appdataretention') || Auth::check('tongji_market') || Auth::check('tongji_newdtconfirm2')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">统计APP <span class="caret"></span></a><?php endif ?>
                    
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('tongji_datainfo')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/datainfo");?>">APP数据分析</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appdetail')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appdetail");?>">APP业务详情</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appdetailtest')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appdetailtest");?>">地推安装详情</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appresourceSee')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appresourceSee");?>">安装量分析</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appresourceList')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appresourceList");?>">安装量排行</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_admin')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/admin");?>">APP资源</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appresource')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appresource");?>">安装上报</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_clientdata')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/clientdata");?>">地推安装上报</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appupdata')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appupdata");?>">激活上报</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_confirm')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/confirm?date=&type=&username=&models=");?>">激活判定(ROM)</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_dtconfirm')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/dtConfirm?date=&type=&username=");?>">激活判定(地推)</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_appdataretention')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/appdataretention");?>">数据留存</a><?php endif ?> </li>
                        <li><?php if (Auth::check('tongji_market')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/market");?>">地推数据</a><?php endif ?></li>
                        <li><?php if (Auth::check('tongji_newdtconfirm2')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/tongji/newdtconfirm2?date=".date('Y-m-d', strtotime('-1 day'))."&username=");?>">代理商激活判定</a><?php endif ?></li>
                    </ul>
                </li>

                <li><?php if (Auth::check('article_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/article");?>">文章资讯</a><?php endif ?></li>
                <li class="dropdown">
                    <?php if(Auth::check('posts_index') || Auth::check('shop_index') || Auth::check('recruitmanage_category') || Auth::check('recruitmanage_resume')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">内容管理 <span class="caret"></span></a><?php endif ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('posts_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts");?>">内容管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('shop_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/shop/index");?>">积分商城管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('recruitmanage_category')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/recruitmanage/category");?>">招聘分类管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('recruitmanage_resume')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/recruitmanage/resume");?>">招聘简历管理</a><?php endif ?> </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <?php if (Auth::check('serachInfo_create') || Auth::check('serachInfoSale_create') || Auth::check('serachInfoSale_admin') || Auth::check('serachInfo_admin')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">录入库 <span class="caret"></span></a><?php endif ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('serachInfoSale_create')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/serachInfoSale/create");?>">销售录入信息</a><?php endif ?> </li>
                        <li><?php if (Auth::check('serachInfoSale_admin')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/serachInfoSale/admin");?>">销售信息管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('serachInfo_create')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/serachInfo/create");?>">客服录入信息</a><?php endif ?> </li>
                        <li><?php if (Auth::check('serachInfo_admin')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/serachInfo/admin");?>">客服信息管理</a><?php endif ?> </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <?php if (Auth::check('member_index') || Auth::check('member_memberLastContacetime') || Auth::check('member_operation') || Auth::check('agentprice_subprice')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">会员管理 <span class="caret"></span></a><?php endif ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('member_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/index");?>">会员管理与任务发布</a><?php endif ?> </li>
                        <li><?php if (Auth::check('member_memberLastContacetime')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/memberLastContacetime/cate/0");?>">会员联络统计</a><?php endif ?> </li>
                        <li><?php if (Auth::check('member_operation')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/operation");?>">修改已判定业务数据</a><?php endif ?> </li>
                        <li><?php if (Auth::check('agentprice_subprice')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/agentprice/subprice");?>">代理商单价管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('pay_mendIncome')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/mendIncome");?>">收益补入</a><?php endif ?> </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <?php if (Auth::check('memberPool_IndexNoPro') || Auth::check('memberPool_visit') || Auth::check('memberPool_taskType') || Auth::check('task_checkList') || Auth::check('task_showTaskList')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">任务管理 <span class="caret"></span></a><?php endif ?>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('memberPool_indexNoPro')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/memberPool/IndexNoPro");?>">我的用户池</a><?php endif ?> </li>
                        <!--<li><?php /*if (Auth::check('Mytask_weekly')):*/?><a href="<?php /*echo Yii::app()->createUrl($this->getModule()->id."/Mytask/Weekly");*/?>">我的周任务</a><?php /*endif */?> </li>-->
                        <!--<li><?php /*if (Auth::check('memberPool_dropTask')):*/?><a href="<?php /*echo Yii::app()->createUrl($this->getModule()->id."/memberPool/DropTask");*/?>">我的降量任务</a><?php /*endif */?> </li>-->
                        <li><?php if (Auth::check('memberPool_visit')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/memberPool/Visit");?>">我的回访任务</a><?php endif ?> </li>
                        <li><?php if (Auth::check('memberPool_taskType')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/memberPool/TaskType");?>">我的任务提醒列表</a><?php endif ?> </li>
                        <li><?php if (Auth::check('task_checkList')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/task/CheckList");?>">任务审核</a><?php endif ?> </li>
                        <li><?php if (Auth::check('task_showTaskList')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/task/ShowTaskList");?>">任务查询</a><?php endif ?> </li>
                    </ul>
                </li>


                <li class="dropdown">
                    <?php if(Auth::check('manage_index') || Auth::check('mail_createMailToUidList') || Auth::check('sendmessage_weixinMessage') || Auth::check('softbox_index') || Auth::check('softroute_index') || Auth::check('romSoftpak_index') || Auth::check('share_index') || Auth::check('default_flush') || Auth::check('page_index') || Auth::check('link_index')):?><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">系统设置 <span class="caret"></span></a><?php endif ?>

                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('manage_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage");?>">管理员</a><?php endif ?> </li>
                        <li><?php if (Auth::check('mail_createMailToUidList')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/mail/CreateMailToUidList");?>">群发站内信</a><?php endif ?> </li>
                        <li><?php if (Auth::check('sendmessage_weixinMessage')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/sendMessage/weixinMessage");?>">微信模板</a><?php endif ?> </li>
                        <li><?php if (Auth::check('softbox_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/softbox/index");?>">刷机盒子</a><?php endif ?> </li>
                        <li><?php if (Auth::check('softroute_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/softroute/index");?>">路由器</a><?php endif ?> </li>
                        <li><?php if (Auth::check('romSoftpak_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/romSoftpak/index");?>">统计软件</a><?php endif ?> </li>
                        <li><?php if (Auth::check('recycleSoftpak_recycle')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/recycleSoftpak/recycle");?>">统计软件回收</a><?php endif ?> </li>
                        <li><?php if (Auth::check('share_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/share/index");?>">回流统计</a><?php endif ?> </li>
                        <li><?php if (Auth::check('controlPackage_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/controlPackage/index");?>">监控包管理</a><?php endif ?> </li>
                        <li class="divider"></li>
                        <li><?php if (Auth::check('default_flush')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/default/flush");?>">清除缓存</a><?php endif ?> </li>
                        <li><?php if (Auth::check('page_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/page");?>">独立页面</a><?php endif ?> </li>
                        <li><?php if (Auth::check('link_index')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/link");?>">友情链接</a><?php endif ?> </li>
                        <!--<li><a href="#">公告</a></li>-->
                    </ul>
                </li>

                <li class="dropdown dropdown-more">
                    <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="javascript:;">
                        更多<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                    </ul>
                </li>


            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo Yii::app()->user->manage_name;?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php if (Auth::check('default_testdata')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/default/testdata");?>">测试数据管理</a><?php endif ?> </li>
                        <li><?php if (Auth::check('manage_myInfo')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/myInfo");?>">资料修改</a><?php endif ?> </li>
                        <li><?php if (Auth::check('manage_myPassword')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/myPassword");?>">密码修改</a><?php endif ?> </li>
                        <li><?php if (Auth::check('manageMessage_myWageList')):?><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manageMessage/myWageList");?>">工资查看</a><?php endif ?> </li>
                        <li></li>
                        <li></li>
                        <li class="divider"></li>

                        <li><a href="/site/logout">安全退出</a></li>
                    </ul>
                </li>
            </ul>


            <ul class="nav navbar-nav navbar-right">

                <li><a href="<?php echo $last ?>" id = 'last_contact'><i class="glyphicon glyphicon-comment icon-white"></i><?php echo $tit;?></a></li>

                <!--<li><a href = '#' id = 'last_msg'></a></li>-->
                <li><a href = '#' id = 'yu_e'></a></li>
                <li><a href='#' id="download_now"></a></li>
                <div class="tooltip" style="position: absolute; top: 28px;width: 380px;height: 220px;display: none;">

                    <table style="margin-top:15px" id = 'msg_list'>

                    </table>

                    <a href="#" id = 'check_remind' style='margin-top:20px;'></a>
                </div>
            </ul>





        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<link rel="stylesheet" type="text/css" href="/css/admin/more.css">


<!--导航结束-->
<div class="container-fluid" id="thispage">
    <?php echo $content; ?>
</div>
<div id="modal" title="提示" style="display:none;"></div>
<?php $this->renderPartial('/layouts/remind') ?>
</body>
</html>
<script type="text/javascript" src="/css/admin/more.js"></script>
