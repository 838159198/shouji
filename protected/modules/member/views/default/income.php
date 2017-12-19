<?php
/* @var $this DefaultController */
/* @var $dataProvider IDataProvider */
/* @var $point float */
/* @var $date string */
/* @var $sum string */
/* @var $adList array */
$this->breadcrumbs = array('收益明细');

echo '<h4 class="text-center">收益明细</h4>';

/*$this->widget('zii.widgets.CMenu', array(
    'items' => array(array('label' => '网吧收益明细', 'url' => array('branch'))),
    'htmlOptions' => array('class' => 'breadcrumb')
));*/

echo CHtml::beginForm('income', 'get', array('class'=>'form-inline')),
CHtml::label('选择月份', 'm'),
CHtml::dropDownList('m', $date, Common::getDateMonthList()),
CHtml::submitButton('查询', array('class'=>'btn btn-info')),
CHtml::endForm();

function _date_format($date)
{
    return $date . ' ' . DateUtil::getWeek(date('w', strtotime($date)));
}

$columns = array();
$columns[] = array('name' => 'dates', 'headerHtmlOptions' => array('style' => 'width: 86px;color:#33ef94'),'value' => '$data[\'dates\']','type'=>'html', 'header' => '激活量/收益');
foreach ($adList as $k => $v) {
    $columns[] = array('name' => $k, 'header' => $v, 'type' => 'html');
}

$uid=$this->uid;
$member=Member::model()->find("id=$uid");
if($member["agent"]!=69)
{
    $columns[] = array('name' => 'temp','value' => '$data[\'temp\']', 'header' => '活动奖励');
}

$columns[] = array('name' => 'amount','value' => 'is_numeric($data[\'amount\']) ? number_format($data[\'amount\'],2) : $data[\'amount\']', 'header' => '收益总额');


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'enablePagination' => false,
    'enableSorting' => false,
    'summaryText' => $date,
    'columns' => $columns,
));
?>
<tr></tr>
<!--<table id="fsdfsd" style="width: 100%"><tr></tr></table>-->

<br>
<label class="btn btn-info" style="margin-bottom: 10px;"><span style="font-weight: bold;">合计：</span><?php echo "业务收益".number_format($sum, 2) ?><?php if($agentincome>0){echo " + 推广奖金".$agentincome;} ?></label>

<div class="alert alert-info">
    <ul>
        <li>只显示最近三个月的收入数据</li>
        <li>提成按天与按月计算合计数有误差，实际金额以财务提现中的实际发生额为准</li>
    </ul>
</div>
<style type="text/css">
    .grid-view table.items th{background:none;background-color: #4A515B;color: #ffffff;line-height: 24px;}
    .grid-view table.items tr{line-height: 24px;}
    ul,li{list-style: none;list-style-type: none;padding: 0px;}
    input[type="submit"]{height: 22px; line-height: 22px;margin-left: 10px;margin-top: -2px;padding-top: 0px;}
    #fsdfsd td{text-align: center; font-size: 12px; font-weight: bold; padding: 0.3em; color: #000000;border: 1px #D0E3EF solid;}
    #fsdfsd td span{ color: #000000; }
</style>

<script type="text/javascript">
    Array.prototype.indexOf = function(val) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == val) return i;
        }
        return -1;
    };
    Array.prototype.remove = function(val) {
        var index = this.indexOf(val);
        if (index > -1) {
            this.splice(index, 1);
        }
    };


    $(function(){
        $(".items").attr("id","ple_table");
        var cells = document.getElementById("ple_table").rows.item(0).cells.length;
        var prodata;
        var sumdata="";
        var idata="";
        var valuedate=0;

        for(var i=2;i<cells;i++)
        {
            idata+=i+",";
            prodata=0;
            $(".items tr td:nth-child("+i+")").each(function(){
                prodata=parseFloat($(this).text());
                if(prodata>0)
                {
                    sumdata+=i+",";
                    return false;
                }
            });
        }
        var arr0 = idata.split(",");
        var arr = sumdata.split(",");
        for(var j=0;j<arr.length;j++){
            arr0.remove(arr[j]);
        }
        for(var k=0;k<arr0.length;k++){
            $(".items tr :nth-child("+arr0[k]+")").hide();
        }

       //列求和
        for(var g=0;g<arr.length;g++){
            var isum=0;
            var ivalue=0;
            var isuml=0;
            var ivaluel=0;
            if(arr[g]==""){continue;}
            $(".items tr td:nth-child("+arr[g]+")").each(function(){
                var b=$(this).text();
                var bbb=0;
                var aa=b.indexOf("/");
                /*if(aa!=-1)
                {*/
                    bbb=b.substring(0,aa);
                    b=b.substring(aa+1);

                    ivalue=parseFloat(b);
                    if (ivalue){isum=isum+ivalue}

                    ivaluel=parseFloat(bbb);
                    if (ivaluel){isuml=isuml+ivaluel}

                /*}*/



            });

            isum=isum.toFixed(2);
            isuml=parseInt(isuml);
            var t=arr[g]-1;
            var iname=$("#yw0_c"+t).text();

            if(g>0)
            {
                $('.nonono').append('<td><span style="color: #0b88fd;font-weight: bold;">'+isuml+'</span>/'+isum+'</td>');
            }

            else
            {
                $('tbody').append('<tr class="nonono even"></tr>');
                $('.nonono').append('<td style="width: 86px;color:#000000;vertical-align: middle;font-weight: bold;">总&nbsp;计</td><td><span style="color: #0b88fd;font-weight: bold;">'+isuml+'</span>/'+isum+'</td>');
            }


        }
        $('.nonono').append('<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>');


    });
</script>