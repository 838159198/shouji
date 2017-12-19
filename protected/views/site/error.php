<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <script  type="text/javascript" src="/css/site/js/jquery-1.7.2.min.js"></script>
    <title>error</title>
    <style type="text/css">
        body{ font-size: 12px; color: #333; margin: 0; padding: 0; font-family: "Microsoft YaHei", "微软雅黑", SimSun, "宋体", Heiti, "黑体", sans-serif;}
        ul,li,dl,dt,dd{ margin: 0; padding: 0; list-style: none;}
        h1,h2,h3,h4,h5,h6{ margin: 0; padding: 0; font-size: 12px;}
        img,img a{ border: 0;}
        .container{ width: 500px;height:300px;margin:0 auto;margin-top: 150px;position: relative;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #ff0000;
            border-radius: 6px;
            outline: 0;
            -webkit-box-shadow: 0 3px 9px #ff0000;
            box-shadow: 0 3px 9px #ff0000;}
        .header{ height: 50px; line-height: 50px; border-bottom: 1px solid #e5e5e5; font-size: 18px; font-weight: bold; text-align: center; color: #cc0000;}

        .con{ height: 250px; margin: 0; padding: 0; position: relative;  overflow: hidden;}
        .con_l{ width: 170px; float: left; overflow: hidden;}
        .con_r{ width:320px; float: right; overflow: hidden;}
        .con_l .errorimg{ width: 139px; height: 148px; margin-left: 20px; margin-top: 40px;}
        .code{ font-size: 50px; color: #999; margin-top: 30px; text-align: left; z-index: -99; overflow: hidden;}
        .message{ text-align: left; font-size: 18px; color: #ff0000; overflow:hidden;margin-top: 25px;}
        .tips{ font-size: 16px; color: #999; text-align: left; margin-top: 25px; overflow: hidden;}
        .tips a{ color: #0000ff;   text-decoration: underline;}
        .tips a:hover{ color: #ff0000; text-decoration:none;}


    </style>
</head>
<body>
<div class="container">
    <div class="header">发生错误</div>
    <div class="con">
        <div class="con_l">
            <div class="errorimg"><img src="/css/images/error.png" border="0"></div>
        </div>
        <div class="con_r">
            <div class="code"><?php echo $code; ?></div>
            <div class="message"><?php echo CHtml::encode($message); ?></div>
            <div class="tips">快速入口：<a href="<?php echo (Yii::app()->request->urlReferrer=="")?"/":Yii::app()->request->urlReferrer;?>">返回上一页</a> - <a href="/">返回网址首页</a></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var i = 10;
    var intervalid;
    intervalid = setInterval("fun()", 1000);
    function fun() {
        if (i == 0) {
            window.location.href = "<?php echo (Yii::app()->request->urlReferrer=="")?"/":Yii::app()->request->urlReferrer;?>";
            clearInterval(intervalid);
        }
        i--;
    }
</script>
</body>
</html>