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
                    当前位置：<li><a href="/msg">管理主页</a></li>
                    <li class="active">密码修改</li>
                </ol>
            </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal",'onsubmit'=>"if(!checkAll()){return false;}"),
            )); ?>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">原始密码</label>
                <div class="col-sm-5">
                    <?php echo $form->passwordField($model, 'old', array('class'=>'form-control','placeholder' => '请填写原始密码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'old',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">修改密码</label>
                <div class="col-sm-5">
                    <?php echo $form->passwordField($model, 'password', array('class'=>'form-control','placeholder' => '请填写修改密码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'password',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">重复密码</label>
                <div class="col-sm-5">
                    <?php echo $form->passwordField($model, 'again', array('class'=>'form-control','placeholder' => '请填写重复密码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'again',array('class'=>"errorMessageTips"));?></div>
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