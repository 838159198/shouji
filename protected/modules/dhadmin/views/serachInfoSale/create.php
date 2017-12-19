<?php
/* @var $this SerachInfoSaleController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'销售录入信息',
);

$this->menu=array(
	array('label'=>'List SerachInfoSale', 'url'=>array('index')),
	array('label'=>'Manage SerachInfoSale', 'url'=>array('admin')),
);
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">销售录入信息</h1>
</div>


<?php $this->renderPartial('_form', array('model'=>$model,'uid'=>$uid)); ?>