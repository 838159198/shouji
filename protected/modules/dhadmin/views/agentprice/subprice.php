<style type="text/css">
    select,input{height: 31px;width: 120px}
    #admin-form select{margin-top: 1px}
    #fdsfsd{margin-top: -3px;margin-left: 15px}
</style>
<div class="container-fluid">
    <div class="row">
        <!--左侧-->
        <div class="col-md-12">

            <h4 class="text-center">代理商单价管理</h4>
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->menu,
                'htmlOptions' => array('class' => 'breadcrumb')
            ));

            ?>
            <div class="alert alert-info" >提示：代理商单价和子用户单价为平台默认，不可被删除。</div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'admin-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array("class"=>"form-inline"),
            )); ?>
            <?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>
            单价：<?php echo $form->textField($model, 'price',array("class"=>"input-append",)); ?>
            用户类别：<?php echo $form->dropDownList($model, 'sign',array("0"=>"子用户","1"=>"代理商"),array("class"=>"form-control",'disabled'=>'disabled')); ?>
            用户名：<?php echo $form->textField($model, 'uid',array("class"=>"input-append",)); ?>
            <input type="submit" class="btn btn-info" id="fdsfsd" value="添加">
            <?php $this->endWidget(); ?>
            <?php
            //判断是否有提示信息
            if(Yii::app()->user->hasFlash('credits_status')){?>
                <div class="alert alert-success" style="margin-top: 10px">
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
                'emptyText'=>'没有发现用户数据',
                'filter' => $model,
                'columns' => array(
                    array(
                        'name'=>'id',
                        'value'=>'$data->id',
                        'htmlOptions'=>array('style'=>'text-align:center;'),
                    ),
                    array(
                        'name'=>'agent',
                        'value'=>'$data->agent',
                        'htmlOptions'=>array('style'=>'text-align:center;'),
                    ),
                    array(
                        'name'=>'uid',
                        'value'=>'$data->uid==0 ? "子用户单价" : ($data->uid==$data->agent?"代理商单价":$data->member->username)',
                        'htmlOptions'=>array('style'=>'text-align:center;'),
                    ),
                    array(
                        'name'=>'price',
                        'value'=>'$data->price',
                        'htmlOptions'=>array('style'=>'text-align:center;'),
                    ),
                    array(
                        'name'=>'updatetime',
                        'value'=>'empty($data->updatetime)?"--": $data->updatetime',
                        'htmlOptions'=>array('style'=>'text-align:center;'),
                    ),
                    array(
                        'header'=>'操作',
                        'class' => 'CButtonColumn',
                        'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/agentprice/update",array("id"=>$data->id));',
                        'template'=>'{update}  {delete}',
                        'buttons'=>array(
                            /*演示代码测试
                             * 'print'=>array(
                                'label'=>'更新',
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

        </div>
    </div>
</div>
<script type="text/javascript">

</script>

