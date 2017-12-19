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
<?php //echo md5_file('D:\Documents\aa.xlsx');exit;?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">业务包列表 <small></small></h1>
</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="<?php echo $this->createUrl("productList/create");?>" class="btn btn-success">上传业务包</a>
    </div>
    <div class="alert alert-danger">搜索时注意：浏览器2的业务类型字母简写为wzdq</div>
</div>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')):?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>
    </div>
<?php endif;?>
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
        array(
            'name'=>'type',
            'value'=>'$data->type',
            'htmlOptions'=>array('style'=>'text-align:center;width:100px;'),
        ),
        array(
            'name'=>'agent',
            'value'=>'$data->Xagent',
            'type'=>"raw",
            'filter'=>CHtml::listData(ProductList::model()->listDataAgent,'key','value'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'渠道号',
            'name'=>'pakid',
            'value'=>'$data->pakid',
            'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
        ),
        'pakname',
        'sign',
        'appurl',
        'version',
        'createtime',
        array(
            'name'=>'filesize',
            'value'=>'empty($data->filesize)?"--":$data->filesize',
            'type'=>"raw",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'isshow',
            'value'=>'$data->Xisshow',
            'type'=>"raw",
            'filter'=>CHtml::listData(ProductList::model()->listDataIsShow,'key','value'),
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'status',
            'value'=>'$data->Xstatus',
            'filter'=>CHtml::listData(ProductList::model()->listDataStatus,'key','value'),
            'type'=>"raw",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/productList/edit",array("id"=>$data->id));',
            'template'=>'{update}',// {delete}
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

