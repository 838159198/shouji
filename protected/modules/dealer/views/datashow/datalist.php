<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>数据上报</h1>
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

            echo CHtml::beginForm('datalist', 'post', array('class'=>'form-inline')),
            CHtml::label('选择月份&nbsp;', 'm'),
            CHtml::dropDownList('m', $date, Common::getDateMonthList(6),array('class'=>'input-small')),
            CHtml::submitButton('查询', array('class'=>'btn btn-info')),
            CHtml::endForm();

            $columns[] = array('name' => "日期", 'headerHtmlOptions' => array('width'=>'30%'),'value' => '$data[\'date\'] ','type'=>'html');
            $columns[] = array('name' => "手机数量", 'value' => '$data[\'counts\']');
            $columns[] = array('name' => "安装量", 'value' => '$data[\'ancounts\']');
            /*$columns[] = array('name' => "到达量", 'value' => '$data[\'accessdata\']');*/
            $uid=$this->uid;
            if($uid==1158 || $uid==1128)
            {
                $columns[] = array('name' => "操作",'value' => '"<a href=/dealer/datashow/datalistday?date=".$data[\'date\'].">详情</a>"','type'=>'html');
            }
            else
            {
                $columns[] = array('name' => "操作",'value' => '','type'=>'html');
            }


            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>
    </div>
</div>