<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style/v1/base.css" />
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.animate-colors-min.js"></script>
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.SuperSlide.2.1.1.js"></script>
<!--//返回顶部开始-->
<style>
    #to_top {
        position:fixed;
        right:50px;
        bottom:100px;
        width:30px; height:40px;
        cursor:pointer; color:#fff
        ｝
</style>
<script>
    window.onload = function(){
        var oTop = document.getElementById("to_top");
        var screenw = document.documentElement.clientWidth || document.body.clientWidth;
        var screenh = document.documentElement.clientHeight || document.body.clientHeight;
        oTop.style.left = screenw - oTop.offsetWidth +"px";
        oTop.style.top = screenh - oTop.offsetHeight + "px";
        oTop.onclick = function(){
            document.documentElement.scrollTop = document.body.scrollTop =0;
        }
    }
</script>
<!--//返回顶部结束-->
<div class="list-shop-head" style="margin-top: 10px;height: 150px">
    <div class="list-shop-head-content">
        <h3 style="text-align: center">积分兑换</h3>
        <div class="list-head-description" style="text-align: center;padding-left: 120px;font-size: large"> 小积分，大礼品！</div>
    </div>
</div>

<!--//商品列表-->
<div class="shop-list">
    <div class="shop-list-l">
        <div class="shop-detail">
            <div class="shop-detail-head">
                <div class="shop-detail-head-pic"><img src="<?php echo $data['coverimage'];?>" border="0"></div>
                <div class="shop-detail-head-info">
                    <div class="shop-detail-head-info-title"><?php echo CHtml::encode($data['title']);?></div>
                    <div class="shop-detail-head-info-jf">所需积分：<b><?php echo $data['credits'];?></b></div>
                    <div class="shop-detail-head-info-num"><span>库存：<?php echo $data['kucun'];?></span></div>
                    <div class="shop-detail-head-info-gz">已有<?php echo $data['hits'];?>人关注本商品</div>
                    <?php if(Yii::app()->user->isGuest){?>
                        <div class="shop-detail-head-nobuy"><a href="/login">请先登录账号</a></div>
                    <?php }else{?>
                        <div class="shop-detail-head-buy">
                            <?php if($data['kucun']<=0){?>
                            <a href="#">此商品库存已不足</a>
                            <?php }elseif( strtotime($data['down_datetime'])<time()){?>
                            <a href="#">此商品已下架</a>
                            <?php }else{?>
                            <a href="/<?php echo strtolower(Yii::app()->user->getState('type'));?>/shop/buy?id=<?php echo $data['id']?>">我要兑换</a>
                            <?php }?>
                        </div>
                    <?php }?>

                </div>
            </div>
            <div class="shop-detail-info-title"><h3>商品详情</h3></div>
            <div class="shop-detail-content"><?php echo $data['content'];?></div>
        </div>
        <!-- shop-detail//-->

    </div>
    <!-- 左侧END//-->
    <div class="shop-list-r">
        <div class="shop-list-jifen">
            <div class="shop-list-jifen-title"><h3>Ta们正在兑换...</h3></div>
            <div class="shop-list-jifen-content">
                <ul>
                    <?php $this->widget('application.components.widget.shop.BuyRecordWidget',array("num"=>20)); ?>
                </ul>
            </div>
        </div>
        <script type="text/javascript">jQuery(".shop-list-jifen").slide({mainCell:".shop-list-jifen-content ul",autoPlay:true,effect:"topMarquee",vis:10,interTime:50,trigger:"click"});</script>
        <!--积分//-->
        <div class="shop-list-hot">
            <div class="shop-list-hot-title"><h3>兑换排行</h3></div>
            <div class="shop-list-hot-content">
                <ul>
                    <?php $this->widget('application.components.widget.shop.NumHotWidget',array("num"=>10)); ?>
                </ul>
            </div>
        </div>
        <!--热门//-->
    </div>
    <!-- 右侧END//-->
</div>
<!--商品列表//-->
<div id="to_top">返回顶部</div>

<!-- 积分商城// -->
