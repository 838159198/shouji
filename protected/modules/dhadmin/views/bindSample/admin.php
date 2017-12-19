<?php
/* @var $this BindSampleController */
/* @var $model BindSample */

$this->breadcrumbs=array(
	'Bind Samples'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BindSample', 'url'=>array('index')),
	array('label'=>'Create BindSample', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bind-sample-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<style type="text/css">
	.grid-view .button-column {
		width: 90px;
	}
</style>
<div class="page-header app_head">
	<h1 class="text-center text-primary">独立资源管理 <small></small></h1>
</div>

<div class="row-fluid">
	<div class="app_button">
		<a href="<?php echo $this->createUrl("bindSample/admin");?>" class="btn btn-info">资源列表</a>
		<a href="<?php echo $this->createUrl("bindSample/create");?>" class="btn btn-success">创建资源</a>
	</div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bind-sample-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'uid',
		'type',
		'val',
        array(
            'name'=>'username',
            'value' => '$data->username'
        ),
        array(
            'header'=>"用户业务开启状态",
            'value'=>'$data->OpenStatus',
            'htmlOptions'=>array('style'=>'text-align:center;'),
            'type'=>'html',
        ),
		array(
		'name'=>'status',
		'value' => '$data->status == 0 ? "<span style=color:green;>已分配</span>" : "<span style=color:red;>未分配</span>"',"type"=>"html",
		'filter'=>CHtml::listData(BindSample::model()->listDataType,'key','value'),
		'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		//'value' => '$data->closed == 0 ? "<span style=color:red;>可用</span>" : "<span style=color:green;>已封号</span>"',"type"=>"html",
/*		array(
			'name'=>'allot',
			'value' => '$data->allot == 0 ? "<span style=color:green;>自动分配</span>" : "<span style=color:blue;>手动分配</span>"',"type"=>"html",
			'filter'=>CHtml::listData(BindSample::model()->listDataType0,'key','value'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),*/
		array(
			'name'=>'closed',
			'value' => '$data->closed == 0 ? "<span style=color:green;>可用</span>" : "<span style=color:red;>已封号</span>"',"type"=>"html",
			'filter'=>CHtml::listData(BindSample::model()->listDataType1,'key','value'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
            'name'=>'utype',
            'value' => '$data->utype == 0 ? "<span style=color:green;>独立用户</span>" : "<span style=color:red;>平台分组</span>"',"type"=>"html",
            'filter'=>CHtml::listData(BindSample::model()->listDatauType1,'key','value'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'klradio',
            'value' => '$data->klradio',
            'htmlOptions'=>array('width'=>'80px'),
        ),
		array(
			'header'=>'操作',
			'template'=>'{update}',
			'buttons'=>array('view'=>array(
				'options'=>array("target"=>"_self"),
			),'update'=>array(
				'options'=>array("target"=>"_self"),
			),),
			'class'=>'CButtonColumn',
		),
	),
)); ?>
