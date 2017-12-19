<!--<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php /*echo $data['username'];*/?> <small>资料修改</small></h1>
</div>-->
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')):?>
    <div class="container-fluid">
        <div class="alert alert-success ">
            <b ><?php echo Yii::app()->user->getFlash('status');?></b>
        </div>
    </div>
<?php endif;?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">资料修改</li>
                </ol>
            </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">手机号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'tel', array('class'=>'form-control','placeholder' => '请输入手机号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'tel',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">电子邮件</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'mail', array('class'=>'form-control','placeholder' => '请输入email')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'mail',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">QQ号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'qq', array('class'=>'form-control','placeholder' => '请输入QQ号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'qq',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">微信号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'weixin_name', array('class'=>'form-control','placeholder' => '请输入微信号')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'weixin_name',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">确认提交</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>


        </div>
    </div>
</div>