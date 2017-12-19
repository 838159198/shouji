<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/member">管理主页</a></li>
                    <li class="active">订单查询</li>
                </ol>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>序号</th>
                    <th>商品名称</th>
                    <th>数量</th>
                    <th>使用积分</th>
                    <th>兑换时间</th>
                    <th>收货人</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php $i=1; foreach($data as $row):?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo CHtml::encode($row['gname']);?></td>
                        <td><?php echo $row['num'];?></td>
                        <td><?php echo $row['realcredits'];?></td>
                        <td><?php echo date("Y-m-d H:i:s",$row['create_datetime']);?></td>
                        <td><?php echo $row['username']?></td>
                        <td><?php echo $row['xstatus']?></td>
                        <?php if ($row['address']==null || $row['address']=='') {?>
                            <td><a href="<?php echo $this->createUrl("drawAddress",array("id"=>$row['id']));?>">请编辑收货地址</a></td>
                        <?php }else{?>
                            <td><a href="<?php echo $this->createUrl("orderdetail",array("id"=>$row['id']));?>">查看详情</a></td>
                        <?php }?>
                    </tr>
                    <?php $i++;endforeach;?>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid" style="margin-top: 20px;">
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
</div></div>
