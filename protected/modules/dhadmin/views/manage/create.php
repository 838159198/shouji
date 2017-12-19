<div class="page-header app_head">
    <h1 class="text-center text-primary">创建管理员账号 <small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active disabled">管理员系统</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/index");?>" class="list-group-item">用户列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/create");?>" class="list-group-item">重新创建账号</a>
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
                <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'username', array('class'=>'form-control','placeholder' => '请输入用户名')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'username',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户组</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'group',CHtml::listData($group,"id","name"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'group',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">角色</label>
                <div class="col-sm-5"><?php echo $form->dropDownList($model, 'role', Role::model()->getDownList(),array("class"=>"form-control")) ?></div>
                <div class="col-md-5"><?php echo $form->error($model, 'role',array('class'=>"errorMessageTips")); ?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("0"=>"锁定","1"=>"正常"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">姓名</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'name', array('class'=>'form-control','placeholder' => '请输入姓名')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">性别</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'sex',array("0"=>"男","1"=>"女"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'sex',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">有效证件号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'idcard', array('class'=>'form-control','placeholder' => '请输入有效证件号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'idcard',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">联系电话</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'phone', array('class'=>'form-control','placeholder' => '请输入联系电话')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'phone',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'mail', array('class'=>'form-control','placeholder' => '请输入email')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'mail',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">QQ号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'qq', array('class'=>'form-control','placeholder' => '请输入QQ号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'qq',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">备注</label>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'remark', array('class'=>'form-control','placeholder' => '请输入备注内容')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'remark',array('class'=>"errorMessageTips"));?></div>
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