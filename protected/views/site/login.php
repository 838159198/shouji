<style type="text/css">
    body{ background: #f2f2f2;}
</style>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('reg_ok')):?>
    <div class="reg-tips">
        <b><?php echo Yii::app()->user->getFlash('reg_ok');?></b>
    </div>
<?php endif;?>
<div class="reg-container">
    <div class="reg-header"><h3>您好，欢迎回来！</h3></div>
    <div class="reg-content">
        <div class="reg-content-l">
            <div class="reg-forminfo">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'reg-form',
                    //'enableAjaxValidation'=>true,
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                )); ?>
                <dl>
                    <dt><font color="#ff0000">*</font> 用户名:</dt>
                    <dd><?php echo $form->textField($model,'username',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'username'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 密码:</dt>
                    <dd><?php echo $form->passwordField($model,'password',array("class"=>"reg-input reg-password")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'password'); ?></div>
                </dl>
                <dl>
                    <dt> </dt>
                    <dd><?php echo $form->checkBox($model,'rememberMe'); ?> <?php echo $form->label($model,'rememberMe'); ?></dd>
                    <div class="error"><?php echo $form->error($model,'rememberMe'); ?></div>
                </dl>

                <dl>
                    <dt><font color="#ff0000">*</font> 验证码:</dt>
                    <dd><?php if (CCaptcha::checkRequirements()) {
                            echo $form->textField($model, 'verifyCode', array('placeholder' => '验证码', 'class' => 'reg-input-verifyCode'));
                            ?>
                            <?php $this->widget('CCaptcha', array(
                                    'buttonLabel' => '',
                                    'id'=>'captcha',
                                    'clickableImage' => true,
                                    'imageOptions' => array('width' => '150', 'height' => '34'),
                                )
                            );}?></dd>
                    <div class="error"><?php echo $form->error($model,'verifyCode'); ?></div>
                </dl>
                <dl>
                    <dt> </dt>
                    <dd><input type="submit" class="reg-button" value="立即登录"></dd>
                </dl>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- // 左侧结束 -->
        <div class="reg-content-r">
            <div class="reg-r-img"><img src="/css/site/images/login1.png" border="0"></div>
            <div class="reg-r-txt">
                <p>没有账号？<a href="/reg">立即注册</a></p>
                <p>如果忘记密码，请与客服联系。</p>
            </div>
        </div>
        <!-- //右侧结束 -->
    </div>
    <!-- //reg-content__END//-->
</div>
<script type="text/javascript" src="/js/jQuery.md5.js"></script>
<script type="text/javascript">
//    $("form").submit(function(e){
//
//    });
        $('.reg-button').bind('click',function () {
            $(this).unbind('click');
            var pwd = $.trim($('.reg-password').val());
            if (pwd!=''){
                var pwd1 = pwd.split("").reverse().join("");
                var pwd2= $.md5(pwd1);
                var pwd3= pwd2.split("").reverse().join("");
                var pwd4= $.md5(pwd3);
                $('.reg-password').val(pwd4);
            }

        })
    $(function () {
        if ($('.reg-password').hasClass('error')){
            $('.reg-password').val('');
        }
    })


</script>