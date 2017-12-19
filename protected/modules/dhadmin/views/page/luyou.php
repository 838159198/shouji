<div class="page-header app_head">
    <h1 class="text-center text-primary">路由设备会员管理 <small></small></h1>
</div>
<div>
	<button class="btn btn-primary" type="button" onclick="add()">添加会员</button>
	<button class="btn btn-primary" type="button" onclick="handle()">操作记录</button>
</div>
<div style="margin-top:10px">
	<span>类型：</span>
	<select class="input-sm" style="font-size:14px" id="select_type">
		<option value="0">全部类型</option>
		<option value="1">优先资金</option>
		<option value="2">优先设备</option>
	</select>
	<span style="padding-left:20px">发货状态：</span>
	<select class="input-sm" style="font-size:14px" id="select_status">
		<option value="0">全部状态</option>
		<option value="1">已发货</option>
		<option value="2">未发货</option>
	</select>
	<span style="padding-left:20px">申请日期：</span>
	<?php echo CHtml::textField('lastjointime','', Bs::cls(Bs::INPUT_SMALL)) ?> — <?php echo CHtml::textField('lastovertime','', Bs::cls(Bs::INPUT_SMALL)) ?>
	<span style="padding-left:20px">会员账号：</span>
	<input type="text" style="height:30px" name="username" id="username">
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
                    'name'=>'type',
                    'value'=>'$data["type"]==1 ? "优先资金" : "优先设备"',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'会员账号',
                    'name'=>'username',
                    // 'value'=>'$data["mac"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'申请时间',
                    'name'=>'createtime',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'申请路由设备数量',
                    'name'=>'router_num',
                    // 'value'=>'ProductList::getmemname($data["uid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'押金金额',
                    'name'=>'sum',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                 array(
                    'header'=>'发放状态',
                    'name'=>'status',
                    'value'=>'$data["status"]==1 ? "已发货" : "未发货"',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'详情',
                    'name'=>'status',
                    'type'=>'raw',
                    'value'=>'ProductList::detail($data["id"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                // array(
                //     'header'=>'详细',
                //     'name'=>'id',
                //     // 'value'=>'ProductList::handle($data["id"])',
                //     'type'=>'raw',
                //     'htmlOptions'=>array('style'=>'text-align:center')
                // ),
               
                
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
<div id="tian_jia" style="width:600px;height: auto;background-color: #FFFFFF;position: fixed;left:37%;top:100px;z-index:3;border-radius: 6px;border:1px solid rgba(0, 0, 0, 0.3);display:none ;padding-left: 0px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
<p style="text-align: center;font-size:25px;margin-top:0px;background-color: #286090;color:#fff">添加会员</p>
<form action="/dhadmin/page/luyou" method="post" onsubmit="return yanzheng()">
<table style="margin-left: 140px;border-collapse:separate; border-spacing:0px 10px;" cellspacing="20" >
	<tr>
		<td style="text-align: right">*会员账号：</td>
		<td><input type="text" id="account_number" name="account_number"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*姓名：</td>
		<td><input type="text" id="name" name="name"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*门店住址：</td>
		<td><input type="text" id="address" name="address"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*电话：</td>
		<td><input type="text" id="iphone" name="iphone" onkeyup="value=value.replace(/[^\-\+\0-9]/g,'')"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*用户类型：</td>
		<td>
			<select class="input-sm" style="font-size:14px;width:178px" name="type" id="type">
				<option value="1">优先资金</option>
				<option value="2">优先设备</option>
			</select>
		</td>
	</tr>
	<tr>
		<td  style="text-align: right">*申请路由台数：</td>
		<td><input type="text" id="number" name="number" onkeyup="value=value.replace(/[^0-9]/g,'')"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*押金金额：</td>
		<td><input type="text" id="sum" name="sum" onkeyup="value=value.replace(/[^0-9]/g,'')"></td>
	</tr>
	<tr>
		<td  style="text-align: right">*设备编码：</td>
		<td><textarea onkeyup="value=value.replace(/[\；]/g,';')" id="coding" name="coding" placeholder="多个设备码以分号隔开" rows="3" clos="12" style="width:178px"></textarea></td>
	</tr>
	<tr>
		<td  style="text-align: right">备注：</td>
		<td><textarea id="beizhu" name="beizhu" rows="3" clos="10" style="width:178px"></textarea></td>
	</tr>
</table>
<input style="margin-left:48%;margin-bottom: 20px" type="submit" value="添加" class="btn btn-primary">

</form>
<div style="position:relative;text-align: right;margin-right: 10px;padding-top:5px;margin-bottom:5px;border-top:1px solid #ccc">
<button  class="btn btn-primary" type="button" onclick="guanbi()">关闭</button>  
</div>
</div>
<!-- 弹出层--添加 -->
<script>
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
})
/*添加*/
function add(){
	$('#test').show();
	$('#tian_jia').show();
}
/*关闭*/
function guanbi(){
	$('#test').hide();
	$('#tian_jia').hide();
	$('table').find('input').val('');
	$('table').find('textarea').val('');
	$('table').find('select').val(1);
}
/*验证*/
function yanzheng(){
	var account_number=$('#account_number').val();
	var name=$('#name').val();
	var address=$('#address').val();
	var iphone=$('#iphone').val();
	var type=$('#type').val();
	var number=$('#number').val();
	var sum=$('#sum').val();
	var coding=$('#coding').val();
	if(account_number=='' || name=='' || address=='' || iphone=='' || type=='' || number=='' || sum=='' || coding==''){
		alert('带星号的都不能为空');
		return false;
	}
	var leixing=$('#type').find('option:selected').html();
	var a=confirm('二次确认\n会员账号：'+account_number+'\n用户类型：'+leixing+'\n申请设备台数：'+number+'\n押金金额：'+sum);
	if(a){
		return true;
	}else{
		return false;
	}
	return true;

}
/*查询*/
function chaxun(){
	var type=$('#select_type').val();
	var status=$('#select_status').val();
	var begin=$('#lastjointime').val();
	var end=$('#lastovertime').val();
	if(begin=='' || end==''){
		alert('请补全查询日期');return false;
	}
	var username=$.trim($('#username').val());
	location.href='/dhadmin/page/luyou?type='+type+'&status='+status+'&begin='+begin+'&end='+end+'&username='+username;
}
/*获取url中参数*/
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
$(function(){
	var type=GetQueryString('type');
	var status=GetQueryString('status');
	var begin=GetQueryString('begin');
	var end=GetQueryString('end');
	var username=GetQueryString('username');
	console.log(typeof(type));
	if(type!=null){
		$('#select_type').val(type);
	}
	if(status!=null){
		$('#select_status').val(status);
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
/*操作跳转*/
function handle(){
	location.href='/dhadmin/page/record';
}
</script>