<p>【用户信息】</p>
<p>用户名：<?php echo $member['username']?></p>
<p>积分：<?php echo $member['credits']?></p>
<hr>
<?php foreach($data as $row):?>

    <p><img src="<?php echo $row['coverimage']?>" border="0" /></p>
    <p>标题：<?php echo CHtml::encode($row['title']);?></p>
    <p>积分：<?php echo $row['credits'];?></p>
    <p><a href="<?php echo $this->createUrl("buy",array("id"=>$row['id']));?>">兑换商品</a></p>
    <hr>
<?php endforeach;?>
<div class="pager">
    <?php
    $this->widget('CLinkPager',array(
            'header'=>'',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '末页',
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'pages' => $pages,
            'maxButtonCount'=>8,
        )
    );
    ?>
</div>