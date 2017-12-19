
<?php
$this->breadcrumbs = array(
    '盒子列表' => array('index'),
    '更新盒子',
);
?>
<div class="breadcrumbs">
   <a href="/dhadmin/softbox/index">盒子列表</a> &raquo; <span>更新盒子信息</span></div>
<h4 class="text-center">更新盒子信息</h4>

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
                    <label for="inputEmail3" class="col-sm-2 control-label">设备码 *</label>
                    <div class="col-sm-5">
                        <?php echo $form->textField($model, 'box_number', array('class'=>'form-control','disabled'=>'disabled')); ?>
                    </div>
                    <div class="col-md-5"><?php echo $form->error($model, 'box_number',array('class'=>"errorMessageTips"));?></div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">用户名 *</label>
                    <div class="col-sm-5">
                        <input list="browsers" id="credits" style="width: 100%">
                        <datalist id="browsers">
                            <?php foreach($member as $m){ ?>
                                <option value="<?=$m->username?>##<?=$m->id?>"></option>
                            <?php }?>
                        </datalist>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">用户id *</label>
                    <div class="col-sm-5">
                        <?php echo $form->textField($model, 'uid', array('class'=>'form-control')); ?>
                    </div>
                    <div class="col-md-5"><?php echo $form->error($model, 'uid',array('class'=>"errorMessageTips"));?></div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">状 &nbsp;&nbsp;态&nbsp;&nbsp;</label>
                    <div class="col-sm-5">
                        <?php echo $form->dropDownList($model,'status',array("0"=>"禁用","1"=>"正常"),array("class"=>"form-control"));?>
                    </div>
                    <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">接口型号</label>
                    <div class="col-sm-5">
                        <?php echo $form->dropDownList($model,'type',array("0"=>"二口","1"=>"八口"),array("class"=>"form-control",'disabled'=>'disabled'));?>
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
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $("#Softbox_uid").focus(function(){
            var p_text=$("#credits").val();
            var arr=p_text.split("##")
            $("#Softbox_uid").val(arr[1]);
        });
    });

</script>