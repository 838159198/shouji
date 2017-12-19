<div class="page-header app_head">
    <h1 class="text-center text-primary">路由操作记录 <small></small></h1>
</div>
<div style="margin-top:10px">
	<span>类型：</span>
	<select class="input-sm" style="font-size:14px" id="select_type">
		<option value="0">全部类型</option>
		<option value="1">优先资金</option>
		<option value="2">优先设备</option>
	</select>
	<span style="padding-left:20px">操作内容：</span>
	<select class="input-sm" style="font-size:14px" id="select_status">
		<option value="0">全部内容</option>
		<option value="1">添加会员</option>
		<option value="2">解封押金</option>
		<option value="3">冻结金扣除</option>
		<option value="4">发货状态修改</option>
	</select>
	<span style="padding-left:20px">会员账号：</span>
	<input type="text" style="height:30px" name="username" id="username">
	<span style="padding-left:20px">申请日期：</span>
	<?php echo CHtml::textField('lastjointime','', Bs::cls(Bs::INPUT_SMALL)) ?> — <?php echo CHtml::textField('lastovertime','', Bs::cls(Bs::INPUT_SMALL)) ?>
	
	<button class="btn btn-primary" type="button" onclick="chaxun()">查询</button>
</div>

<?php


    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'effectivepolicy-grid',
            'dataProvider'=>$model,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'',
            'columns'=>array(
                array(
                    'header'=>'类型',
                    'name'=>'status',
                    'value'=>'$data["status"]==1 ? "优先资金" : "优先设备"',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'会员账号',
                    'name'=>'uid',
                    'value'=>'ProductList::getmemname($data["uid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'操作时间',
                    'name'=>'createtime',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'操作内容',
                    'name'=>'type',
                    'value'=>'ProductList::content($data["type"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'详情',
                    'name'=>'id',
                    'type'=>'raw',
                    'value'=>'ProductList::lydetail($data["id"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                 array(
                    'header'=>'操作人',
                    'name'=>'mid',
                    'value'=>'ProductList::imei($data["mid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
               
                
            ),
        ));


?>


<style type="text/css">
	#test {
    width:100%;
    height:100%;
    background-color:#aaaaaa;
    position:absolute;
    top:0;
    left:0;
    z-index:2;
    opacity:0.3;
    /*兼容IE8及以下版本浏览器*/
    filter: alpha(opacity=30);
    display:none;
}
</style>
<!-- 弹出层--添加 -->
<div id="test">
</div>
<div id="tian_jia" style="width:500px;height: auto;background-color: #FFFFFF;position: fixed;left:37%;top:100px;z-index:3;border-radius: 6px;border:1px solid rgba(0, 0, 0, 0.3);display:none ;padding-left: 0px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
<p style="text-align: center;font-size:25px;margin-top:0px;background-color: #286090;color:#fff">详情</p>
<form action="/dhadmin/page/dealwith" method="post" onsubmit="return yanzheng()">
<table style="margin-left: 140px;border-collapse:separate; border-spacing:0px 10px;" cellspacing="20" >
	<tr>
		<td style="text-align: right">会员账号：</td>
		<td><span id="zhanghao"></span></td>
	</tr>
	<tr>
		<td  style="text-align: right">操作时间：</td>
		<td><span id="shijian"></span></td>
	</tr>
	<tr>
		<td  style="text-align: right">操作内容：</td>
		<td><span id="neirong"></span>
		</td>
	</tr>
	<tr style="display: none" id="fahuo">
		
	</tr>
	<tr>
		<td  style="text-align: right">设备数量：</td>
		<td><span id="shuliang"></span></td>
	</tr>
	<tr id="yajine">
		<td  style="text-align: right">押金金额：</td>
		<td><span id="jine"></span></td>
	</tr>
	<tr>
		<td  style="text-align: right;vertical-align:top">设备编码：</td>
		<td><div id="bianma" style="word-break:break-all;width:200px;margin: 0px;padding:0px"></div></td>
	</tr>
	<tr>
		<td  style="text-align: right">操作人：</td>
		<td><span id="caozuoren"></span></td>
	</tr>
	<tr>
		<td  style="text-align: right">备注：</td>
		<td><div id="bei" style="word-break:break-all;width:200px"></div></td>
	</tr>
	<!-- app_router_list  表的id -->
	<input type="hidden" name="list_id" id="list_id" value=""> 
</table>
<!-- <input style="margin-left:48%;margin-bottom: 20px" type="submit" value="确认" class="btn btn-primary"> -->

</form>
<div style="position:relative;text-align: right;margin-right: 10px;padding-top:5px;margin-bottom:5px;border-top:1px solid #ccc">
<button  class="btn btn-primary" type="button" onclick="guanbi()">关闭</button>  
</div>
</div>




<script>
/*获取url中参数*/
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

$(function(){
		$("#lastjointime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#lastovertime").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#lastovertime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#lastjointime").datepicker("option", "maxDate", selectedDate);
            }
        });


        var type=GetQueryString('type');
		var status=GetQueryString('status');
		var begin=GetQueryString('begin');
		var end=GetQueryString('end');
		var username=GetQueryString('username');
		console.log(typeof(type));
		if(type!=null){
			$('#select_status').val(type);
		}
		if(status!=null){
			$('#select_type').val(status);
		}
		if(begin!=null){
			$('#lastjointime').val(begin);
		}
		if(end!=null){
			$('#lastovertime').val(end);
		}
		if(username!=null){
			$('#username').val(username);
		}
})

/*查询*/
function chaxun(){
	var type=$('#select_type').val();
	var status=$('#select_status').val();
	var begin=$('#lastjointime').val();
	var end=$('#lastovertime').val();
	var username=$.trim($('#username').val());
	location.href='/dhadmin/page/record?status='+type+'&type='+status+'&begin='+begin+'&end='+end+'&username='+username;
}

/*详情*/
function xiangqing(username,manage,time,type,coding,beizhu,num,general,luyou){
	console.log(bianma);
	$('#zhanghao').text(username);
	$('#shijian').text(time);
	$('#neirong').text(type);
	$('#caozuoren').text(manage);
	$('#bei').text(beizhu);
	$('#shuliang').text(num+'台');

	console.log(coding.split(';')[1]);
	var att='';
	for(var i=0;i<coding.split(';').length; i++){
		att+=coding.split(';')[i]+'<br>';
	}
	$('#bianma').html(att);

	if(type=='发货状态修改'){
		$('#fahuo').text('').append('<td  style="text-align: right">发货状态：</td><td><span id="keyi">已发货</span></td>').show();

	}
	if(type=='解封押金'){
		$('#fahuo').text('').append('<td  style="text-align: right">解封金额：</td><td><span id="keyi">'+general+'元</span></td>').show();
		$('#yajine').hide();

	}
	if(type=='冻结金扣除'){
		$('#fahuo').text('').append('<td  style="text-align: right">扣除金额：</td><td><span id="keyi">'+general+'元</span></td>').show();
		$('#yajine').hide();

	}
	if(type=="添加会员" || type=="发货状态修改"){
		$('#jine').text(general+'元');
	}
	// if(luyou!=''){

	// }

	$('#test').show();
	$('#tian_jia').show();

}
/*关闭*/
function guanbi(){
	$('#test').hide();
	$('#tian_jia').hide();
	$('table').find('span').text('');
	$('table').find('div').text('');
	$('#fahuo').hide();
	
}

</script>