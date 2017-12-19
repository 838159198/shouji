<?php
/* @var $this BindSampleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bind Samples',
);

$this->menu=array(
	array('label'=>'Create BindSample', 'url'=>array('create')),
	array('label'=>'Manage BindSample', 'url'=>array('admin')),
);
?>

<h1>Bind Samples</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
