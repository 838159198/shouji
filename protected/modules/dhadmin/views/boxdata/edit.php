<div class="page-header app_head">
    <h1 class="text-center text-primary">盒子文件更新<small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">盒子文件系统</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/boxdata/index");?>" class="list-group-item">盒子文件列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/boxdata/create");?>" class="list-group-item">上传盒子文件</a>
            </div>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal",'enctype'=>'multipart/form-data'),
            )); ?>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户id</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"uid",array("class"=>"form-control","readonly"=>"readonly")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'uid',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品id</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"tid",array("class"=>"form-control","readonly"=>"readonly")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'tid',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文件名</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"name",array("class"=>"form-control","readonly"=>"readonly")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips")); ?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">类别</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"classify",array("class"=>"form-control","readonly"=>"readonly")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'classify',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">版本号</label>
                <div class="col-sm-5"><?php echo $form->textField($model, 'version',array("class"=>"form-control")) ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'version',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文件</label>
                <div class="col-sm-5"><?php echo $form->fileField($model,"downPath"); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'downPath',array('class'=>"errorMessageTips")); ?></div>
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