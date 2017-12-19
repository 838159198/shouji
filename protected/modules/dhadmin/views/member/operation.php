<div class="page-header app_head"><h1 class="text-center text-primary">修改已判定业务数据</h1></div>

<!-- <?php echo CHtml::beginForm($this->createUrl(''), 'post'); ?> -->
业务选择
<select style="height:28px" name="type">
	<option></option>
	<?php
	$arr=Ad::getAdList(false);
	foreach ($arr as $key => $value) {
		echo '<option value="'.$key.'">'.$value.'</option>';
	}

	?>

</select> &nbsp;
判定日期
<?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'attribute' => 'p_title',
            // 'value' => $param['firstDate']['first'], //设置默认值
            'name' => 'date',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'showOtherMonths'=> true,
                'selectOtherMonths'=> true
            ),
            'htmlOptions' => array(
                'maxlength' => 8,
            ),
        ));
?>
&nbsp;
用户名
<input name="username" type="text">
&nbsp;
状态
<select style="height:28px" name="status">
	<option></option>
	<option value='0'>已封号</option>
	<option value='1'>已激活</option>

</select>
&nbsp;&nbsp;
<input class="btn btn-primary" type="submit" style="width:80px" onclick="submit()" name="submit" value="搜索">
<!-- <?php  echo CHtml::submitButton('搜索', Bs::cls(Bs::BTN_PRIMARY));?> --><br><br>
<!-- <?php echo CHtml::endForm(); ?><br> -->
<button class="btn btn-info" style="background-color: #449d44;border-color:#449d44" onclick="read(0)">搜索已封号</button>  
<?php if(Auth::check('member_fenghao')){
echo '<button class="btn btn-info" style="background-color: #449d44;border-color:#449d44" onclick="jiefeng(0)">批量解封</button>';
} ?>
 <button class="btn btn-info" style="background-color: #d9534f;border-color:#d9534f" onclick="read(1)">搜索已激活</button> 
 <?php if(Auth::check('member_fenghao')){
echo '<button class="btn btn-info" style="background-color: #d9534f;border-color:#d9534f" onclick="jiefeng(1)">批量封号</button>';
} ?>
  

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'income-grid',
    // 'enablePagination' => false,
    // 'enableSorting' => false,
    // 'itemsCssClass' => Bs::TABLE,
    'itemsCssClass' =>'items',//分页表格的样式
    'dataProvider' => $data,
    // 'filter' => $model,
    'columns' => array(
    	array(
            'header'=>'<input onclick="quanxuan()" type="checkbox" name="all" id="all">',
            'value' => 'Member::input($data["id"])',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array('name' => 'username',
            'header'=>'用户名',
            'value' => '$data["username"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name' => 'yewu',
            'header'=>'业务',
            'value'=>'Member::yewu($data["yewu"])',
            'htmlOptions'=>array('style'=>'text-align:center')
            
        ),
        array(
            'name'=>'md5',
            'header'=>'MD5',
            // 'value' => '$data["md5"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'imeicode',
            'header'=>'imei',
            'value'=>'$data["imeicode"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'installtime',
            'header'=>'安装日期',
            'value'=>'$data["installtime"]',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'finishstatus',
            'header'=>'状态',
            'value'=>'Member::colorstatus($data["finishstatus"])',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
         array(
            'header'=>'操作',
            'value'=>'Member::handle($data["id"],$data["finishstatus"])',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),

        
    ),
)); ?>
<script>
//提交
function submit(){
    var yewu=$('[name=type]').val();
    var date=$('[name=date]').val();
    var username=$.trim($('[name=username]').val());
    var status=$('[name=status]').val();
    if(yewu==''){
        alert('业务选择不能为空'); return false;
    }
    if(date==''){
        alert('日期不能为空');return false;

    }
    // var json={'yewu':yewu,'date':date,'username':username,'status':status};
    location.href="/dhadmin/member/operation?yewu="+yewu+"&date="+date+"&username="+username+"&status="+status;
}

//全选
function quanxuan(){
    if($('#all').is(':checked')){
        $('[type=checkbox]').prop('checked','checked');
    }else{
        $('[type=checkbox]').removeAttr('checked');
    }
}
//获取url中参数
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
//搜索
function read(status){
    if(GetQueryString("yewu")==null){
        var a='';
    }else{
        var a=GetQueryString("yewu");
    }
    if(GetQueryString("date")==null){
        var b='';
    }else{
        var b=GetQueryString("date");
    }
    if(GetQueryString("username")==null){
        var c='';
    }else{
        var c=GetQueryString("username");
    }
    location.href='/dhadmin/member/operation?yewu='+a+'&date='+b+'&username='+c+'&status='+status;
}
//批量封号和解封
function jiefeng(status){ 
    var select=false;
    var id='';
    var date=GetQueryString("date");
    $('[name=checkbox]:checked').each(function(i){
        if(typeof($(this).attr('key'))=='undefined'){

        }else{
            id+=$(this).attr('key')+',';
            select=true;
        }
        
    })
    if(select){
        if(status==0){
            var a=confirm('是否确定要解封');
        }else{
            var a=confirm('是否确定要封号');
        }
        
        if(a){
            $.post(
                '/dhadmin/member/jiefeng',
                {id:id,status:status,date:date},
                function(data){
                    console.log(data);
                    if(data==1){
                        alert('操作成功');
                        location.reload();
                    }
                    else if(data==2){
                        alert('操作失败');
                    }
                }

           ) 
        }
    }else{
        alert('请选择要操作的数据');
    }

    
}
$(function(){
    if(GetQueryString("yewu")!=''){
        $('[name=type]').val(GetQueryString("yewu"));
    }
    if(GetQueryString("date")!=''){
        $('[name=date]').val(GetQueryString("date"));
    }
    if(GetQueryString("username")!=''){
        $('[name=username]').val(GetQueryString("username"));
    }
    if(GetQueryString("status")!=''){
        $('[name=status]').val(GetQueryString("status"));
    }




})

</script>