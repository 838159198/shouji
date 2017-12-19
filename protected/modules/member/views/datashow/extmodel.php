<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>ROM测试安装</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;width: 140px; margin-right: 20px;margin-left: 10px;text-align: center;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
    .text-primary{color: #069ecb;text-align:center;}
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .tongji{width: 94%;margin-bottom: 70px;line-height: 25px;}
    .tongji h4{font-weight: bold;color:#4A515B}
    .finst{background-color: #4a515b;margin-top: -5px;}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="alert alert-success tongji">
                <h4>验证说明（此功能主要是方便ROM作者验证统计软件是否生效）</h4>
                查询方法（手机拨号处输入<span style="color: #ff0000;font-weight: bold;">*#06#</span>即可查看IMEI码）<br>
                1、将统计软件内置ROM包，成功刷入测试机后可输入测试机IMEI码查询到实时记录；<br>
                2、查询到记录说明统计软件安装成功，统计软件可以正常统计数据；<br>
                <span style="color: #ff0000;font-weight: bold;">注：</span>如使用同一台手机测试，必须在ROM包内预装至少1款新的软件或者保证测试手机中软件不激活，否则无法统计到本次刷机记录。
            </div>

            <?php
            /* @var $this DatashowController */
            /* @var $dataProvider CArrayDataProvider */
            /* @var $date string */

            $columns[] = array('name' => "测试时间", 'value' => '$data[\'installtime\']');
            // $columns[] = array('name' => "测试次数", 'value' => '$data[\'installcount\']');
            $columns[] = array('name' => "手机型号", 'value' => '$data[\'model\']');

            echo CHtml::beginForm('extmodel', 'get', array('class'=>'input-append')),
            CHtml::button('IMEI码：',  array('class'=>'btn btn-success finst')),'',
            '<input type="text" class="input-small" name="imeicode" value="'.$iemi.'"/>',
            CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
            CHtml::endForm();

            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>

        </div>
    </div>
</div>
