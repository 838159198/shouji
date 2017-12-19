
<?php $dom=Common::getUrlDomain(); $i=1; foreach($data as $row):?>
    <?php if($i<=3){?>
    <li><i class="shop-list-hot-itop"><?php echo $i;?></i><a href="<?php if($dom=="z") {echo "/lvcha/shop/goods?id=".$row['id'];} elseif($dom=="kongtong261") {echo "/kongt/shop/goods?id=".$row['id'];} else {echo $row['url'];}?>" target="_blank"><?php echo CHtml::encode($row['title']);?></a></li>
        <?php }else{?>
    <li><i><?php echo $i;?></i><a href="<?php if($dom=="z") {echo "/lvcha/shop/goods?id=".$row['id'];} elseif($dom=="kongtong261") {echo "/kongt/shop/goods?id=".$row['id'];}else {echo $row['url'];}?>" target="_blank"><?php echo CHtml::encode($row['title']);?></a></li>
        <?php }?>
<?php $i++;endforeach;?>