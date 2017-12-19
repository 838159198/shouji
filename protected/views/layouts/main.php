<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/site/base.css" />
    <script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/site/js/jquery-1.7.2.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>

</head>
<body>
<div class="top">
    <div class="top-content">
        <div class="top-user">
            <?php $userstate=Yii::app()->user;?>
                <?php if($userstate->getState('type')=="Member" || $userstate->getState('type')=="Agent" || $userstate->getState('type')=="Synthsize"){?>
                    您好，<?php echo Yii::app()->user->getState('member_username');?>！<a href="/member"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Ditui"){?>
                    您好，<?php echo $userstate->getState('member_username');?>！<a href="/ditui"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Msg"){?>
                    您好，<?php echo $userstate->getState('member_username');?>！<a href="/msg"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Newdt"){?>
                    您好，<?php echo $userstate->getState('member_username');?>！<a href="/newdt"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Dealer"){?>
                    您好，<?php echo $userstate->getState('member_username');?>！<a href="/dealer"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Msg" || $userstate->getState("type")=="Other"){?>
                    您好，<?php echo $userstate->getState('member_username');?>！<a href="/product/review"><font color="#fff000">进入会员中心</font></a> - <a href="/site/logout">安全退出</a>
                <?php }elseif($userstate->getState("type")=="Manage"){?>
                    您好，<?php echo $userstate->getState('manage_username');?>！<a href="/dhadmin"><font color="#fff000">进入管理中心</font></a> - <a href="/site/logout">安全退出</a>
            <?php } else{?>
            你好，欢迎访问速推APP推广联盟！
            <?php }?>
        </div>
        <div class="top-us">
            <a href="/reg">注册账号</a> | <a href="/inc/aboutus">联系我们</a> | <a href="/recruit/index" style="font-size: 14px;font-weight: bold;color: #9a9afb">人才招聘</a>
        </div>
    </div>
</div>
<!-- //顶部结束 -->
<div class="header">
    <div class="header-content">
        <div class="logo">
            <h1><a href="/">速推手机广告联盟</a></h1>
        </div>
        <div class="nav">
            <ul>
                <li><a href="/"<?php if($this->getId()."_".$this->getAction()->id=="site_index"):?> class="active"<?php endif;?>>首页</a></li>
<!--                <li><a href="/article/news" <?php //if(in_array($this->getId(),array("article"))):?>< class="active"<?php //endif;?>>行业资讯</a></li>-->
                <li><a href="/question"<?php if(Yii::app()->request->getUrl()=="/question"):?> class="active"<?php endif;?>>新手教程</a></li>
                <li><a href="/product"<?php if($this->getId()."_".$this->getAction()->id=="product_index"):?> class="active"<?php endif;?>>APP列表</a></li>
                <li><a href="/notice"<?php if(in_array("notice",explode("/",Yii::app()->request->getUrl()))):?> class="active"<?php endif;?>>最新公告</a></li>
                <li><a href="/shop/index"<?php if($this->getId()."_".$this->getAction()->id=="shop_index"):?> class="active"<?php endif;?>>积分兑换</a></li>
                <li><a href="/inc/aboutus"<?php if($this->getId()."_".$this->getAction()->id=="page_detail"):?> class="active"<?php endif;?>>关于我们</a></li>
                <?php if($userstate->getState("type")=="Member" || $userstate->getState("type")=="Agent" || $userstate->getState("type")=="Synthsize"){?>
                    <li><a href="/member" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Ditui"){?>
                    <li><a href="/ditui" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Newdt"){?>
                    <li><a href="/newdt" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Dealer"){?>
                    <li><a href="/dealer" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Msg"){?>
                    <li><a href="/msg" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Other"){?>
                    <li><a href="/product/review" style="background-color: #005b9a"><font color="#fff000">会员中心</font></a></li>
                <?php }elseif($userstate->getState("type")=="Manage"){?>
                    <li><a href="/dhadmin" style="background-color: #005b9a"><font color="#fff000">管理中心</font></a></li>
                <?php }else{?>
                    <li><a href="/login"<?php if($this->getId()."_".$this->getAction()->id=="site_login"):?> class="active"<?php endif;?> style="background-color: #005b9a">会员登录</a></li>
                <?php }?>

            </ul>
        </div>
    </div>
</div>
<!-- //头部结束 -->
<div class="container" id="page">

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>
    <!-- //包含页面 -->
    <style type="text/css">
        .footer-content ul .foottit3of3 a{float:left;width:150px;margin-left:26px;line-height:20px;font-size:13px;letter-spacing:1px;background-color:#848484;margin-bottom:5px;color:#ffffff;text-decoration:none;}
        .footer-content ul .foottit3of3 a:hover{background-color:#9aafe5;color:#000000;}
    </style>
    <div class="footer">
        <div class="footer-content">
            <ul>
                <li class="foottit1of3">速推优势<hr><br>
                    <span>
                        <strong style="color:#c1c0c0">推广资源丰富</strong><br>联盟可供推广盈利的APP资源丰富，可自主选择下载安装<br>
                        <strong style="color:#c1c0c0">安装简单快捷</strong><br>客户端安装便捷，安全无毒<br>
                        <strong style="color:#c1c0c0">数据一目了然</strong><br>后台数据一目了然，应用安装量、激活量随时查看<br>
                        <strong style="color:#c1c0c0">收益实时到账</strong><br>打款不收任何手续费，费用实时到账
                    </span>
                </li>
                <li class="foottit2of3">联系我们<hr><br>
                    <span class="linst">
                        <strong>广告投放</strong>：0411-39585580转815<br>
                        <strong >QQ</strong>：2675089893<br>
                        <strong>渠道合作</strong>：0411-39585580转806<br>
                        <strong>QQ</strong>：3135539730<br>
                        <strong>客服24小时服务热线</strong>：400-091-8058
                    </span>
                </li>
                <li class="foottit3of3">官方微信:sutuiapp<hr><br>
                    <img src="/css/site/images/sutuiapp.jpg"/>
<!--                    <a href="/reg" target="_blank">注册账号</a>
                    <a href="/product" target="_blank">下载APP</a>
                    <a href="/question" target="_blank">安装激活</a>
                    <a href="/question" target="_blank">获取收益</a>
                    <a href="/question" target="_blank">收益提现</a>-->
                </li>
            </ul>
        </div>
        <div class="footer-copyright">Copyright &copy; SuTuiAPP.COM Corporation, All Rights Reserved 速推APP推广联盟 版权所有 <br>辽ICP备15007856号-2</div>
    </div>
    <!-- //底部结束 -->

</div><!-- page -->
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?81331afe657494d7483f628e0a619c77";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>