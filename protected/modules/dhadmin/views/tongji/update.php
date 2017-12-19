	<div class="page-header app_head">
		<h1 class="text-center text-primary">业务资源编辑<small></small></h1>
	</div>

	<div class="col-md-2">
        <div class="list-group">
            <li class="list-group-item active">业务资源</li>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/bindSample/admin");?>" class="list-group-item">返回列表</a>
            <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/bindSample/create");?>" class="list-group-item">创建资源</a>
        </div>
	</div>
	<div class="col-md-10">
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>