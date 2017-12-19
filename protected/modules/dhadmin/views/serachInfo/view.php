<?php
/* @var $this SerachInfoController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'录入信息管理'=>array('serachInfo/admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SerachInfo', 'url'=>array('index')),
	array('label'=>'Create SerachInfo', 'url'=>array('create')),
	array('label'=>'Update SerachInfo', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SerachInfo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SerachInfo', 'url'=>array('admin')),
);
?>

<h4 class="text-center">用户信息查看</h4></br>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'tel',
		'mail',
		'qq',
		array(
			'name'=>'reg_status',
			'value'=>$model->reg_status=="0"?"未注册":"已注册"
		),
		array(
			'name'=>'status',
			'value'=>$model->status=="0"?"未审核":($model->status=="1"?"有效":"无效")
		),
		'content',
		'com',
		'area',
		'source',
		'manage_id',
		'createtime',
		'motifytime',
		'tixingtime',
	),
)); ?>
