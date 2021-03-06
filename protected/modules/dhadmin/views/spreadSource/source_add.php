

<?php
$this->breadcrumbs = array(
    '渠道列表' => array('index'),
    '添加渠道',
);
?>

    <div class="breadcrumbs">
        <a href="/dhadmin/shop/index">首页</a> &raquo; <a href="/dhadmin/spreadSource/index">推广渠道列表</a> &raquo; <span>添加渠道</span></div>
    <h4 class="text-center">添加渠道</h4>

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
            <label for="inputEmail3" class="col-sm-2 control-label">渠道名称</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'source_name', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'source_name',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">渠道标识</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'source_mark', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'source_mark',array('class'=>"errorMessageTips"));?></div>
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
