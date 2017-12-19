
<?php
$this->breadcrumbs = array(
    '商品列表' => array('index'),
    '更新商品',
);
?>
<div class="breadcrumbs">
    <a href="/dhadmin/shop/index">首页</a> &raquo; <a href="/dhadmin/shop/index">商品列表</a> &raquo; <span>更新分类</span></div>
<h4 class="text-center">更新分类</h4>

<?php
/* @var $this AdminController */
/* @var $model Manage */
/* @var $form CActiveForm */

?>

<div class="form">
    <?php
    //判断是否有提示信息
    if(Yii::app()->user->hasFlash('status')){?>
        <script type="text/javascript">alert("添加成功！");</script>
        <div class="alert alert-success">
            <b><?php echo Yii::app()->user->getFlash('status');?></b>

        </div>
    <?php }?>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">分类名 *</label>
                    <div class="col-sm-5">
                        <?php echo $form->textField($model, 'cname', array('class'=>'form-control')); ?>
                    </div>
                    <div class="col-md-5"><?php echo $form->error($model, 'cname',array('class'=>"errorMessageTips"));?></div>
                </div>




                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-5">
                        <?php echo $form->dropDownList($model,'status',array("0"=>"关闭","1"=>"正常"),array("class"=>"form-control"));?>
                    </div>
                    <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
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
</div>
<script type="text/javascript">
    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh-CN', weekStart:1,todayBtn:1,
            autoclose:1,
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0
        });
    });
</script>