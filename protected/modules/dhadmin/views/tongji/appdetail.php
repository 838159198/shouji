<div class="page-header app_head"><h1 class="text-center text-primary">APP业务详情</h1></div>
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
        /* @var $this TongjiController */
        /* @var $dataProvider CArrayDataProvider */
        /* @var $date string */
        /* @var $date2 string */
        /* @var $type string */

        echo CHtml::beginForm('appdetail', 'get', array('class'=>'input-append')),
        '业务类型：'.CHtml::dropDownList('type', $type, Ad::getAdList(),array("empty"=>"")).'&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="liu" value="existrate"/>留存率排序&nbsp;&nbsp;&nbsp;&nbsp;<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
        '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date" name="date2" size="10"  data-rule="required" type="text" value=',$date2,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
        CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
        CHtml::endForm();
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'columns' =>
                array(
                    'username',
                    'install',
                    'uninstall',
                    'installother',
                    'existrate',
                )
        ));
        ?>
    </div>

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