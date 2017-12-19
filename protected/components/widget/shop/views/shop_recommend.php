<?php $dom=Common::getUrlDomain(); foreach($data as $row):?>
    <li>
        <a href="<?php if($dom=="z") {echo "/lvcha/shop/goods?id=".$row['id'];} elseif($dom=="kongtong261") {echo "/kongt/shop/goods?id=".$row['id'];} else {echo $row['url'];}?>" target="_blank">
            <div class="list-shop-list-img"><img src="<?php echo $row['previewimage'];?>" border="0"></div>
            <div class="listshoptitle"><?php echo CHtml::encode($row['title']);?></div>
            <div class="listshoprow">
                <span class="listshopjifen"><?php echo $row['credits'];?></span>
                <?php if(strtotime($row['down_datetime'])<time()) { ?>
                    <span class="listshopxiajia1">已下架</span>
                <?php }?>
                <span class="listshopbtn">积分兑换</span>
            </div>
        </a>
    </li>
<?php endforeach;?>