<?php
$this->breadcrumbs = array('商品列表');
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

$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';?>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('article_status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('article_status');?></b>
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
        array(
            'name'=>'title',
            'value'=>'$data->title',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'address',
            'type'=>'raw',
            'value'=>'empty($data->address)?"":"<a target=_blank href=$data->address>购买地址</a>"',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'credits',
            'value'=>'$data->credits',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'num',
            'value'=>'$data->num',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'order',
            'value'=>'$data->order',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'name'=>'create_datetime',
            'value'=>'date("Y-m-d H:i:s", $data->create_datetime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        /*array(
            'name'=>'update_datetime',
            'value'=>'date("Y-m-d H:i:s", $data->update_datetime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),*/
        array(
            'name'=>'uid',
            'value'=>'$data->user->name',
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
            'name'=>'hits',
            'value'=>'$data->hits',
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
            'viewButtonUrl'=>'Yii::app()->createUrl("dhadmin/shop/goodsDetail",array("id"=>$data->id));',
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/shop/goodsUpdate",array("id"=>$data->id));',
            //'deleteButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{view}{update}',
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
