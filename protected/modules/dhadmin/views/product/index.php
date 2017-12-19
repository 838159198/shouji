<div class="page-header app_head">
    <h1 class="text-center text-primary">业务产品中心 <small></small></h1>
</div>
<!--<div class="row">
    <ol class="breadcrumb">当前位置：
        <li><a href="/<?php /*echo $this->getModule()->id;*/?>">系统首页</a></li>
        <li class="active">产品列表</li>
    </ol>
</div>-->

<div class="row-fluid">
    <div class="app_button">
        <a href="<?php echo $this->createUrl("product/create");?>" class="btn btn-success">添加产品</a>
        <a href="<?php echo $this->createUrl("product/addCategroy");?>" class="btn btn-success">增加分类</a>
        <a href="<?php echo $this->createUrl("product/prompt");?>" class="btn btn-success">溫馨提示</a>
    </div>
    <div class="app_button">

    </div>
</div>
<div class="row-fluid">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>产品ID</th>
            <th>产品名称</th>
            <th>唯一标识</th>
            <th>官方价格</th>
            <th>用户显示价格</th>
            <th>用户实际价格</th>
            <th>开通人数</th>
            <th>添加时间</th>
            <th>更新时间</th>
            <th>状态</th>
            <th width="75">操作</th>
        </tr>
    </thead>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <tbody>
        <?php foreach($data as $row):?>
        <tr>
            <td><?php echo $row['order'];?></td>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['pathname'];?></td>
            <td><?php echo $row['officialprice'];?></td>
            <td><?php echo $row['quote'];?></td>
            <td><?php echo $row['price'];?></td>
            <td><?php echo $row['enrollment'];?></td>
            <td><?php echo date("Y-m-d H:i:s",$row['createtime']);?></td>
            <td><?php echo date("Y-m-d H:i:s",$row['updatetime']);?></td>
            <td><?php echo $row['xstatus'];?></td>
            <td><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/edit/id/".$row['id']);?>" class="label label-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<div class="row-fluid text-center">
    <nav>
        <?php
        $this->widget('CLinkPager',array(
                'header'=>'',
                'cssFile'=>false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'htmlOptions'=>array("class"=>"pagination pagination-lg"),
            )
        );
        ?>
    </nav>

</div>