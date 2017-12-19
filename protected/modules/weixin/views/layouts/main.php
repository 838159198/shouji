<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.0.0/weui.css"/>
    <link rel="stylesheet" href="/css/weixin/style/base.css"/>
    <script src="/css/weixin/js/zepto.min.js"></script>
</head>
<body ontouchstart="">
<?php echo $content; ?>
<!--<div class="weui-footer weui-footer_fixed-bottom">

    <p class="weui-footer__text">速推APP推广联盟 版权所有</p>
</div>-->
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/css/weixin/js/base.js"></script>
</body>
</html>
