<?php
    $user = Yii::app()->user;
    $uid=$this->uid;
    $manageid=$user->getState('member_manage');
    $member=Member::model()->find("id=$uid");
    $agent=$member["agent"];
?>
<div class="list-group">
    <?php $a =$this->getId()."_".$this->getAction()->id;?>
    <div  class="list-group-item activea "><b>业务管理</b></div>
    <a href="<?php echo Yii::app()->createUrl("/dealer/product");?>" class="list-group-item <?php if($a=="product_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 产品列表</a>
    <a href="<?php echo Yii::app()->createUrl("/dealer/datashow/datalist");?>" class="list-group-item <?php if($a=="datashow_datalist"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 数据上报</a>
    <a href="<?php echo Yii::app()->createUrl("/dealer/datashow/productupdate");?>" class="list-group-item <?php if($a=="datashow_productupdate"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 产品更新状况</a>
    <a href="<?php echo Yii::app()->createUrl("/dealer/datashow/extmodel");?>" class="list-group-item <?php if($a=="datashow_extmodel"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> ROM测试</a>
    <a href="<?php echo Yii::app()->createUrl("/dealer/datashow/instruction");?>" class="list-group-item <?php if($a=="datashow_instruction"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 业务说明</a>

    <div  class="list-group-item activea "><b>财务管理</b></div>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/default/income");?>" class="list-group-item <?php if($a=="default_income"):?>list-group-item-infoA<?php endif;?>" target="_blank"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 收益明细</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/withdraw/index");?>" class="list-group-item <?php if($a=="withdraw_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 财务提现</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/paylog/index");?>" class="list-group-item <?php if($a=="paylog_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 付款记录</a>
    <?php
        $m=Member::model()->getById($this->uid);
        if($m["type"]==1)
        {
            echo '<a href="/dealer/incomes/index" class="list-group-item"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 子用户收益</a>';
        }
    ?>
    <div  class="list-group-item  activea "><b>个人信息</b></div>
    <a href="<?php echo Yii::app()->createUrl("/dealer/info");?>" class="list-group-item <?php if($a=="info_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 我的信息</a>
    <a href="<?php echo Yii::app()->createUrl("/dealer/info/edit");?>" class="list-group-item <?php if($a=="info_edit"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 资料修改</a>
    <?php if($agent!=69 ||($agent==69 && $uid==69) || $manageid==1): ?>
    <a href="<?php echo Yii::app()->createUrl("/dealer/info/bank");?>" class="list-group-item <?php if($a=="info_bank"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> 财务信息修改</a>
    <?php endif; ?>
    <a href="<?php echo Yii::app()->createUrl("/dealer/info/password");?>" class="list-group-item <?php if($a=="info_password"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 密码修改</a>
    <!--<a href="<?php /*echo Yii::app()->createUrl($this->getModule()->id."/spread/index");*/?>" class="list-group-item  <?php /*if($a=="spread_index"):*/?>list-group-item-infoA<?php /*endif;*/?>" ><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 推广链接</a>-->

    <div  class="list-group-item activea "><b>商城管理</b></div>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/members/location/index");?>" class="list-group-item <?php if($a=="location_index"):?>list-group-item-infoA<?php endif;?>" ><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 收货地址</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/shop/order/index");?>" class="list-group-item <?php if($a=="order_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 订单查询</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/members/creditslog");?>" class="list-group-item <?php if($a=="credits_log"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 积分记录</a>
    <a href="<?php echo Yii::app()->createUrl("/site/logout");?>" class="list-group-item list-group-item"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 安全退出</a>

</div>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><b>在线客服</b></h3>
    </div>
    <div class="panel-body">
        <div class="text-center">
            <p><script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzkzODAzODI0M180NjU2MDRfNDAwMDkxODA1OF8"></script></p>
        </div>
    </div>
</div>