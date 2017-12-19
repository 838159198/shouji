<div class="page-header app_head"><h1 class="text-center text-primary">地推安装详情</h1></div>
<style type="text/css">
    .grid-view{width:47%;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .grid-view table.items {width: 200%;}
    .grid-view .summary { width: 198%; }
    .text-primary span{margin-left: 190px;}
    .grid-view table.items th{background: none;background: #4a515b;}
    .grid-view table.items tr{line-height: 30px;}
    .finst{margin-left: 100px;background-color: #4a515b;margin-top: -5px;}
    .col-md-10 {width: 105%;}

</style>
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
   <div class="col-md-10">

        <?php
        /* @var $this TongjiController */
        /* @var $dataProvider CArrayDataProvider */
        /* @var $date string */
        /* @var $date2 string */

        echo CHtml::beginForm('appdetailtest', 'get', array('class'=>'input-append')),
        '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
        '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
<input class = "input-small" id="date2" name="date2" size="10"  data-rule="required" type="text" value=',$date2,'  onblur="checkDateInput(this)">
&nbsp;&nbsp;</div>',
        CHtml::submitButton('提交',  array('class'=>'btn btn-info')),' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        CHtml::button('上一天', array('class'=>'btn btn-success','id'=>'pre')),' ',
        CHtml::button('下一天', array('class'=>'btn btn-success','id'=>'next')),'',
        CHtml::endForm();

        $typeList = Ad::getAdList();
        $columns = array();
        foreach ($typeList as $k => $v) {
            $columns[] = array(
                'name' => $v,
                'value' => "\$data['" . $k . "']",
                'type'=>'html',
            );
        }
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'columns' => array_merge(array(
                array('name' => '用户名', 'value' => '$data["username"]'),
                array('name' => '手机合计', 'value' => '$data["sum"]','type'=>'html'),
            ), $columns),
        ));
        ?>
    </div>

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
        for(var i=2;i<=cells;i++)
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
        for(var g=0;g<=arr.length;g++){
            var isuml=0;
            var ivaluel=0;
            if(arr[g]==""){continue;}
            $(".items tr td:nth-child("+arr[g]+")").each(function(){
                var b=$(this).text();
                ivaluel=parseFloat(b);
                isuml=isuml+ivaluel;
            });
            isuml=parseInt(isuml);
            if(g>0)
            {
                $('.nonono').append('<td><span style="color: #0b88fd;font-weight: bold;">'+isuml+'</span></td>');
            }
            else
            {
                $('#ple_table tbody').append('<tr class="nonono even"></tr>');
                $('.nonono').append('<td style="width: 86px;color:#000000;vertical-align: middle;font-weight: bold;">总&nbsp;计</td><td><span style="color: #0b88fd;font-weight: bold;">'+isuml+'</td>');
            }

        }
        $('.nonono').append('<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>');

    });
    $('#pre').click(function(){
        function dateAdd(dd,n){
            var strs= new Array();
            strs = dd.split("-");
            var y = strs[0];
            var m = strs[1];
            var d = strs[2];
            var t = new Date(y,m-1,d);
            var str = t.getTime()+n*(1000*60*60*24);
            var newdate = new Date();
            newdate.setTime(str);
            var strYear=newdate.getFullYear();
            var strDay=newdate.getDate();
            if(strDay < 10){
                strDay = "0"+strDay;
            }
            var strMonth=newdate.getMonth()+1;
            if(strMonth < 10){
                strMonth = "0"+strMonth;
            }
            var strdate=strYear+"-"+strMonth+"-"+strDay;
            return strdate;
        }
        var olddate=$('#date').val();
        var date=dateAdd(olddate,-1);
        window.location.href='/dhadmin/tongji/appdetailtest?date='+date+'&date2='+date+'&yt0=提交';

    });
    $('#next').click(function(){
        function dateAdd(dd,n){
            var strs= new Array();
            strs = dd.split("-");
            var y = strs[0];
            var m = strs[1];
            var d = strs[2];
            var t = new Date(y,m-1,d);
            var str = t.getTime()+n*(1000*60*60*24);
            var newdate = new Date();
            newdate.setTime(str);
            var strYear=newdate.getFullYear();
            var strDay=newdate.getDate();
            if(strDay < 10){
                strDay = "0"+strDay;
            }
            var strMonth=newdate.getMonth()+1;
            if(strMonth < 10){
                strMonth = "0"+strMonth;
            }
            var strdate=strYear+"-"+strMonth+"-"+strDay;
            return strdate;
        }
        var olddate=$('#date').val();
        var date=dateAdd(olddate,1);
        window.location.href='/dhadmin/tongji/appdetailtest?date='+date+'&date2='+date+'&yt0=提交';

    })
</script>

