
<?php foreach($data as $k => $row):?>
    <?php
    $str="";
    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $row['username'])<=0)
    {
        $len = strlen($row['username']);
        $leng = $len-4;
        $rep = '';
        for ($i=0;$i<$leng;$i++){
            $rep = '*'.$rep;
        }
        $str = substr_replace($row['username'],$rep,2,$leng);
    }
    else
    {
        $str ="jsd**fd";
    }
     ?>
    <?php if ($k%2 == 0):?>
        <li style="background: #C42418"><div class="jilu-div1"><?php echo CHtml::encode($str);?></div>
        <div class="jilu-div2"><?php echo CHtml::encode($row['draw']);?></div>
    </li>
     <?php endif;?>
    <?php if ($k%2 != 0):?>
        <li><div class="jilu-div1"><?php echo CHtml::encode($str);?></div>
            <div class="jilu-div2"><?php echo CHtml::encode($row['draw']);?></div>
        </li>
    <?php endif;?>
<?php endforeach;?>
