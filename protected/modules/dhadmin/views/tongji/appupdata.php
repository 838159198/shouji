<?php
/* @var $this TongjiController */
/* @var $model RomAppupdata */
$date=date('Y-m-d', strtotime('-30 day'));//列表显示1个月的上报数据
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
	<h1 class="text-center text-primary">激活上报 <small></small></h1>
</div>
<div class="row-fluid">
    <div class="alert alert-danger">注意：激活上报列表只显示现在往前推30天的数据</div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bind-sample-grid',
	'dataProvider'=>$model->search($date),
	'filter'=>$model,
	'columns'=>array(
		'uid',
		array(
            'name'=>'username',
            'value' => '$data->username'
		),
		'appname',
        'tjcode',
		'sys',
		'mac',
        'simcode',
		'imeicode',
        'appmd5',
		'model',
        'com',
        'runlength',
        'runcount',
        'ip',
        'runtime',
		'date',
        'createtime',
        array(
            'name'=>'type',
            'value' => '$data->type == 1 ? "<span style=color:#0000ff;>已卸载</span>" : "<span style=color:green;>正常</span>"',"type"=>"html",
            'filter'=>CHtml::listData(RomAppupdata::model()->listDataType,'key','value'),
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
