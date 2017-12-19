<style type="text/css">
    .col-md-10 {width: 100%; }
    .input-append{padding-left: 15px;}
    .grid-view {float: none;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    select{height: 30px;}
</style>
<script type="text/javascript" src="/css/bootstrap/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/html2canvas/html2canvas.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/tableExport.js"></script>



<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap-table.css" />
<script type="text/javascript" src="/css/bootstrap/js/bootstrap-table.js"></script>
<script src="http://cdn.bootcss.com/bootstrap-table/1.9.1/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.11.1/extensions/export/bootstrap-table-export.js"></script>

<script src="/css/bootstrap/js/tableexport.js"></script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">代理商-业务APP激活判定<small></small></h1>
</div>
<div class="alert alert-info" style="margin-left:16px; margin-right:14px;">
    日数据已判断激活，默认判断为选择日期当天和之前的数据集合--判断日期应为今天的前一天日期
</div>

<?php
/* @var $date string */
/* @var $type string */
/* @var $username string */

echo CHtml::beginForm('newdtConfirm', 'get', array('class'=>'input-append')),
'<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        <input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
        &nbsp;&nbsp;</div>',
'用户名：<input class = "input-small" id="username" name="username" type="text" value=',$username,'>',
CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
CHtml::endForm();
?>

<div class="col-md-10">
    <script type="text/javascript">
        var PAY_URL = '<?php echo $this->createUrl('subupdate') ?>';
        var NOPAY_URL = '<?php echo $this->createUrl('nosubupdate') ?>';
    </script>
<!--    <table class="table table-bordered table-hover">-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th>imeicode</th>-->
<!--            <th>安装软件数量</th>-->
<!--            <th>激活数量</th>-->
<!--            <th>卸载数量</th>-->
<!--            <th>剩余数量</th>-->
<!--            <th>留存百分比</th>-->
<!--            <th width="120">操作</th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        --><?php //if(!empty($data)){?>
<!--            --><?php //foreach($data as $row):?>
<!--                <tr>-->
<!--                    <td>--><?php //echo $row['imeicode'];?><!--</td>-->
<!--                    <td>--><?php //echo $install_num=RomAppresource::getInstall($row['imeicode'],$time);?><!--</td>-->
<!--                    <td>--><?php //echo $row['count'];?><!--</td>-->
<!--                    <td>--><?php //echo $uninstall=RomAppupdata::getByUninstall($row['imeicode']);?><!--</td>-->
<!--                    <td>--><?php //echo $yu=$install_num-$uninstall;?><!--</td>-->
<!--                    <td>--><?php //echo $install_num==0 ?0 :($yu/$install_num)*100;?><!--%</td>-->
<!--                    <td>-->
<!--                        <a href="--><?php //echo Yii::app()->createUrl($this->getModule()->id."/agent/subuser?uid=".$row['uid']);?><!--" target="_blank" class="label label-primary">判定</a>-->
<!--                        <a href="--><?php //echo Yii::app()->createUrl($this->getModule()->id."/agent/subuser?uid=".$row['uid']);?><!--" target="_blank" class="label label-primary">封号</a>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            --><?php //endforeach; }?>
<!--        <!--                    <tr>-->
<!--        <!--                        <td>合计：</td>-->
<!--        <!--                        <td></td>-->
<!--        <!--                        <td></td>-->
<!--        <!--                    </tr>-->
<!--        </tbody>-->
<!--    </table>-->

    <?php
    /* @var $this TongjiController */
    /* @var $data CActiveDataProvider */
    /* @var $status int */
    /* @var $date string */
    $status=0;
    function getDates()
    {
        $dated=Yii::app()->getRequest()->getQuery('date');
        $datev=strtotime($dated);
        return $datev;
    }
    ?>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'admin-grid',
        'dataProvider' => $dataProvider,
        //'filter'=>RomAppupdata::model(),
        'columns' => array(
            array(
                'name' => '全选',
                'value' => '$data["uid"]."-".$data["imeicode"]."-".$data["count"]."-".$data["count1"]."-".RomAppupdata::getByUninstall($data["imeicode"])."-".getDates()',
                'selectableRows' => 2,
                'header' => CHtml::checkBox('all', false),
                'class' => 'CCheckBoxColumn',
                'checkBoxHtmlOptions' => array('name' => 'rid[]'),
                'visible' => $status == 0,
            ),
            array(
                'name'=>'username',
                'value' => '$data["username"]'
            ),
            array(
                'name'=>'imeicode',
                'value' => '$data["imeicode"]'
            ),
            array(
                'name'=>'createtime',
                'value' => '$data["createtime"]'
            ),
            array(
                'header'=>'安装软件数量',
                'name'=>'count',
                'value' => '$data["count"]',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'激活数量',
                'name'=>'count1',
                'value' => '$data["count1"]',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'卸载数量',
                'name'=>'count2',
                'value' => 'RomAppupdata::getByUninstall($data["imeicode"])',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'统计次数',
                'name'=>'count2',
                'value' => 'RomAppupdataDay::getThreeCount($data["imeicode"])',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'剩余数量',
                'name'=>'count3',
                'value' => '$data["count"]-RomAppupdata::getByUninstall($data["imeicode"])',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'留存百分比',
                'name'=>'count3',
                'value' => 'sprintf("%.2f", ($data["count"]-RomAppupdata::getByUninstall($data["imeicode"]))/($data["count"]))',
                "type"=>"html",
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'label' => '判定激活',
                        'imageUrl' => false,
                        'url' => "Yii::app()->createUrl('dhadmin/tongji/subupdate',array('rid'=>\$data['uid'].'-'.\$data['imeicode'].'-'.\$data['count'].'-'.\$data['count1'].'-'.RomAppupdata::getByUninstall(\$data['imeicode']).'-'.getDates()))",
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
                        'url' => "Yii::app()->createUrl('dhadmin/tongji/nosubupdate',array('rid'=>\$data['uid'].'-'.\$data['imeicode'].'-'.\$data['count'].'-'.\$data['count1'].'-'.RomAppupdata::getByUninstall(\$data['imeicode']).'-'.getDates()))",
                    ),

                ),
                'template' => '{income}',
            ),
        ),

    ));
    ?>

<!--    <div class="row-fluid text-center">-->
<!--        <nav>-->
<!--            --><?php
//            $this->widget('CLinkPager',array(
//                    'header'=>'',
//                    'cssFile'=>false,
//                    'firstPageLabel' => '首页',
//                    'lastPageLabel' => '末页',
//                    'prevPageLabel' => '上一页',
//                    'nextPageLabel' => '下一页',
//                    'pages' => $pages,
//                    'maxButtonCount'=>8,
//                    'htmlOptions'=>array("class"=>"pagination pagination-lg"),
//                )
//            );
//            ?>
<!--        </nav>-->
<!--    </div>-->
</div>

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
                            //document.location.reload();
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

    function shift_select() {
        var arr = [];
        $('.checkbox-column input').click(function(e){
            arr.push($('.items tbody tr').index($(this).parent().parent()));
            if(e.shiftKey){
                var iMin = Math.min(arr[arr.length-2],arr[arr.length-1]);
                var iMax = Math.max(arr[arr.length-2],arr[arr.length-1]);
                for(i=iMin;i<=iMax;i++){
                    $(".items tbody tr:eq("+i+") .checkbox-column input").attr("checked","checked"); //为li 标签添加selected类
                }
            }
        })
    }
    $(function () {
        shift_select();
    })
</script>
