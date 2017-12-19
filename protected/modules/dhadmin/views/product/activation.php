<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:44
 * 业务激活量相关
 */
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary">业务激活量相关 <small></small></h1>
</div>

<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .finst{margin-left: 100px;background-color: #4a515b;margin-top: -5px;}
    .col-md-10 {width: 105%;}

</style>
<div class="col-md-10">
    <?php

    echo CHtml::beginForm('activation', 'get', array('class'=>'input-append')),
        '用户名：<input type="text" name="username" value='.$username.'>&nbsp;&nbsp;&nbsp;&nbsp;
         业务类型：'.CHtml::dropDownList('type', $type, Ad::getAdList()).'&nbsp;&nbsp;&nbsp;&nbsp;
<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value="'.$start.'"  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
    '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date" name="date2" size="10"  data-rule="required" type="text" value="'.$end.'"  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
    CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
    CHtml::endForm();

    ?>
</div>
<style>
    .btn_a{
        display: block;
        width: 88px;
    }
</style>

<?php
echo '&nbsp;' . CHtml::link('导出Excel', 'excel', array('class'=>'btn btn-primary btn_a'));
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'admin-grid',
    'dataProvider' => $dataProvider,
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
            'name'=>'用户ID',
            'value'=>'$data["uid"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'用户名',
            'value'=>'Member::getUsernameByMid($data["uid"])',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'Imeicode',
            'value'=>'$data["imeicode"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'Sim码',
            'value'=>'$data["simcode"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'Mac',
            'value'=>'$data["mac"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'手机型号',
            'value'=>'$data["model"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'上报时间',
            'value'=>'$data["createtime"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
    ),
));
?>

<script type="text/javascript">
    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh-CN', weekStart:1,todayBtn:1,
            autoclose:1,
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0
        });


    });
</script>

