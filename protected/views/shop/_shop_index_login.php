<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style/v1/base.css" />
<?php $userstate=Yii::app()->user;
// print_r($userstate);exit; ?>
<?php if($userstate->getState('type')!='' && isset($userstate->member_uid)){?>
<?php if($this->beginCache("shop_index_user_".$userstate->member_uid, array('duration'=>30))) { ?>
        <div class="list-shop-user-row">
    <div class="list-shop-user-avatar"></div>
    <div class="list-shop-user-info">
        <p><?php echo $userstate->member_username;?>，您好！</p>
        <?php $member = Member::model()->findByPk($userstate->member_uid);?>
        <p>积分：<font color="#ff6600"><?php echo $member['credits'];?></font> </p>
    </div>
</div>
<div class="list-shop-user-infoa">
    <?php $order=ShopGoodsOrder::model()->findAll(array("condition"=>"mid={$userstate->member_uid}","order"=>"id DESC","select"=>array("gname","create_datetime"),'limit'=>5));?>
    <div class="list-shop-user-infoa-tit">我的兑换记录：</div>
    <ul>
        <?php foreach($order as $row):?>
        <li><span><?php echo date("Y-m-d",$row['create_datetime']);?></span><?php echo $row['gname']?></li>
        <?php endforeach;?>
    </ul>
</div>
<?php $this->endCache(); } ?>
<?php }else{?>
    <div class="list-shop-user-row">
        <div class="list-shop-user-avatar"></div>
        <div class="list-shop-user-info">
            <p>亲，您还未登录！</p>
            <p>登录后即可查看您的积分信息</p>
        </div>
    </div>
    <!-- <div class="list-shop-user-infoa">
        <p>我的积分  ：---</p>
    </div> -->
    <div class="list-shop-row-user-login"><a href="/login">立即登录</a></div>
    <?php }?>
