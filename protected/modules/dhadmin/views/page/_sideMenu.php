<div class="list-group">
    <li class="list-group-item active disabled">页面系统</li>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/page/index");?>" class="list-group-item">页面列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/page/create");?>" class="list-group-item">添加页面</a>
</div>