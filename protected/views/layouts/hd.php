<!DOCTYPE html>
<html lang="zh-CN">
<head id="Head1">
    <title><?php echo $this->pageTitle;?></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link type="text/css" rel="stylesheet" href="/css/hd/layout.css" />
    <link rel="stylesheet" type="text/css" href="/css/hd/zzysj/zzysj.css" />
</head>
<body style="background-color: #ffdc7e;">

<div class="topbar" id="top_nav">
    <div class="div_m">

        <div class="loation_box" id="divHeadLogin">
            <ul class="person_part">
                <li>您好，欢迎来到速推APP推广联盟</li>
            </ul>

            <ul class="person_part">
                <?php if(Yii::app()->user->isGuest){?>
                <li class="login_li" style="width:60px">
                    <a href="/login" class="topbar_reg topbar_login" onclick="loginInit()">请登录</a>
                    <s class="vertical_line"></s></li>
                <li>
                    <a href="/reg" target="_blank"><img src="/css/hd/hb.gif"/></a>
                    <a href="/reg" target="_blank" class="topbar_reg" style="color:#c30005">立即注册</a><s class="vertical_line"></s></li>
                <?php }else{?>
                    <li><a href="/member">进入管理中心</a></li>
                <?php }?>
            </ul>

        </div>
        <div class="loation_box" id="divHeadUsers" style="position: relative; z-index: 10;float:left">
        </div>
        <ul class="site_link">
            <li><a href="/" target="_blank" title="速推网站首页">速推首页</a><s class="vertical_line"></s></li>
            <li><a href="/product" target="_blank" title="业务产品">业务产品</a><s class="vertical_line"></s></li>
            <!--<li><a href="#" target="_blank" title="更多活动">更多活动</a><s class="vertical_line"></s></li>-->
            <li><a href="/question" target="_blank" title="新手教程">新手教程</a></li>
        </ul>
    </div>
</div>
<?php echo $content;?>
<div class="clear"></div>
<div class="downbar">
    <div class="downbar_all clearfix" style="display:none">
        <ul>

        </ul>
    </div>
    <div class="downbar_all_line"></div>
    <div class="downbar_bot_text">
        <p>
            Copyright &copy; SuTuiAPP.COM Corporation, All Rights Reserved<br />
            速推APP推广联盟 版权所有 辽ICP备15007856号
        </p>
    </div>
</div>

<div id="divNG">
</div>
</body>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?81331afe657494d7483f628e0a619c77";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</html>