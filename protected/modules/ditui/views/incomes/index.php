<div class="page-header app_head">
    <h1 class="text-primary"><span>&nbsp;</span>子用户收益列表</h1>
</div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
    .text-primary{color: #069ecb;text-align:center;}
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .grid-view table.items tr td{text-align: center}
    .grid-view table.items tr td span{width: 100%;float: left}
    .grid-view table.items tr td .imgst{width: 100%;float: left;padding-top: 10px;}
    .btn-info{margin-left: 5px;}
    .username{width: 80px;height: 30px;}
    .summary .label{color: #000020}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php
            function getSum($id, $type, $controller)
            {
                if (!isset($controller->incomeList[$id])) return 0;
                $income = $controller->incomeList[$id];
                if ($type == 0) {
                    return $income['memberSum'];
                } else {
                    return $income['agentSum'];
                }
            }
            function getInstall($id)
            {

                if (empty($_GET["startdate"])) {$startdate = DateUtil::getDate(strtotime('-1 day'));}
                else {$startdate=$_GET["startdate"];}
                if (empty($_GET["enddate"])) {$enddate = DateUtil::getDate(strtotime('-1 day'));}
                else {$enddate=$_GET["enddate"];}
                $memberb=Member::model()->findAll('status=1 and subagent=760 and id='.$id);
                if(!empty($memberb))
                {
                    if (empty($startdate)) $startdate = DateUtil::getDate(strtotime('-1 day'));
                    if (empty($enddate)) $enddate = DateUtil::getDate(strtotime('-1 day'));
                    //子用户总安装统计
                    $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid = {$id} and DATE_FORMAT(createtime,'%Y%m%d') >=DATE_FORMAT('{$startdate}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <=DATE_FORMAT('{$enddate}','%Y%m%d')";
                    $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();
                    return $monthdata[0]["counts"];
                }
                else
                {return "";}


            }

            echo CHtml::beginForm('index', 'get', array("class"=>"form-inline"));
            echo '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >起止日期：
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
   <input class = "input-small stdate" id="date" name="startdate" size="10"  data-rule="required" type="text" value=',$startdate,'  onblur="checkDateInput(this)">
&nbsp;--</div>';
            echo '&nbsp;&nbsp;<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input class = "input-small endate" id="date" name="enddate" size="10"  data-rule="required" type="text" value=',$enddate,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>';
           // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户名：<input name="username" class="username" type="text" value="">';
            echo CHtml::submitButton('提交', array("class"=>"btn btn-info"));
            echo CHtml::endForm();

            if(empty($agentincome))
            {
                $agentincome['760']="";
            }
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'members-grid',
                'summaryText' => '<span class="label">会员总数：' . $data->getTotalItemCount() . ' 预估收益：' . number_format($incomeTotal,2) . ' 预估提成：' . $agentincome['760'] . '</span>',
                'dataProvider' => $data,
                'columns' => array(
                    'username',
                    array('name' => '安装量', 'value' =>  'getInstall($data->id)'),
                    array('name' => '预估收益', 'value' => 'number_format(getSum($data->id,0,$this->grid->getController()),2)'),
                    array(
                        'class' => 'CLinkColumn',
                        'label' => '收入明细',
                        'urlExpression' => 'array("income","id"=>$data->id)',
                        'visible' => false
                    ),
                ),
            ));
            ?>

        </div>
        <div class="alert alert-info" style="float: left; width: 76.6%;margin-left: 16px;">
            <ul>
                <li>只显示最近三个月的收入数据</li>
                <li>提成按天与按月计算合计数有误差，实际金额以财务提现中的实际发生额为准</li>
            </ul>
        </div>
    </div>
</div>


<script type="text/javascript">
    var SELECT_MONTH_REPLACE_URL = '<?php echo $this->createUrl('index',array('m'=>''))?>';
    $(function () {
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