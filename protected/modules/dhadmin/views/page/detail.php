<div class="page-header app_head">
    <h1 class="text-center text-primary">详情 <small></small></h1>
</div>
<style>
	.oaa{
		text-align: right;
	}
	.odd{
		text-align: left;
	}
</style>
<!-- 详情 -->
<div class="alert alert-danger">
	<table style="margin-left: 38%">
		<tr>
			<td class="oaa">类型：</td>
			<td class="odd"><?php echo $data['type'] == 1 ? '优先资金' : '优先设备';?></td>
			<td class="oaa">会员账号：</td>
			<td class="odd"><?php echo $data['username']?></td>
		</tr>
		<tr>
			<td class="oaa">姓名：</td>
			<td class="odd"><?php echo $data['name']?></td>
			<td class="oaa">门店地址：</td>
			<td class="odd"><?php echo $data['address']?></td>
		</tr>
		<tr>
			<td class="oaa">电话：</td>
			<td class="odd"><?php echo $data['tel']?></td>
			<td class="oaa">申请设备数量：</td>
			<td class="odd"><?php echo $data['router_num'].'台'?></td>
		</tr>
		<tr>
			<td class="oaa">申请时间：</td>
			<td class="odd"><?php echo $data['createtime']?></td>
			<td class="oaa">&nbsp;&nbsp;&nbsp;&nbsp;设备发放时间：</td>
			<td class="odd"><?php echo $data['sendtime']=='0000-00-00 00:00:00' ? '' : $data['sendtime'] ?></td>
		</tr>
		<tr>
			<td class="oaa">押金金额：</td>
			<td class="odd"><?php echo $data['sum'].'元'?></td>
			<td class="oaa">冻结金额：</td>
			<td class="odd"><?php echo $data['dj_sum'].'元'?></td>
		</tr>
		<tr>
			<td class="oaa">扣款金额：</td>
			<td class="odd"><?php echo $data['kc_sum'].'元'?></td>
			<td class="oaa">发货状态：</td>
			<td class="odd">
				<select id="status">
				<?php
					if($data['status']==1){
						echo '<option value="1" readonly>已发货</option>
						<option value="2">未发货</option>';
					}else{

						echo '<option value="1">已发货</option>
					<option value="2" selected>未发货</option>';
					}
				?>
					
				</select>
				<button <?php echo $data['status']==1 ? 'disabled' : ''?> class="btn btn-xs" style="background-color: #337ab7;color:#fff;margin-bottom:8px" type="button" onclick="change()">确认</button>
			</td>
		</tr>
		
		<tr>
			<td class="oaa">备注：</td>
			<td colspan="3">
				<div style="border: 0px solid grey;height:auto;max-width: 400px">
					<?php echo $data['beizhu'];?>
				</div>
			</td>
		</tr>
	</table>
</div>
<!-- 设备管理 -->
<div class="page-header app_head">
    <h1 class="text-center text-primary">设备管理 <small></small></h1>
</div>
<?php


    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'effectivepolicy-grid',
            'dataProvider'=>$model,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'',
            'columns'=>array(
                array(
                    'header'=>'设备码',
                    'name'=>'coding',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'扣除金额',
                    'name'=>'kc_sum',
                    // 'value'=>'$data["status"]==2 ? "150" : "0"',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'设备状态',
                    'name'=>'status',
                    'type'=>'raw',
                    'value'=>'ProductList::condition($data["status"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                // array(
                //     'header'=>'申请路由设备数量',
                //     'name'=>'router_num',
                //     // 'value'=>'ProductList::getmemname($data["uid"])',
                //     'htmlOptions'=>array('style'=>'text-align:center')
                // ),
                // array(
                //     'header'=>'押金金额',
                //     'name'=>'sum',
                //     'htmlOptions'=>array('style'=>'text-align:center')
                // ),
                //  array(
                //     'header'=>'发放状态',
                //     'name'=>'status',
                //     'value'=>'$data["status"]==1 ? "已发货" : "未发货"',
                //     'htmlOptions'=>array('style'=>'text-align:center')
                // ),
                array(
                    'header'=>'操作',
                    'type'=>'raw',
                    'value'=>'ProductList::operation($data["id"],$data["uid"],$data["coding"],$data["router_id"],$data["status"],$data["handle_status"])',
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
<p style="text-align: center;font-size:25px;margin-top:0px;background-color: #286090;color:#fff">设备押金处理</p>
<form action="/dhadmin/page/dealwith" method="post" onsubmit="return yanzheng()">
<table style="margin-left: 140px;border-collapse:separate; border-spacing:0px 10px;" cellspacing="20" >
	<tr>
		<td style="text-align: right">设备编码：</td>
		<td><span id="t_coding"></span></td>
	</tr>
	<tr>
		<td  style="text-align: right">可解冻资金：</td>
		<td><span id="kjd_sum"></span>元</td>
	</tr>
	<tr>
		<td  style="text-align: right">设备状态：</td>
		<td>
			<select class="input-sm" style="font-size:14px;width:178px" name="t_status" id="t_status">
				<option value="1">=====设备状态=====</option>
				<option value="2">损坏</option>
				<option value="3">退回</option>
			</select>
		</td>
	</tr>
	<tr>
		<td  style="text-align: right">扣除金额：</td>
		<td><input type="text" id="kouchu" name="kouchu" onkeyup="modify()" ></td>
	</tr>
	<tr>
		<td  style="text-align: right">解冻金额：</td>
		<td><input type="text" id="jiedong" name="jiedong"></td>
	</tr>
	<tr>
		<td  style="text-align: right">备注：</td>
		<td><textarea id="beizhu" name="beizhu" rows="3" clos="10" style="width:178px"></textarea></td>
	</tr>
	<!-- app_router_list  表的id -->
	<input type="hidden" name="list_id" id="list_id" value=""> 
</table>
<input style="margin-left:48%;margin-bottom: 20px" type="submit" value="提交" class="btn btn-primary">

</form>
<div style="position:relative;text-align: right;margin-right: 10px;padding-top:5px;margin-bottom:5px;border-top:1px solid #ccc">
<button  class="btn btn-primary" type="button" onclick="guanbi()">关闭</button>  
</div>
</div>




<script type="text/javascript">
/*获取url中参数*/
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
/*修改*/
function change(){
	var status=$('#status').val();
	if(status==2){
		return false;
	}
	var a=confirm('是否更改发货状态');
	var id=GetQueryString('id');
	if(a){
		if(status==1){
			$.post(
				'/dhadmin/page/detail',
				{status:status,s_id:id},
				function(data){
					if(data==1){
						location.reload();
					}else{
						alert('修改失败');
					}
				}
			)
		}

	}


}
/*处理*/
function handle(id,uid,coding,kjd_sum,status){
	if(<?php echo $data['status']?> ==2){
		alert('未发货，不能进行设备处理'); return false;
	}

	$('#t_coding').text(coding);//设备码
	$('#kjd_sum').text(kjd_sum);
	$('#list_id').val(id);

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
/*修改*/
function modify(){
	var kouchu=$('#kouchu').val();
	var kjd_sum=$('#kjd_sum').html();
	if(kjd_sum-kouchu>=0){
		$('#jiedong').val(kjd_sum-kouchu);
	}else{
		$('#jiedong').val(0);
	}
	
}
/*验证*/
function yanzheng(){
	var kjd_sum=$('#kjd_sum').html();
	var kouchu=$('#kouchu').val();
	var jiedong=$('#jiedong').val();
	var status=$('#t_status').val();
	var coding=$('#t_coding').text();
	if(kjd_sum-kouchu-jiedong<0){
		alert('扣除金额+解冻金额应小于可解冻资金');
		return false;
	}
	if(status==1){
		alert('请选择设备状态');
		return false;
	}
	if(kouchu==''){
		alert('请填写扣除金额');
		return false;
	}
	if(jiedong==''){
		alert('请填写解冻金额');
		return false;
	}
	var a=confirm('二次确认\n设备码：'+coding+'\n解封金额：'+jiedong+'元\n扣除金额：'+kouchu+'元');
	if(a){
		return true;
	}else{
		return false;
	}
	return true;
}
</script>