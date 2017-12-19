<?php
/* @var $this MemberController */
/* @var $json string */
/* @var $memberName string */
/* @var $first string */
/* @var $last string */
/* @var $uid string */
$this->breadcrumbs = array(
    '会员管理列表' => array('index'),
    '曲线图',
);
?>
<script type="text/javascript">
    var json = <?php echo $json;?>;
    var types = <?php echo $types;?>;
    var aa = <?php echo $aa;?>;
    var thisUrl = '<?php echo $this->createUrl('') ?>';
</script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">会员(<?php echo $memberName ?>)曲线图</h1>
</div>

<div id="searchdate" class="text-center" style="margin-top: 20px;">
    <!--    开始日期 : <input id="datefirst" type="text" value="--><?php //echo $first ?><!--"-->
    <!--                  onclick="WdatePicker({isShowClear:false,readOnly:true,startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'});"/>-->
    <!--    结束日期 : <input id="datelast" type="text" value="--><?php //echo $last ?><!--"-->
    <!--                  onclick="WdatePicker({isShowClear:false,readOnly:true,startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd'});"/>-->
    开始日期 : <input id="datefirst" type="text" value="<?php echo $first ?>" lang="date"/>
    结束日期 : <input id="datelast" type="text" value="<?php echo $last ?>" lang="date"/>
    <input type="hidden" id="searchid" value="<?php echo $uid ?>"/>
    <input id="searchgraphs" type="button" class="sbtn" value="查询"/>
</div>
<div id="highcharts" style="width:1000px;height:600px;margin: 0 auto;margin-top: 20px;"></div>
<script type="text/javascript">
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
                $("#datelast").datepicker("option", "minDate", selectedDate);
            }
        });
    })
</script>