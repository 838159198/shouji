<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>应用安装排行（Top10）</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
    .text-primary{color: #069ecb;text-align:center;}
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .grid-view table.items tr td{text-align: center}
    .grid-view table.items tr td span{width: 100%;float: left}
    .grid-view table.items tr td .imgst{width: 100%;float: left;padding-top: 10px;}
    .grid-view table.items tr td .imgst img{width: 72px;height: 72px;}
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
            $columns[] = array('name' => "应用类型",'headerHtmlOptions' => array('width'=>'30%'),'value' => '"<div class=imgst><img src=".$data[\'pic\']."></div><span>".$data[\'name\']."</span> " ','type'=>'html');
            $columns[] = array('name' => "应用说明", 'value' => '$data[\'content\']');
            $columns[] = array('name' => "安装数量", 'value' => '$data[\'counts\']+10000');
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>
    </div>
</div>