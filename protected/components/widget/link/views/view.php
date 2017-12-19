<?php foreach($data as $row):?>
    <li><a href="<?php echo $row['website']?>" target="_blank"><?php echo CHtml::encode($row['name'])?></a></li>
<?php endforeach;?>