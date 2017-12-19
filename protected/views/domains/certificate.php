<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>查看证书</title>
    <script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/core/jquery-1.9.1.min.js"></script>
    <link href="/css/rom/css/rom.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="tbody">
    <div class="rom-top-bg">
        <div style="background: url(/css/rom/images/top.gif) no-repeat center top; height: 400px;">
        </div>
    </div>
    <div class="nav">
        <div id="bNav">
            <ul>
                <?php $cookies = Yii::app()->request->getCookies();$utm_source = isset($cookies['utm_source']) ? $cookies['utm_source']->value : '';
                $uid = isset($cookies['agent']) ? $cookies['agent']->value : '';
                $host = 'http://'.$_SERVER['HTTP_HOST'];
                ?>
                <li><a href="<?=$host?>/t/<?=$uid?>?utm_source=2" title="查看证书">查看证书</a></li>

                <li><a href="#f2" title="分享证书" name="f2">分享证书</a></li>

                <li><a href="#f3" title="证书说明" name="f3">证书说明</a></li>

                <li style="border: none"><a href="/domains/cert" title="我的证书" >我的证书</a></li>
            </ul>
        </div>
    </div>

    <div class="content_1000">

        <div style="margin-top: 30px;margin-bottom: 20px;float: left;">
            <?php if($is_show){?>
            <div style="background: url(/css/rom/images/cert.gif) no-repeat center top; height: 450px;width:1000px;margin-bottom: 50px;float: left" id="c1">
                <div style="float: left;width: 186px;height: 250px;line-height: 350px;padding-left: 65px;font-size: 18px;font-family: 微软雅黑;text-align: center;color: #c5932c "><b><?=$username?></b></div>
                <div style="float: right;width: 120px;height: 250px;line-height: 470px;padding-right:360px;font-size: 18px;font-family: 微软雅黑;color: red;text-align: center;"><b><?=$num?></b></div>
                <div style="float:left;width: 1000px;height: 100px;text-align: center;line-height: 100px;color: #c5932c;font-size: 36px;font-family: '微软雅黑'"><?=$grade?></div>
            </div>

                <div style="height: 450px;width:1000px;float: left">
                    <div style="background: url(/css/rom/images/cert2_09.gif) no-repeat center top; height: 450px;">
                        <div  style="height: 300px;width: 550px;margin-top: 80px;margin-left: 450px;float: left;">
                            <p style="margin-top: 50px;line-height: 30px">将下列网址通过微博，贴吧，QQ等方式发送给你的朋友，<br/>将会获得积分奖励</p>
                            <div style="margin-top: 30px;line-height: 30px">
                                <input type="text" class="input-xxlarge" value="http://sutuiapp.com/t/<?=$t+123456?>?utm_source=2" style="width: 60%;margin-top: 10px;line-height: 30px" id="url">
                                <div class="bdsharebuttonbox">
                                    <a href="#" class="bds_tsina" data-cmd="tsina"></a>
                                    <a href="#" class="bds_qzone" data-cmd="qzone"></a>
                                    <a href="#" class="bds_tieba" data-cmd="tieba"></a>
                                    <a href="#" class="bds_sqq" data-cmd="sqq"></a>
                                    <a href="#" class="bds_more" data-cmd="more"></a>
                                </div>
                                <script>window._bd_share_config=
                                    {"common":
                                    {"bdSnsKey":{},"bdText":"《ROM大师评选》活动","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"32","bdUrl":"http://sutuiapp.com/t/<?=$t+123456?>?utm_source=2",
                                        "bdDesc":"各位亲们：我正在速推APP推广平台参加《ROM大师评选》活动，您也来一起参加吧！","bdSign" : "off"
                                    },
                                        "share":{}
                                    };
                                    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }else{?>
            <div style="margin-bottom: 50px;height: 300px;width:1000px;text-align: center;line-height: 300px;color: red;font-size: 36px;border:10px solid #45aeff;border-radius:20px"><?=$grade?></div>
            <?php }?>

        </div>
        <div class="cert_icon" style="width: 1000px;  float: left;">
            <ul>
                <li id="f2"><a href="#"><img src="/css/rom/images/cert2_10.gif"></a></li>
                <li><a href="/domains/cert"><img src="/css/rom/images/cert2_12s.gif"></a></li>
                <li><a href="/reg"><img src="/css/rom/images/cert2_14.gif"></a></li>
            </ul>
        </div>
    </div>

    <div class="cert_ad" id="f3">
        <div style="background: url(/css/rom/images/cert2_12.gif) no-repeat center top; height: 400px; width: 1000px; margin: 0 auto;" >
            <div class="radelef"></div>
            <div class="raderig">
                <ul>
                    <li>2016年有效设备量大于60000部的用户，分享证书链接将获得10000积分</li>
                    <li style="height: 20px;line-height: 20px;">2016年有效设备量大于30000部小于60000部的用户，分享证书链接将获得5000积分</li>
                    <li>2016年有效设备量小于30000部的用户，分享证书链接将获得1000积分</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer_content">
            Copyright &copy; SuTuiAPP.COM Corporation, All Rights Reserved<br />
            速推APP推广联盟 版权所有 辽ICP备15007856号
        </div>
    </div>
</div>

</body>

</html>


