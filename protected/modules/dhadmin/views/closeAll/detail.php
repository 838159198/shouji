<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>

<div class="page-header app_head">
    <h1 class="text-center text-primary"> 操作记录详情<small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;"><?php echo "共".$count."条";?></span>
</div>
<div class="row-fluid">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>业务名</th>
            <th>用户名</th>
            <th>操作时间</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=0;foreach($data as $row):$i++?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $row->type;?></td>
            <td><?php echo $row->u->username;?></td>
            <td><?php echo date("Y-m-d H:i:s",$row->createtime);?></td>

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


