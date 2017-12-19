<?php
/* @var $this MemberPoolController */
/* @var $dataProvider CActiveDataProvider */

?>
    <h4 class="text-center">备选用户池</h4>
<br>
<table class="table table-hover table-striped table-condensed" id = 'memberpool'>
    <tr class = 'ready_show' id = 'hidethis'>
        <th >客服</th>
        <th >用户名</th>
        <th >打回日期</th>
    </tr>

    <?php foreach ($dataProvider as $key=>$val) { ?>
        <tr style="color:#FF6FB7;font-weight:bold;" class = 'hidethistoo'>
            <td><?php echo $val["manage"]['name'] ?></td>
            <td><?php echo $val["member"]['username'] ?></td>
            <td><?php echo $val['createtime'] ?></td>
        </tr>
    <?php } ?>

</table>

