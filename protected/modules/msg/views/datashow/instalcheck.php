<div class="page-header app_head">
    <h1 class="text-primary"><span id="mh1">&nbsp;</span>安装量分析</h1>
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
    .finst{margin-left: 100px;background-color: #4a515b;margin-top: -5px;}
</style>
<script type="text/javascript">
    var json = <?php echo $json;?>;
    var thisUrl = '<?php echo $this->createUrl('') ?>';
</script>
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
            /* @var $date string */
            /* @var $date2 string */
            /* @var $monthdata string */
            $columns[] = array('name' => "安装数量", 'value' => '$data[\'counts\']');
            $columns[] = array('name' => "手机型号", 'value' => '$data[\'model\']');

            echo CHtml::beginForm('instalcheck', 'get', array('class'=>'input-append')),
            '<div class="input-group date form_date form-date-first" data-date="" data-date-format="yyyy-mm-dd" >
    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input class = "input-small" id="datefirst" name="date" size="10"  data-rule="required" type="text" value=',$date,' >
&nbsp;&nbsp;</div>',
            '<div class="input-group date form_date form-date-last" data-date="" data-date-format="yyyy-mm-dd" >
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input class = "input-small" id="datelast" name="date2" size="10"  data-rule="required" type="text" value=',$date2,' >
&nbsp;&nbsp;</div>',
            CHtml::submitButton('提交',  array('class'=>'btn btn-info','id'=>'mf')),'',
            CHtml::button('总安装数量'.$monthdata[0]["counts"],  array('class'=>'btn btn-success finst','id'=>'mfinst')),'',
            CHtml::endForm();

            echo '<p class="alert alert-danger">
                    <span class="alertrig">安装量指的是查询日期范围内统计软件安装到手机的数量。<br>比如：2017年01月01日您制作的ROM（需内置统计软件）刷入10部手机，您的安装量就是10个。</span>
                </p>';

            echo '<div id="container" class="alert alert-danger"></div>';


            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ));
            ?>
        </div>

    </div>
</div>


<script type="text/javascript">
    $(function () {
        var d = new Date();
        var str = d.getFullYear()+"-"+p(d.getMonth()-1)+"-01";
        $('.form-date-first').datetimepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            todayBtn: 1,
            language: 'zh',
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0,
            startDate:str,
            endDate: d,
        }).on('changeDate',function(ev){
            var startTime = $('#datefirst').val();
            $(".form-date-last").datetimepicker('setStartDate',startTime);
            $(".form-date-first").datetimepicker('hide');
        });
        $('.form-date-last').datetimepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            todayBtn: 1,
            language: 'zh',
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0,
            startDate:str,
            endDate: d,
        }).on('changeDate',function(ev){
            var endTime = $('#datelast').val();
            $(".form-date-first").datetimepicker('setEndDate',endTime);
            $(".form-date-first").datetimepicker('hide');
        });
    });

    $.fn.datetimepicker.dates['zh'] = {
        days:       ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六","星期日"],
        daysShort:  ["日", "一", "二", "三", "四", "五", "六","日"],
        daysMin:    ["日", "一", "二", "三", "四", "五", "六","日"],
        months:     ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月","十二月"],
        monthsShort:  ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"],
        meridiem:    ["上午", "下午"],
        //suffix:      ["st", "nd", "rd", "th"],
        today:       "今天"
    };

    function p(s) {
        return s < 10 ? '0' + s: s;
    }

</script>