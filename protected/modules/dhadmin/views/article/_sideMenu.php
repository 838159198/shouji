<div class="list-group">
    <li class="list-group-item active">文章系统</li>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/article/category");?>" class="list-group-item">文章分类</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/article/categoryCreate");?>" class="list-group-item">添加分类</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/article/index");?>" class="list-group-item">文章列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/article/create");?>" class="list-group-item">添加文章</a>
</div>