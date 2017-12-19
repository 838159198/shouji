<?php
/* @var $this StatsController */
/* @var $first string */
/* @var $last string */
/** @var $json string */

$this->breadcrumbs = array(
    '平台数据统计'
);
?>
<style type="text/css" rel="stylesheet">
    #highcharts{width: 1100px;height:auto;margin: 0 auto;}
    #searchdate{ margin-bottom: 20px;}
</style>
<script type="text/javascript">
    var json = <?php echo $json;?>;
    var thisUrl = '<?php echo $this->createUrl('') ?>';
    $(function () {
        $("#datefirst").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#datefirst").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#datelast").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#datelast").datepicker("option", "maxDate", selectedDate);
            }
        });

    });
</script>

<div class="page-header app_head"><h1 class="text-center text-primary">业务收入曲线图</h1></div>


<div id="searchdate" class="text-center">
    <!--    开始日期 : <input id="datefirst" type="text" value="--><?php //echo $first ?><!--"-->
    <!--                  onclick="WdatePicker({isShowClear:false,readOnly:true,startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'});"/>-->
    <!--    结束日期 : <input id="datelast" type="text" value="--><?php //echo $last ?><!--"-->
    <!--                  onclick="WdatePicker({isShowClear:false,readOnly:true,startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'});"/>-->
    开始日期 : <input id="datefirst" type="text" value="<?php echo $first ?>" lang="date"/>
    结束日期 : <input id="datelast" type="text" value="<?php echo $last ?>" lang="date"/>
    <input type="hidden" id="searchid" value=""/>
    <input id="searchgraphs" type="button" class="sbtn" value="查询"/>
</div>
<div id="highcharts" style="width:1000px;height:600px;"></div>

