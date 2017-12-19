<?php $dom=Common::getUrlDomain(); foreach($data as $row):?>
<li><span><?php echo date("Y-m-d",$row['createtime']);?></span>
    <a href="<?php if($dom=="z" && $row['cid']==5) {echo "/lvcha/help/detail?id=".$row['id'];} elseif($dom=="z" && $row['cid']==4) {echo "/lvcha/notice/detail?id=".$row['id'];} elseif($dom=="kongtong261" && $row['cid']==4) {echo "/kongt/notice/detail?id=".$row['id'];} elseif($dom=="kongtong261" && $row['cid']==5) {echo "/kongt/help/detail?id=".$row['id'];} else {echo $row['url'];}?>">
        <?php echo CHtml::encode($row['title'])?>
    </a>
</li>
<?php endforeach;?>