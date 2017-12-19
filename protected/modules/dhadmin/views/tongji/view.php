<?php
/* @var $this BindSampleController */
/* @var $model BindSample */

$this->breadcrumbs=array(
	'Bind Samples'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List BindSample', 'url'=>array('index')),
	array('label'=>'Create BindSample', 'url'=>array('create')),
	array('label'=>'Update BindSample', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BindSample', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BindSample', 'url'=>array('admin')),
);
?>

<h1>View BindSample #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'val',
		'status',
		'allot',
		'closed',
	),
)); ?>
