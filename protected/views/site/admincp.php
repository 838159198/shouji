<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="author" content="zkn">
    <meta name="robots" content="noindex,nofollow">
    <?php Yii::app()->clientScript->registerCoreScript('jquery',CClientScript::POS_HEAD);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap/js/bootstrap.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap/css/bootstrap.min.css");?>
    <title>登录</title>
<style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
    }

    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin .checkbox {
        font-weight: normal;
    }
    .form-signin .form-control {
        position: relative;
        height: auto;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin input[type="text"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

</style>
</head>

<body>
<div class="container">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        //'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'htmlOptions'=>array('class'=>"form-signin")
    )); ?>
        <h2 class="form-signin-heading">管理员登录...</h2>
        <label for="inputEmail" class="sr-only">用户名</label>
        <?php echo $form->textField($model, 'username', array('placeholder' => '用户名','class'=>'form-control')); ?>

        <label for="inputPassword" class="sr-only">密码</label>
        <?php echo $form->passwordField($model, 'password', array('placeholder' => '密码','class'=>'form-control')); ?>

        <div class="input-group">
            <?php if (CCaptcha::checkRequirements()) {
                echo $form->textField($model, 'verifyCode', array('placeholder' => '验证码', 'class' => 'form-control'));
                ?>
            <span class="input-group-addon" id="basic-addon2" style="padding: 0"><?php $this->widget('CCaptcha', array(
                        'buttonLabel' => '',
                        'id'=>'captcha',
                        'clickableImage' => true,
                        'imageOptions' => array('width' => '120', 'height' => '40'),
                    )
                );?></span>
                <?php }  ?>

        </div>
    <div class="checkbox">
        <label>
            <?php echo $form->checkBox($model, 'rememberMe'); ?> 保存登录状态
        </label>
    </div>

    <?php
    //$form->errorSummary($model,"","",array("class"=>"alert alert-danger"));
    echo $form->error($model, 'username',array("class"=>"alert alert-danger"));
    echo $form->error($model, 'password',array("class"=>"alert alert-danger"));
    echo $form->error($model, 'verifyCode',array("class"=>"alert alert-danger"));
    ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登 录</button>
    <?php $this->endWidget(); ?>

</div> <!-- /container -->

</body>
</html>