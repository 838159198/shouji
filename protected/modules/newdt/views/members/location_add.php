<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/newdt">管理主页</a></li>
                    <li class="active">收货地址</li>
                </ol>
            </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal",'onsubmit'=>"if(!checkAll()){return false;}"),
            )); ?>
            <div class="culture">
                <p class="alert alert-info">
                    <strong class="alertlef">数据添加说明：</strong><span class="alertrig">
                        带（*）的内容是必须填写的！
                    </span>
                </p>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">姓名 *</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'name', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">手机号码 *</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'tel', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'tel',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">收货地址 *</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'address', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'address',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>

        </div>
    </div>
</div>

