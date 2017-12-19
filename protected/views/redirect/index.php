<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <script  type="text/javascript" src="/css/site/js/jquery-1.7.2.min.js"></script>
    <title>跳转页</title>
<style type="text/css">
body{ font-size: 12px; color: #333; margin: 0; padding: 0; font-family: "Microsoft YaHei", "微软雅黑", SimSun, "宋体", Heiti, "黑体", sans-serif;}
    ul,li,dl,dt,dd{ margin: 0; padding: 0; list-style: none;}
    h1,h2,h3,h4,h5,h6{ margin: 0; padding: 0; font-size: 12px;}
    img,img a{ border: 0;}
    .container{ width: 500px;height:300px;margin:0 auto;margin-top: 150px;position: relative;
        background-color: #fff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid #999;
        border-radius: 6px;
        outline: 0;
        -webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);
        box-shadow: 0 3px 9px rgba(0,0,0,.5);}
    .header{ height: 50px; line-height: 50px; border-bottom: 1px solid #e5e5e5; font-size: 18px; font-weight: bold; text-align: center;}
    .loadimg{ text-align: center; margin: 0 auto; padding: 0; margin-top: 50px; overflow: hidden;}
    .message{ text-align: center; font-size: 18px; color: #ff0000; overflow:hidden;margin-top: 35px;}
.tips{ font-size: 16px; color: #333; text-align: center; margin-top: 35px; overflow: hidden;}
    .tips a{ color: #0000ff;   text-decoration: underline;}
    .tips a:hover{ color: #ff0000; text-decoration:none;}
    #mes{ color: #ff0000; font-size: 18px; font-weight: bold;}
</style>
</head>
<body>
<div class="container">
    <div class="header">温馨提示</div>
    <div class="loadimg"><img src="/css/images/load3.gif" border="0"></div>
    <div class="message"><?php echo CHtml::encode($message); ?></div>
    <div class="tips"><span id="mes">3</span> 秒后自动跳转......如未跳转，请<a href="<?php echo $url;?>">点击此处</a>进行页面跳转。</div>
</div>
<script type="text/javascript">
    var i = 3;
    var intervalid;
    intervalid = setInterval("fun()", 1000);
    function fun() {
        if (i == 0) {
            //window.location.href = "<?php echo $url;?>";
            location.href = $("a").attr("href");
            clearInterval(intervalid);
        }
        document.getElementById("mes").innerHTML = i;
        i--;
    }
</script>
</body>
</html>