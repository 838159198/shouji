<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>推广用户详情</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
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
            $columns[] = array('name' => "注册时间", 'headerHtmlOptions' => array('width'=>'30%'),'value' => '$data[\'jointime\'] ','type'=>'html');
            $columns[] = array('name' => "用户ID", 'value' => '$data[\'username\']');
            $columns[] = array('name' => "设备数量", 'value' => '$data[\'usercounts\']');

            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>
    </div>
</div>