<?php
$this->breadcrumbs = array('监控包列表');
$this->menu = array(
    array('label' => '监控包列表', 'url' => array('controlPackage/index')),
    array('label' => '添加监控包', 'url' => array('controlPackage/packageAdd')),
    array('label' => '一键导入', 'url' => array('controlPackage/packageAll')),
);
?>
<style type="text/css">
    select {
        display: inline-block;
        padding: 4px;
        font-size: 13px;
        line-height: 18px;
        color: #808080;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
</style>
<h4 class="text-center">监控包管理</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$template = '';
//$template .= Auth::check('manage.admin.view') ? ' {view}' : '';

$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';?>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>
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
    'emptyText'=>'没有发现用户',
    'filter' => $model,
    'columns' => array(
        'id',
        'package_name',
        array(
            'name'=>'type',
            'value'=>'$data->xtype',
            'filter'=>CHtml::listData(ControlPackage::model()->listDataType,'key','value'),
            'type'=>"html",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'name',
            'value'=>'$data->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'createtime',
            'value'=>'$data->createtime',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
       /* array(
            'name'=>'updatetime',
            'value'=>'$data->updatetime',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),*/
        array(
            'name'=>'mid',
            'value'=>'$data->m->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'status',
            'value'=>'$data->xstatus',
            'filter'=>CHtml::listData(ControlPackage::model()->listDataStatus,'key','value'),
            'type'=>"html",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        //'lasttime',
        //'status',
        /*array(
            'name'=>'create_datetime',
            'value'=>'date("Y-m-d", $data->create_datetime)',
        ),*/

        array(
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/controlPackage/packageUpdate",array("id"=>$data->id));',
            //'deleteButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{update}',
            'afterDelete'=>'function(link,success,data){alert(data) }',
            'buttons'=>array(
                /*演示代码测试
                 * 'print'=>array(
                    'label'=>'打印',
                    'url'=>'Yii::app()->controller->createUrl("print", array("id"=>$data->id))',
                    'options'=>array("target"=>"_blank","onclick"=>"return del()"),
                ),*/
                /*'delete'=>array(
                    'options'=>array("onclick"=>"return del()"),
                    'click'=>"none",
                ),*/
            ),

        ),
    ),
));
?>
