<?php
$this->breadcrumbs = array(
    '订单详情',
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/newdt">管理主页</a></li>
                    <li class="active">订单查询</li>
                </ol>
            </div>
            <hr>
            <p>商品名称：<?php echo $data['gname'];?></p>
            <p>兑换数量：<?php echo $data['num'];?></p>
            <p>使用积分：<?php echo $data['realcredits'];?></p>
            <p>兑换时间：<?php echo date("Y-m-d H:i:s",$data['create_datetime']);?></p>
            <p>姓名：<?php echo $data['username'];?></p>
            <p>电话：<?php echo $data['tel'];?></p>
            <p>地址：<?php echo $data['address'];?></p>
            <p>备注：<?php echo $data['remarks'];?></p>
            <p style="font-weight: bold">物流名称：<?php echo $data['mailname'];?></p>
            <p style="font-weight: bold">快递单号：<?php echo $data['mailcode'];?></p>
            <hr>
            <p><b>【客服回复】</b></p>
            <div><font color="#ff0000"><?php echo $data['reply'];?></font></div>
        </div>
    </div>
</div>
