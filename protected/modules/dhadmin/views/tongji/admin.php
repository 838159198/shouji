<?php
/* @var $this TongjiController */
/* @var $model RomSoftpak */

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
	<h1 class="text-center text-primary">统计APP资源列表 <small></small></h1>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bind-sample-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'uid',
		array(
            'name'=>'username',
            'value' => '$data->username'
		),
		'serial_number',
		'type',
		'version',

		array(
		'name'=>'status',
		'value' => '$data->status == 0 ? "<span style=color:green;>已分配</span>" : "<span style=color:red;>未分配</span>"',"type"=>"html",
		'filter'=>CHtml::listData(RomSoftpak::model()->listDataType,'key','value'),
		'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'allot',
			'value' => '$data->allot == 0 ? "<span style=color:green;>自动分配</span>" : "<span style=color:blue;>手动分配</span>"',"type"=>"html",
			'filter'=>CHtml::listData(RomSoftpak::model()->listDataType0,'key','value'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'closed',
			'value' => '$data->closed == 0 ? "<span style=color:green;>可用</span>" : "<span style=color:red;>已封号</span>"',"type"=>"html",
			'filter'=>CHtml::listData(RomSoftpak::model()->listDataType1,'key','value'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
/*		array(
			'header'=>'操作',
			'template'=>'{update}',
			'buttons'=>array('view'=>array(
				'options'=>array("target"=>"_self"),
			),'update'=>array(
				'options'=>array("target"=>"_self"),
			),),
			'class'=>'CButtonColumn',
		),*/
	),
)); ?>
