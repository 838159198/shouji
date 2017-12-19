<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>

<div class="page-header app_head">
    <h1 class="text-center text-primary">活动管理 <small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;"><?php echo "共".$count."条";?></span>
</div>

<div class="row-fluid">

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>期号</th>
            <th>标题</th>
            <th>产品</th>
            <th>原价</th>
            <th>活动价格</th>
            <th>报名时间</th>
            <th>活动时间</th>
            <th>发布时间</th>
            <th>发布状态</th>
            <th>报名人数 / 审核通过 / 拒绝</th>
            <th width="120">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $row):?>
        <tr>
            <td><?php echo $row['periods'];?></td>
            <td> <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/huodong2?periods=".$row['periods'])."&name=".$row->p->name;?>" ><?php echo $row['title'];?></a></td>
            <td><?php echo $name=$row->p->name;?></td>
            <td><?php echo $row->p->price;?>元</td>
            <td><?php echo sprintf("%.2f", ($row->p->price)*1.2)?>元</td>
            <td><?php echo substr($row['userstarttime'],0,10)?> 至 <?php echo substr($row['userendtime'],0,10)?></td>
            <td><?php echo substr($row['starttime'],0,10)?> 至 <?php echo substr($row['endtime'],0,10)?></td>
            <td><?php echo empty($row['publishtime'])?"--":date("Y-m-d H:i:s",$row['publishtime']);?></td>
            <td><?php echo $row['publish_status']==0? "<span style='color: #ff2b10'><b>未发布</b></span>" :"已发布"?></td>
            <?php
                $pcamlog_model = new CampaignLog();
                $criteria = new CDbCriteria();
                $criteria->addCondition("cid=". $row['periods']);
                $count = $pcamlog_model->count($criteria);


            ?>
            <td><?=$count;?>/<?php echo CampaignLog::findByPeriods($row['periods'],1)?>/<?php echo CampaignLog::findByPeriods($row['periods'],2)?>人</td>
            <td>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/huodong2?periods=".$row['periods'])."&name=".$name;?>" class="label label-primary">查看</a>
                <a href="javascript:edit(<?=$row['periods']?>)" class="label label-primary">修改</a>
            </td>
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

<!-- 每期详情弹出框 -->
<div class="modal fade" id="tt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
    function edit(periods){
        var _url="/dhadmin/product/showCampaign?periods="+periods;
        $('#tt').removeData("bs.modal");
        $("#tt").modal({remote: _url});
    }
</script>

