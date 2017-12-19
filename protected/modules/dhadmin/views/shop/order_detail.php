<?php
$this->breadcrumbs = array(
    '商品列表' => array('index'),
    '商品详情',
);
?>
<div class="breadcrumbs">
    <a href="/dhadmin/shop/index">首页</a> &raquo; <a href="/dhadmin/shop/goodsOrder">订单列表</a> &raquo; <span>订单详情</span></div>


<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label"></label>
        <div class="col-sm-5">
            <div><h4 class="text-center">订单详情</h4></div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">商品名称</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['gname'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">商品链接</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo empty($data->goods->address)?"":$data->goods->address;?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">购买积分</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['credits'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">数量</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['num'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">总积分</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['realcredits'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">姓名</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['username'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['tel'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">地址</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['address'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['remarks'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">创建时间</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo date("Y-m-d H:i:s",$data['create_datetime']);?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">更新时间</label>
        <div class="col-sm-5">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo date("Y-m-d H:i:s",$data['update_datetime']);?>" disabled>
        </div>
    </div>
</form>
<div class="container-fluid">
    <div class="row">
        <div >
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                <div class="col-sm-5">
                    <div class="alert alert-info" >带（*）的内容是必须填写的！</div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">物流名称 *</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'mailname', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'mailname',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">快递单号 *</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'mailcode', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'mailcode',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态*</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($data,'status',array("3"=>"确认收货","1"=>"已发货","0"=>"待发货","2"=>"取消订单"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">回复内容</label>
                <div class="col-sm-5">
                    <?php echo $form->textArea($data, 'reply', array('class'=>'form-control')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'reply',array('class'=>"errorMessageTips"));?></div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?php if($data['status'] !=2 && $data['status'] !=3){?>
                    <button type="submit" class="btn btn-primary">确认提交</button>
                    <?php } ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

