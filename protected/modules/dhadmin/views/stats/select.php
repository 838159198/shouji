<div class="page-header app_head">
	<h1 class="text-center text-primary">业务数据查询</h1>
</div>
<div style="margin-bottom: 20px">
	<span>用户名&nbsp;</span><input style="height:30px" type="text" name="username" id="username" value="<?php echo $params['username'];?>">&nbsp;&nbsp;&nbsp;&nbsp;
    <span>时间</span>
    <?php echo CHtml::textField('Member_jointime',$params['begin'], Bs::cls(Bs::INPUT_SMALL)) ?>                
    -
    <?php echo CHtml::textField('lastjointime',$params['end'], Bs::cls(Bs::INPUT_SMALL)) ?>        
    <button style="margin-left: 5px;margin-right:10px" class="btn btn-primary" type="button" onclick="chaxun()">查询</button>	
</div>
<div id="jieguo" style="vertical-align: center;margin:0px;padding:0px;display: none">
    <div style="border-bottom: 1px solid black; width:42%;float:left">
    	
    </div>
    <div style="float: left;width:6%;text-align: center;margin-left: 5%;font-size:20px;margin-top:-20px;font-weight: bold">
    查询结果
    </div>
    <div style="border-bottom: 1px solid black; width:42%;float:right;">
        
    </div>
</div>
<div class="page-header app_head" style="border-bottom: 0px"></div>
<div class="alert alert-danger" style="text-align: center;display: none">
	用户：<span><?php echo $params['username'];?></span><br>
	查询日期：<span><?php echo $params['begin'].'——'.$params['end']?></span><br>
	安装手机台数：<span><?php echo !empty($alert) ? $alert['sum'] : 0;?></span>&nbsp;
    有卡台数：<span><?php echo !empty($alert) ? $alert['yes'] : 0;?></span>&nbsp;
    无卡台数：<span><?php echo !empty($alert) ? $alert['no'] : 0;?></span><br>
	激活手机台数：<span><?php echo !empty($alert) ? $alert['jihuo'] :0;?></span>

</div>
<!-- 分页列表 -->
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'effectivepolicy-grid',
            'dataProvider'=>$data,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'-',
            'columns'=>array(
               
                array(
                    'header'=>'业务名称',
                    'name'=>'type',
                    'value'=>'RomAppresource::proname($data["type"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'安装数量',
                    'name'=>'total',
                    'value'=>'$data["total"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'有卡安装数量',
                    'name'=>'yeska',
                    // 'value'=>'MemberInfo::ipnum($data["id"],$data["dates"],$data["city"],$data["province"],$data["ip"])',
                    'value'=>'$data["yeska"]',
                    'type'=>'raw',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'无卡安装数量',
                    'name'=>'noka',
                    'value'=>'$data["noka"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                 array(
                    'header'=>'激活数量',
                    // 'name'=>'type',
                    'type'=>'raw',
                    'value'=>'RomAppresource::selectnum($data["uid"],$data["begin"],$data["end"],$data["type"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                
            ),
        ));
    
?>

<script>

function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

$(function(){
        var username=GetQueryString('username');
        // console.log(typeof(username));
        if(typeof(username)!='object'){
            $('.alert.alert-danger').show();
            $('#jieguo').show();
        }


	$("#Member_jointime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#lastjointime").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#lastjointime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#Member_jointime").datepicker("option", "maxDate", selectedDate);
            }
    });
})
/*查询*/
function chaxun(){
	var username=$.trim($('#username').val());
	var begin=$('#Member_jointime').val();
	var end=$('#lastjointime').val();
	if(username==''){
		alert('用户名不能为空');
		return false;
	}
	if(begin==''){
		alert('开始时间不能为空');
		return false;
	}
	if(end==''){
		alert('结束时间不能为空');
		return false;
	}
    location.href='/dhadmin/stats/select?username='+username+'&begin='+begin+'&end='+end;
	// $.post(
	// 	'/dhadmin/stats/select',
	// 	{username:username,begin:begin,end:end},
	// 	function(data){
	// 		console.log(data);
	// 		if(data.code==1001){
	// 			alert('系统不存在该用户，查询失败');
	// 			// return false;
	// 		}
	// 	}, 
	// 	'json'
	// )
	
}

</script>