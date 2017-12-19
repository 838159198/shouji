<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php echo $data['username'];?> <small>修改密码</small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">我的信息</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/myInfo");?>" class="list-group-item">修改资料</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/myPassword");?>" class="list-group-item">修改密码</a>
            </div>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php
            //判断是否有提示信息
            if(Yii::app()->user->hasFlash('status')):?>
                <div class="alert alert-success">
                    <b><?php echo Yii::app()->user->getFlash('status');?></b>
                </div>
            <?php endif;?>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">密码</label>
                <div class="col-sm-5">
                    <?php echo $form->passwordField($data, 'password', array('class'=>'form-control','placeholder' => '请输入新密码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'password',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">重复密码</label>
                <div class="col-sm-5">
                    <?php echo $form->passwordField($data, 'password2', array('class'=>'form-control','placeholder' => '请确认密码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'password2',array('class'=>"errorMessageTips"));?></div>
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