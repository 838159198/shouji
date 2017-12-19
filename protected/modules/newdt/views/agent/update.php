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
                    当前位置：<li><a href="/newdt">管理主页</a></li>
                    <li class="active">资料修改</li>
                </ol>
            </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
<!--            <div class="form-group">-->
<!--                <label for="inputEmail3" class="col-sm-2 control-label">子用户</label>-->
<!--                <div class="col-sm-5">-->
<!--                    --><?php //echo $form->dropDownList($data, 'uid', CHtml::listData($members,"id","username"),array("class"=>"form-control","empty"=>"-==请选择用户==-")); ?>
<!--                </div>-->
<!--                <div class="col-md-5">--><?php //echo $form->error($data, 'uid',array('class'=>"errorMessageTips"));?><!--</div>-->
<!--            </div>-->

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">价格</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'price', array('class'=>'form-control','placeholder' => '请输入价格')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'price',array('class'=>"errorMessageTips"));?></div>
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