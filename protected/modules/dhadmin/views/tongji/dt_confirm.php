<style type="text/css">
    .col-md-10 {width: 100%; }
    .input-append{padding-left: 15px;}
    .grid-view {float: none;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    select{height: 30px;}
</style>
<div class="page-header app_head">
    <h1 class="text-center text-primary">地推-业务APP激活判定<small></small></h1>
</div>
<div class="alert alert-info" style="margin-left:16px; margin-right:14px;">
    <?php
    $activity=SystemLog::model()->findAll('type=:type and target=:target order by id desc limit 1 ',array('type'=>'DTACTIVITY','target'=>$type));
    if(!empty($activity))
    {
        echo "<span style='font-weight: bolder;font-size: 16px;color: #00da67'>".substr($activity[0]["date"],0,10)."&nbsp;&nbsp;</span>";
    }
    ?>
    日数据已判断激活，默认判断为选择日期当天和之前的数据集合--判断日期应为今天的前一天日期
</div>

<?php
/* @var $date string */
/* @var $type string */
/* @var $username string */

echo CHtml::beginForm('dtConfirm', 'get', array('class'=>'input-append')),
'<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        <input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
        &nbsp;&nbsp;</div>',
    '业务类型：'.CHtml::dropDownList('type', $type, Ad::getAdList()).'&nbsp;&nbsp;',
'用户名：<input class = "input-small" id="username" name="username" type="text" value=',$username,'>',
CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
CHtml::endForm();
?>

<div class="col-md-10">
    <script type="text/javascript">
        var PAY_URL = '<?php echo $this->createUrl('updates') ?>';
        var NOPAY_URL = '<?php echo $this->createUrl('noupdates') ?>';
    </script>

    <?php
    /* @var $this TongjiController */
    /* @var $data CActiveDataProvider */
    /* @var $status int */
    /* @var $date string */
    $status=0;
    function getDates()
    {
        $dated=Yii::app()->getRequest()->getQuery('date');
        $datev=str_replace('-', '',$dated);
        return $datev;
    }
    ?>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'admin-grid',
        'dataProvider' => $data,
        //'filter'=>RomAppupdata::model(),
        'columns' => array(
            array(
                'name' => '全选',
                'value' => '$data->uid."-".$data->type."-".$data->imeicode."-".getDates()."-dt"',
                'selectableRows' => 2,
                'header' => CHtml::checkBox('all', false),
                'class' => 'CCheckBoxColumn',
                'checkBoxHtmlOptions' => array('name' => 'rid[]'),
                'visible' => $status == 0,
            ),
            array(
                'name'=>'username',
                'value' => '$data->username'
            ),
            'type', 'imeicode', 'simcode', 'model', 'status', 'installtime', 'installcount', 'ip',

            array(
                'name'=>'from',
                'value' => '$data->from == 1 ? "<span style=color:#0000ff;>pc助手</span>" : "<span style=color:green;>速传</span>"',"type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'label' => '判定激活',
                        'imageUrl' => false,
                        'url' => "Yii::app()->createUrl('dhadmin/tongji/updates',array('rid'=>\$data->uid.'-'.\$data->type.'-'.\$data->imeicode.'-'.getDates().'-dt'))",
                    )
                ),
                'visible' => $status == 0,
                'template' => '{update}',
            ),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'income' => array(
                        'label' => '业务封号',
                        'imageUrl' => false,
                        'url' => "Yii::app()->createUrl('dhadmin/tongji/noupdates',array('rid'=>\$data->uid.'-'.\$data->type.'-'.\$data->imeicode))",
                    ),

                ),
                'template' => '{income}',
            ),
        ),

    ));
    ?>

    <div class="row-fluid">
        <div class="app_button">

            <?php
            if ($status == 0) {
                echo CHtml::button('判定激活', array_merge(
                    array('class'=>'btn btn-success'),
                    array('id' => 'payAll')
                ));
                echo "&nbsp;&nbsp;&nbsp;&nbsp;".CHtml::button('业务封号', array_merge(
                        array('class'=>'btn btn-danger'),
                        array('id' => 'nopayAll')
                    ));
            }?>
        </div>
    </div>


    <script type="text/javascript">
        $(function () {
            $("#all").click(function () {
                $(":checkbox").attr("checked", this.checked);
            });

            $("#payAll").click(function () {

                var ids = [];
                $(":checkbox[name='rid[]']").each(function () {
                    if (this.checked) {
                        ids.push(this.value);
                    }
                });
                if (ids.length > 0) {
                    $.post(PAY_URL, {'rid[]': ids}, function (data) {
                        if (data && data === "success") {
                            alert("完成激活");
                            document.location.reload();
                        } else {
                            alert("激活失败");
                            //$("#modal").html("请重试").dialog({autoOpen: true, modal: true, width: 400});
                        }
                    });
                }
            });

            $("#nopayAll").click(function () {

                var ids = [];
                $(":checkbox[name='rid[]']").each(function () {
                    if (this.checked) {
                        ids.push(this.value);
                    }
                });
                if (ids.length > 0) {
                    $.post(NOPAY_URL, {'rid[]': ids}, function (data) {
                        if (data && data === "success") {
                            alert("完成封号");
                            document.location.reload();
                        } else {
                            alert("封号失败");
                            //$("#modal").html("请重试").dialog({autoOpen: true, modal: true, width: 400});
                        }
                    });
                }
            });

        });
    </script>
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