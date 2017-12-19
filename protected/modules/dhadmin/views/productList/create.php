<div class="page-header app_head">
    <h1 class="text-center text-primary">上传业务包<small></small></h1>
</div>
<div class="alert alert-danger">注意：1,每个业务类型最多可以上传20个包，版本号必须以字母V或v开头<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;2,上传时显示状态默认为不显示，用户后台会看不到该业务包，只有测试通过才可以将显示状态改为显示
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">业务包系统</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/productList/index");?>" class="list-group-item">业务包列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/productList/create");?>" class="list-group-item">上传业务包</a>
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
                <label for="inputPassword3" class="col-sm-2 control-label">版本号</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"version",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'version',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">渠道号</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"pakid",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'pakid',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">包名</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"pakname",array("class"=>"form-control")); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'pakname',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户分组</label>
                <div class="col-sm-5"><?php echo $form->dropDownList($model, 'agent', ProductList::model()->getlistDataMemberGroup(),array("class"=>"form-control")) ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'agent',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品类型</label>
                <div class="col-sm-5"><?php echo $form->dropDownList($model, 'type', ProductList::model()->getProductList(),array("class"=>"form-control")) ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'type',array('class'=>"errorMessageTips")); ?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("0"=>"不可用","1"=>"可用"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">显示状态</label>
                <div class="col-sm-5">
                    <?php $model->isshow = '0';  echo $form->dropDownList($model,'isshow',array("0"=>"不显示","1"=>"显示"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'isshow',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">文件</label>
                <div class="col-sm-5"><?php echo $form->fileField($model,"appurl"); ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'appurl',array('class'=>"errorMessageTips")); ?></div>
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
<script>
$(function(){
    $('#ProductList_isshow').children('option:eq(1)').hide();
})
</script>
