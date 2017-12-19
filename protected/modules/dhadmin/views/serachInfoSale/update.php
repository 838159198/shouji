<?php
/* @var $this SerachInfoSaleController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'销售录入信息管理'=>array('serachInfoSale/admin'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SerachInfoSale', 'url'=>array('index')),
	array('label'=>'Create SerachInfoSale', 'url'=>array('create')),
	array('label'=>'View SerachInfoSale', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SerachInfoSale', 'url'=>array('admin')),
);
?>
    <div class="page-header app_head">
        <h1 class="text-center text-primary">修改用户信息</h1>
    </div>

<?php $this->renderPartial('_upform', array('model'=>$model)); ?>