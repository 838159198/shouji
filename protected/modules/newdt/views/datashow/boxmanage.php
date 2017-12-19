
<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>盒子套餐配置</h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <div class="col-md-10">
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));?>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>
    </div>
<?php }?>
            <div class="row-fluid">
                <div class="app_button">
                    <a href="/newdt/datashow/boxmanage" class="btn btn-success <?php if($this->getAction()->getId()=='boxmanage'):?>active<?php endif?>">装机盒子</a>
                    <a href="/newdt/datashow/route" class="btn btn-success <?php if($this->getAction()->getId()=='route'):?>active<?php endif?>">路由器</a>
                    <a href="/newdt/datashow/help" class="btn btn-success <?php if($this->getAction()->getId()=='help'):?>active<?php endif?>">装机助手</a>
                </div>
            </div>
<?php $this->widget('zii.widgets.grid.CGridView', array(

    'id' => 'admin-grid',
    'dataProvider' => $model->uidsearch($this->uid),
    'afterAjaxUpdate' => 'function() { pop_ajaxupdate(); }',
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
        array(
            'name'=>'uid',
            'value'=>'$data->user->username',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'box_number',
            'value'=>'$data->box_number',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'header'=>'套餐',
            'type'=>'raw',
            'value'=>'isset($data->b->pack_id)?Softbox::model()->getPackageNameById($data->b->pack_id):"--"',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'header'=>'类型',
            'type'=>'raw',
            'value'=>'$data->box_number=="MDAZRJ"?"一键装机助手":"装机盒子"',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        array(
            'header'=>'操作',
            'htmlOptions'=>array('class'=>'button-column','style'=>"width:120px;text-align:center;",'val'=>'box','id'=>'tc'),
            'class' => 'CButtonColumn',
            'updateButtonUrl'=>'Yii::app()->createUrl("");',
            'updateButtonOptions'=>array('title'=>'点击选择套餐'),
            //'deleteButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{update}',
            'afterDelete'=>'function(link,success,data){alert(data) }',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'选择套餐',
                    'imageUrl'=>null,
                    'options' => array('class'=>'btn btn-primary'), // HTML 标签属性设置
                ),

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

<link rel="stylesheet" type="text/css" href="/css/newdt/boxmanage.css">


        <div class="pop">
            <div class="popMain">
                <div class="popTop">
                    <div style="width: 150px;font-size: 25px;margin: 7.5px auto;">盒子套餐配置</div>
                 </div>
            <div class="popMiddle">
                    <div>
                        <span class="box_code">设备码：</span>
                        <span class="box_label"></span>
                    </div>
                    <div style="margin-top: 30px">
                        <span class="package">选择套餐：</span>
                        <select class="package_select"></select>
                    </div>
            </div>
            <div class="popBottom">
                <div class="cancel">取消</div>
                <div class="confirm">确认</div>
            </div>
        </div>
    </div>
   </div>
</div>
</div>
<script src="/css/newdt/boxmanage.js"></script>


