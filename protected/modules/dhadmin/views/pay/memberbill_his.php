<?php
/* @var $this PayController */
/* @var $memberbill CActiveDataProvider */


$this->breadcrumbs = array(
    '财务管理' => 'index',
);
$this->menu = array(
    array('label' => '未支付', 'url' => array('member', 'status' => MemberPaylog::STATUS_FALSE)),
    array('label' => '已支付', 'url' => array('member', 'status' => MemberPaylog::STATUS_TRUE)),
);
?>

    <h4 class="text-center">打回提现记录</h4>
<div class="text-button"><input type="button" value="用户余额为负数" onclick="location.href='/manage/pay/Memberbill'"> </div>
<div class="text-button" id="dhtx"><input type="button" value="打回提现记录" onclick="location.href='/manage/pay/Memberbill_his'"> </div>

<style type="text/css">
   .text-button{text-align:left; margin-bottom:10px; margin-right:15px; display:inline-block;}
   .text-button input[type="button"]{width:110px; height:30px; color:#ffffff; background-color:#5c9ccc;}
   #dhtx input[type="button"]{ color:#444444;}
    th{
        height:30px;
        width:200px;
        color: white;
        background-color:#5c9ccc;
        text-align: center;
    }
    td{
        border-top:1px solid #ffffff;
        height:23px;
        width:200px;
        color:#444444;
        background-color:#E5F1F4;
        text-align: center;
    }

</style>
<table class="items">
    <thead>
    <tr>
        <th>用户</th>
        <th>申请金额</th>
        <th>余额负数</th>
        <th>申请时间</th>
        <th>打回时间</th>
    </tr>
    </thead>
    <tbody>

        <?php
        foreach ($memberbill as $item) {
            echo '<tr class="odd">';

            echo '<td>' . $item['username'] . '</td>';
            echo '<td>' . $item['sums'] . '</td>';
            echo '<td>' . $item['surplus'] . '</td>';
            echo '<td>' . $item['ask_time'] . '</td>';
            echo '<td>' . $item['answer_time'] . '</td>';
        }
        if(empty($memberbill)){echo '<td style="font-size:14px; font-weight:bold;">无数据</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
            echo '<td>&nbsp;</td>';
        }
        echo '</tr>';
        ?>

    </tbody>
</table>


