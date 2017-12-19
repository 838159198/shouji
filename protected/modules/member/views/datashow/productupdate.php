<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>产品更新状况</h1>
</div>
<style type="text/css">
    .grid-view{width:100%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 100%;}
    .grid-view .summary { width: 198%; }
    .text-primary{color: #069ecb;text-align:center;}
    .text-warning{color: red;text-align:center;}
    .text-warning{color: green;text-align:center;}
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .grid-view table.items tr td{text-align: center}

    .CButtonColumn{}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php
            /* @var $this DatashowController */
            /* @var $dataProvider CArrayDataProvider */
            $columns[] = array('name' => "产品名称", 'headerHtmlOptions' => array('width'=>'30%'),'value' => '$data[\'name\'] ','type'=>'html');
            $columns[] = array('name' => "版本号", 'value' => '$data[\'version\']');
            $columns[] = array('name' => "状态", 'value' => '$data[\'status\']== 1 ? "<b class=text-primary>有效</b>" : "<b class=text-warning>无效</b>" ','type'=>'html');

            $columns[] = array('name' => "更新日期", 'value' => '$data[\'createtime\']');
            echo CHtml::beginForm('productupdate', 'get', array('class'=>'input-append')),
            '<span>资源类型 : </span>',
            CHtml::dropDownList('type', 1, Ad::getAdList(false), array('empty' => '','class'=>'input-small')),
            CHtml::submitButton('提交',  array('class'=>'btn btn-info')),
            CHtml::endForm();
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>
    </div>
</div>