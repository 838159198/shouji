<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style/v1/base.css" />
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.min.js"></script>
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.animate-colors-min.js"></script>
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.SuperSlide.2.1.1.js"></script>
<div class="list-shop-row-container">
    <div class="list-shop-row">
        <div class="list-shop-row-banner">
            <div id="slideBox" class="list-shop-banner">
                <!-- 图片轮播 -->
                <div class="hd">
                    <ul><li>1</li><li>2</li><li>3</li></ul>
                </div>
                <div class="bd">
                    <ul>
                        <li><a href="#" target="_blank"><img src="../images/shop/shop_banner.png" /></a></li>
                        <li><a href="#" target="_blank"><img src="../images/shop/shop_banner3.jpg"  /></a></li>
                        <li><a href="#m" target="_blank"><img src="../images/shop/shop_banner2.jpg"  /></a></li>
                    </ul>
                </div>
                <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                <a class="prev" href="javascript:void(0)"></a>
                <a class="next" href="javascript:void(0)"></a>
            </div>
            <script id="jsID" type="text/javascript">
                var ary = location.href.split("&");
                jQuery(".list-shop-banner").slide( { mainCell:".bd ul",autoPlay:true});
            </script>
        </div>
        <div class="list-shop-row-user">
            <?php $this->renderPartial("_shop_index_login");?>
        </div>
    </div>
</div>
<div class="list-shop-wrap-container">
    <div class="list-shop-wrap">
        <div class="list-shop-wrap-l">
            <div class="list-shop-tj">
                <div class="list-shop-tj-title">
                    <h3>热门兑换商品</h3>
                </div>
                <div class="list-shop-tj-content">
                    <ul>
                        <?php $this->widget('application.components.widget.shop.HotWidget',array("num"=>3,'order'=>'hits')); ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- list-shop-wrap-l END//-->
        <div class="list-shop-wrap-r">
            <div class="list-shop-jifen">
                <div class="list-shop-jifen-title"><h3>Ta们正在兑换...</h3></div>
                <div class="list-shop-jifen-content">
                    <ul>
                        <?php $this->widget('application.components.widget.shop.BuyRecordWidget',array("num"=>20)); ?>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">jQuery(".list-shop-jifen").slide({mainCell:".list-shop-jifen-content ul",autoPlay:true,effect:"topMarquee",vis:7,interTime:50,trigger:"click"});</script>
            <!-- 积分记录END//-->
        </div>
        <!-- list-shop-wrap-r END//-->
    </div>
</div>
<!-- // 列表 -->
<div class="list-shop-container">
    <div class="list-shop-list-wrap">
        <div class="list-shop-list-title"><a href="/shop/index"><h3>积分商品</h3></a>
            <?php foreach($categorys as $category){?>
            <span class="more1"  ><a href="/shop/index?id=<?=$category['id']?>" <?php if(isset($_GET['id']) && $_GET['id']==$category['id'] ):?> style="color: #0064cd"<?php endif?>><?=$category['cname']?></a></span>
            <?php }?>
            <span class="more"><a href="/shop/all">查看全部商品</a></span></div>
        <div class="list-shop-list-content">
            <ul>
                <?php $this->widget('application.components.widget.shop.RecommendWidget',array("num"=>12,"cid"=>isset($_GET['id'])?$_GET['id']:0 )); ?>
            </ul>
        </div>
    </div>
    <div class="list-shop-morebtn"><a href="/shop/all">查看全部积分商品</a></div>
</div>
<!-- 列表 END //-->
<!-- 积分商城// -->