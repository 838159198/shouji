<div class="page-header app_head">
    <h1 class="text-center text-primary">业务包信息修改<small></small></h1>
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
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
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
                    <?php echo $form->dropDownList($model,'isshow',array("0"=>"不显示","2"=>"客服确认"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'isshow',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">包路径</label>
                <div class="col-sm-5"><?php echo $form->textField($model,"appurl",array("class"=>"form-control",'readonly'=>true)); ?></div>
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
<!-- <script>
$(function(){
    $('#ProductList_isshow').children('option:eq(1)').attr('value','2');

})
</script> -->