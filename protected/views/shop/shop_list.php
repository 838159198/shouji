<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style/v1/base.css" />
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.animate-colors-min.js"></script>
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/style/js/jquery.SuperSlide.2.1.1.js"></script>
<div class="list-shop-head" style="margin-top: 10px;height: 150px">
    <div class="list-shop-head-content">
        <h3 style="text-align: center">积分兑换</h3>
        <div class="list-head-description" style="text-align: center"> &nbsp;&nbsp;&nbsp;&nbsp;小积分，大礼品！</div>
    </div>
</div>

<!--//商品列表-->
<div class="shop-list">
    <div class="shop-list-l">

        <div class="shop-list-title"><a href="/shop/all"><h3 style="border: none">全部商品</h3></a>

            <?php foreach($categorys as $category){?>
                <span class="more1"><a href="/shop/all?id=<?=$category['id']?>" <?php if(isset($_GET['id']) && $_GET['id']==$category['id'] ):?> style="color: #0064cd"<?php endif?>><?=$category['cname']?></a></span>
            <?php }?>
            <span class="slmore"><a href="/help/66" target="_blank">如何赚取积分？</a></span></div>

        <div class="shop-list-con">
            <ul>
                <?php foreach($data as $row):?>
                <li>
                    <a href="<?php echo $row['url']?>">
                        <div class="shop-list-con-img"><img src="<?php echo $row['previewimage'];?>" border="0"></div>
                        <div class="sltitle"><?php echo CHtml::encode($row['title']);?></div>
                        <div class="slrow">
                            <span class="sljifen"><?php echo $row['credits'];?></span>
                            <?php if(strtotime($row['down_datetime'])<time()) { ?>
                                <span class="sllog1">已下架</span>
                            <?php }?>
                            <span class="sllog"><?php echo $row['num'];?>人兑换</span>
                        </div>
                    </a>
                </li>
                <?php endforeach;?>

            </ul>
        </div>
        <div class="list-page">
            <?php
            $this->widget('CLinkPager',array(
                    'cssFile'=>false,
                    'header'=>'',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '末页',
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'pages' => $pages,
                    'maxButtonCount'=>8,
                    'htmlOptions'=>array("id"=>"xtlistpage",'class'=>false)
                )
            );
            ?>
        </div>
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

<!-- 积分商城// -->