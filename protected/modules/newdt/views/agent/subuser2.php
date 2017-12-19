<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/4
 * Time: 11:31
 */
?>
<style>
    input,select{height: 34px;width: 100px}
    tr th,tr td{text-align: center}
    .input-small{height:34px;}
    .input-append{height:30px;margin-bottom: 15px}
    .table .table-bordered .table-hover tr th,tr td{text-align: center}
    .btn-info {margin-top: 0px;}
    .table  thead tr{background-color: rgba(88, 121, 128, 0.45)}
    .form_date{width:200px;float:left;}

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->

        <div class="col-md-10">
            <div class="page-header app_head">
                <h1 class="text-center text-primary">2级代理商数据统计 <small></small></h1>
                <!--    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;">--><?php //echo "共".$count."条";?><!--</span>-->
            </div>
            <div class="app_button">
                <a href="<?php echo $this->createUrl("agent/index");?>" class="btn btn-success">汇总数据</a>
                <!--            <a href="--><?php //echo $this->createUrl("agent/subuser");?><!--" class="btn btn-success">子用户数据</a>-->
            </div>

                <?php
                echo CHtml::beginForm('subuser2', 'get', array('class'=>'input-append')),
                    ' <input type="hidden" name="sub" value='.$_GET['sub'].'>
                    用户名：'.CHtml::dropDownList('uid', $type, Member::getListSubuser($_GET['sub'])).'&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm" >
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                <input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,' >&nbsp;&nbsp;</div>',
                CHtml::submitButton('提交',array('class'=>'btn btn-info')),'',
                CHtml::endForm();
                ?>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>安装数量</th>
                        <th>有效安装数量</th>
                        <th width="120">收入</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $counts=0;$count2=0;$count3=0;if(!empty($nowDays)){?>
                    <?php  foreach($nowDays as $k=> $v) {?>
                        <tr>
                            <td><?php echo $v;?></td>
                            <td><?php $aa=RomAppresource::getDayInstall($type,$k);$counts= $counts+$aa;echo $aa;?></td>
                            <?php $arr=RomSubagentdata::getDayActivationNum($type,$k);?>
                            <td><?php $count2 =$count2+ $arr[0]['activation'];echo $arr[0]['activation'];?></td>
                            <td>
                                <?php  $count3 += $arr[0]['income'];echo $arr[0]['income'];?>
                            </td>
                        </tr>
                    <?php } }?>
                    <tr>
                        <td>合计</td>
                        <td><?php echo $counts;?> </td>
                        <td><?php echo $count2;?></td>
                        <td><?php echo sprintf("%.2f",$count3);?></td>
                    </tr>
                    </tbody>
                </table>

        </div>
    </div>
</div>
<div class="row-fluid text-center">
    <nav>

    </nav>
</div>
<script type="text/javascript">

    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh', weekStart:1,todayBtn:1,
            autoclose:1,
            startView: 'year',
            minView:'year',
            forceParse:0
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

</script>