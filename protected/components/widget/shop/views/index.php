<?php $dom=Common::getUrlDomain(); foreach($data as $row):?>
    <li>
        <a href="<?php if($dom=="z") {echo "/lvcha/shop/goods?id=".$row['id'];} elseif($dom=="kongtong261") {echo "/kongt/shop/goods?id=".$row['id'];} else {echo $row['url'];}?>" target="_blank">
            <div class="index-shop-img"><img src="<?php echo $row['previewimage'];?>" border="0"></div>
            <div class="shoptitle"><?php echo CHtml::encode($row['title']);?></div>
            <div class="shoprow">
                <span class="shopjifen"><?php echo $row['credits'];?></span>
                <span class="shopbtn">积分兑换</span>
            </div>
        </a>
    </li>
<?php endforeach;?>