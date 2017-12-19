<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>手机安装明细</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;margin-left: 5px;height: 31px;}
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
    .items img{width: 72px; height: 72px;}
    .CButtonColumn{}
</style>
<h4 class="text-primary" style="color"><span id="mh1">&nbsp;</span>型号：<?=$models?>&nbsp;&nbsp;&nbsp;首次安装时间：<?=$day?></h4>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <a href="javascript:history.go(-1)" class="btn btn-success" style="text-align: center;margin-left: 20px;width: 80px;height: 32px;">返回</a>
        <div class="col-md-10">

            <?php
            /* @var $this DatashowController */
            /* @var $dataProvider CArrayDataProvider */

            $columns[] = array('name' => "序号", 'value' => '$data[\'id\']');
            $columns[] = array('name' => "图标", 'value' => '"<img src=".$data[\'pic\'].">"','type'=>'html');
            $columns[] = array('name' => "名称", 'value' => '$data[\'name\']');

            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>
    </div>
</div>