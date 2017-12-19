<div class="list-head">
    <div class="list-head-content">
        <h3>帮助文档</h3>
    </div>
</div>
<div class="view-wrap">
    <div class="view-notice-title">
        <h1><?php echo CHtml::encode($data['title']);?></h1>
        <div class="view-notice-info"><i class="icon_time"></i>发布时间：<?php echo date("Y-m-d",$data['createtime']);?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon_people"></i>发布人：迅推</div>
    </div>
    <div class="view-notice-content">
        <?php echo $data['content'];?>

    </div>
</div>