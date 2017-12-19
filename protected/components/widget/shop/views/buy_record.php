<?php foreach($data as $row):?>
    <li><?php echo Helper::truncate_asterisk($row->member->username,4);?>&nbsp;&nbsp;兑换<?php echo CHtml::encode($row['gname']);?></li>
<?php endforeach;?>