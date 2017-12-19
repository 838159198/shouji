<?php
/* @var $this SerachInfoSaleController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'销售录入信息管理'=>array('serachInfoSale/admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SerachInfoSale', 'url'=>array('index')),
	array('label'=>'Create SerachInfoSale', 'url'=>array('create')),
	array('label'=>'Update SerachInfoSale', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SerachInfoSale', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SerachInfoSale', 'url'=>array('admin')),
);
?>

<h4 class="text-center">用户信息查看</h4></br>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
        'username',
		'tel',
		'qq',
		array(
			'name'=>'reg_status',
			'value'=>$model->reg_status=="0"?"未注册":"已注册"
		),
		array(
			'name'=>'status',
			'value'=>$model->status=="0"?"未审核":($model->status=="1"?"有效":"无效")
		),
		'com',
		'area',
        'userarea',
		'content',
		'createtime',
		'motifytime',
		'tixingtime',
        'zixuntime',
	),
)); ?>
