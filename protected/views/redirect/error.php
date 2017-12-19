<?php
/* @var $this RedirectController */
/* @var $code string */
/* @var $message string */
/* @var $error array */
$request = Yii::app()->request;
?>

<div class="container">
    <div class="form-signin">
        <div class="row">
            <div class="h-logo"></div>
        </div>

        <h4>错误</h4>

        <p class="text-center alert alert-error"><?php echo CHtml::encode($message . ' ' . $code); ?></p>

        <p class="text-center">
            <?php echo CHtml::link('返回首页', Yii::app()->homeUrl) ?>
            &nbsp;&nbsp;
            <?php echo empty($request->urlReferrer) ? '' : CHtml::link('返回上一页', $request->urlReferrer) ?>
        </p>
    </div>
</div>