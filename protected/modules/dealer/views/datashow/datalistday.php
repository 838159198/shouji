<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span><?=$day?>日手机统计</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;margin-left: 5px;height: 31px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view{float: left;}
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

            echo CHtml::beginForm('datalistday', 'post', array('class'=>'form-inline')),
            CHtml::label('搜索imei&nbsp;', 'imei'),
            CHtml::textField('imei', "",array('class'=>'input-small')),
            CHtml::hiddenField('dates', $day,array('class'=>'input-small')),
            CHtml::submitButton('查询', array('class'=>'btn btn-info')),
            CHtml::endForm();


            $columns[] = array('name' => "序号", 'value' => '$data[\'id\']');
            $columns[] = array('name' => "手机型号", 'value' => '$data[\'model\']');
            $columns[] = array('name' => "imei", 'value' => '$data[\'imeicode\']');
            $columns[] = array('name' => "安装量", 'value' => '$data[\'ancounts\']');
            $columns[] = array('name' => "到达量", 'value' => '$data[\'accessdata\']');
            $columns[] = array('name' => "安装时间", 'headerHtmlOptions' => array('width'=>'30%'),'value' => '$data[\'day\']','type'=>'html');
            $columns[] = array('name' => "安装详情",'value' => '"<a href=/dealer/datashow/datalistonly?date=".$data[\'day\']."&imei=".$data[\'imeicode\'].">查看</a>"','type'=>'html');

            echo '<a href="/dealer/datashow/datalist" class="btn btn-success" style="width: 80px;height: 32px;margin-top: 20px;">返回</a><br>';
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>



    </div>
</div>