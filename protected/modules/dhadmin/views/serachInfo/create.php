<?php
/* @var $this SerachInfoController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'录入信息',
);

$this->menu=array(
	array('label'=>'List SerachInfo', 'url'=>array('index')),
	array('label'=>'Manage SerachInfo', 'url'=>array('admin')),
);
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">录入信息</h1>
</div>


<?php $this->renderPartial('_form', array('model'=>$model,'uid'=>$uid)); ?>