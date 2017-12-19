<?php
$this->breadcrumbs = array('积分动态');
$this->menu = array(array('label' => '商品列表', 'url' => array('shop/index')),
    array('label' => '订单列表', 'url' => array('shop/goodsOrder')),
    array('label' => '添加商品', 'url' => array('shop/goodsAdd')),
    array('label' => '积分记录', 'url' => array('memberCredits/index')),
    array('label' => '商品分类', 'url' => array('shop/addCategory')),

);
?>

<h4 class="text-center">商品分类</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$template = '';
//$template .= Auth::check('manage.admin.view') ? ' {view}' : '';

//$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
//$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';?>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array("class"=>"form-inline"),
)); ?>
<?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>
分类名：<?php echo $form->textField($model, 'cname',array("class"=>"input-small")); ?>
状态：<?php echo $form->dropDownList($model, 'status',array("0"=>"关闭","1"=>"正常"),array("class"=>"form-control")); ?>

<button type="submit" class="btn">确认提交</button>
<?php $this->endWidget(); ?>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('credits_status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('credits_status');?></b>
    </div>
<?php }?>
<?php $this->widget('zii.widgets.grid.CGridView', array(

    'id' => 'admin-grid',
    'dataProvider' => $model->search(),
    'pager'=>array(
        'class'=>'CLinkPager',//定义要调用的分页器类，默认是CLinkPager，需要完全自定义，还可以重写一个，参考我的另一篇博文：http://blog.sina.com.cn/s/blog_71d4414d0100yu6k.html
// 'cssFile'=>false,//定义分页器的要调用的css文件，false为不调用，不调用则需要亲自己css文件里写这些样式
        'header'=>'转往分页：',//定义的文字将显示在pager的最前面
// 'footer'=>'',//定义的文字将显示在pager的最后面
        'firstPageLabel'=>'首页',//定义首页按钮的显示文字
        'lastPageLabel'=>'尾页',//定义末页按钮的显示文字
        'nextPageLabel'=>'下一页',//定义下一页按钮的显示文字
        'prevPageLabel'=>'前一页',//定义上一页按钮的显示文字
//关于分页器这个array，具体还有很多属性，可参考CLinkPager的API
    ),
//    'filter' => $model,
    'columns' => array(
        'id',
        //'title',

        array(
            'name'=>'cname',
            'value'=>'$data->cname',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'status',
            //'value'=>'$data->status==1?"正常":"隐藏"',
            'value'=>'$data->xstatus',
            'type'=>"html",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'name'=>'add_time',
            'value'=>'date("Y-m-d H:i:s",$data->add_time)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'mid',
            'value'=>'$data->user->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/shop/categoryUpdate",array("id"=>$data->id));',
            //'deleteButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{update}',
            'afterDelete'=>'function(link,success,data){alert(data) }',
            'buttons'=>array(

            ),
        ),
    ),
));
?>
