<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="language" content="zh-CN"/>
    <meta name="robots" content="all">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="Copyright" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css" />
    <script type="text/javascript" src="/js/core/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/js/core/jquery-migrate-1.1.1.js"></script>
    <script type="text/javascript" src="/css/bootstrap/js/bootstrap.min.js"></script>
    <title>推广活动注册账号</title>

    <link rel="stylesheet" type="text/css" href="/css/tgzc/tgzc_phone.css">
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="/js/core/css/custom-theme/jquery.ui.1.10.0.ie.css"/>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->

</head>
<body>
<div class="topbar" id="top_nav">

            <ul>
                    <li>欢迎来到速推APP推广联盟</li>
                    <li class="login_li" style="width:60px">
                        <a href="/login" class="topbar_reg topbar_login" onclick="loginInit()">请登录</a>
                    </li>
                <li><a href="/" target="_blank" title="速推网站首页">速推首页</a><s class="vertical_line"></s></li>
            </ul>
</div>


<div class="phone-main" style="">
    <div class="phone_01"><img src="/css/tgzc/images_phone/720_01.gif" width="721" height="256"></div>
    <div class="phone_02">
        <div class="phone_02_title">适用人群</div>
        <ul>
            <li><div class="phone_02_li_div01"><img src="/css/tgzc/images_phone/720_04.gif"><div class="phone_02_li_div02">ROM开发者</div></div></li>
            <li><div class="phone_02_li_div01"><img src="/css/tgzc/images_phone/720_06.gif"><div class="phone_02_li_div02">手机门店销售</div></div></li>
            <li><div class="phone_02_li_div01"><img src="/css/tgzc/images_phone/720_08.gif"><div class="phone_02_li_div02">手机批发商</div></div></li>
        </ul>
    </div>
    <div class="phone_03">
        <div class="phone_03_div01">
            <div class="phone_03_div02">
                <div class="phone_03_div03">
                        <div class="phone_03_div04"></div>
                        <div class="phone_03_div05">马上注册</div>
                        <div class="phone_03_div06">注册后将会获得1000积分奖励</div>
                </div>
            </div>
            <div class="phone_03_div07">
                <div class="container h-content">
                    <div class="span9">
                        <div role="form">
                            <?php $form=$this->beginWidget('CActiveForm', array(
                                'id'=>'reg-form',
                                'action'=>'/reg',
                                //'enableAjaxValidation'=>true,
                                'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                ),
                            ));
                            $datacome=array(9=>"ROM开发者",8=>"线下手机销售",4=>"批发商",5=>"微信/QQ/网站",6=>"广告合作",7=>"其它",);
                            ?>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_username"><span class="required">*&nbsp;</span>用户名:</label></div>
                                <div class="controls">
                                    <?php echo $form->textField($model,'username',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'username'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_password"><span class="required">*&nbsp;</span>登录密码: </label></div>
                                <div class="controls">
                                    <?php echo $form->passwordField($model,'password',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'password'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_again"><span class="required">*&nbsp;</span>确认密码: </label></div>
                                <div class="controls">
                                    <?php echo $form->passwordField($model,'password2',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'password2'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_qq"><span class="required">*&nbsp;</span>QQ: </label></div>
                                <div class="controls">
                                    <?php echo $form->textField($model,'qq',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'qq'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_tel"><span class="required">*&nbsp;</span>手机号码: </label></div>
                                <div class="controls">
                                    <?php echo $form->textField($model,'tel',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'tel'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_type"><span class="required">*&nbsp;</span>用户类型: </label></div>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'type', $datacome,array("empty"=>"","class"=>"form-control")) ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'type'); ?></div>
                            </div>
                            <div class="control-group" style="display: none">
                                <div class="control-labeldiv"><label class="control-label required" for="Member_invitationcode">邀请码: </label></div>
                                <div class="controls">
                                    <?php echo $form->textField($model,'invitationcode',array("class"=>"form-control")); ?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'invitationcode'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-labeldiv"><label class="control-label" for="Member_verifyCode"><span class="required">*&nbsp;</span>验证码:</label></div>
                                <div class="controls" style="">
                                    <?php if (CCaptcha::checkRequirements()) {
                                        echo $form->textField($model, 'verifyCode', array('placeholder' => '验证码', 'class' => 'form-control-verifyCode'));
                                        ?>
                                        <?php $this->widget('CCaptcha', array(
                                                'buttonLabel' => '',
                                                'id'=>'captcha',
                                                'clickableImage' => true,
                                                'imageOptions' => array('width' => '60', 'height' => '34'),
                                            )
                                        );}?>
                                </div>
                                <div class="error"><?php echo $form->error($model,'verifyCode'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <input class="btn btn-primary btn-lg long-btn leave-lg" type="submit" name="yt0" value="提交" />
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="phone_04"><img src="/css/tgzc/images_phone/720_14.gif" width="601" height="177"></div>
    <div class="phone_05">
        <div class="phone_05_title">手机软件</div>
        <ul>
            <li><div class="phone_05_div01"><img src="/uploads/image/20150605/20150605172040_65764.png"><div class="phone_05_div02">UC浏览器<br><span>2元/台</span></div></li>
            <li><div class="phone_05_div01"><img src="/uploads/image/20150608/20150608091349_87396.png"><div class="phone_05_div02">360手机助手<br><span>3元/台</span></div></li>
            <li><div class="phone_05_div01"><img src="/uploads/image/20150608/20150608091842_49770.png"><div class="phone_05_div02">手机百度<br><span>2元/台</span></div></li>
        </ul>
    </div>
    <div class="phone_06">
            <img class="phone_06_img01" src="/css/tgzc/images_phone/720_27.gif" width="480" height="66" alt="">
            <div class="phone_06_div01">
                    <div class="phone-list">
                        <div class="phone-obtain-main">
                            <div class="phone-obtain-list">
                                <div class="phone-obtain-list-content">
                                    <ul>
                                        <?php $this->widget('application.components.widget.tgzc.IncomeListWidget',array("num" => 20)); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <script  type="text/javascript" src="/js/core/jquery.SuperSlide.2.1.1.js"></script>
                <script type="text/javascript">
                    jQuery(".phone-obtain-list").slide({
                        mainCell:".phone-obtain-list-content ul",
                        autoPlay:true,
                        effect:"topMarquee",
                        vis:10,
                        interTime:50,
                        trigger:"click"
                    });
                </script>
        </div>
    <div class="phone_foot">

            <div class="phone_bot_text">
                <span>
                    Copyright &copy; SuTuiAPP.COM Corporation<br/>All Rights Reserved<br/>
                    速推APP推广联盟 版权所有 辽ICP备15007856号
                </span>
            </div>

    </div>

    </div>

























</body>
</html>
