<?php
/* @var $this TongjiController */
/* @var $model RomAppresource */
$date=date('Y-m-d', strtotime('-180 day'));
$createstamp=strtotime($date);//列表显示两个月的数据
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
	<h1 class="text-center text-primary">安装上报<small></small></h1>
</div>
<div class="row-fluid">
    <div class="alert alert-danger">注意：安装上报列表只显示现在往前推60天的数据</div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bind-sample-grid',
	'dataProvider'=>$model->search($createstamp),
	'filter'=>$model,
	'columns'=>array(
		//'uid',
		array(
            'name'=>'username',
            'value' => '$data->username'
		),
		'type',
        'tjcode',
		'imeicode',
        'simcode',
        'model',
		'createtime',
		'closeend',
        'finishdate',
        'finishtime',
        'installcount',
        'ip',
        'installtime',
		array(
		'name'=>'status',
		'value' => '$data->status == 0 ? "<span style=color:green;>不监视</span>" : "<span style=color:red;>监视</span>"',"type"=>"html",
		'filter'=>CHtml::listData(RomAppresource::model()->listDataType,'key','value'),
		'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'finishstatus',
			'value' => '$data->finishstatus == 0 ? "<span style=color:red;>未完成</span>" : "<span style=color:green;>完成激活</span>"',"type"=>"html",
			'filter'=>CHtml::listData(RomAppresource::model()->listDataType0,'key','value'),
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
        array(
            'name'=>'from',
            'value'=>'$data->from=="1"?"<span style=color:red;>pc助手</span>":($data->from=="2"?"<span style=color:red;>速传</span>":"<span style=color:green;>rom</span>")',
            "type"=>"html",
            'filter'=>CHtml::listData(RomAppresource::model()->listDataType1,'key','value'),
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
