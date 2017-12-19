<?php
/* @var $this TongjiController */
/* @var $model ClientData */

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
    <h1 class="text-center text-primary">client data安装上报<small></small></h1>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'bind-sample-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'id',
            'value'=> '$data->id',
            'htmlOptions'=>array('style'=>'width:60px;text-align:center;')
        ),
        array(
            'name'=>'user_id',
            'value' => 'empty($data->member->username)?"--":$data->member->username'
        ),
        array(
            'name'=>'app_id',
            'value'=>'$data->product->name',
            'htmlOptions'=>array('style'=>'text-align:left;')
        ),
        'imei',
        'iccid',
        array(
            'name'=>'models',
            'value'=>'$data->models==null?"--":$data->models',
            'htmlOptions'=>array('style'=>'text-align:left;')
        ),
        array(
            'name'=>'system_version_code',
            'value'=>'$data->system_version_code',
            'htmlOptions'=>array('style'=>'width:80px;text-align:center;')
        ),
        'sim_operator_name',
        array(
            'name'=>'mac',
            'value'=>'$data->mac',
            'htmlOptions'=>array('style'=>'width:120px;text-align:center;')
        ),
        array(
            'name'=>'mobi_ip',
            'value'=>'$data->mobi_ip',
            'htmlOptions'=>array('style'=>'width:120px;text-align:center;')
        ),
        array(
            'name'=>'pc_ip',
            'value'=>'$data->pc_ip',
            'htmlOptions'=>array('style'=>'width:120px;text-align:center;')
        ),
        array(
            'name'=>'from',
            'value' => '$data->from == 1 ? "<span style=color:green;>电脑</span>" : "<span style=color:red;>手机</span>"',"type"=>"html",
            'filter'=>CHtml::listData($model->fromList,'key','value'),
            'htmlOptions'=>array('style'=>'width:50px;text-align:center;'),
        ),
        array(
            'name'=>'createtime',
            'value'=>'date("Y-m-d H:i:s",$data->createtime)',
            //'filter'=>false,
            'htmlOptions'=>array('style'=>'width:150px;text-align:center;'),
            //'type'=>'html',
        ),
        array(
            'name'=>'status',
            'value' => '$data->status',
            "type"=>"html",
            'filter'=>CHtml::listData($model->statusList,'key','value'),
            'htmlOptions'=>array('style'=>'width:50px;text-align:center;'),
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
