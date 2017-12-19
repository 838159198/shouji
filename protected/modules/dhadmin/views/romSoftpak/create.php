<div class="page-header app_head">
    <h1 class="text-center text-primary">上传统计软件<small></small></h1>
</div>
<div class="alert alert-danger">注意：
   <p style="margin-left: 40px">
        上传统计软件时，统计id、版本号（整数）不能为空
       </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">统计软件系统</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/romSoftpak/index");?>" class="list-group-item">统计软件列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/romSoftpak/create");?>" class="list-group-item">上传上传统计软件</a>
            </div>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => true,
                'enableAjaxValidation'=>true,
                'htmlOptions' => array('class' => "form-horizontal",'enctype'=>'multipart/form-data'),
            )); ?>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">统计id</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"serial_number",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'serial_number',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">版本号</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"version",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'version',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户类型</label>
                <div class="col-sm-5"><?php echo $form->dropDownList($model, 'type', RomSoftpak::model()->getlistDataUserGroup(),array("class"=>"form-control")) ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'type',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文件</label>
                <div class="col-sm-5"><?php echo $form->fileField($model,"url"); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'url',array('class'=>"errorMessageTips")); ?></div>
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
