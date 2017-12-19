<div class="page-header app_head">
    <h1 class="text-center text-primary">文章栏目创建 <small></small></h1>
</div>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')){?>
    <script type="text/javascript">alert("添加成功！");</script>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>

    </div>
<?php }?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="alert alert-success">带（*）的内容是必须填写的！</div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">栏目名称</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'name', array('class'=>'form-control','placeholder' => '请输入栏目名称')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">SEO标题</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'seotitle', array('class'=>'form-control','placeholder' => '请输入SEO标题')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'seotitle',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">唯一标识</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'pathname', array('class'=>'form-control','placeholder' => '请输入栏目标识')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'pathname',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("0"=>"关闭","1"=>"正常"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">关键字</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'keywords', array('class'=>'form-control','placeholder' => '请输入关键字')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'keywords',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">描述</label>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'descriptions', array('class'=>'form-control','placeholder' => '请输入描述信息')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'descriptions',array('class'=>"errorMessageTips"));?></div>
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