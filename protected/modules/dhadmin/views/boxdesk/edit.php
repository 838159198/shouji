<div class="page-header app_head">
    <h1 class="text-center text-primary">盒子桌面更新<small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">盒子桌面系统</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/boxdesk/index");?>" class="list-group-item">盒子桌面列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/boxdesk/create");?>" class="list-group-item">上传盒子桌面文件</a>
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
                <label for="inputPassword3" class="col-sm-2 control-label">统计id</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"tid",array("class"=>"form-control","readonly"=>"readonly")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'tid',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">版本号</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"version",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'version',array('class'=>"errorMessageTips")); ?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文件</label>
                <div class="col-sm-5"><?php echo $form->fileField($model,"downloadurl"); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'downloadurl',array('class'=>"errorMessageTips")); ?></div>
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