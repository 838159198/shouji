<?php
/* @var $this SerachInfoController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'录入信息管理'=>array('serachInfo/admin'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SerachInfo', 'url'=>array('index')),
	array('label'=>'Create SerachInfo', 'url'=>array('create')),
	array('label'=>'View SerachInfo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SerachInfo', 'url'=>array('admin')),
);
?>
    <div class="page-header app_head">
        <h1 class="text-center text-primary">修改用户信息</h1>
    </div>

<?php $this->renderPartial('_upform', array('model'=>$model)); ?>