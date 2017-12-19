<?php
/* @var $this SerachInfoSaleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Serach Infos',
);

$this->menu=array(
	array('label'=>'Create SerachInfoSale', 'url'=>array('create')),
	array('label'=>'Manage SerachInfoSale', 'url'=>array('admin')),
);
?>

<h1>Serach Infos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
