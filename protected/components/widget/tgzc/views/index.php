<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/4
 * Time: 9:16
 */
//print_r($data);
?>
<?php foreach($data as $row):?>

    <li> <img src="/css/tgzc/images/bjx.png"> <?php $province = '来自'.$row['province'].'的';echo CHtml::encode($province);?>
         <?php $name = substr($row['name'],0,3);$name = $name.'先生';echo CHtml::encode($name);?>&nbsp;&nbsp;
        收入<?php echo CHtml::encode($row['amount']);?>元</li>
<?php endforeach;?>