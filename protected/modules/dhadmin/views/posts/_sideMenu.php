<div class="list-group">
    <li class="list-group-item active ">内容系统</li>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts");?>" class="list-group-item">内容列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts/list?cateid=1");?>" class="list-group-item">公告列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts/list?cateid=3");?>" class="list-group-item">常见问题</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts/list?cateid=2");?>" class="list-group-item">帮助中心</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/posts/create");?>" class="list-group-item">添加内容</a>
</div>