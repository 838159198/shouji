<?php foreach ($data as $row): ?>
    <?php if (!empty($row['tel'])){?>
    <li><div class="div1" ><?php echo Helper::truncate_asterisk($row->username,3);?></div>
        <div class="div2" ><?php echo Helper::truncate_asterisk($row->address,18);?></div>
        <div class="div3" ><?php echo CHtml::encode($row['gname']);?></div>
        <div class="div4" ><?php if ($row['status']==1){?>
                <?php echo CHtml::encode('已发货'); ?>
            <?php } else {?>
                <?php echo CHtml::encode('未发货'); ?>
        <?php }?></div>
    <div class="div5" ><?php  $dataTime = date('Y.m.d',$row['update_datetime']);  echo CHtml::encode($dataTime);?></div>
    </li>
<?php };?>
<?php endforeach;?>
