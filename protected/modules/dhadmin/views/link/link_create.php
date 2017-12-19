<div class="page-header app_head">
    <h1 class="text-center text-primary">添加友情链接 <small></small></h1>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => "form-horizontal"),
    )); ?>

    <div class="alert alert-success">带（*）的内容是必须填写的！</div>

    <?php //echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">网站名称*</label>
        <div class="col-md-5">
            <?php echo $form->textField($model, 'name', array('class'=>'form-control','placeholder' => '请输入网站名称')); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">网址*</label>
        <div class="col-md-5">
            <?php echo $form->textField($model, 'website', array('class'=>'form-control','placeholder' => '请输入网址')); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'website',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">状态*</label>
        <div class="col-sm-5">
            <?php echo $form->dropDownList($model,'status',array("0"=>"隐藏","1"=>"正常"),array("class"=>"form-control"));?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">分类*</label>
        <div class="col-sm-5">
            <?php echo $form->dropDownList($model, 'cid', CHtml::listData($category,"id","name"),array("class"=>"form-control","empty"=>"-==请选择==-")); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'cid',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">QQ</label>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'qq', array('class'=>'form-control','placeholder' => '请输入QQ')); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'qq',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">序号*</label>
        <div class="col-sm-5">
            <?php echo $form->textField($model, 'num', array('class'=>'form-control','placeholder' => '请输入序号')); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'num',array('class'=>"errorMessageTips"));?></div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-5">
            <?php echo $form->textArea($model, 'remarks', array('class'=>'form-control','placeholder' => '请输入备注信息')); ?>
        </div>
        <div class="col-md-5"><?php echo $form->error($model, 'remarks',array('class'=>"errorMessageTips"));?></div>
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
