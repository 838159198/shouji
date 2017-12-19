<div class="page-header app_head">
    <h1 class="text-center text-primary">活动报名中心 <small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <li class="list-group-item active">活动报名</li>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/huodong");?>" class="list-group-item">返回列表</a>
            </div>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-5">
                    <?php echo $username; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态审核</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("1"=>"审核通过","2"=>"审核拒绝"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">拒绝理由</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'bak', array('class'=>'form-control','placeholder' => '拒绝理由')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'bak',array('class'=>"errorMessageTips"));?></div>
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
