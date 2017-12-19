<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/4
 * Time: 11:31
 */
?>
<style>
    .form-inline{margin-bottom: 10px;}
    .form_date{width:200px;float:left;}
    select{height: 32px;}
    input{height: 32px}
    tr th,tr td{text-align: center}
    .table thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="page-header app_head">
            <h1 class="text-center text-primary">数据统计 <small></small></h1>
            <!--    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;">--><?php //echo "共".$count."条";?><!--</span>-->
        </div>
        <div class="col-md-10">
            <div class="app_button" >
                <button  class="btn btn-success" >汇总数据</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>安装总量：<?php echo $countArr[0]  ?>个</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>有效安装总量：<?php echo $countArr[1] ?>个</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>总收入：<?php echo $countArr[2] ?>元</span>
            </div>
            <?php if(in_array($this->uid,array(707))) {?>
            <div style="float: right;margin-bottom: 20px;margin-right:20px">
            <!-- 代理商实际收入2017-11-28 -->
                <?php 
                    $total=0;
                    if(!empty($earn)){
                        foreach($earn as $row){
                            $arr=RomSubagentdata::getActivationNum( $row['uid'],$startdate,strtotime($enddate),$row['subagent']);
                            $total+=$arr[0]['activation'];
                        }
                    }
                    
                ?>
                <p>代理商实际收入：<?php echo $total*2;?>元</p>
            <!-- 代理商实际收入2017-11-28 -->
                <span>直属子用户收益：<?php echo RomSubagentdata::getRealData( $this->uid,$startdate,strtotime($enddate)) ?>元</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span>下级代理商收益：<?php echo RomSubagentdata::getRealDataTwo( $this->uid,$startdate,strtotime($enddate)) ?>元</span>
            </div>
            <?php }?>
            <!--div class="alert alert-info" >提示：数据未判定时，有效安装数量显示为空；当没有有效激活数据时，有效安装数量显示为0</div-->
            <?php
            $enddate=empty($enddate)? date("Y-m-d"):$enddate;
            echo  CHtml::beginForm('index', 'get', array("class"=>"form-inline")),
' 用户名：'.CHtml::dropDownList('type', $type, Member::getListSub($this->uid),array("empty"=>"默认所有")).'&nbsp;&nbsp;&nbsp;&nbsp;
<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="startdate" name="startdate" size="10"  data-rule="required" type="text" value="'.date("Y-m-d",$startdate).'" >
&nbsp;&nbsp;</div>',
                '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="enddate" name="enddate" size="10"  data-rule="required" type="text" value="'.$enddate.'" >
&nbsp;&nbsp;</div>',
            CHtml::submitButton('提交',  array('class'=>'btn btn-info','onclick'=>'return check()')),
            CHtml::endForm()
            ?>

                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>类型</th>
                        <th>用户名</th>
                        <th>安装数量</th>
                        <th>有效安装数量</th>
                        <th>收入</th>
                        <th width="120">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count=0;$count2=0;$count3=0;if(!empty($data)){

                        ?>
                    <?php foreach($data as $row):?>
                        <tr>
                            <td><?php echo $row['subagent']==1?"子用户":"2级代理商";?></td>
                            <td><?php echo $row['username'];?></td>
                            <td><?php $count +=$row['count'];echo $row['count'];?></td>
                            <?php $arr=RomSubagentdata::getActivationNum( $row['uid'],$startdate,strtotime($enddate),$row['subagent']);

                            ?>
                            <td><?php $count2+=$arr[0]['activation'];echo $arr[0]['activation'];?></td>
                            <td><?php $count3+=$arr[0]['income'];echo $arr[0]['income'];?></td>
                            <?php if($row['subagent']==1){?>
                            <td>
                                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/agent/subuser?uid=".$row['uid']);?>" target="_blank" class="label label-primary">查看详情</a>
                            </td>
                            <?php }?>
                            <?php if($row['subagent']==2){?>
                                <td>
                                    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/agent/subuser2?sub=".$row['uid']);?>" target="_blank" class="label label-primary">查看详情</a>
                                </td>
                            <?php }?>
                        </tr>
                    <?php endforeach; }?>

                    <tr>
                        <td>合计</td>
                        <td></td>
                        <td><?php echo $count;?> </td>
                        <td><?php echo $count2;?> </td>
                        <td><?php echo sprintf("%.2f",$count3);?> </td>
                    </tr>
                    </tbody>
                </table>

            <div class="row-fluid text-center">
                <nav>
                    <?php
                    $this->widget('CLinkPager',array(
                            'selectedPageCssClass' => 'active', //当前页的class
                            'hiddenPageCssClass' => 'disabled', //禁用页的class
                            'header'=>'',
                            'cssFile'=>false,
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '末页',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'pages' => $pages,
                            'maxButtonCount'=>8,
                            'htmlOptions'=>array("class"=>"pagination pagination-lg"),
                        )
                    );
                    ?>
                </nav>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh', weekStart:1,todayBtn:1,
            autoclose:1,

            startView:2,
            minView:2,
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

    function check(){
        var startdate=$('#startdate').val();
        var enddate=$('#enddate').val();
        var a = /^(\d{4})-(\d{2})-(\d{2})$/;
        if (!a.test(startdate)) {
            alert("开始日期格式不正确!");
            return false;
        }
        if (!a.test(enddate)) {
            alert("结束日期格式不正确!");
            return false;
        }

        if(startdate=='' || enddate==''){
            alert('请选择查询日期');
            return false;
        }
        if(startdate>enddate){
            alert('开始日期不能大于结束日期');
            return false;
        }


    }

</script>