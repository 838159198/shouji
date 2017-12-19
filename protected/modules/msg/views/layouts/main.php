<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <?php Yii::app()->clientScript->registerCoreScript('jquery',CClientScript::POS_HEAD);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap/js/bootstrap.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap/css/bootstrap.min.css");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/member/member.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.css");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.datepicker-zh-CN.min.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.1.10.0.ie.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.0.custom.css");?>
     <?php if (Common::isMobile()) {Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/member/member-mobile.css");} ?>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/respond/respond.min.js");?>
    <title>管理系统</title>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/css/respond/respond.min.js"></script>
    <script type="text/javascript" src="/css/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<!--[if lt IE 10]>
<style type="text/css">
    #ie6-warning{
        background:rgb(255,255,225) no-repeat scroll 3px center;
        position:absolute;
        top:0;
        left:0;
        font-size:14px;
        color:#ff0000;
        width:100%;
        height: 60px;
        line-height: 60px;

        text-align:center;
        z-index: 9999999;
        border-bottom:#FFA0A2 solid 2px;
        font-weight: bold;
    }
    #ie6-warning a {
        text-decoration:none;
    }
</style>
<div id="ie6-warning">尊敬的用户您好！我们发现您正在使用IE浏览器版本太低，为了更好的浏览本站。建议您升级到 <a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank">Internet Explorer 10</a> 或使用以下浏览器：<a href="http://se.360.cn/" target="_blank">360安全浏览器</a>、<a href="http://chrome.360.cn/" target="_blank">360极速浏览器</a> 、<a href="http://liulanqi.baidu.com/" target="_blank">百度浏览器</a>、<a href="http://ie.sogou.com/" target="_blank">搜狗浏览器</a>.</div>
<script type="text/javascript">
    function position_fixed(el, eltop, elleft){
// check if this is IE6
        if(!window.XMLHttpRequest)
            window.onscroll = function(){
                el.style.top = (document.documentElement.scrollTop + eltop)+"px";
                el.style.left = (document.documentElement.scrollLeft + elleft)+"px";
            }
        else el.style.position = "fixed";
    }
    position_fixed(document.getElementById("ie6-warning"),0, 0);
</script>
<![endif]-->

<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->baseUrl.'/'?>';
    var MAIL_URL = '<?php echo $this->createUrl('/msg/mail/count') ?>';
</script>
<style type="text/css">
    .qtip{
        margin-top:8px;
        margin-left:14px;
        color:red;
    }
    #infocount{color:#ff0000; margin-left:-25px;}
</style>
<script type="text/javascript">
    $(function () {
        $("#tipmail").qtip({
            position: 'bottomLeft'
        });
        $.get(MAIL_URL, function (data) {
            if (data) {
                var count = Number(data);
                if (count > 0) {
                    $("#infocount").text("("+count+")");
                    var tip = $("#tipmail");
                    tip.attr("title", "您有 " + count + " 条重要信息，请注意查看");
                    tip.trigger("mouseover");
                }
            }
        });
    });
</script>
<div class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img width="166" height="37" alt="Brand" src="<?php echo Yii::app()->request->baseUrl;?>/css/member/images/logo.png">
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo Yii::app()->createUrl("/msg");?>">首页</a></li>
                <li><a href="<?php echo Yii::app()->createUrl("/msg/product");?>">产品列表</a></li>
                <li>
                    <?php echo CHtml::link('消息', array('mail/index'), array('id' => 'tipmail', 'title' => '没有新消息')) ?>
                </li>
                <li><a href="<?php echo Yii::app()->createUrl("/msg/mail/index");?>" id="infocount"></a></li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="margin-top: 5px" role="button" aria-expanded="false"><?php echo Yii::app()->user->member_username;?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/site/logout">安全退出</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-text navbar-right"><h3 style="margin: 0;"><span class="label label-danger" style="font-family: '微软雅黑', '宋体', Arial, sans-serif; font-weight: normal;">联系电话：0411-39585580</span></h3></div>
        </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
</div>
<!--导航结束-->
<div class="container-fluid">
    <?php echo $content; ?>
</div>
<div class="container-fluid">
    <div class="footer">
        <p>速推手机广告联盟 &copy; 版权所有 <?php //echo date("Y",time());?> 联系电话：0411-39585580</p>
    </div>
</div>
</body>
</html>