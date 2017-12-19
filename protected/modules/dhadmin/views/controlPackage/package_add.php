<?php
$this->breadcrumbs = array(
    '监控包列表' => array('index'),
    '添加监控包',
);
?>

    <div class="breadcrumbs">
       <a href="/dhadmin/controlPackage/index">监控包列表</a> &raquo; <span>添加监控包</span></div>
    <h4 class="text-center">添加监控包</h4>

<?php
/* @var $this AdminController */
/* @var $model Manage */
/* @var $form CActiveForm */

?>
<div class="container-fluid">
    <div class="alert alert-info" >带（*）的内容是必须填写的！</div>
<div class="row">
    <!--左侧-->
    <div class="col-md-10">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => false,
            'htmlOptions' => array('class' => "form-horizontal"),
        )); ?>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">包名 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'package_name', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'package_name',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">业务名称 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'name', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">分组</label>
            <div class="col-sm-5">
                <?php echo $form->dropDownList($model,'type',array("0"=>"ROM","99"=>"线下","707"=>"707"),array("class"=>"form-control"));?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'type',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">确认提交</button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="form">
    <?php
    //判断是否有提示信息
    if(Yii::app()->user->hasFlash('status')){?>
        <script type="text/javascript">alert("添加成功！");</script>
        <div class="alert alert-success">
            <b><?php echo Yii::app()->user->getFlash('status');?></b>

        </div>
    <?php }?>
</div>
