<?php foreach($data as $row):?>
    <dl>
        <dt><?php echo Helper::truncate_asterisk($row->member->username,3);?></dt>
        <dd><?php echo $row['MoneyContent'];?></dd>
    </dl>
<?php endforeach;?>