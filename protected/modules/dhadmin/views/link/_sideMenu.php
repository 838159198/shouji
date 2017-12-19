<div class="list-group">
    <a href="#" class="list-group-item active disabled">友情链接系统</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/link/index");?>" class="list-group-item">友链列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/link/create");?>" class="list-group-item">添加友链</a>
</div>