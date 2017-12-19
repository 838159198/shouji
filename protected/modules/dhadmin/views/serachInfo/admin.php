<style type="text/css">
	td a{ color: #333; text-decoration: none; }
	td a:hover{ color: #333; text-decoration: none; }
	select{
		height:28px;
	}
</style>

<?php
/* @var $this SerachInfoController */
/* @var $model SerachInfo */

$this->breadcrumbs=array(
	'录入信息管理',
);

$this->menu=array(
	array('label'=>'List SerachInfo', 'url'=>array('index')),
	array('label'=>'Create SerachInfo', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});

$('.search-form form').submit(function(){
	$('#serach-info-grid').yiiGridView('update', {

		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">录入信息管理</h1>
</div>

<h5 style="color:#2b79a8;margin-bottom:-10px;margin-top: 10px;line-height: 25px;">
你可以通过如下字符比较查询 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>)</br>
	支持模糊查询，支持多条件查询，支持点击字段排序列表</br>
	注册状态：0=>未注册1=>已注册</br>
	有效状态：0=>未审核1=>有效2=>无效
</h5>

<?php /*echo CHtml::link('高级搜索','#',array('class'=>'search-button')); */?><!--
<div class="search-form" style="display:none">
<?php /*$this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php
$id = Yii::app()->user->manage_id;
$role = Manage::model()->getRoleByUid($id);

if (($role <5))
{
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'serach-info-grid',
		'dataProvider'=>$model->search(0,0),
		'filter'=>$model,
		'columns'=>array(
			array(
				'name'=>'pro_name',
				'type'=>'raw',
				'value'=>'$data->pro->name'
			),
			'name',
			array(
				'name'=>'tel',
				'type'=>'raw',
				'value'=>'"<a href=javascript:void(0) title=".$data->tel.">".Common::substr($data->tel,15)."</a>"'
			),
			'mail',
			'qq',
			array(
				'name'=>'reg_status',
				'type'=>'raw',
				'value'=>'$data->reg_status=="0"?"<span style=font-weight:bold;>未注册</span>":"<span style=color:darkgreen;font-weight:bold;>已注册</span>"',
				'filter'=>CHtml::listData(SerachInfo::model()->listDatacurStatus,'key','value'),
				'htmlOptions'=>array("style"=>"text-align:left;width:162px;"),
			),
			array(
				'name'=>'status',
				'type'=>'raw',
				'value'=>'$data->status=="0"?"<span style=font-weight:bold;>未审核</span>":($data->status=="1"?"<span style=color:darkgreen;font-weight:bold;>有效</span>":"<span style=color:red;font-weight:bold;>无效</span>")',
				'filter'=>CHtml::listData(SerachInfo::model()->listDatacur,'key','value'),
				'htmlOptions'=>array("style"=>"text-align:left;width:162px;"),
			),
			array(
				'name'=>'createtime',
				'type'=>'raw',
				'value'=>'substr($data->createtime,0,10)'
			),
			array(
				'name'=>'zixuntime',
				'type'=>'raw',
				'value'=>'substr($data->zixuntime,0,10)'
			),
			array(
				'header'=>'咨询记录',
				'class'=>'CButtonColumn',
				'template'=>'{recharge}',
				'buttons'=>array(
					'recharge' => array(
						'label'=>'添加',
						'url'=>'Yii::app()->createUrl("dhadmin/serachInfo/zxjlcreate", array("id"=>$data->id))',
						'options'=>array("target"=>"_blank"),
					),
				),
			),

			array(
				'header'=>'操作',
				'template'=>'{view}{update}',
				'buttons'=>array('view'=>array(
					'options'=>array("target"=>"_blank"),
				),'update'=>array(
					'options'=>array("target"=>"_blank"),
				),),
				'class'=>'CButtonColumn',
			),

			array(
				'header'=>'有效审核',
				'htmlOptions'=>array('style'=>'width:70px'),
				'type'=>'raw',
				'value'=>'SerachInfo::button($data->id,$data->status,$data->reg_status)',
				// 'template'=>'{reis}/{renone}',
				// 'buttons'=>array(
				// 	'reis' => array(
				// 		'label'=>'有效',
				// 		'url'=>'Yii::app()->createUrl("dhadmin/serachInfo/updatestatus", array("id"=>$data->id,"isv"=>$data->status,"isr"=>$data->reg_status,"status"=>"1"))'
				// 	),
				// 	'renone' => array(
				// 		'label'=>'无效',
				// 		'url'=>'Yii::app()->createUrl("dhadmin/serachInfo/updatestatus", array("id"=>$data->id,"isv"=>$data->status,"isr"=>$data->reg_status,"status"=>"2"))'
				// 	)
				// ),


			),


		)
	));
}
else
{
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'serach-info-grid',
		'dataProvider'=>$model->search(0,0),
		'filter'=>$model,
		'columns'=>array(
			array(
				'name'=>'pro_name',
				'type'=>'raw',
				'value'=>'$data->pro->name'
			),
			'name',
			array(
				'name'=>'tel',
				'type'=>'raw',
				'value'=>'"<a href=javascript:void(0) title=".$data->tel.">".Common::substr($data->tel,15)."</a>"'
			),
			'mail',
			'qq',
			array(
				'name'=>'reg_status',
				'type'=>'raw',
				'value'=>'$data->reg_status=="0"?"<span style=font-weight:bold;>未注册</span>":"<span style=color:darkgreen;font-weight:bold;>已注册</span>"',
				'filter'=>CHtml::listData(SerachInfo::model()->listDatacurStatus,'key','value'),
				'htmlOptions'=>array("style"=>"text-align:left;width:162px;"),
			),
			array(
				'name'=>'status',
				'type'=>'raw',
				'value'=>'$data->status=="0"?"<span style=font-weight:bold;>未审核</span>":($data->status=="1"?"<span style=color:darkgreen;font-weight:bold;>有效</span>":"<span style=color:red;font-weight:bold;>无效</span>")',
				'filter'=>CHtml::listData(SerachInfo::model()->listDatacur,'key','value'),
				'htmlOptions'=>array("style"=>"text-align:left;width:162px;"),
			),
			array(
				'name'=>'content',
				'type'=>'raw',
				'value'=>'"<a href=javascript:void(0) title=".$data->content.">".Common::substr($data->content,10)."</a>"'
			),
			array(
				'name'=>'createtime',
				'type'=>'raw',
				'value'=>'substr($data->createtime,0,10)'
			),
			array(
				'name'=>'tixingtime',
				'type'=>'raw',
				'value'=>'substr($data->tixingtime,0,10)'
			),
			array(
				'name'=>'zixuntime',
				'type'=>'raw',
				'value'=>'substr($data->zixuntime,0,10)'
			),
			array(
				'header'=>'咨询记录',
				'class'=>'CButtonColumn',
				'template'=>'{recharge}',
				'buttons'=>array(
					'recharge' => array(
						'label'=>'添加',
						'url'=>'Yii::app()->createUrl("dhadmin/serachInfo/zxjlcreate", array("id"=>$data->id))',
						'options'=>array("target"=>"_blank"),
					),
				),
			),

			array(
				'header'=>'操作',
				'template'=>'{view}{update}',
				'buttons'=>array('view'=>array(
					'options'=>array("target"=>"_blank"),
				),'update'=>array(
					'options'=>array("target"=>"_blank"),
				),),
				'class'=>'CButtonColumn',
			),


		)
	));
}


?>
<!-- <script>
$(function(){

	$('a[title=有效]').attr('style','background-color:#32CD32;border:1px #32CD32 solid');
	$('a[title=无效]').attr('style','background-color:#CD3333;border:1px #CD3333 solid');
})

</script> -->
