<div class="page-header app_head">
    <h1 class="text-center text-primary">管理员列表 <small></small></h1>
</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="<?php echo $this->createUrl("manage/create");?>" class="btn btn-success">创建管理员</a>
    </div>
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
    //'filter' => $model,
    'columns' => array(
        'id',
        'username',
        'name',
        array(
            'name'=>'group',
            'value'=>'$data->mangegroup->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        'qq',
        'mail',
        array(
            'name'=>'overtime',
            'value'=>'date("Y-m-d H:i:s", $data->overtime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'name'=>'status',
            //'value'=>'$data->status==1?"正常":"隐藏"',
            'value'=>'$data->Xstatus',
            'filter'=>CHtml::listData(Member::model()->listDataStatus,'key','value'),
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
            'viewButtonUrl'=>'Yii::app()->createUrl("dhadmin/manage/detail",array("id"=>$data->id));',
            'viewButtonOptions'=>array("target"=>"_blank"),
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/manage/edit",array("id"=>$data->id));',
            //'deleteButtonUrl'=>'Yii::app()->createUrl("dhadmin/member/del",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{view} {update}',// {delete}
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
