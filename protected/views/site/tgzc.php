<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2016/12/30
 * Time: 13:08
 */
?>
<title>推广活动注册账号</title>
<link rel="stylesheet" type="text/css" href="/css/tgzc/tgzc_pc.css">

<div class="tgzc_main">
    <div class="tgzc_topimg"></div>

    <div class="tgzc_wrap">

        <div class="tgzc_04"><img src="/css/tgzc/images/tgy_03.gif" ><img src="/css/tgzc/images/tgy_04.gif" ></div>
        <div class="tgzc_div_ul">
            <ul class="tgzc_ul" style="width: 726px;height: 293px;margin: 0 auto">
                <li><img src="/css/tgzc/images/tgy_08.gif" width="190" height="185" alt=""><div class="tgzc_font" >ROM开发者</div></li>
                <li style="margin-left: 78px;"><img src="/css/tgzc/images/tgy_11.gif" width="190" height="185" alt=""><div class="tgzc_font">手机门店销售</div></li>
                <li style="margin-left: 78px;"><img src="/css/tgzc/images/tgy_14.gif" width="190" height="185" alt=""><div class="tgzc_font">手机批发商</div></li>
            </ul>
        </div>
        <div class="tgzc_zc" style="margin-left: 20px;float: left">
            <div style="width: 540px;height: 105px;background-color: white;border-radius:10px 10px 0 0;">
                <div style="width: 150px;margin-left: 30px;float: left">
                    <div style="font-size: 35px;font-weight: bold;width: 150px;margin-top: 40px">马上注册</div>
                </div>
                <div style="float: left;width: 300px;height: 50px;height: 86px">
                     <div style="margin-top: 60px;margin-left: 20px;font-size: 20px;font-weight: bold">注册后将会获得1000积分奖励</div>
                </div>
            </div>
            <div style="width: 540px;height: 386px;margin-top: 12px;background-color: white;border-radius:0 0 10px 10px;">
                <div class="reg-container">
                    <div class="reg-content">
                        <div class="reg-content-l">
                            <div class="reg-forminfo">
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
                                <div style="width: 520px;height: 5px;"></div>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 用户名:</dt>
                                    <dd><?php echo $form->textField($model,'username',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'username'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 密码:</dt>
                                    <dd><?php echo $form->passwordField($model,'password',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'password'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 确认密码:</dt>
                                    <dd><?php echo $form->passwordField($model,'password2',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'password2'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 手机号码:</dt>
                                    <dd><?php echo $form->textField($model,'tel',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'tel'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> QQ号码:</dt>
                                    <dd><?php echo $form->textField($model,'qq',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'qq'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 用户类型:</dt>
                                    <dd><?php echo $form->dropDownList($model, 'type', $datacome,array("empty"=>"","class"=>"reg-select")) ?></dd>
                                    <div class="error"><?php echo $form->error($model,'type'); ?></div>
                                </dl>

                                <dl>
                                    <dt> 邀请码:</dt>
                                    <dd><?php echo $form->textField($model,'invitationcode',array("class"=>"reg-input")); ?></dd>
                                    <div class="error"><?php echo $form->error($model,'invitationcode'); ?></div>
                                </dl>
                                <dl>
                                    <dt><font color="#ff0000">*</font> 验证码:</dt>
                                    <dd><?php if (CCaptcha::checkRequirements()) {
                                            echo $form->textField($model, 'verifyCode2', array('placeholder' => '验证码', 'class' => 'reg-input-verifyCode'));
                                            ?>
                                            <?php $this->widget('CCaptcha', array(
                                                    'buttonLabel' => '',
                                                    'id'=>'captcha',
                                                    'captchaAction'=>'site/captcha',
                                                    'clickableImage' => true,
                                                    'imageOptions' => array('width' => '100', 'height' => '34'),
                                                )
                                            );}?>
                                    <div class="error"><?php echo $form->error($model,'verifyCode2'); ?></div>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt> </dt>
                                    <dd><input type="submit" class="reg-button" value="提交"></dd>
                                </dl>
                                <?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                    <!-- //reg-content__END//-->
                </div>
            </div>
        </div>
        <div class="tgzc_phb" style="float: left;margin-left: 44px;width: 377px;height: 504px;background-color: #fff">
            <div style="margin-left: -15px;margin-top: 21px"><img src="/css/tgzc/images/tgy_20.gif" width="292" height="57" alt=""></div>
            <div class="right-list">
                <div class="tgzc-main">
                    <div class="tgzc-obtain-list">
                        <div class="tgzc-obtain-list-content">
                            <ul>
                                <?php $this->widget('application.components.widget.tgzc.IncomeListWidget',array("num" => 20)); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <script  type="text/javascript" src="/js/core/jquery.SuperSlide.2.1.1.js"></script>
            <script type="text/javascript">
                jQuery(".tgzc-obtain-list").slide({
                    mainCell:".tgzc-obtain-list-content ul",
                    autoPlay:true,
                    effect:"topMarquee",
                    vis:10,
                    interTime:50,
                    trigger:"click"
                });
            </script>
        </div>
    </div>
</div>
<div class="tgzc_main01">
    <div class="" style="width: 1000px;margin: 0 auto;position: relative">
    <div class="tgzc_wrap01">

        <div><img src="/css/tgzc/images/tgy_22.gif" width="1000" height="49" alt=""></div>
        <div><img src="/css/tgzc/images/tgy_05.gif"></div>
        <div>
            <ul class="tgzc_ul02" style="width: 900px;height: 276px;margin: 0 auto">
                <li class="tgzc_li0"><img src="/uploads/image/20150605/20150605172040_65764.png" width="90" height="90" alt=""><div class="tgzc_font_li1">UC浏览器</div><div class="tgzc_font_li2">2元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20160629/20160629101918_25623.png" width="90" height="90" alt=""><div class="tgzc_font_li1">应用宝</div><div class="tgzc_font_li2">2元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20161220/20161220131623_35125.png" width="90" height="90" alt=""><div class="tgzc_font_li1">百度浏览器</div><div class="tgzc_font_li2">2元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20150605/20150605172746_78587.png" width="90" height="90" alt=""><div class="tgzc_font_li1">PP助手精装版</div><div class="tgzc_font_li2">2元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20150608/20150608091349_87396.png" width="90" height="90" alt=""><div class="tgzc_font_li1">360手机助手</div><div class="tgzc_font_li2">3元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20150608/20150608091842_49770.png" width="90" height="90" alt=""><div class="tgzc_font_li1">手机百度</div><div class="tgzc_font_li2">2元/台</div></li>
                <li class="tgzc_li1"><img src="/uploads/image/20150729/20150729115902_49377.png" width="90" height="90" alt=""><div class="tgzc_font_li1">美团</div><div class="tgzc_font_li2">1.4元/台</div></li>

            </ul>
        </div>
        <div class="tgzc_36"><img src="/css/tgzc/images/tgy_36.jpg" width="904" height="343" alt=""></div>
        <div><img src="/css/tgzc/images/tgy_40.gif" width="1000" height="42" alt=""></div>
    </div>
    </div>
</div>

