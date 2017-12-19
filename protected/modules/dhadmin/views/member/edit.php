<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php echo $data['username'];?> <small>修改用户信息</small></h1>
</div>
<!--<div class="row">
    <ol class="breadcrumb">当前位置：
        <li><a href="<?php /*echo $this->createUrl("/".$this->getModule()->id);*/?>">系统首页</a></li>
        <li><a href="<?php /*echo $this->createUrl("article/index");*/?>">文章</a></li>
        <li class="active">文章列表</li>
    </ol>
</div>-->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu",array('data'=>$data));?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">手机号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'tel', array('class'=>'form-control','placeholder' => '请输入手机号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'tel',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($data,'status',array("0"=>"锁定","1"=>"正常"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户类型</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($data,'type',array("0"=>"普通用户","1"=>"代理商","2"=>"代理商子用户","8"=>"新线下门店","3"=>"原线下门店","4"=>"批发商","5"=>"微信/QQ/网站","6"=>"广告合作","7"=>"其它"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'type',array('class'=>"errorMessageTips"));?></div>
            </div>


            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'mail', array('class'=>'form-control','placeholder' => '请输入email')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'mail',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">QQ号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'qq', array('class'=>'form-control','placeholder' => '请输入QQ号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'qq',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">微信号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'weixin_name', array('class'=>'form-control','placeholder' => '请输入微信号')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'weixin_name',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">备注</label>
                <div class="col-sm-5">
                    <?php echo $form->textArea($data, 'content', array('class'=>'form-control','placeholder' => '请输入备注内容')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'content',array('class'=>"errorMessageTips"));?></div>
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